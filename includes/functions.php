<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

/* 
Code for: Check New Order
*/	

function woa_ajax_order_viewed() {
	
	$order_id = isset( $_POST['order_id'] ) ? $_POST['order_id'] : 0;
	
	global $wpdb;
	$wpdb->delete( $wpdb->prefix . 'woocommerce_order_alert', array( 'order_id' => $order_id ) );
	
	die();
}
add_action('wp_ajax_woa_ajax_order_viewed', 'woa_ajax_order_viewed');
add_action('wp_ajax_nopriv_woa_ajax_order_viewed', 'woa_ajax_order_viewed');


function woa_ajax_check_new_order() {
	
	global $wpdb;
	$all_orders	= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_order_alert WHERE status = 'new'");
	$html 		= "";
	$cur_symbol	= get_woocommerce_currency_symbol();
	
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
	
	echo json_encode( array( 
		'count'	=> count( $all_orders ),
		'html' 	=> $html,
		'audio'	=> woa_get_alarm_audio()
	) );
	
	die();
}
add_action('wp_ajax_woa_ajax_check_new_order', 'woa_ajax_check_new_order');
add_action('wp_ajax_nopriv_woa_ajax_check_new_order', 'woa_ajax_check_new_order');

function woa_woocommerce_checkout_update_order_meta_function( $order_id, $data ){
	
	$woa_checking_status = get_option( 'woa_checking_status' );
	$woa_checking_status = empty( $woa_checking_status ) ? 'checking_off' : $woa_checking_status;
	
	if( $woa_checking_status == 'checking_on' ) :
		
		$order = wc_get_order( $order_id );
		
		if( woa_check_pro_conditions( $order ) ) {
			
			global $wpdb;
			$wpdb->insert( $wpdb->prefix . 'woocommerce_order_alert',  
				array( 
					'order_id' => $order_id,
					'user_id' => get_current_user_id(),
					'order_amount' => $order->get_total(),
					'status' => 'new',
					'datetime' => current_time('mysql'),
				)
			);
		}
		
	endif;
}
add_action('woocommerce_checkout_update_order_meta', 'woa_woocommerce_checkout_update_order_meta_function', 10, 2);

/* add_action('woocommerce_checkout_update_order_meta', 'woocommerce_checkout_update_order_meta', 10, 2);
function woocommerce_checkout_update_order_meta( $order_id, $data ){
	
	update_option( 'aaa_order_id', $order_id );
	update_option( 'aaa_order_data', $data );
}

add_action( 'wp_footer', 'wp_footer_function' );
function wp_footer_function(){
	
	$aaa_order_id = get_option( 'aaa_order_id' );
	$aaa_order_data = get_option( 'aaa_order_data' );
	
	$woa_checking_status = get_option( 'woa_checking_status' );
	$woa_checking_status = empty( $woa_checking_status ) ? 'checking_off' : $woa_checking_status;
	
	$order = wc_get_order( 112 );
		
	if( woa_check_pro_conditions( $order ) ) {
			
		
		echo "<pre>"; print_r( $order ); echo "</pre>";
	}
	
	echo "<pre>"; print_r( $aaa_order_data ); echo "</pre>";
} */


/* 
Code for: Update Checking Status
*/	

function woa_ajax_update_checking_status(){
	
	$status = isset( $_POST['status'] ) ? $_POST['status'] : 'checking_off';
	update_option( 'woa_checking_status', $status );
	die();
}
add_action('wp_ajax_woa_ajax_update_checking_status', 'woa_ajax_update_checking_status');
add_action('wp_ajax_nopriv_woa_ajax_update_checking_status', 'woa_ajax_update_checking_status');

	
/* 
Code for: Admin Notices
*/	

function woa_admin_notice__error() {
	
	$class 		= 'notice notice-error';
	$message 	= 'These features are not functional in free version !';
	$buy_link 	= 'http://pluginbazar.ml/product/woocommerce-order-alert/';
	
	printf('<div class="%1$s"><p>%2$s <a href="%3$s" target="_blank"> %4$s</a></p></div>', $class, $message, $buy_link, 'Purchase Now'); 
}
// add_action( 'admin_notices', 'woa_admin_notice__error' );

	

/* 
Code for: Panel Checker
*/

function action_woa_panel_checker_function(){
	
	include WOA_PLUGIN_DIR . "templates/admin-panel-checker.php";
}
add_action( 'woa_panel_checker', 'action_woa_panel_checker_function' );


/* 
Code for: Turn off checking on page load
*/

function action_woa_turnoff_checking_function(){
	if( isset( $_GET['page'] ) && $_GET['page'] == 'woc-order-alert' ) 
		update_option( 'woa_checking_status', 'checking_off' );
}
// add_action( 'admin_init', 'action_woa_turnoff_checking_function' );







/* 
Code for: Get alarm audio
*/	

function woa_get_alarm_audio(){
	
	$audio_file = '';
	$audio_file = empty( $audio_file ) ? WOA_PLUGIN_URL."assets/back/_1.mp3" : $audio_file;
	
	return $audio_file;
}

/* 
Code for: Check extra conditions
*/	

function woa_check_pro_conditions( $order = null ){
	
	return true;
}

