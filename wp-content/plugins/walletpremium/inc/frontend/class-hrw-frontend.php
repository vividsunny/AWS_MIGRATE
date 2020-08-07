<?php

/**
 * Frontend
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Frontend' ) ) {

    /**
     * Class
     */
    class HRW_Frontend {

        /**
         * Initiate the User Wallet
         */
        public static function init() {

            add_action( 'woocommerce_after_checkout_validation' , array ( __CLASS__ , 'validate_place_order' ) , 10 , 2 ) ;
        }

        public static function validate_place_order( $data , $errors ) {
            try {
                if ( isset( $data[ 'payment_method' ] ) && 'HR_Wallet_Gateway' == $data[ 'payment_method' ] ) {
                    if ( HRW_Wallet_User::get_available_balance() < WC()->cart->get_total( 'edit' ) ) {
                        throw new Exception( get_option( 'hrw_general_insufficient_product_purchase_restriction_msg' ) ) ;
                    }
                }
            } catch ( Exception $ex ) {
                wc_add_notice( $ex->getMessage() , 'error' ) ;
            }
        }

    }

}

HRW_Frontend::init() ;

