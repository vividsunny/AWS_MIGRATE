<?php

/**
 * Plugin Name: Refund Premium
 * Description: Refund is a comprehensive WooCommerce Refund System which allows you to Handle Refund Requests from your Buyers and Process the Refund Requests within the Site.
 * Plugin URI:
 * Version: 2.1
 * Author: Hoicker
 * Author URI: https://hoicker.com
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

/**
 * Include once will help to avoid fatal error by load the files when you call init hook.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

/**
 * Function to check whether WooCommerce is active or not.
 */
if ( ! function_exists( 'hrr_maybe_woocommerce_active' ) ) {

	function hrr_maybe_woocommerce_active() {

		if ( is_multisite() ) {
			// This Condition is for Multi Site WooCommerce Installation.
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
				if ( is_admin() ) {
					add_action( 'init' , 'hrr_display_warning_message' ) ;
				}
				return false ;
			}
		} else {
			// This Condition is for Single Site WooCommerce Installation.
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				if ( is_admin() ) {
					add_action( 'init' , 'hrr_display_warning_message' ) ;
				}
				return false ;
			}
		}
		return true ;
	}

}

/**
 * Display Warning message.
 */
function hrr_display_warning_message() {
	echo "<div class='error'><p> Refund for WooCommerce Plugin will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>" ;
}

//Retrun if WooCommerce is not active.
if ( ! hrr_maybe_woocommerce_active() ) {
	return ;
}

//Define constant.
if ( ! defined( 'HRR_PLUGIN_FILE' ) ) {
	define( 'HRR_PLUGIN_FILE' , __FILE__ ) ;
}

//Include main class file.
if ( ! class_exists( 'HR_Refund' ) ) {
	include_once('inc/class-refund.php') ;
}

//Return Refund class object.
if ( ! function_exists( 'HRR' ) ) {

	function HRR() {
		return HR_Refund::instance() ;
	}

}

//Initialize the plugin.
HRR() ;
