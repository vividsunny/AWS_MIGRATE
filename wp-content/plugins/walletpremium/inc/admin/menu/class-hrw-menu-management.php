<?php

/*
 * Menu Management
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Menu_Management' ) ) {

    include_once('class-hrw-settings.php') ;

    /**
     * HRW_Menu_Management Class.
     */
    class HRW_Menu_Management {

        /**
         * Plugin slug.
         */
        protected static $plugin_slug = 'hrw' ;

        /**
         * Menu slug.
         */
        protected static $menu_slug = 'hrw_wallet' ;

        /**
         * Settings slug.
         */
        protected static $settings_slug = 'hrw_settings' ;

        /**
         * Class initialization.
         */
        public static function init() {
            add_action( 'admin_menu' , array( __CLASS__ , 'add_menu_pages' ) ) ;
        }

        /**
         * Add menu pages
         */
        public static function add_menu_pages() {
            $dash_icon_url = HRW_PLUGIN_URL . '/assets/images/dash-icon.png' ;
            //Add Menu Page
            add_menu_page( esc_html__( 'Wallet Premium' , HRW_LOCALE ) , esc_html__( 'Wallet Premium' , HRW_LOCALE ) , 'manage_options' , self::$menu_slug , '' , $dash_icon_url ) ;

            //Settings Submenu
            $settings_page = add_submenu_page( self::$menu_slug , esc_html__( 'Settings' , HRW_LOCALE ) , esc_html__( 'Settings' , HRW_LOCALE ) , 'manage_options' , self::$settings_slug , array( __CLASS__ , 'settings_page' ) ) ;

            add_action( sanitize_key( 'load-' . $settings_page ) , array( __CLASS__ , 'settings_page_init' ) ) ;
        }

        /**
         * Settings page init
         */
        public static function settings_page_init() {
            global $current_tab , $current_section , $current_sub_section , $current_action ;

            // Include settings pages.
            $settings = HRW_Settings::get_settings_pages() ;

            $tabs = hrw_get_allowed_setting_tabs() ;

            // Get current tab/section.
            $current_tab = ( empty( $_GET[ 'tab' ] ) || ! array_key_exists( $_GET[ 'tab' ] , $tabs )) ? key( $tabs ) : sanitize_title( wp_unslash( $_GET[ 'tab' ] ) ) ;

            $section = isset( $settings[ $current_tab ] ) ? $settings[ $current_tab ]->get_sections() : array() ;

            $current_section     = empty( $_REQUEST[ 'section' ] ) ? key( $section ) : sanitize_title( wp_unslash( $_REQUEST[ 'section' ] ) ) ;
            $current_section     = empty( $current_section ) ? $current_tab : $current_section ;
            $current_sub_section = empty( $_REQUEST[ 'subsection' ] ) ? '' : sanitize_title( wp_unslash( $_REQUEST[ 'subsection' ] ) ) ;
            $current_action      = empty( $_REQUEST[ 'action' ] ) ? '' : sanitize_title( wp_unslash( $_REQUEST[ 'action' ] ) ) ;

            do_action( sanitize_key( self::$plugin_slug . '_settings_save_' . $current_tab ) , $current_section ) ;
            do_action( sanitize_key( self::$plugin_slug . '_settings_reset_' . $current_tab ) , $current_section ) ;
        }

        /**
         * Settings page output
         */
        public static function settings_page() {
            HRW_Settings::output() ;
        }

    }

    HRW_Menu_Management::init() ;
}