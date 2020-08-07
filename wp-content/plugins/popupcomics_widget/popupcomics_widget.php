<?php
/**
 * Plugin Name:       Popupcomics Widget
 * Description:       A widget to create script for display of new arrivals in various locations.
 * Plugin URI:        
 * Version:           1.0.0
 * Author:            Team Vivid
 * Author URI:        http://vividwebsolutions.in/
 * Requires at least: 3.0.0
 * Tested up to:      4.4.2
 *
 * @package popupcomics_Widget
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WIDGET_TEMPLATE_DIR', plugin_dir_path( __FILE__ ) .'custom/templates/' );

/**
 * Main popupcomics_Widget Class
 *
 * @class popupcomics_Widget
 * @version	1.0.0
 * @since 1.0.0
 * @package	popupcomics_Widget
 */

require_once( 'class/class_create_page.php' );

final class popupcomics_Widget {

	/**
	 * Set up the plugin
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'popupcomics_Widget_setup' ), -1 );
		require_once( 'custom/widget_functions.php' );
		require_once( 'custom/popupcomics_setting.php' );
		require_once( 'class/class_template_page.php' );
		require_once( 'class/class_shortcode.php' );
		require_once( 'class/class_ajax.php' );
	}

	/**
	 * Setup all the things
	 */
	public function popupcomics_Widget_setup() {
		add_action( 'wp_enqueue_scripts', array( $this, 'popupcomics_Widget_css' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'popupcomics_Widget_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'popupcomics_Widget_admin_js' ) );
		add_filter( 'template_include',   array( $this, 'popupcomics_Widget_template' ), 11 );
		add_filter( 'wc_get_template',    array( $this, 'popupcomics_Widget_wc_get_template' ), 11, 5 );
		
	}

	/**
	 * Enqueue the CSS
	 *
	 * @return void
	 */
	public function popupcomics_Widget_css() {
		wp_enqueue_style( 'popupcomics_Widget-custom-css', plugins_url( '/assets/popupcomics_Widget_style.css', __FILE__ ) );
	}

	/**
	 * Enqueue the Javascript
	 *
	 * @return void
	 */
	public function popupcomics_Widget_admin_js() {
		wp_register_script( 'widget_admin', plugins_url( '/assets/admin/widget_admin.js', __FILE__ ), array( 'wp-color-picker' ) );
     	wp_localize_script( 'widget_admin', 'widget_admin_script', array(
	        'ajax_url' =>  admin_url("admin-ajax.php") ,
	        'plugin_dir' =>  plugin_dir_path( __FILE__ ),
      	) );
	}
	/**
	 * Enqueue the Javascript
	 *
	 * @return void
	 */
	public function popupcomics_Widget_js() {
		// wp_enqueue_script( 'popupcomics_Widget-custom-js', plugins_url( '/assets/popupcomics_Widget_custom.js', __FILE__ ), array( 'jquery' ) );
		
		wp_register_script( 'widget_grid', plugins_url( '/assets/widget_grid.js', __FILE__ ) );
     	wp_localize_script( 'widget_grid', 'widget_script', array(
	        'ajax_url' =>  admin_url("admin-ajax.php") ,
	        'plugin_dir' =>  plugin_dir_path( __FILE__ ),
      	) );

      	// This script will contain all the code which will process our load more button
      	wp_register_script( 'widget_loadmore', plugins_url( '/assets/widget_loadmore.js', __FILE__ ), array('jquery') );
      	wp_localize_script( 'widget_loadmore', 'widget_loadmore_params', array(
        	'ajaxurl' => admin_url( 'admin-ajax.php' )
      	) );

	}

	/**
	 * Look in this plugin for template files first.
	 * This works for the top level templates (IE single.php, page.php etc). However, it doesn't work for
	 * template parts yet (content.php, header.php etc).
	 *
	 * Relevant trac ticket; https://core.trac.wordpress.org/ticket/13239
	 *
	 * @param  string $template template string.
	 * @return string $template new template string.
	 */
	public function popupcomics_Widget_template( $template ) {
		if ( file_exists( untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/custom/templates/' . basename( $template ) ) ) {
			$template = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/custom/templates/' . basename( $template );
		}

		return $template;
	}

	/**
	 * Look in this plugin for WooCommerce template overrides.
	 *
	 * For example, if you want to override woocommerce/templates/cart/cart.php, you
	 * can place the modified template in <plugindir>/custom/templates/woocommerce/cart/cart.php
	 *
	 * @param string $located is the currently located template, if any was found so far.
	 * @param string $template_name is the name of the template (ex: cart/cart.php).
	 * @return string $located is the newly located template if one was found, otherwise
	 *                         it is the previously found template.
	 */
	public function popupcomics_Widget_wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
		$plugin_template_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/custom/templates/woocommerce/' . $template_name;

		if ( file_exists( $plugin_template_path ) ) {
			$located = $plugin_template_path;
		}

		return $located;
	}

} // End Class

/**
 * The 'main' function
 *
 * @return void
 */
function popupcomics_Widget_main() {
	new popupcomics_Widget();
}

/**
 * Initialise the plugin
 */
add_action( 'plugins_loaded', 'popupcomics_Widget_main' );
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );
// add_action( 'plugins_loaded', array( 'PageCreate', 'get_instance' ) );
register_activation_hook( __FILE__, array( 'PageCreate', 'popupcomics_Widget_plugin_activation' ) );