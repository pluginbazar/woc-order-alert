<?php
/*
	Plugin Name: Order Listener for WooCommerce
	Plugin URI: https://pluginbazar.com/
	Description: Play sound as notification instantly on new order in your WooCommerce store
	Version: 3.1.5
	Author: Pluginbazar
	Author URI: https://pluginbazar.com/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb;

defined( 'ABSPATH' ) || exit;
defined( 'OLISTENER_PLUGIN_URL' ) || define( 'OLISTENER_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'OLISTENER_PLUGIN_DIR' ) || define( 'OLISTENER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'OLISTENER_PLUGIN_FILE' ) || define( 'OLISTENER_PLUGIN_FILE', plugin_basename( __FILE__ ) );
defined( 'OLISTENER_PLUGIN_LINK' ) || define( 'OLISTENER_PLUGIN_LINK', 'https://pluginbazar.com/plugin/order-listener-for-woocommerce-play-sounds-instantly-on-orders/' );
defined( 'OLISTENER_TICKET_URL' ) || define( 'OLISTENER_TICKET_URL', 'https://pluginbazar.com/my-account/tickets/?action=new' );
defined( 'OLISTENER_DOCS_URL' ) || define( 'OLISTENER_DOCS_URL', 'https://pluginbazar.com/docs/order-listener-for-woocommerce/' );
defined( 'OLISTENER_CONTACT_URL' ) || define( 'OLISTENER_CONTACT_URL', 'https://pluginbazar.com/contact/' );
defined( 'OLISTENER_REVIEW_URL' ) || define( 'OLISTENER_REVIEW_URL', 'https://wordpress.org/support/plugin/woc-order-alert/reviews/#new-post' );
defined( 'OLISTENER_DATA_TABLE' ) || define( 'OLISTENER_DATA_TABLE', $wpdb->prefix . 'woocommerce_order_listener' );


if ( ! class_exists( 'Olistener_main' ) ) {
	/**
	 * Class Olistener_main
	 */
	class Olistener_main {

		protected static $_instance = null;

		/**
		 * Olistener_main constructor.
		 */
		function __construct() {

			$this->loading_scripts();
			$this->loading_functions_classes();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		}


		/**
		 * @return \Olistener_main
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}


		/**
		 * Load Textdomain
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'woc-order-alert', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Loading Functions and Classes
		 */
		function loading_functions_classes() {

			require_once OLISTENER_PLUGIN_DIR . 'includes/class-pb-settings-3.3.php';
			require_once OLISTENER_PLUGIN_DIR . 'includes/class-hooks.php';
			require_once OLISTENER_PLUGIN_DIR . 'includes/class-functions.php';

			require_once OLISTENER_PLUGIN_DIR . 'includes/functions.php';
		}


		/**
		 * Admin Scripts
		 */
		function admin_scripts() {

			wp_enqueue_script( 'olistener-admin', plugins_url( '/assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'olistener-admin', 'olistener', array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'confirmText' => esc_html__( 'Are you really want to reset the listener?', 'woc-order-alert' ),
				'interval'    => olistener()->get_interval(),
			) );

			wp_enqueue_style( 'tool-tip', OLISTENER_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'pb-core', OLISTENER_PLUGIN_URL . 'assets/pb-core.css' );
			wp_enqueue_style( 'olistener-admin', OLISTENER_PLUGIN_URL . 'assets/admin/css/style.css' );
		}


		/**
		 * Loading Scripts
		 */
		function loading_scripts() {
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}
	}

	Olistener_main::instance();
}

function pb_sdk_init_woc_order_alert() {

	if ( ! class_exists( 'Pluginbazar\Client' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/sdk/class-client.php' );
	}

	global $olistener_sdk;

	$olistener_sdk = new Pluginbazar\Client( esc_html( 'Order Listener for WooCommerce' ), 'woc-order-alert', 36, '3.1.5' );
}

/**
 * @global \Pluginbazar\Client $olistener_sdk
 */
global $olistener_sdk;

pb_sdk_init_woc_order_alert();