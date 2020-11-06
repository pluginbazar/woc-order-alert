<?php
/**
 * Class Functions
 *
 * @author Pluginbazar
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Olistener_functions' ) ) {
	/**
	 * Class Olistener_functions
	 */
	class Olistener_functions {


		/**
		 * Return interval in milliseconds
		 *
		 * @return mixed|void
		 */
		function get_interval() {

			return apply_filters( 'olistener_filters_interval', (int) round( 60 / (int) $this->get_option( 'olistener_req_per_minute', 30 ) * 1000 ) );
		}

		/**
		 * Check if this plugin is pro version or not
		 *
		 * @return bool
		 */
		function is_pro() {
			return class_exists( 'Olistener_pro_main' );
		}


		/**
		 * Return wpdb object
		 *
		 * @return wpdb
		 */
		function get_wpdb() {
			global $wpdb;

			return $wpdb;
		}

		/**
		 * Return settings pages
		 *
		 * @return mixed|void
		 */
		function get_settings_pages() {

			$settings = array(
				'listener'          => array(
					'page_nav'    => __( 'Checker', 'woc-order-alert' ),
					'show_submit' => false,
				),
				'listener-settings' => array(
					'page_nav'      => __( 'Settings', 'woc-order-alert' ),
					'page_settings' => array(
						array(
							'title'   => __( 'General', 'woc-order-alert' ),
							'options' => array(
								array(
									'id'       => 'olistener_audio',
									'title'    => __( 'Custom audio', 'woc-order-alert' ),
									'details'  => __( 'You can set any custom audio as alarm.', 'woc-order-alert' ),
									'type'     => 'media',
									'disabled' => ! olistener()->is_pro(),
								),
								array(
									'id'          => 'olistener_req_per_minute',
									'title'       => __( 'Requests per Minute', 'woc-order-alert' ),
									'details'     => __( 'You can limit the requests per minute to the server. We heard some servers do not allow too many requests per minute, to handle this case, just decrease the check per minute to the server.', 'woc-order-alert' ),
									'type'        => 'number',
									'default'     => '30',
									'placeholder' => '30',
								),
							)
						),
						array(
							'title'       => __( 'Searching Rules', 'woc-order-alert' ),
							'description' => __( 'You can configure custom searching rules for order listener. If you need to add any custom rules, Please contact support.', 'woc-order-alert' ),
							'options'     => array(
								array(
									'id'       => 'olistener_enable_rules',
									'title'    => __( 'Enable Searching Rules', 'woc-order-alert' ),
									'type'     => 'checkbox',
									'disabled' => ! olistener()->is_pro(),
									'args'     => array(
										'yes' => __( 'Yes! Enable these searching rules.', 'woc-order-alert' ),
									),
								),
								array(
									'id'            => 'olistener_products_included',
									'title'         => __( 'Products Included', 'woc-order-alert' ),
									'details'       => __( 'When any of these products are ordered only then alarm will start', 'woc-order-alert' ),
									'type'          => 'select2',
									'args'          => 'POSTS_%product%',
									'multiple'      => true,
									'disabled'      => ! olistener()->is_pro(),
									'field_options' => array(
										'placeholder' => __( 'Select Products', 'woc-order-alert' ),
									),
								),
								array(
									'id'            => 'olistener_categories_included',
									'title'         => __( 'Product Categories Included', 'woc-order-alert' ),
									'details'       => __( 'When any product from these categories are ordered only then alarm will start', 'woc-order-alert' ),
									'type'          => 'select2',
									'args'          => 'TAX_%product_cat%',
									'multiple'      => true,
									'disabled'      => ! olistener()->is_pro(),
									'field_options' => array(
										'placeholder' => __( 'Select Categories', 'woc-order-alert' ),
									),
								),
								array(
									'id'            => 'olistener_tags_included',
									'title'         => __( 'Product Tags Included', 'woc-order-alert' ),
									'details'       => __( 'When any product from these tags are ordered only then alarm will start', 'woc-order-alert' ),
									'type'          => 'select2',
									'args'          => 'TAX_%product_tag%',
									'multiple'      => true,
									'disabled'      => ! olistener()->is_pro(),
									'field_options' => array(
										'placeholder' => __( 'Select Tags', 'woc-order-alert' ),
									),
								),
								array(
									'id'          => 'olistener_min_order_amount',
									'title'       => __( 'Minimum order amount', 'woc-order-alert' ),
									'details'     => __( 'When this selected amount or more will be ordered only then alarm will start', 'woc-order-alert' ),
									'type'        => 'number',
									'placeholder' => __( '100', 'woc-order-alert' ),
									'disabled'    => ! olistener()->is_pro(),
								),
								array(
									'id'       => 'olistener_rules_relation',
									'title'    => __( 'Relation', 'woc-order-alert' ),
									'type'     => 'checkbox',
									'args'     => array(
										'products'   => __( 'Products', 'woc-order-alert' ),
										'categories' => __( 'Product Categories', 'woc-order-alert' ),
										'tags'       => __( 'Product Tags', 'woc-order-alert' ),
										'amount'     => __( 'Order Minimum Amount', 'woc-order-alert' ),
									),
									'details'  => __( 'Please select the conditions you wish to check for new order checking. If you leave empty, system will notify you if any of the condition is matched.', 'woc-order-alert' ),
									'disabled' => ! olistener()->is_pro(),
								),
							)
						),
					),
				),
				'listener-help'     => array(
					'page_nav'      => __( 'Help', 'woc-order-alert' ),
					'show_submit'   => false,
					'priority'      => 40,
					'page_settings' => array(

						array(
							'title'       => __( 'Help & support', 'woc-order-alert' ),
							'description' => __( 'Here is all about help and support.', 'woc-order-alert' ),
							'options'     => array(
								array(
									'id'      => '__1',
									'title'   => esc_html__( 'Support Ticket', 'woc-order-alert' ),
									'details' => sprintf( '%1$s<br>' . __( '<a href="%1$s" target="_blank">Create Support Ticket</a>', 'woc-order-alert' ), OLISTENER_TICKET_URL ),
								),
								array(
									'id'      => '__2',
									'title'   => esc_html__( 'Can\'t Login..?', 'woc-order-alert' ),
									'details' => sprintf( __( '<span>Unable to login <strong>Pluginbazar.com</strong></span><br><a href="%1$s" target="_blank">Get Immediate Solution</a>', 'woc-order-alert' ), OLISTENER_CONTACT_URL ),
								),
								array(
									'id'      => '__3',
									'title'   => esc_html__( 'Like this Plugin?', 'woc-order-alert' ),
									'details' => sprintf( __( '<span>To share feedback about this plugin Please </span><br><a href="%1$s" target="_blank">Rate now</a>', 'woc-order-alert' ), OLISTENER_REVIEW_URL ),
								),
							)
						),
					),
				),
			);

			return apply_filters( 'olistener_filters_setting_pages', $settings );
		}

		/**
		 * PB_Settings Class
		 *
		 * @param array $args
		 *
		 * @return PB_Settings
		 */
		function PB_Settings( $args = array() ) {

			return new PB_Settings( $args );
		}


		/**
		 * Print notice to the admin bar
		 *
		 * @param string $message
		 * @param bool $is_success
		 * @param bool $is_dismissible
		 */
		function print_notice( $message = '', $is_success = true, $is_dismissible = true ) {

			if ( empty ( $message ) ) {
				return;
			}

			if ( is_bool( $is_success ) ) {
				$is_success = $is_success ? 'success' : 'error';
			}

			printf( '<div class="notice notice-%s %s"><p>%s</p></div>', $is_success, $is_dismissible ? 'is-dismissible' : '', $message );
		}


		/**
		 * Return option value
		 *
		 * @param string $option_key
		 * @param string $default_val
		 *
		 * @return mixed|string|void
		 */
		function get_option( $option_key = '', $default_val = '' ) {

			if ( empty( $option_key ) ) {
				return '';
			}

			$option_val = get_option( $option_key, $default_val );
			$option_val = empty( $option_val ) ? $default_val : $option_val;

			return apply_filters( 'woc_filters_option_' . $option_key, $option_val );
		}


		/**
		 * Return Post Meta Value
		 *
		 * @param bool $meta_key
		 * @param bool $post_id
		 * @param string $default
		 *
		 * @return mixed|string|void
		 */
		function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

			if ( ! $meta_key ) {
				return '';
			}

			$post_id    = ! $post_id ? get_the_ID() : $post_id;
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			$meta_value = empty( $meta_value ) ? $default : $meta_value;

			return apply_filters( 'woc_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
		}


		/**
		 * Return Arguments Value
		 *
		 * @param string $key
		 * @param string $default
		 * @param array $args
		 *
		 * @return mixed|string
		 */
		function get_args_option( $key = '', $default = '', $args = array() ) {

			global $wooopenclose_args;

			$args    = empty( $args ) ? $wooopenclose_args : $args;
			$default = empty( $default ) ? '' : $default;
			$key     = empty( $key ) ? '' : $key;

			if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
				return $args[ $key ];
			}

			return $default;
		}
	}
}

global $olistener;
$olistener = new Olistener_functions();