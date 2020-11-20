<?php
/**
 * Class Hooks
 *
 * @author Pluginbazar
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Olistener_hooks' ) ) {
	/**
	 * Class Olistener_hooks
	 */
	class Olistener_hooks {
		/**
		 * Olistener_hooks constructor.
		 */
		function __construct() {

			add_action( 'init', array( $this, 'register_everything' ) );
			add_action( 'listener', array( $this, 'render_listener' ) );
			add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
			add_action( 'wp_ajax_olistener', array( $this, 'olistener_listening' ) );
			add_action( 'admin_bar_menu', array( $this, 'handle_admin_bar_menu' ), 9999, 1 );

			add_filter( 'woocommerce_webhook_deliver_async', '__return_false' );
			add_filter( 'woocommerce_rest_check_permissions', '__return_true' );
			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_' . OLISTENER_PLUGIN_FILE, array( $this, 'add_plugin_actions' ), 10, 2 );
		}

		/**
		 * Add custom links to Plugin actions
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function add_plugin_actions( $links ) {

			$action_links = array_merge( array(
				'listener' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=olistener' ), esc_html__( 'Listener', 'woc-order-alert' ) ),
				'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=olistener&tab=listener-settings' ), esc_html__( 'Settings', 'woc-order-alert' ) ),
			), $links );

			if ( ! olistener()->is_pro() ) {
				$action_links['go-pro'] = sprintf( '<a target="_blank" class="plugin-meta-buy" href="%s">%s</a>', esc_url( OLISTENER_PLUGIN_LINK ), esc_html__( 'Go Premium', 'woc-order-alert' ) );
			}

			return $action_links;
		}


		/**
		 * Add custom links to plugin meta
		 *
		 * @param $links
		 * @param $file
		 *
		 * @return array
		 */
		function add_plugin_meta( $links, $file ) {

			if ( OLISTENER_PLUGIN_FILE === $file ) {

				$row_meta = array(
					'documentation' => sprintf( '<a class="olistener-doc" target="_blank" href="%s">%s</a>', esc_url( OLISTENER_DOCS_URL ), esc_html__( 'Read Documentation', 'woc-order-alert' ) ),
					'support'       => sprintf( '<a class="olistener-support" target="_blank" href="%s">%s</a>', esc_url( OLISTENER_TICKET_URL ), esc_html__( 'Create Support Ticket', 'woc-order-alert' ) ),
				);

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}


		/**
		 * Add nodes to WP Admin Bar
		 *
		 * @param WP_Admin_Bar $wp_admin_bar
		 */
		function handle_admin_bar_menu( \WP_Admin_Bar $wp_admin_bar ) {
			if ( current_user_can( 'manage_woocommerce' ) ) {
				$wp_admin_bar->add_node(
					array(
						'id'     => 'olistener',
						'title'  => esc_html__( 'Order Listener', 'woc-order-alert' ),
						'href'   => admin_url( 'admin.php?page=olistener' ),
						'parent' => false,
					)
				);

				printf( '<style>li#wp-admin-bar-olistener > a, li#wp-admin-bar-olistener > a:hover, li#wp-admin-bar-olistener > a:focus, li#wp-admin-bar-olistener > a:active { color: #fff !important; background: #e61f63 !important; border-radius: 58px; margin-left: 10px !important; padding: 0 18px !important; }</style>' );
			}
		}


		/**
		 * Search unread orders and send to ajax handler
		 */
		function olistener_listening() {

			$all_orders = olistener()->get_wpdb()->get_results( "SELECT * FROM " . OLISTENER_DATA_TABLE . " WHERE read_status = 'unread'" );

			ob_start();
			foreach ( $all_orders as $order_item ) {

				$item_data = array(
					sprintf( '<a href="post.php?post=%1$s&action=edit" target="_blank">#%1$s</a>', $order_item->order_id ),
					sprintf( '<a href="post.php?post=%s&action=edit" target="_blank">%s</a>', $order_item->order_id, $order_item->billing_name ),
					sprintf( __( 'Total - %s', 'woc-order-alert' ), wc_price( $order_item->order_total ) ),
					sprintf( __( '<i>Order placed on %s</i>', 'woc-order-alert' ), get_the_time( 'jS F, g:i a', $order_item->order_id ) ),
					sprintf( '<div class="order-action mark-read tt--top" aria-label="Mark as Read"><span class="dashicons dashicons-visibility"></span></div>' ),
				);
				$item_data = array_map( function ( $item ) {
					return sprintf( '<td>%s</td>', $item );
				}, $item_data );

				printf( '<tr>%s</tr>', implode( '', $item_data ) );

				olistener()->get_wpdb()->update( OLISTENER_DATA_TABLE, array( 'read_status' => 'read' ), array( 'id' => $order_item->id ) );
			}

			wp_send_json_success(
				array(
					'count' => count( $all_orders ),
					'html'  => ob_get_clean(),
					'audio' => olistener_get_audio()
				)
			);
		}


		/**
		 * Handle payload for new order
		 *
		 * @param WP_REST_Request $data
		 */
		function handle_payload( \ WP_REST_Request $data ) {

			$json_params  = $data->get_json_params();
			$json_params  = is_array( $json_params ) ? $json_params : array();
			$billing      = olistener()->get_args_option( 'billing', array(), $json_params );
			$billing_name = sprintf( '%s %s', olistener()->get_args_option( 'first_name', '', $billing ), olistener()->get_args_option( 'last_name', '', $billing ) );
			$order_id     = olistener()->get_args_option( 'id', '', $json_params );

			if ( ! empty( $order_id ) && apply_filters( 'olistener_filters_should_notify', true, $order_id, $json_params ) ) {
				olistener()->get_wpdb()->insert( OLISTENER_DATA_TABLE,
					array(
						'order_id'     => $order_id,
						'billing_name' => $billing_name,
						'order_total'  => olistener()->get_args_option( 'total', '', $json_params ),
						'read_status'  => 'unread',
						'datetime'     => current_time( 'mysql' ),
					)
				);
			}
		}


		/**
		 * Register endpoints
		 */
		function register_endpoints() {
			register_rest_route( 'olistener', '/new', array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_payload' ),
				'permission_callback' => '__return_true'
			) );
		}


		/**
		 * Render
		 */
		function render_listener() {
			include OLISTENER_PLUGIN_DIR . 'templates/listener.php';
		}


		/**
		 * Register Post Types and Settings
		 */
		function register_everything() {

			/**
			 * Create table if not exists
			 */
			olistener_create_table();

			if ( function_exists( 'WC' ) ) {
				/**
				 * Create webhook
				 */
				olistener_create_webhook();
			}


			/**
			 * Register Settings Nav Menu
			 */
			olistener()->PB_Settings( array(
				'add_in_menu'      => true,
				'menu_type'        => 'menu',
				'menu_title'       => esc_html__( 'Order Listener', 'woc-order-alert' ),
				'page_title'       => esc_html__( 'Order Listener Settings', 'woc-order-alert' ),
				'menu_page_title'  => esc_html__( 'Order Listener Settings', 'woc-order-alert' ),
				'capability'       => 'manage_options',
				'menu_icon'        => 'dashicons-bell',
				'menu_slug'        => 'olistener',
				'position'         => 55.5,
				'pages'            => olistener()->get_settings_pages(),
				'plugin_name'      => esc_html( 'Order Listener for WooCommerce' ),
				'plugin_slug'      => 'woc-order-alert',
				'disabled_notice'  => sprintf( '%s <a href="%s">%s</a>',
					esc_html__( 'This feature is locked.', 'woc-order-alert' ), OLISTENER_PLUGIN_LINK,
					esc_html__( 'Get pro', 'woc-order-alert' )
				),
				'enable_feedback'  => true,
				'required_plugins' => array(
					'woocommerce' => esc_html( 'WooCommerce' ),
				),
			) );
		}
	}

	new Olistener_hooks();
}