<?php

/**
 * Shortcodes
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if( ! class_exists( 'HRW_Shortcodes' ) ) {

    /**
     * Class.
     */
    class HRW_Shortcodes {

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'hrw' ;

        /**
         * Class instance.
         */
        protected static $instance = null ;

        /**
         * Class Initialization.
         */
        public static function instance() {
            return is_null( self::$instance ) ? ( self::$instance = new self() ) : self::$instance ;
        }

        public function __construct() {

            $shortcodes = apply_filters( 'hrw_load_shortcodes' , array(
                'hrw_dashboard' ,
                'hrw_topup_form' ,
                'hrw_wallet_balance' ,
                'hrw_transaction_log' ,
                'hrw_available_wallet_funds' ,
                    ) ) ;

            foreach( $shortcodes as $shortcode_name ) {

                add_shortcode( $shortcode_name , array( __CLASS__ , 'process_shortcode' ) ) ;
            }
        }

        /**
         * Process Shortcode
         */
        public static function process_shortcode( $atts , $content , $tag ) {

            $shortcode_name = str_replace( 'hrw_' , '' , $tag ) ;

            $function = 'shortcode_' . $shortcode_name ;

            switch( $shortcode_name ) {
                case 'dashboard':
                case 'topup_form':
                case 'wallet_balance':
                case 'transaction_log':
                case 'available_wallet_funds':
                    ob_start() ;
                    self::$function() ; // output for shortcode
                    $content = ob_get_contents() ;
                    ob_end_clean() ;
                    break ;

                default:
                    ob_start() ;
                    do_action( "hrw_shortcode_{$shortcode_name}_content" ) ;
                    $content = ob_get_contents() ;
                    ob_end_clean() ;
                    break ;
            }

            return $content ;
        }

        /**
         * Output shortcode Top-up form.
         */
        public static function shortcode_topup_form() {

            HRW_Topup_Handler::render_form() ;
        }

        /**
         * Output shortcode dashboard.
         */
        public static function shortcode_dashboard() {
            if( ! is_user_logged_in() ) {
                HRW_Form_Handler::show_info( esc_html__( 'You are not allowed to access to wallet dashboard' , HRW_LOCALE ) ) ;
                return ;
            }

            HRW_Dashboard::output() ;
        }

        /**
         * Output shortcode balance.
         */
        public static function shortcode_wallet_balance() {

            if( ! is_user_logged_in() )
                return ;

            HRW_Dashboard::render_overview() ;
        }

        /**
         * Output shortcode Transaction Table.
         */
        public static function shortcode_transaction_log() {

            if( ! HRW_Wallet_User::wallet_exists() )
                return ;

            HRW_Dashboard::render_activity() ;
        }

        /**
         * Output shortcode Available Wallet Funds.
         */
        public static function shortcode_available_wallet_funds() {

            if( ! HRW_Wallet_User::wallet_exists() )
                return ;

            echo sprintf( '<span class ="%s">%s</span>' , 'hrw_wallet_amount_value' , hrw_price( HRW_Wallet_User::get_available_balance() ) ) ;
        }

    }

    HRW_Shortcodes::instance() ;
}
