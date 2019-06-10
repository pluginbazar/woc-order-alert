<?php
/*
* @Author : PickPlugins
* @Copyright : 2015 PickPlugins.com
* @Version : 1.0.8
* @URL : https://github.com/jaedm97/Pick-Settings
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class Pick_settings_sidebar_woa {
	
    public function __construct(){
		
		add_action( 'pick_settings_sidebar_content', array( $this, 'pick_settings_sidebar_content' ) );
		add_filter( 'pick_settings_filter_sidebar_title', array( $this, 'pick_settings_filter_sidebar_title' ), 10, 1 );
	}
	
	function related_plugins(){
		
		return array(
			'woo-advanced-variation' => array(
				'title' => 'WooCommerce Advanced Variation',
				'details' => 'This is a WooCommerce extension to manage Variation easily for the customers while adding to cart.',
				'price' => 'Download Free',
				'link' => 'https://wordpress.org/plugins/woo-advanced-variation/',
			),
			'woc-open-close' => array(
				'title' => 'Woocommerce Open Close',
				'details' => 'Complete business hour/schedule management, completely hassle free',
				'price' => 'From 39$',
				'link' => 'https://www.pluginbazar.net/product/woocommerce-open-close/?r='.get_site_url(),
			),
			'woc-order-alert' => array(
				'title' => 'Woocommerce Order Alert',
				'details' => 'This is a awesome solution for WooCommerce Order Alert.',
				'price' => 'From 29$',
				'link' => 'https://www.pluginbazar.net/product/woocommerce-order-alert/?r='.get_site_url(),
			),
			'wp-poll' => array(
				'title' => 'WP Poll',
				'details' => 'Complete poll manager for WordPress',
				'price' => 'Download Free',
				'link' => 'https://wordpress.org/plugins/wp-poll/',
			),
			'wp-live-messenger' => array(
				'title' => 'WP Live Messenger',
				'details' => 'Just Put your Facebook Page URL and sit behind the Messenger to hear from your visitors.',
				'price' => 'Download Free',
				'link' => 'https://wordpress.org/plugins/wp-live-messenger/',
			),
		);
	}
	
	function pick_settings_filter_sidebar_title( $sidebar_title ){
		
		return __( 'Special Collections', WOA_TD );
	}
	
	public function pick_settings_sidebar_content() {
		
		echo "<div class='pb_related_plugins'>";
		foreach( $this->related_plugins() as $key => $plugin ):
		
			echo "<div class='pb_related_plugin'>";
			echo "<img src='".WOA_PLUGIN_URL."/assets/pb-images/$key.jpg' />";
			echo "<h3>{$plugin['title']}</h3>";
			echo "<p>{$plugin['details']}</p>";
			echo "<a class='pb_plugin_buy' href='{$plugin['link']}' target='_blank'>{$plugin['price']}</a>";
			echo "</div>";
		
		endforeach;
		echo "</div>";
		
echo "<style>
.pb_related_plugins {
    height: 450px;
    overflow-y: scroll;
}
.pb_related_plugins::-webkit-scrollbar {
    width: 5px;
} 
.pb_related_plugins::-webkit-scrollbar-thumb {
    background-color: #d84141;
    outline: none;
}

.pb_related_plugin {
    background: #dedede;
    padding: 15px;
    border-radius: 3px;
	margin-bottom: 15px;
}
.pb_related_plugin img {
    width: 100%;
}
.pb_plugin_buy {
    letter-spacing: 1px;
    display: inline-block;
    background: #208fce;
    color: #fff;
    padding: 5px 12px;
    font-size: 13px;
    cursor: pointer;
    -webkit-appearance: none;
    -webkit-font-smoothing: inherit;
    text-decoration: none !important;
    -webkit-transition: 0.15s all ease;
    -moz-transition: 0.15s all ease;
    -ms-transition: 0.15s all ease;
    -o-transition: 0.15s all ease;
    transition: 0.15s all ease;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}
.pb_plugin_buy:hover, .pb_plugin_buy:active, .pb_plugin_buy:focus {
    color: #fff;
    background: #096ca5;
	outline:none;
	box-shadow:none;
}
</style>";
	}
	
} new Pick_settings_sidebar_woa();