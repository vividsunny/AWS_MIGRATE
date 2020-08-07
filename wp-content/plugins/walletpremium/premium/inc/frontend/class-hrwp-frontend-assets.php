<?php

/**
 * Frontend Assets Premium
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Fronend_Assets' ) ) {

    /**
     * Class.
     */
    class HRWP_Fronend_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {
            add_action( 'hrw_frontend_after_enqueue_css' , array ( __CLASS__ , 'external_css_files' ) , 10 , 1 ) ;
            add_action( 'hrw_frontend_after_enqueue_js' , array ( __CLASS__ , 'external_js_files' ) , 10 , 1 ) ;
        }

        /**
         * Enqueue external CSS files
         */
        public static function external_css_files( $suffix ) {
            wp_register_style( 'hrw-inline-style' , false ) ; // phpcs:ignore
            wp_enqueue_style( 'hrw-inline-style' ) ;

            self::add_inline_style() ;

            //Partial form
            wp_enqueue_style( 'hrw_partial_form' , HRW_PLUGIN_URL . '/premium/assets/css/frontend/partial-form.css' , array () , HRW_VERSION ) ;
        }

        /**
         * Add Inline Style
         */
        public static function add_inline_style() {
            $contents = get_option( 'hrw_advanced_custom_css' , '' ) ;

            wp_add_inline_style( 'hrw-inline-style' , $contents ) ;
        }

        /**
         * Enqueue external JS files
         */
        public static function external_js_files( $suffix ) {

            //Enqueue frontend script
            wp_enqueue_script( 'hrw_frontend' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/frontend.js' , array ( 'jquery' ) , HRW_VERSION ) ;

            wp_localize_script(
                    'hrw_frontend' , 'hrw_frontend_params' , array (
                'ajax_url'             => admin_url( 'admin-ajax.php' ) ,
                'front_end_nonce'      => wp_create_nonce( 'hrw-frontend-nonce' ) ,
                'low_wallet_amount'    => get_option( 'hrw_advanced_low_wallet_amount_limit' ) ,
                'wallet_amount'        => HRW_Wallet_User::get_available_balance() ,
                'alert_message'        => get_option( 'hrw_advanced_alert_low_balance' ) ,
                'popup_low_wallet_msg' => get_option( 'hrw_messages_low_wallet_balance_msg' ) ,
                    )
            ) ;
        }

    }

    HRWP_Fronend_Assets::init() ;
}
