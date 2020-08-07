<?php

/**
 *  Premium Info Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Premium_Info_Handler' ) ) {

    /**
     * Class
     */
    class HRW_Premium_Info_Handler {

        /**
         * Class Initialization.
         */
        public static function init() {
            //Premium info related css and js files
            add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'enqueue_scripts' ) ) ;
            //Display the premium banner
            add_action( 'hrw_before_tab_sections' , array( __CLASS__ , 'premium_banner' ) ) ;
            //Display the compatibility premium banner
            add_action( 'hrw_after_compatibility_content' , array( __CLASS__ , 'compatibility_premium_banner' ) ) ;
//            //Display the Shortcodes premium banner
//            add_action( 'hrw_after_shortcodes_content' , array( __CLASS__ , 'shortcodes_premium_banner' ) ) ;
        }

        /*
         * Enqueue CSS and JS files
         */

        public static function enqueue_scripts() {
            $screen_ids   = hrw_page_screen_ids() ;
            $newscreenids = get_current_screen() ;
            $screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

            if ( ! in_array( $screenid , $screen_ids ) )
                return ;

            //CSS
            wp_enqueue_style( 'hrw-premium-info' , HRW_PLUGIN_URL . '/assets/css/backend/premium-info.css' , array() , HRW_VERSION ) ;

            //JS
            wp_enqueue_script( 'hrw-premium-info' , HRW_PLUGIN_URL . '/assets/js/admin/premium-info.js' , array( 'jquery' ) , HRW_VERSION ) ;
            wp_localize_script(
                    'hrw-premium-info' , 'hrw_premium_info_params' , array(
                'premium_info_msg' => sprintf( esc_html__( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/plugins/wallet" target="_blank">' . esc_html__( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ,
                    )
            ) ;
        }

        /*
         * Display the compatibility Premium Banner
         */

        public static function compatibility_premium_banner() {
            $message = sprintf( esc_html__( 'Compatiblity is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/plugins/wallet" target="_blank">' . esc_html__( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;
            echo '<div class="hrw_premium_info_message"><p><i class="fa fa-info-circle"></i> ' . $message . '</p></div>' ;
        }

//        /*
//         * Display the Shortcodes Premium Banner
//         */
//
//        public static function shortcodes_premium_banner() {
//            $message = sprintf( esc_html__( 'Shortcodes are available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/plugins/wallet" target="_blank">' . esc_html__( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;
//            echo '<div class="hrw_premium_info_message"><p><i class="fa fa-info-circle"></i> ' . $message . '</p></div>' ;
//        }

        /*
         * Display the Premium Banner
         */

        public static function premium_banner() {
            include_once HRW_PLUGIN_PATH . '/inc/admin/menu/views/premium-banner.php' ;
        }

    }

    HRW_Premium_Info_Handler::init() ;
}
