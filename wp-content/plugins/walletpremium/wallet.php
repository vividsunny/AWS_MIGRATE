<?php

/*
 * Plugin Name: Wallet Premium
 * Description: Wallet is a WooCommerce plugin which allows your customers to add funds to their wallet and make use of the funds on future purchases.
 * Version: 2.6
 * Author: Hoicker
 * Author URI: https://hoicker.com
 * Text Domain: wallet
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 3.8.0
 */

if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

/* Include once will help to avoid fatal error by load the files when you call init hook */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

/**
 * Function to check whether WooCommerce is active or not
 */
function hrw_maybe_woocommerce_active() {

    if( is_multisite() ) {
        // This Condition is for Multi Site WooCommerce Installation
        if( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ( ! is_plugin_active( 'woocommerce/woocommerce.php' )) ) {
            if( is_admin() ) {
                add_action( 'init' , 'hrw_display_warning_message' ) ;
            }
            return false ;
        }
    } else {
        // This Condition is for Single Site WooCommerce Installation
        if( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            if( is_admin() ) {
                add_action( 'init' , 'hrw_display_warning_message' ) ;
            }
            return false ;
        }
    }
    return true ;
}

/**
 * Display Warning message
 */
function hrw_display_warning_message() {
    echo "<div class='error'><p> Wallet Plugin will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>" ;
}

// retrun if WooCommerce is not active
if( ! hrw_maybe_woocommerce_active() )
    return ;

//Define constant
if( ! defined( 'HRW_PLUGIN_FILE' ) ) {
    define( 'HRW_PLUGIN_FILE' , __FILE__ ) ;
}

// Include main class file
if( ! class_exists( 'HR_Wallet' ) )
    include_once('inc/class-wallet.php') ;

//return Wallet class object
if( ! function_exists( 'HRW' ) ) {

    function HRW() {
        return HR_Wallet::instance() ;
    }

}

//initialize the plugin. 
HRW() ;

