<?php	
/*
* @Author 		Pluginbazar
* Copyright: 	2015 Pluginbazar
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

update_option( 'woa_checking_status', 'checking_off' );
// global $wpdb;

// $cur_symbol 	= get_woocommerce_currency_symbol();
// $all_orders 	= $wpdb->get_results("SELECT * FROM {$wpdb->prefix}woocommerce_order_alert");

?>

<div class="woa-panel-checker">

	<div class="pc-section pc-section-checker">
		
		<div class="pc-section-title">Check New Order</div>
		<div class="pc-checker-loading"><i class="fa fa-cog"></i></div>
		<div class="pc-checker-buttons">
			<div class="button pc-start"><span>Start Checking</span> &nbsp <i class="fa fa-play"></i></div>
			<div class="button pc-stop" disabled><span>Stop Checking</span> &nbsp <i class="fa fa-stop"></i></div>
			<div class="button pc-mute">Sound On <i class="fa fa-volume-up"></i></div>
		</div>
		
	</div>
	
	
	<div class="pc-section pc-section-orderlist">
		
		<div class="pc-section-title">New Order List</div>
		<div class="pc-orders-list">
		<?php 
		// foreach( $all_orders as $order ) : 
			
			// if( $order->user_id == 0 ) $customer_name = __("Guest", WOA_TD);
			// else {
				// $customer = get_userdata( $order->user_id );	
				// $customer_name = $customer->display_name;	
			// }
			// $order_date = date_i18n( "F j, Y, g:i a", strtotime( $order->datetime ) );
			
			// echo "<div class='single-order'>";
			// echo "<div class='meta order-id'><a target='_blank' href='post.php?post={$order->order_id}&action=edit'>#{$order->order_id}</a></div>";
			// echo "<div class='meta order-amount'>{$order->order_amount} $cur_symbol</div>";
			// echo "<div class='meta order-customer'>{$customer_name}</div>";
			// echo "<div class='meta order-date'><span>$order_date</span></div>";
			// echo "<div class='meta order-viewed button'><i class='fa fa-eye-slash'></i></div>";
			// echo "</div>";
		// endforeach;
		?>
		</div>
		
	</div>
	
	

</div>