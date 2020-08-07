<?php

/**
 * Admin Assets
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Admin_Assets' ) ) {

    /**
     * Class.
     */
    class HRWP_Admin_Assets {

        /**
         * Class Initialization.
         */
        public static function init() {

            add_filter( 'hrw_admin_assets' , array( __CLASS__ , 'external_js_files' ) ) ;
        }

        /**
         * Enqueue external js files
         */
        public static function external_js_files( $enqueues ) {

            $screen_ids   = hrw_page_screen_ids() ;
            $newscreenids = get_current_screen() ;
            $screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            $enqueues[ 'hrw-premium-admin' ] = array(
                'callable' => array( 'HRWP_Admin_Assets' , 'admin' ) ,
                'restrict' => in_array( $screenid , $screen_ids ) ,
                    ) ;

            return $enqueues ;
        }

        /**
         * Enqueue Admin end required JS files
         */
        public static function admin( $suffix ) {
            //Module
            wp_enqueue_script( 'hrw-module' , HRW_PLUGIN_URL . '/premium/assets/js/admin/module.js' , array( 'jquery' , 'blockUI' , 'jquery-ui-sortable' ) , HRW_VERSION ) ;
        }

    }

    HRWP_Admin_Assets::init() ;
}
