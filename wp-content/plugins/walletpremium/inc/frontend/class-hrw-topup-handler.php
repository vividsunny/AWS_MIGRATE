<?php

/**
 *  Handles Top-up
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists ( 'HRW_Topup_Handler' ) ) {

    /**
     * Class
     */
    class HRW_Topup_Handler {

        /**
         * Plugin slug.
         */
        private static $plugin_slug = 'hrw' ;

        /**
         * Top-up Product
         */
        public static $topup_product = '' ;

        /**
         * Class Initialization.
         */
        public static function init() {

            self::set_topup_product () ;

            //set price for Top-up product
            add_action ( 'woocommerce_before_calculate_totals' , array ( __CLASS__ , 'set_price' ) , 1 , 1 ) ;
            //set cart quantity as 1 in cart page
            add_filter ( 'woocommerce_cart_item_quantity' , array ( __CLASS__ , 'set_cart_item_quantity' ) , 10 , 2 ) ;
            //set cart quantity as 1 in cart page
            add_filter ( 'woocommerce_add_to_cart_validation' , array ( __CLASS__ , 'validate_other_product_add_to_cart' ) , 10 , 5 ) ;
            //Show messages based on wallet restriction
            add_action ( 'wp_head' , array ( __CLASS__ , 'show_notices' ) ) ;
            //handle payment gateways for Top-up
            add_filter ( 'woocommerce_available_payment_gateways' , array ( __CLASS__ , 'handle_payment_gateways' ) , 5 , 1 ) ;
            //update order meta
            add_action ( 'woocommerce_checkout_update_order_meta' , array ( __CLASS__ , 'update_order_meta' ) ) ;
        }

        /*
         * Set Top-up product
         */

        public static function set_topup_product() {
            $topup_product = array_filter ( get_option ( 'hrw_general_topup_product_id' , array () ) ) ;

            self::$topup_product = reset ( $topup_product ) ;
        }

        /*
         * Set cart quantity as 1 in cart page
         */

        public static function set_cart_item_quantity( $quantity , $cart_item_key ) {
            $cart_items = WC ()->cart->get_cart () ;

            if ( ! isset ( $cart_items[ $cart_item_key ][ 'hrw_wallet' ] ) )
                return $quantity ;

            return 1 ;
        }

        /*
         * validate other product add to cart
         */

        public static function validate_other_product_add_to_cart( $passed ) {

            if ( ! hrw_topup_product_in_cart () )
                return $passed ;

            wc_add_notice ( esc_html__ ( 'Top-up product is in cart and hence you are unable to add other products to cart' , HRW_LOCALE ) , 'error' ) ;

            return false ;
        }

        /*
         * Display Top-up Form
         */

        public static function render_form() {

            try {
                //If guest user
                if ( ! is_user_logged_in () ) {
                    throw new Exception ( 'Please login to view Top-up form' ) ;
                }

                //restricts Top-up for user
                if ( get_option ( 'hrw_general_enable_topup' ) != 'yes' ) {
                    throw new Exception ( 'You are not allowed to Top-up your wallet' ) ;
                }

                do_action ( 'hrw_validate_topup_form_display' ) ;

                //product is not configured in settings
                if ( empty ( self::$topup_product ) ) {
                    throw new Exception ( 'Top-up product is not available' ) ;
                }

                if ( HRW_Wallet_User::get_wallet_status () == 'hrw_blocked' ) {
                    throw new Exception ( 'Your Wallet is Locked' ) ;
                }

                $thresholed_value = ( float ) get_option ( 'hrw_general_topup_maximum_wallet_balance' ) ;
                if ( ! empty ( $thresholed_value ) && HRW_Wallet_User::get_available_balance () >= $thresholed_value ) {
                    throw new Exception ( get_option ( 'hrw_messages_topup_maximum_wallet_balance_msg' ) ) ;
                }

                hrw_get_template ( 'topup-form.php' ) ;
            } catch ( Exception $ex ) {

                HRW_Form_Handler::show_info ( $ex->getMessage () ) ;
            }
        }

        /*
         * Set custom price for Top-up product 
         */

        public static function set_price( $cart_object ) {
            foreach ( $cart_object->cart_contents as $key => $value ) {
                if ( ! isset ( $value[ 'hrw_wallet' ] ) )
                    continue ;

                if ( self::$topup_product != $value[ 'hrw_wallet' ][ 'product_id' ] )
                    continue ;

                $value[ 'data' ]->set_price ( $value[ 'hrw_wallet' ][ 'price' ] ) ;
            }
        }

        /*
         * Display Notices based on wallet restriction
         */

        public static function show_notices() {

            //return cart is empty
            if ( ! WC ()->cart->get_cart_contents_count () )
                return ;

            //Top-up product in cart
            if ( ! ($topup_item = hrw_topup_product_in_cart ()) )
                return ;

            if ( ! is_cart () && ! is_checkout () )
                return ;

            //Display Top-up message in cart page
            self::topup_cart_message ( $topup_item ) ;
            //Display Top-up message in checkout page
            self::topup_checkout_message ( $topup_item ) ;
        }

        /*
         * Display Top-up message in cart page
         */

        public static function topup_cart_message( $topup_item ) {

            if ( ! is_cart () )
                return ;

            if ( get_option ( 'hrw_messages_enable_topup_cart_msg' ) == '2' )
                return ;

            $message = get_option ( 'hrw_messages_topup_cart_msg' ) ;
            $message = str_replace ( array ( '{wallet-topup-product}' , '{topup-amount}' ) , array ( get_the_title ( $topup_item[ 'product_id' ] ) , hrw_price ( $topup_item[ 'price' ] ) ) , $message ) ;

            wc_add_notice ( $message , 'notice' ) ;
        }

        /*
         * Display Top-up message in checkout page
         */

        public static function topup_checkout_message( $topup_item ) {
            if ( ! is_checkout () )
                return ;

            if ( get_option ( 'hrw_messages_enable_topup_checkout_msg' ) == '2' )
                return ;

            $message = get_option ( 'hrw_messages_topup_checkout_msg' ) ;
            $message = str_replace ( array ( '{wallet-topup-product}' , '{topup-amount}' ) , array ( get_the_title ( $topup_item[ 'product_id' ] ) , hrw_price ( $topup_item[ 'price' ] ) ) , $message ) ;

            wc_add_notice ( $message , 'notice' ) ;
        }

        /*
         * Handles the payment gateways for Top-up
         */

        public static function handle_payment_gateways( $wc_gateways ) {
            if ( ! hrw_topup_product_in_cart () )
                return apply_filters ( 'hrw_get_available_payment_gateways_for_non_topup' , $wc_gateways ) ;

            //Hide wallet gateway when Top-up
            if ( array_key_exists ( 'HR_Wallet_Gateway' , $wc_gateways ) )
                unset ( $wc_gateways[ 'HR_Wallet_Gateway' ] ) ;

            $restricted_gatways = get_option ( 'hrw_general_topup_hide_wc_gateways' , array () ) ;
            if ( hrw_check_is_array ( $restricted_gatways ) ) {
                foreach ( $restricted_gatways as $gateway_id ) {
                    if ( ! isset ( $wc_gateways[ $gateway_id ] ) )
                        continue ;

                    unset ( $wc_gateways[ $gateway_id ] ) ; //unset payment gateways
                }
            }

            return apply_filters ( 'hrw_get_available_payment_gateways_for_topup' , $wc_gateways ) ;
        }

        /*
         * Update order meta
         */

        public static function update_order_meta( $order_id ) {
            foreach ( WC ()->cart->get_cart () as $key => $value ) {
                if ( ! isset ( $value[ 'hrw_wallet' ][ 'topup_mode' ] ) )
                    continue ;

                if ( HRW_Topup_Handler::$topup_product != $value[ 'hrw_wallet' ][ 'product_id' ] )
                    continue ;

                update_post_meta ( $order_id , 'hr_wallet_topup_fund' , $value[ 'hrw_wallet' ][ 'price' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet_topup_product' , $value[ 'hrw_wallet' ][ 'product_id' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet_topup_mode' , $value[ 'hrw_wallet' ][ 'topup_mode' ] ) ;
                update_post_meta ( $order_id , 'hr_wallet' , $value[ 'hrw_wallet' ] ) ;
            }
        }

    }

    HRW_Topup_Handler::init () ;
}
