<?php

/**
 * Initialize the Plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Install' ) ) {

    /**
     * Class.
     */
    class HRW_Install {
        /*
         * Plugin Slug
         */

        protected static $plugin_slug = 'hrw' ;

        /**
         *  Class initialization.
         */
        public static function init() {
            add_action( 'init' , array( 'HRW_Updates' , 'maybe_run' ) , 1 ) ;
            add_filter( 'plugin_action_links_' . HRW_PLUGIN_SLUG , array( __CLASS__ , 'settings_link' ) ) ;
        }

        /**
         * Install
         */
        public static function install() {
            HRW_Pages::create_pages() ; //Create pages
            self::set_default_values() ; // default values
            self::update_version() ;
        }

        /**
         * Update current version.
         */
        private static function update_version() {
            update_option( 'hrw_version' , HRW_VERSION ) ;
        }

        /**
         *  Settings link. 
         */
        public static function settings_link( $links ) {
            $setting_page_link = '<a href="' . hrw_get_settings_page_url() . '">' . esc_html__( "Settings" , HRW_LOCALE ) . '</a>' ;

            array_unshift( $links , $setting_page_link ) ;

            return $links ;
        }

        /**
         *  Set settings default values  
         */
        public static function set_default_values() {
            if ( ! class_exists( 'HRW_Settings' ) )
                include_once(HRW_PLUGIN_PATH . '/inc/admin/menu/class-hrw-settings.php') ;

            //default for settings
            $settings = HRW_Settings::get_settings_pages() ;

            foreach ( $settings as $setting ) {
                $sections = $setting->get_sections() ;
                if ( ! hrw_check_is_array( $sections ) )
                    continue ;

                foreach ( $sections as $section_key => $section ) {
                    $settings_array = $setting->get_settings( $section_key ) ;
                    foreach ( $settings_array as $value ) {
                        if ( isset( $value[ 'default' ] ) && isset( $value[ 'id' ] ) ) {
                            if ( get_option( $value[ 'id' ] ) === false )
                                add_option( $value[ 'id' ] , $value[ 'default' ] ) ;
                        }
                    }
                }
            }

            //default for notification
            $notifications = HRW_Notification_Instances::get_notifications() ;

            foreach ( $notifications as $object ) {
                $settings = $object->settings_options_array() ;

                if ( ! hrw_check_is_array( $settings ) )
                    continue ;

                foreach ( $settings as $setting ) {
                    if ( isset( $setting[ 'default' ] ) && isset( $setting[ 'id' ] ) ) {
                        if ( get_option( $setting[ 'id' ] ) === false )
                            add_option( $setting[ 'id' ] , $setting[ 'default' ] ) ;
                    }
                }
            }
        }

    }

    HRW_Install::init() ;
}