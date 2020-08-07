<?php
/**
 * Plugin Name:       Multistore Synchronization
 * Description:       Popupcomics Multistore
 * Plugin URI:       
 * Version:           1.0.0
 * Author:            Team Vivid
 * Author URI:        http://vividwebsolutions.in/
 * Requires at least: 4.9
 * Tested up to:      5.3.2
 *
 * @package PMS_store
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main PMS_store Class
 *
 * @class PMS_store
 * @version	1.0.0
 * @since 1.0.0
 * @package	PMS_store
 */
final class PMS_store {

	/**
	 * Set up the plugin
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'PMS_store_setup' ), -1 );
		require_once( 'custom/pms_functions.php' );
		require_once( 'class/pms_class.php' );
		require_once( 'class/pms_ajax.php' );
	}

	/**
	 * Setup all the things
	 */
	public function PMS_store_setup() {
		add_action( 'admin_enqueue_scripts', array( $this, 'PMS_store_css' ), 999 );
		add_action( 'admin_enqueue_scripts', array( $this, 'PMS_store_js' ) );
	}

	/**
	 * Enqueue the CSS
	 *
	 * @return void
	 */
	public function PMS_store_css() {
		wp_enqueue_style( 'pms_store', plugins_url( '/assets/pms_store.css', __FILE__ ) );
	}

	/**
	 * Enqueue the Javascript
	 *
	 * @return void
	 */
	public function PMS_store_js() {
		wp_enqueue_script( 'pms_store', plugins_url( '/assets/pms_store.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'pms_store', 'pms_store_script', array(
	        'ajax_url' =>  admin_url("admin-ajax.php") ,
	        'plugin_dir' =>  plugin_dir_path( __FILE__ ),
      	) );
	}
	
} // End Class

/**
 * The 'main' function
 *
 * @return void
 */
function PMS_store_main() {
	new PMS_store();
}

/**
 * Initialise the plugin
 */
add_action( 'plugins_loaded', 'PMS_store_main' );
