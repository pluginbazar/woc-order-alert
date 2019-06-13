<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! function_exists( 'woa_ajax_order_viewed' ) ) {
	/**
	 * xAjax Call for New Order Checking
	 */
	function woa_ajax_order_viewed() {

		$order_id = isset( $_POST['order_id'] ) ? $_POST['order_id'] : 0;

		global $wpdb;
		$wpdb->delete( $wpdb->prefix . 'woocommerce_order_alert', array( 'order_id' => $order_id ) );

		wp_send_json_success();
	}
}
add_action( 'wp_ajax_woa_ajax_order_viewed', 'woa_ajax_order_viewed' );
add_action( 'wp_ajax_nopriv_woa_ajax_order_viewed', 'woa_ajax_order_viewed' );


if ( ! function_exists( 'woa_ajax_check_new_order' ) ) {
	/**
	 * Check New Order
	 */
	function woa_ajax_check_new_order() {

		global $wpdb;
		$all_orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_order_alert WHERE status = 'new'" );
		$html       = "";
		$cur_symbol = get_woocommerce_currency_symbol();

		foreach( $all_orders as $order ) :

			if( $order->user_id == 0 ) $customer_name = __("Guest", WOA_TD);
			else {
				$customer = get_userdata( $order->user_id );
				$customer_name = $customer->display_name;
			}
			$order_date = date_i18n( "F j, Y, g:i a", strtotime( $order->datetime ) );

			$wpdb->update( $wpdb->prefix . 'woocommerce_order_alert',
				array( 'status' => 'checked'  	),
				array( 'id' 	=>  $order->id 	)
			);

			$html .= "<div class='single-order'>";
			$html .= "<div class='meta order-id'><a target='_blank' href='post.php?post={$order->order_id}&action=edit'>#{$order->order_id}</a></div>";
			$html .= "<div class='meta order-amount'>{$order->order_amount} $cur_symbol</div>";
			$html .= "<div class='meta order-customer'>{$customer_name}</div>";
			$html .= "<div class='meta order-date'><span>$order_date</span></div>";
			$html .= "<div class='meta order-viewed button' order_id='{$order->order_id}'><i class='fa fa-eye-slash'></i></div>";
			$html .= "</div>";

		endforeach;

		wp_send_json_success(
			array(
//			    'count'	=> count( $all_orders ),
				'count' => 1,
				'html'  => $html,
				'audio' => woa_get_alarm_audio()
			)
		);
	}
}
add_action( 'wp_ajax_woa_ajax_check_new_order', 'woa_ajax_check_new_order' );
add_action( 'wp_ajax_nopriv_woa_ajax_check_new_order', 'woa_ajax_check_new_order' );


if ( ! function_exists( 'woa_woocommerce_checkout_update_order_meta_function' ) ) {
	/**
	 * Update order meta function
	 *
	 * @param $order_id
	 * @param $data
	 */
	function woa_woocommerce_checkout_update_order_meta_function( $order_id, $data ) {

		$woa_checking_status = get_option( 'woa_checking_status' );
		$woa_checking_status = empty( $woa_checking_status ) ? 'checking_off' : $woa_checking_status;

		if ( $woa_checking_status == 'checking_on' ) :

			$order = wc_get_order( $order_id );

			global $wpdb;
			$wpdb->insert( $wpdb->prefix . 'woocommerce_order_alert',
				array(
					'order_id'     => $order_id,
					'user_id'      => get_current_user_id(),
					'order_amount' => $order->get_total(),
					'status'       => 'new',
					'datetime'     => current_time( 'mysql' ),
				)
			);

		endif;
	}
}
add_action( 'woocommerce_checkout_update_order_meta', 'woa_woocommerce_checkout_update_order_meta_function', 10, 2 );


if ( ! function_exists( 'woa_ajax_update_checking_status' ) ) {
	/**
	 * Update Checking Status
	 */
	function woa_ajax_update_checking_status() {

		$status = isset( $_POST['status'] ) ? $_POST['status'] : 'checking_off';
		update_option( 'woa_checking_status', $status );
		die();
	}
}
add_action( 'wp_ajax_woa_ajax_update_checking_status', 'woa_ajax_update_checking_status' );
add_action( 'wp_ajax_nopriv_woa_ajax_update_checking_status', 'woa_ajax_update_checking_status' );


if ( ! function_exists( 'action_woa_panel_checker_function' ) ) {
	/**
	 * Output for Panel Checker
	 */
	function action_woa_panel_checker_function() {

		include WOA_PLUGIN_DIR . "templates/admin-panel-checker.php";
	}
}
add_action( 'woa_panel_checker', 'action_woa_panel_checker_function' );


if ( ! function_exists( 'woa_get_alarm_audio' ) ) {
	/**
	 * Get alarm audio URL
	 *
	 * @return string
	 */
	function woa_get_alarm_audio() {

		$audio_file = get_option( 'woa_settings_audio_file', '' );

		/**
		 * Check from API
		 */
		if ( empty( $audio_file ) && ! WOA_IS_PRO ) {

			$api_url    = sprintf( '%swoa/v1/get-info', WOA_PB_API_URL );
			$response   = wp_remote_retrieve_body( wp_remote_get( $api_url ) );
			$response   = $response ? json_decode( $response ) : false;
			$audio_file = $response ? $response->audio : '';

//			update_option( 'woa_settings_audio_file', $audio_file );
		}

		return $audio_file;
	}
}