<?php

/**
 *  Wallet Usage Premium
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists ( 'HRWP_Wallet_Usage' ) ) {

    /**
     * Class
     */
    class HRWP_Wallet_Usage extends HRW_Wallet_Usage {

        /**
         * Class Initialization.
         */
        public static function init() {

            //Display partial usage form in cart
            add_action ( 'woocommerce_after_cart_table' , array ( __CLASS__ , 'partial_usage_form_in_cart' ) , 10 ) ;
            //Display partial usage form in checkout
            add_action ( 'woocommerce_before_checkout_form' , array ( __CLASS__ , 'partial_usage_form_in_checkout' ) ) ;
            //Filter to validate user wallet
            add_filter ( 'hrw_validate_user_wallet_usage' , array ( __CLASS__ , 'validate_user_wallet' ) , 10 , 1 ) ;
            //Filter to validate low funds
            add_filter ( 'hrw_reached_low_fund_threshold_value' , array ( __CLASS__ , 'validate_low_fund_threshold' ) , 10 , 1 ) ;

            //Process partial usage
            add_action ( 'wp_loaded' , array ( __CLASS__ , 'process_partial_usage' ) , 20 ) ;
            //Process remove partial usage
            add_action ( 'wp_loaded' , array ( __CLASS__ , 'process_remove_partial_usage' ) ) ;
            //Show messages based on wallet restriction
            add_action ( 'wp_head' , array ( __CLASS__ , 'show_notices' ) ) ;
            //Display custom Add fee
            add_action ( 'woocommerce_cart_calculate_fees' , array ( __CLASS__ , 'custom_fees' ) ) ;
            //Display custom remove fee in checkout page
            add_action ( 'woocommerce_before_checkout_form' , array ( __CLASS__ , 'custom_remove_fees' ) ) ;
            //Display custom remove fee in cart page
            add_action ( 'woocommerce_before_cart_table' , array ( __CLASS__ , 'custom_remove_fees' ) ) ;
            //Handle Payment gateways
            add_action ( 'hrw_available_payment_gateways' , array ( __CLASS__ , 'handle_payment_gateways' ) ) ;
            //update order meta
            add_action ( 'woocommerce_checkout_update_order_meta' , array ( __CLASS__ , 'update_order_meta' ) ) ;

            //display Walance Balance Table Below Cart Table
            add_action ( 'woocommerce_after_cart_table' , array ( __CLASS__ , 'display_wallet_balance_in_cart' ) , 12 ) ;
            //display Walance Balance Table in Checkout
            add_action ( 'woocommerce_before_checkout_form' , array ( __CLASS__ , 'display_wallet_balance_in_checkout' ) ) ;
        }

        /*
         * Display partial usage form in cart
         */

        public static function partial_usage_form_in_cart() {
            if ( get_option ( 'hrw_general_enable_partial_payment_in_cart' ) != 'yes' )
                return ;

            //return if page is not cart page
            if ( ! is_cart () )
                return ;

            if ( WC ()->session->get ( 'wallet_credit_applied_amount' ) )
                return ;

            if ( ! HRW_Wallet_User::is_valid () )
                return ;

            //return if Partial usage is not enabled
            if ( ! self::proceed_partial_usage () )
                return ;

            hrw_get_template ( 'cart-partial-form.php' , true ) ;
        }

        /*
         * Display partial usage form in Checkout
         */

        public static function partial_usage_form_in_checkout() {

            if ( get_option ( 'hrw_general_enable_partial_payment_in_checkout' ) != 'yes' )
                return ;

            //return if page is not checkout page
            if ( ! is_checkout () )
                return ;

            if ( WC ()->session->get ( 'wallet_credit_applied_amount' ) )
                return ;

            if ( ! HRW_Wallet_User::is_valid () )
                return ;

            //return if Partial usage is not enabled
            if ( ! self::proceed_partial_usage () )
                return ;

            hrw_get_template ( 'checkout-partial-form.php' , true ) ;
        }

        /*
         * Check if partial usage is enabled 
         */

        public static function proceed_partial_usage() {
            //return if partial usage is disabled
            if ( get_option ( 'hrw_general_enable_partial_payment' ) != 'yes' )
                return false ;

            //return if wallet balance is less than order total
            if ( get_option ( 'hrw_general_partial_payment_restriction_type' ) == '2' ) {
                //return if order total is less than wallet balance
                if ( (HRW_Wallet_User::get_available_balance () >= WC ()->cart->get_total ( false ) ) )
                    return false ;
            }

            return true ;
        }

        /*
         * Validate low fund threshold
         */

        public static function validate_low_fund_threshold( $bool ) {

            if ( HRW_Wallet_User::get_available_balance () <= ( float ) get_option ( 'hrw_advanced_low_wallet_amount_limit' , '' ) )
                return true ;

            return $bool ;
        }

        /*
         * Validate user wallet
         */

        public static function validate_user_wallet( $bool ) {

            if ( ! hrw_product_category_restriction () )
                return false ;

            if ( ! hrw_wallet_usage_user_roles_restriction () )
                return false ;

            return $bool ;
        }

        /**
         * Process Partial Usage Form
         */
        public static function process_partial_usage() {

            $nonce_value = isset ( $_POST[ 'hrw-partial-usage-nonce' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw-partial-usage-nonce' ] ) : null ;
            if ( ! isset ( $_POST[ 'hrw-action' ] ) || empty ( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce ( $nonce_value , 'hrw-partial-usage' ) )
                return ;

            try {
                if ( ! isset ( $_POST[ 'hrw_partial_usage' ] ) || ! ($amount = hrw_sanitize_text_field ( $_POST[ 'hrw_partial_usage' ] )) ) {
                    throw new Exception ( esc_html__ ( 'Please enter some funds' , HRW_LOCALE ) ) ;
                }

                //validation for numeric value
                if ( ! is_numeric ( $amount ) ) {
                    throw new Exception ( esc_html__ ( 'Please enter only numeric values' , HRW_LOCALE ) ) ;
                }

                do_action ( 'hrw_do_partial_usage_validation' ) ;

                //retrun if order total is reached maximum funds 
                $maximum_funds = ( float ) get_option ( 'hrw_general_partial_payment_maximum_amount_limit' ) ;
                if ( $maximum_funds ) {
                    if ( $maximum_funds < $amount )
                        throw new Exception ( sprintf ( esc_html__ ( 'You cannot use more than %s' , HRW_LOCALE ) , $maximum_funds ) ) ;
                }

                if ( ! (WC ()->cart->get_total ( false ) >= $amount ) ) {
                    throw new Exception ( esc_html__ ( 'You have entered funds more than your cart total' , HRW_LOCALE ) ) ;
                }

                if ( HRW_Wallet_User::get_available_balance () < $amount ) {
                    throw new Exception ( esc_html__ ( "You don't have sufficient wallet balance" , HRW_LOCALE ) ) ;
                }

                //validation for minimum amount should not be more than cart total
                if ( ! (WC ()->cart->get_total ( false ) >= $amount ) ) {
                    throw new Exception ( esc_html__ ( 'You have entered funds more than your cart total' , HRW_LOCALE ) ) ;
                }

                //Set add fee
                WC ()->session->set ( 'wallet_credit_applied_amount' , $amount ) ;

                wc_add_notice ( esc_html__ ( 'Applied Successfully' , HRW_LOCALE ) ) ;
            } catch ( Exception $ex ) {

                wc_add_notice ( $ex->getMessage () , 'error' ) ;
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
            if ( hrw_topup_product_in_cart () )
                return ;

            if ( ! is_cart () && ! is_checkout () )
                return ;

            //Display Notices for low funds
            self::low_funds_notice () ;

            do_action ( 'hrw_display_notices' ) ;
        }

        /*
         * Display Notices for low funds
         */

        public static function low_funds_notice() {

            //Display notice if user reached low funds
            if ( get_option ( 'hrw_messages_enable_low_wallet_balance_msg' ) == '1' && HRW_Wallet_User::maybe_reached_low_funds () ) {
                wc_add_notice ( get_option ( 'hrw_messages_low_wallet_balance_msg' ) , 'notice' ) ;
            }
        }

        /**
         * Process Remove Partial Usage Form
         */
        public static function process_remove_partial_usage() {
            $nonce_value = isset ( $_POST[ 'hrw-remove-partial-usage-nonce' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw-remove-partial-usage-nonce' ] ) : null ;
            if ( ! isset ( $_POST[ 'hrw-action' ] ) || empty ( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce ( $nonce_value , 'hrw-remove-partial-usage' ) )
                return ;

            try {

                //unset add fee session
                WC ()->session->__unset ( 'wallet_credit_applied_amount' ) ;

                wc_add_notice ( esc_html__ ( 'Wallet amount removed' , HRW_LOCALE ) ) ;
            } catch ( Exception $ex ) {

                wc_add_notice ( $ex->getMessage () , 'error' ) ;
            }
        }

        /*
         * Diaplay fees remove button
         */

        public static function custom_remove_fees() {
            //Top-up product in cart
            if ( hrw_topup_product_in_cart () )
                return ;

            if ( ! WC ()->session->get ( 'wallet_credit_applied_amount' ) )
                return ;

            //remove button
            echo hrw_get_template_html ( 'remove-partial-usage-form.php' , true ) ;
        }

        /*
         * Diaplay custom fees
         */

        public static function custom_fees() {

            //Top-up product in cart
            if ( hrw_topup_product_in_cart () )
                return ;

            if ( ! WC ()->session->get ( 'wallet_credit_applied_amount' ) )
                return ;

            WC ()->cart->add_fee ( esc_html__ ( 'Wallet Credits' , HRW_LOCALE ) , '-' . WC ()->session->get ( 'wallet_credit_applied_amount' ) , true , '' ) ;
        }

        /*
         * Handles the payment gateways for wallet usage
         */

        public static function handle_payment_gateways( $wc_gateways ) {
            //return if not hide other gateways option 
            if ( get_option ( 'hrw_general_hide_other_wc_gateways' ) != 'yes' )
                return $wc_gateways ;

            $payment_gateways = array () ;
            if ( HRW_Wallet_User::get_available_balance () >= WC ()->cart->get_subtotal () ) {
                if ( isset ( $wc_gateways[ 'HR_Wallet_Gateway' ] ) )
                    $payment_gateways[ 'HR_Wallet_Gateway' ] = $wc_gateways[ 'HR_Wallet_Gateway' ] ;

                return $payment_gateways ;
            }

            return $wc_gateways ;
        }

        /*
         * Update order meta
         */

        public static function update_order_meta( $order_id ) {

            if ( ! WC ()->session->get ( 'wallet_credit_applied_amount' ) )
                return ;

            update_post_meta ( $order_id , 'partial_applied_amount' , WC ()->session->get ( 'wallet_credit_applied_amount' ) ) ;

            //unset add fee session
            WC ()->session->__unset ( 'wallet_credit_applied_amount' ) ;
        }

        /*
         * Display Wallet Balance in Below Cart
         */

        public static function display_wallet_balance_in_cart() {

            if ( get_option ( 'hrw_advanced_enable_cart_wallet_balance' , 'no' ) == "no" )
                return ;

            hrw_get_template ( 'dashboard/wallet-balance.php' , false ) ;
        }

        /*
         * Display Wallet Balance in Checkout
         */

        public static function display_wallet_balance_in_checkout() {

            if ( get_option ( 'hrw_advanced_enable_checkout_wallet_balance' , 'no' ) == "no" )
                return ;

            hrw_get_template ( 'dashboard/wallet-balance.php' , false ) ;
        }

    }

    HRWP_Wallet_Usage::init () ;
}