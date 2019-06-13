<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


$woa_panel_checker = array(
	'page_nav'      => __( 'Checker', WOA_TD ),
	'show_submit'   => false,
	'page_settings' => array(),
	'priority'      => 10,
);

$woa_panel_settings = array(

	'page_nav'      => __( 'Settings', WOA_TD ),
	'priority'      => 20,
	'show_submit'   => false,
	'page_settings' => array(

		'woa_settings_general' => array(
			'title'       => __( 'General', WOA_TD ),
			'description' => __( 'Updates settings from here', WOA_TD ),
			'options'     => array(
				array(
					'id'       => 'woa_settings_audio_file',
					'title'    => __( 'Custom alarm sound', WOA_TD ),
					'details'  => __( 'You can set any custom audio as Alarm.', WOA_TD ),
					'type'     => 'media',
					'disabled' => true,
				),
			)
		),

		'woa_settings_special' => array(
			'title'       => __( 'Special Parameter', WOA_TD ),
			'description' => __( 'Updates settings of order listener with some special parameter', WOA_TD ),
			'options'     => array(
				array(
					'id'       => 'woa_products_included',
					'title'    => __( 'Products included', WOA_TD ),
					'details'  => __( 'When any of these products are ordered only then alarm will start', WOA_TD ),
					'type'     => 'select2',
					'args'     => 'PICK_POSTS_%product%',
					'multiple' => true,
					'disabled' => true,
				),
				array(
					'id'          => 'woa_minimum_order_amount',
					'title'       => __( 'Minimum order amount', WOA_TD ),
					'details'     => __( 'When this selected amount or more will be ordered only then alarm will start', WOA_TD ),
					'type'        => 'number',
					'placeholder' => __( '100', WOA_TD ),
					'disabled'    => true,
				),
				array(
					'id'       => 'woa_check_alll_parameters',
					'title'    => __( 'Relation', WOA_TD ),
					'details'  => __( 'Do you want to pass all the conditions above?', WOA_TD ),
					'type'     => 'checkbox',
					'args'     => array( 'yes' => __( 'Check both conditions (AND)', WOA_TD ) ),
					'disabled' => true,
				),
			)
		),
	),
);

$woa_panel_help = array(

	'page_nav'      => __( 'Help', WOA_TD ),
	'show_submit'   => false,
	'priority'      => 40,
	'page_settings' => array(

		'pick_section_options' => array(
			'title'       => __( 'Help & support', WOA_TD ),
			'description' => __( 'Here is all about help and support.', WOA_TD ),
			'options'     => array(
				array(
					'id'      => 'woa_demo',
					'title'   => esc_html__( 'Demo installation', WOA_TD ),
					'details' => sprintf( '<a href="%s?ref=%s" target="_blank">%s</a><br>%s<br>%s',
						esc_url_raw( 'https://demo.pluginbazar.com/woocommerce-order-alert/wp-admin/admin.php?page=woc-order-alert', array( 'https' ) ),
						$_SERVER['HTTP_HOST'],
						esc_html__( 'Try Demo', WOA_TD ),
						esc_html__( 'Username: demo-woa', WOA_TD ),
						esc_html__( 'Password: pluginbazar', WOA_TD )
					),
				),
				array(
					'id'      => 'woa_premium',
					'title'   => esc_html__( 'Buy Premium', WOA_TD ),
					'details' => sprintf( '<a href="%s" target="_blank">%s</a> %s',
						esc_url_raw( 'https://pluginbazar.com/plugin/woocommerce-order-alert/', array( 'https' ) ),
						esc_html__( 'Try Premium', WOA_TD ),
						esc_html__( 'Change Alarm Audio and get notified for new orders in many ways.', WOA_TD ),
					),
				),
				array(
					'id'      => 'woa_contact',
					'title'   => __( 'Contact for support', WOA_TD ),
					'details' => sprintf( '%s<br>%s <a href="%s" target="_blank">%s</a>',
						esc_html__( 'Getting error? or Any Problem?', WOA_TD ),
						esc_html__( 'Please contact us for support, we will respond immediately.', WOA_TD ),
						esc_url_raw( 'https://pluginbazar.com/forums/forum/woocommerce-order-alert/', array( 'https' ) ),
						esc_html__( 'Ask Forum', WOA_TD )
					),
				),
				array(
					'id'      => 'woa_review',
					'title'   => __( 'Reviews', WOA_TD ),
					'details' => sprintf( '%s<br>%s <a href="%s" target="_blank">%s</a>',
						esc_html__( 'Love this Plugin?', WOA_TD ),
						esc_html__( 'Please Share your thoughts to let community know about this Plugin.', WOA_TD ),
						esc_url_raw( 'https://wordpress.org/support/plugin/woc-order-alert/reviews/#new-post', array( 'https' ) ),
						esc_html__( 'Add Review', WOA_TD )
					)
				),

			)
		),

	),

);


$args = array(
	'add_in_menu'     => true,
	'menu_type'       => 'submenu',
	'menu_title'      => __( 'Order Alert', WOA_TD ),
	'page_title'      => __( 'WooCommerce Order Alert', WOA_TD ),
	'menu_page_title' => __( 'WooCommerce Order Alert - Control System', WOA_TD ),
	'capability'      => "manage_woocommerce",
	'menu_slug'       => "woc-order-alert",
	'parent_slug'     => "woocommerce",
	'pages'           => apply_filters( 'woa_filters_setting_pages', array(
		'woa_panel_checker'  => $woa_panel_checker,
		'woa_panel_settings' => $woa_panel_settings,
		'woa_panel_help'     => $woa_panel_help,
	) ),
	'show_sidebar'    => true,
	'disabled_notice' => sprintf( '%s! <a target="_blank" href="%s">%s</a>',
		esc_html__( 'Feature locked' ),
		esc_url_raw( 'https://pluginbazar.com/plugin/woocommerce-order-alert/', array( 'https' ) ),
		esc_html__( 'Try Premium' )
	),
);

new PB_Settings( $args );



