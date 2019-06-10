<?php
/*
* @Author : PickPlugins
* @Copyright : 2015 PickPlugins.com
* @Version : 1.0.8
* @URL : https://github.com/jaedm97/Pick-Settings
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class PB_setting_pages_woa{
	
    public function __construct(){
		
		add_filter( 'woa_filters_setting_pages', array( $this, 'woa_filters_setting_pages_function' ), 10, 1 );
		add_action( 'pick_settings_woa_license_key_status_text', array( $this, 'woa_license_key_status_text' ), 10, 1 );
		add_action( 'pick_settings_woa_license_key_buttons', array( $this, 'woa_license_key_buttons' ), 10, 1 );
		add_action( 'admin_init', array( $this, 'woa_admin_init' ), 10, 1 );
	}

	
	function woa_admin_init(){
		
		
		$slm_action 		= isset( $_GET['slm_action'] ) ? sanitize_text_field( $_GET['slm_action'] ) : '';
		$woa_license_key 	= get_option( 'woa_license_key' );
		
		if( empty( $slm_action ) || empty( $woa_license_key ) ) return;
		
	
		// echo "<pre>"; print_r( $response ); echo "</pre>";
		
		$api_params = array(
			'slm_action' => $slm_action,
			'secret_key' => '587a84a6de8425.16921718',
			'license_key' => $woa_license_key,
			'registered_domain' => $_SERVER['SERVER_NAME'],
			'item_reference' => urlencode(1020),
		);
		
		$response = wp_remote_get(add_query_arg($api_params, "https://www.pluginbazar.net"), array('timeout' => 20, 'sslverify' => false));
		
		if( is_wp_error($response) ){
			printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error is-dismissible', "Unexpected Error!" ); 
			return;
		}
		
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		if($license_data->result == 'success'){
			
			printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-success is-dismissible', $license_data->message ); 
			update_option( 'woa_license_key_status', 'activated' );
		}
		else {
			
			printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-error is-dismissible', $license_data->message ); 
			update_option( 'woa_license_key_status', 'deactivated' );
		}
		
		wp_redirect( "admin.php?page=woc-order-alert&tab=woa_panel_license" );
	}
	
	public function woa_license_key_buttons( $option ){
		
		global $pagenow;
		parse_str( $_SERVER['QUERY_STRING'], $url_args );
			
		$url_activate 	= http_build_query( array_merge( $url_args, array( 'slm_action' => 'slm_activate' ) ) );
		$url_deactivate = http_build_query( array_merge( $url_args, array( 'slm_action' => 'slm_deactivate' ) ) );

		echo sprintf("<a class='button' href='$pagenow?%s'>%s</a>", $url_activate, __( 'Activate', WOA_TD ) );
		echo "&nbsp";
		echo sprintf("<a class='button' href='$pagenow?%s'>%s</a>", $url_deactivate, __( 'Deactivate', WOA_TD ) );
	}
	
	public function woa_license_key_status_text( $option ){
		
		$woa_license_key_status = get_option( 'woa_license_key_status' );
		$woa_license_key_status = empty( $woa_license_key_status ) ? 'deactivated' : $woa_license_key_status;
		
		echo $woa_license_key_status == 'activated'		
		?	 sprintf("<span style='color:#1CA361;'>%s</span>", __( 'Activated', WOA_TD ) )
		:	 sprintf("<span style='color:#E64A19;'>%s</span>", __( 'Deactivated', WOA_TD ) );
	}
	
	public function woa_filters_setting_pages_function( $setting_pages ){
		
	  $setting_pages['woa_panel_license'] = array(
		'page_nav' => __( 'License', WOA_TD ),
		'priority' => 30,
		// 'show_submit' => false,
		'page_settings' => array(
	
		  'pick_section_keys'	=> array(
			'title' 			=> 	__('License Key Manager',WOA_TD),
			'description' 		=> __('Save and manage your license key',WOA_TD),
			'options' => array(
				array(
					'id'		=> 'woa_license_key_status_text',
					'title'		=> __('License Status',WOA_TD),
					'type'		=> 'custom',
				),
				array(
					'id'		=> 'woa_license_key',
					'title'		=> __('License Key',WOA_TD),
					'details'	=> __('Enter your license key',WOA_TD),
					'type'		=> 'text',
				),
				// array(
					// 'id'		=> 'woa_license_key_status',
					// 'title'		=> __('License Action',WOA_TD),
					// 'details'	=> __('Do you want to activate or deactivate the License key for this Domain?',WOA_TD),
					// 'type'		=> 'select',
					// 'args'		=> array(
						// 'activated' => __( 'Activated', WOA_TD ),
						// 'deactivated' => __( 'Deactivated', WOA_TD ),
					// ),
				// ),
				array(
					'id'		=> 'woa_license_key_buttons',
					'title'		=> __('License Action',WOA_TD),
					'type'		=> 'custom',
				),
			)
		  ),
	    ),
	  );
	  
	  return $setting_pages;
	}
	
} new PB_setting_pages_woa();