<?php

/**
 * My Account Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Myaccount_Handler' ) ) {

    /**
     *  Class.
     */
    class HRW_Myaccount_Handler {

        /**
         * Wallet endpoint.
         */
        public static $wallet_endpoint = 'hrw-wallet' ;

        /**
         * Transaction IDs.
         */
        public static $transaction_ids ;

        /**
         * Page Count.
         */
        public static $page_count ;

        /**
         * Offset.
         */
        public static $offset ;

        /**
         * Current Page.
         */
        public static $current_page ;

        /**
         * Class Initialization.
         */
        public static function init() {

            add_action( 'plugins_loaded' , array( __CLASS__ , 'handle_hooks' ) , 20 ) ;

            /* Display Extra Fields in Registration Form. */
            add_action( 'woocommerce_register_form' , array( __CLASS__ , 'display_extra_fields_in_reg_form' ) ) ;
            add_action( 'woocommerce_register_post' , array( __CLASS__ , 'validate_extra_fields' ) , 10 , 3 ) ;
            add_action( 'woocommerce_created_customer' , array( __CLASS__ , 'save' ) , 10 , 1 ) ;
        }
        //add_filter('');
         public static function handle_hooks() {
            if ( ! apply_filters( 'hrw_visible_wallet_menu' , true ) )
                return ;

            //Add custom rewrite endpoint
            add_action( 'init' , array( __CLASS__ , 'custom_rewrite_endpoint' ) ) ;
            //flush rewrite rules
            add_action( 'wp_loaded' , array( __CLASS__ , 'flush_rewrite_rules' ) ) ;
            //Add custom query vars
            add_filter( 'query_vars' , array( __CLASS__ , 'custom_query_vars' ) , 0 ) ;
            //Add custom Myaccount Menu
            add_filter( 'woocommerce_account_menu_items' , array( __CLASS__ , 'custom_myaccount_menu' ) ) ;
            //Customize the myaccount menu title
            add_filter( 'the_title' , array( __CLASS__ , 'customize_menu_title' ) ) ;
            //display the wallet menu content
            add_action( 'woocommerce_account_' . self::$wallet_endpoint . '_endpoint' , array( __CLASS__ , 'display_wallet_menu_content' ) , 11 ) ;
        }

        /**
         * Custom rewrite endpoint
         */
        public static function custom_rewrite_endpoint() {
            add_rewrite_endpoint( self::$wallet_endpoint , EP_ROOT | EP_PAGES ) ;
        }

        /**
         * Add custom Query variable
         */
        public static function custom_query_vars( $vars ) {
            $vars[] = self::$wallet_endpoint ;

            return $vars ;
        }

        /**
         * Flush Rewrite Rules 
         */
        public static function flush_rewrite_rules() {
            flush_rewrite_rules() ;
        }

        /**
         * Custom My account Menus
         */
       public static function custom_myaccount_menu( $menus ) {
            if ( ! is_user_logged_in() )
                return $menus ;

             if ( ! apply_filters('hrw_wallet_usage_user_roles_restriction' ,true) )
                return $menus ;

            $wallet_menu = array ( self::$wallet_endpoint => get_option( 'hrw_localizations_wallet_menu_label' , 'Wallet' ) ) ;
            $add_menus   = hrw_customize_array_position( $menus , 'dashboard' , $wallet_menu ) ;

            return $add_menus ;
        }
        /**
         * Customize the My account menu title
         */
        public static function customize_menu_title( $title ) {
            global $wp_query ;

            if( is_main_query() && in_the_loop() && is_account_page() ) {
                if( isset( $wp_query->query_vars[ self::$wallet_endpoint ] ) )
                    $title = get_option( 'hrw_localizations_wallet_menu_label' , 'Wallet' ) ;

                remove_filter( 'the_title' , array( __CLASS__ , 'customize_menu_title' ) ) ;
            }

            return $title ;
        }

        /**
         * Display the wallet menu content
         */
        public static function display_wallet_menu_content() {

            hrw_get_template( 'myaccount-wallet.php' , false ) ;
        }

        /*
         * Display Extra Fields in Registration Form.
         */

        public static function display_extra_fields_in_reg_form() {

            if ( get_option( 'hrw_general_enable_phone_number_field' , 'no' ) == 'no' )
                return ;

            hrw_get_template( 'registration-fields.php' , false ) ;
        }

        /*
         * Validate Extra Fields
         */

        public static function validate_extra_fields( $username , $email , $validation_errors ) {

            if ( get_option( 'hrw_general_enable_phone_number_field' , 'no' ) == 'no' )
                return ;

            if ( get_option( 'hrw_general_select_field_option' , '1' ) == '1' )
                return ;

            if ( empty( $_POST[ 'hrw_account_phone' ] ) )
                $validation_errors->add( 'value_empty_error' , esc_html__( 'Phone Number is mandatory' , HRW_LOCALE ) ) ;

            if ( ! is_numeric( $_POST[ 'hrw_account_phone' ] ) )
                $validation_errors->add( 'value_non_numeric_error' , esc_html__( 'Enter a valid Phone Number' , HRW_LOCALE ) ) ;
        }

        /*
         * Save User Extra Details
         */

        public static function save( $user_id ) {

            if ( get_option( 'hrw_general_enable_phone_number_field' , 'no' ) == 'no' )
                return ;

            if ( ! empty( $_POST[ 'hrw_account_phone' ] ) )
                update_user_meta( $user_id , 'hrw_phone_number' , $_POST[ 'hrw_account_phone' ] ) ;
        }

    }

    HRW_Myaccount_Handler::init() ;
}
