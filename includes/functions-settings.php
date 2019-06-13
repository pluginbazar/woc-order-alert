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
					'title'   => __( 'Demo installation', WOA_TD ),
					'details' => __( 'Please contact us for support, <a href="https://www.pluginbazar.net/demo/woocommerce-order-alert/wp-admin/admin.php?page=woc-order-alert">Demo installation view</a><br>Username: demo-woa<br>Password: demo-pluginbazar', WOA_TD ),
					'type'    => 'custom',
				),
				array(
					'id'      => 'woa_premium',
					'title'   => __( 'Buy Premium', WOA_TD ),
					'details' => __( 'Premium version is coming soon with lots of feature! Keep checking at <a href="https://www.pluginbazar.net">Pluginbazar</a>', WOA_TD ),
					'type'    => 'custom',
				),
				array(
					'id'      => 'woa_contact',
					'title'   => __( 'Contact for support', WOA_TD ),
					'details' => __( 'Getting error?<br>Please contact us for support, <a href="https://www.pluginbazar.net/forums/forum/woocommerce-order-alert/">https://www.pluginbazar.net/forums/forum/woocommerce-order-alert/</a>', WOA_TD ),
					'type'    => 'custom',
				),
				array(
					'id'      => 'woa_review',
					'title'   => __( 'Reviews', WOA_TD ),
					'details' => __( 'Love it?<br>Please Share your thoughts to let community know about this Plugin <a href="https://wordpress.org/support/plugin/woc-order-alert/reviews/#new-post">Add Review</a>', WOA_TD ),
					'type'    => 'custom',
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
	'disabled_notice' =>  sprintf( '%s! <a target="_blank" href="%s">%s</a>',
		esc_html__( 'Feature locked' ),
		esc_url_raw( 'https://pluginbazar.com/plugin/woocommerce-order-alert/', array( 'https' ) ),
		esc_html__( 'Try Premium' )
	),
);

new PB_Settings( $args );



