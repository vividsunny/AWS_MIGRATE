<?php

/**
 * Frontend Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Fronend_Assets' ) ) {

    /**
     * Class.
     */
    class HRW_Fronend_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
            add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
        }

        /**
         * Enqueue external CSS files
         */
        public static function external_css_files() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;
            //Enqueue Top-up form CSS
            wp_enqueue_style( 'hrw_Top-up_form' , HRW_PLUGIN_URL . '/assets/css/frontend/topup-form.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'hrw_dashboard' , HRW_PLUGIN_URL . '/assets/css/frontend/dashboard.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'font-awesome' , HRW_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , HRW_VERSION ) ;
            wp_enqueue_style( 'jquery-ui' , HRW_PLUGIN_URL . '/assets/css/jquery-ui' . $suffix . '.css' , array() , HRW_VERSION ) ;

            do_action( 'hrw_frontend_after_enqueue_css' , $suffix ) ;
        }

        /**
         * Enqueue external JS files
         */
        public static function external_js_files() {
            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;
            //Block UI
            wp_register_script( 'blockUI' , HRW_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;
            //Enqueue select2 related js and css
            self::select2( $suffix ) ;
            
            do_action( 'hrw_frontend_after_enqueue_js' , $suffix ) ;
        }

        /**
         * Enqueue select2 scripts and CSS
         */
        public static function select2( $suffix ) {
            wp_enqueue_style( 'select2' , HRW_PLUGIN_URL . '/assets/css/select2/select2' . $suffix . '.css' , array() , '4.0.5' ) ;

            wp_register_script( 'select2' , HRW_PLUGIN_URL . '/assets/js/select2/select2' . $suffix . '.js' , array( 'jquery' ) , '4.0.5' ) ;
            wp_enqueue_script( 'hrw-enhanced' , HRW_PLUGIN_URL . '/assets/js/hrw-enhanced.js' , array( 'jquery' , 'select2' , 'jquery-ui-datepicker' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-enhanced' , 'hrw_enhanced_select_params' , array(
                'search_nonce' => wp_create_nonce( 'hrw-search-nonce' ) ,
                'ajaxurl'      => HRW_ADMIN_AJAX_URL
                    )
            ) ;
        }

    }

    HRW_Fronend_Assets::init() ;
}
