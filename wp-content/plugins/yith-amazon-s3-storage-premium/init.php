<?php
/**
 * Plugin Name: YITH Amazon S3 Storage for WooCommerce Premium
 * Plugin URI: https://yithemes.com/themes/plugins/yith-amazon-s3-storage/
 * Description: <code><strong>YITH Amazon S3 Storage</strong></code> allows you to store all your media library and your downloadable products for WooCommerce in Amazon S3. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 1.1.12
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-amazon-s3-storage
 * Domain Path: /languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 4.2
 **/

/*
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/*
==========
  DEFINE
==========
*/
! defined( 'YITH_AS3S_CONSTANT_NAME' ) && define( 'YITH_AS3S_CONSTANT_NAME', 'AMAZON_S3_STORAGE' );
! defined( 'YITH_AS3S_FILES_INCLUDE_NAME' ) && define( 'YITH_AS3S_FILES_INCLUDE_NAME', 'amazon-s3-storage' );


! defined( 'YITH_WC_AMAZON_S3_STORAGE_VERSION' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_VERSION', '1.1.12' );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_INIT' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_INIT', plugin_basename( __FILE__ ) );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_SLUG' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_SLUG', 'yith-amazon-s3-storage' );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_SECRETKEY' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_SECRETKEY', 'D5utgyPIrfzFJkG3dW7y' );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_FILE' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_FILE', __FILE__ );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_URL' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_URL', plugins_url( '/', __FILE__ ) );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_ASSETS_URL', YITH_WC_AMAZON_S3_STORAGE_URL. 'assets/' );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_TEMPLATE_PATH' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_TEMPLATE_PATH', YITH_WC_AMAZON_S3_STORAGE_PATH . 'templates/' );
! defined( 'YITH_WC_AMAZON_S3_STORAGE_OPTIONS_PATH' ) && define( 'YITH_WC_AMAZON_S3_STORAGE_OPTIONS_PATH', YITH_WC_AMAZON_S3_STORAGE_PATH . 'plugin-options' );

/*
====================================================================
 Sessions initiation and set them up to destroy when log in and out
====================================================================
*/

if ( ! function_exists( 'yith_wc_' . YITH_AS3S_CONSTANT_NAME . '_cyb_session_start' ) ) {

	$functionname = 'yith_wc_' . YITH_AS3S_CONSTANT_NAME . '_cyb_session_start';

	$$functionname = function () {
		if ( ! session_id() ) {
			session_start();
		}

	};

    add_action('muplugins_loaded',$$functionname, 1);

}

if ( ! function_exists( 'yith_wc_' . YITH_AS3S_CONSTANT_NAME . '_cyb_session_end' ) ) {

	$functionname = 'yith_wc_' . YITH_AS3S_CONSTANT_NAME . '_cyb_session_end';

	$$functionname = function () {
	    if( session_id() ) {
            session_destroy();
        }
	};

	add_action( 'wp_logout', $$functionname );
	add_action( 'wp_login', $$functionname );
}

/*
================================
 Plugin Framework Version Check
================================
*/
! function_exists( 'yit_maybe_plugin_fw_loader' ) && require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'plugin-fw/init.php' );
yit_maybe_plugin_fw_loader( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) );

/* Load text domain */
load_plugin_textdomain( 'yith-amazon-s3-storage', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/*
===============================
 * Instance main plugin class
===============================
*/

if ( ! function_exists( 'YITH_WC_AMAZON_S3_STORAGE_MAIN' ) ) {
	/**
	 * Unique access to instance of YITH_WC_AMAZON_S3_STORAGE class
	 *
	 * @return YITH_WC_AMAZON_S3_STORAGE_MAIN Premium
	 * @since 1.0.0
	 */

	function YITH_WC_AMAZON_S3_STORAGE_MAIN() {

		// Load required classes and functions
		require_once( constant( 'YITH_WC_AMAZON_S3_STORAGE_PATH' ) . 'includes/class.yith-wc-amazon-s3-storage.php' );

		return YITH_WC_Amazon_S3_Storage_Main_Class::instance();

	}
}

if ( ! function_exists( 'yith_wc_amazon_s3_storage_install' ) ) {

	function yith_wc_amazon_s3_storage_install() {

		/**
		 * Instance main plugin class
		 */
		YITH_WC_AMAZON_S3_STORAGE_MAIN();

        /* Load amazon s3 storage text domain */
        load_plugin_textdomain( 'yith-amazon-s3-storage', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}
}

add_action( 'plugins_loaded', 'yith_wc_amazon_s3_storage_install', 11 );
