<?php
/*
	Plugin Name: WooCommerce Order Alert
	Plugin URI: https://pluginbazar.net/
	Description: This is a awesome solution for WooCommerce Order Alert.
	Version: 3.0.0
	Author: Pluginbazar
	Author URI: https://www.pluginbazar.net/
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


class WooCommerceOrderAlert {


	/**
	 * WooCommerceOrderAlert constructor.
	 */
	public function __construct() {

		$this->define_constants();
		$this->loading_scripts();
		$this->loading_functions_classes();

		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		add_action( 'activated_plugin', array( $this, 'redirect_control_system' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}


	/**
	 * Load Textdomain
	 */
	public function load_textdomain() {

		load_plugin_textdomain( 'woc-order-alert', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Redirect to Plugin Checker Page after Activating the Plugin
	 *
	 * @param $plugin
	 */
	public function redirect_control_system( $plugin ) {

		if ( $plugin == 'woc-order-alert/woocommerce-order-alert.php' ) {
			wp_safe_redirect( admin_url( 'admin.php?page=woc-order-alert' ) );
			exit;
		}
	}


	/**
	 * On Activation Create the Custom Data Table
	 */
	public function activation() {

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}woocommerce_order_alert (
			id int(100) NOT NULL AUTO_INCREMENT,
			order_id int(100) NOT NULL,
			user_id int(100) NOT NULL,
			order_amount int(100) NOT NULL,
			status VARCHAR(50) NOT NULL,
			datetime DATETIME NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}


	/**
	 * Loading Functions and Classes
	 */
	public function loading_functions_classes() {

		require_once( plugin_dir_path( __FILE__ ) . 'includes/class-pb-settings.php' );

		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
		require_once( plugin_dir_path( __FILE__ ) . 'includes/functions-settings.php' );
	}


	/**
	 * Admin Scripts
	 */
	public function admin_scripts() {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'woa-admin-js', plugins_url( '/assets/back/js/scripts.js', __FILE__ ), array( 'jquery' ), date( "H:s" ) );
		wp_localize_script( 'woa-admin-js', 'woa_ajax', array( 'woa_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_style( 'woa_fontawesome', WOA_PLUGIN_URL . 'assets/fonts/font-awesome.css' );
		wp_enqueue_style( 'woa_admin_style', WOA_PLUGIN_URL . 'assets/back/css/style.css', array(), date( "H:s" ) );
	}


	/**
	 * Loading Scripts
	 */
	public function loading_scripts() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}


	/**
	 * Define Constants
	 */
	public function define_constants() {

		define( 'WOA_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
		define( 'WOA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WOA_IS_PRO', false );
		define( 'WOA_TD', 'woc-order-alert' );
		define( 'WOA_PB_API_URL', esc_url_raw( 'http://clients.local/api/wp-json/' ) );
	}
}

new WooCommerceOrderAlert();