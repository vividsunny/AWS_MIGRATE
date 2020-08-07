<?php

/**
 *  Handles Top-up
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Wallet_Usage' ) ) {

    /**
     * Class
     */
    class HRW_Wallet_Usage {

        /**
         * Class Initialization.
         */
        public static function init() {

            //Show messages based on wallet restriction
            add_action( 'wp_head' , array( __CLASS__ , 'show_notices' ) ) ;

            //handle payment gateways for wallet usage
            add_filter( 'woocommerce_available_payment_gateways' , array( __CLASS__ , 'handle_payment_gateways' ) , 10 , 1 ) ;
        }

        /*
         * Display Notices based on wallet restriction
         */

        public static function show_notices() {

            //return cart is empty
            if ( ! WC()->cart->get_cart_contents_count() )
                return ;

            //Top-up product in cart
            if ( hrw_topup_product_in_cart() )
                return ;

            if ( ! is_cart() && ! is_checkout() )
                return ;

            //Display minimum/maximum cart total message
            self::minimum_maximum_cart_total_message() ;
            //Display date restriction message
            self::date_restriction_message() ;
        }

        /*
         * Display minimum/maximum cart total message
         */

        public static function minimum_maximum_cart_total_message() {
            if ( HRW_Wallet_User::validate_minimum_maximum_cart() )
                return ;

            $max_value = ( float ) get_option( 'hrw_general_wallet_usage_maximum_amount' , 0 ) ;
            $min_value = ( float ) get_option( 'hrw_general_wallet_usage_minimum_amount' , 0 ) ;

            if ( $min_value && WC()->cart->get_subtotal() < $min_value ) {
                $message = get_option( 'hrw_messages_wallet_usage_minimum_cart_total_msg' ) ;
                $message = str_replace( array( '{min-cart-total}' ) , array( hrw_price( $min_value ) ) , $message ) ;

                wc_add_notice( $message , 'notice' ) ;
            }

            if ( $max_value && WC()->cart->get_subtotal() > $max_value ) {
                $message = get_option( 'hrw_messages_wallet_usage_maximum_cart_total_msg' ) ;
                $message = str_replace( array( '{max-cart-total}' ) , array( hrw_price( $max_value ) ) , $message ) ;

                wc_add_notice( $message , 'notice' ) ;
            }
        }

        /*
         * Display date restriction message
         */

        public static function date_restriction_message() {
            if ( HRW_Wallet_User::validate_from_to_date() )
                return ;

            $from_date = get_option( 'hrw_general_wallet_usage_from_date_restriction' ) ;
            $to_date   = get_option( 'hrw_general_wallet_usage_to_date_restriction' ) ;
            $from_date = HRW_Date_Time::get_date_object_format_datetime( $from_date , 'date' , false ) ;
            $to_date   = HRW_Date_Time::get_date_object_format_datetime( $to_date , 'date' , false ) ;

            $message = get_option( 'hrw_messages_wallet_usage_date_restriction_msg' ) ;
            $message = str_replace( array( '{from-duration}' , '{to-duration}' ) , array( $from_date , $to_date ) , $message ) ;

            wc_add_notice( $message , 'notice' ) ;
        }

        /*
         * Handles the payment gateways for wallet usage
         */

        public static function handle_payment_gateways( $wc_gateways ) {
            //Top-up product in cart
            if ( hrw_topup_product_in_cart() )
                return $wc_gateways ;

            if ( ! HRW_Wallet_User::is_valid() ) {
                //Hide wallet gateway
                if ( array_key_exists( 'HR_Wallet_Gateway' , $wc_gateways ) )
                    unset( $wc_gateways[ 'HR_Wallet_Gateway' ] ) ;
            }

            return apply_filters( 'hrw_available_payment_gateways' , $wc_gateways ) ;
        }

    }

    HRW_Wallet_Usage::init() ;
}
