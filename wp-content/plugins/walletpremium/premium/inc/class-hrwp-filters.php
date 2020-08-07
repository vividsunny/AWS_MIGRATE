<?php

/**
 *  Display Filters
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Filters_Premium' ) ) {

    /**
     * Class
     */
    class HRWP_Filters_Premium {

        /**
         * Class Initialization.
         */
        public static function init() {

            //Customize the wallet expired day
            add_filter( 'hrw_wallet_expired_day' , array( __CLASS__ , 'wallet_expired_day' ) , 10 , 1 ) ;
            //Customize the price value
            add_filter( 'hrw_alter_price' , array( __CLASS__ , 'alter_price' ) , 10 , 1 ) ;
            //Get the price decimals
            add_filter( 'hrw_get_price_decimals' , array( __CLASS__ , 'get_price_decimals' ) , 10 , 1 ) ;
            // Hide the wallet menu. 
            add_filter( 'hrw_visible_wallet_menu' , array( __CLASS__ , 'hide_wallet_menu' ) , 10 , 1 ) ;
            // Hide the wallet expiry date. 
            add_filter( 'hrw_visible_expiry_date' , array( __CLASS__ , 'hide_expire_date' ) , 10 , 1 ) ;
            
            add_filter('hrw_wallet_usage_user_roles_restriction', array( __CLASS__ , 'wallet_user_roles_restriction' ) , 10 , 1 );
            }

        /**
         * Wallet expired day
         */
        public static function wallet_expired_day( $expired_day ) {
            $expired_day = get_option( 'hrw_general_wallet_expiry_limit' , '' ) ;

            if ( ! $expired_day )
                return 365 ;

            return $expired_day ;
        }

        /**
         * Alter Price
         */
        public static function get_price_decimals( $decimal ) {

            $type = get_option( 'hrw_advanced_round_off_type' , '1' ) ;
            if ( $type == '2' ) {
                return '0' ;
            } else if ( $type == '3' ) {
                return $decimal ;
            } else {
                return '2' ;
            }
        }

        /**
         * Alter Price
         */
        public static function alter_price( $price ) {
            $type = get_option( 'hrw_advanced_round_off_type' , '1' ) ;

            if ( $type == '1' ) {
                $method = get_option( 'hrw_advanced_round_off_method' , '1' ) ;

                if ( $method == '2' ) {
                    $price = ceil( $price ) ;
                } else {
                    $price = floor( $price * 100 ) / 100 ;
                }
            }

            return $price ;
        }

        /**
         * Hide the Wallet menu.
         */
        public static function hide_wallet_menu( $bool ) {
            $hide_menu = get_option( 'hrw_advanced_hide_wallet_menu' , 'no' ) ;
     
            if ( $hide_menu == 'no' ) {
                return $bool ;
            }

            return false ;
        }

        /**
         * Hide the Wallet expiry date.
         */
        public static function hide_expire_date( $bool ) {
            $expire_date = get_option( 'hrw_general_hide_expiry_date' , 'no' ) ;
            if ( $expire_date == 'no' ) {
                return $bool ;
            }

            return false ;
        }
        public static function wallet_user_roles_restriction(){
           if(hrw_wallet_usage_user_roles_restriction()){
               return true;
           }
           return false;
        }
    }

    HRWP_Filters_Premium::init() ;
}