<?php

/**
 * User Wallet
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Wallet_User' ) ) {

    /**
     * Class
     */
    class HRW_Wallet_User {

        /**
         * User ID
         */
        public static $user_id ;

        /**
         * Wallet ID
         */
        public static $wallet_id ;

        /**
         * User Email ID
         */
        public static $user_email_id ;

        /**
         * User Phone
         */
        public static $user_phone ;

        /**
         * User
         */
        public static $user ;

        /**
         * Wallet Object
         */
        public static $wallet_object ;

        /**
         * Initiate the User Wallet
         */
        public static function init() {

            self::populate_data() ;
        }

        /**
         * Populate Data
         */
        public static function populate_data() {
            self::$user_id = get_current_user_id() ;

            //return if guest
            if ( ! self::$user_id )
                return ;

            self::$wallet_id = self::maybe_get_wallet_id() ;

            //return if user not having wallet
            if ( ! self::$wallet_id )
                return ;

            self::$wallet_object = hrw_get_wallet( self::$wallet_id ) ;
        }

        /**
         * Wallet exists
         */
        public static function wallet_exists() {
            if ( ! self::$wallet_id )
                return false ;

            return true ;
        }

        /**
         * May be get wallet id
         */
        public static function maybe_get_wallet_id() {

            return hrw_get_wallet_id_by_user_id( self::$user_id ) ;
        }

        /**
         * Get User
         */
        public static function get_user() {
            if ( self::$user )
                return self::$user ;

            if ( self::get_user_id() ) {
                self::$user = get_userdata( self::get_user_id() ) ;
            }

            return self::$user ;
        }

        /**
         * Get User ID
         */
        public static function get_user_id() {
            return self::$user_id ;
        }

        /**
         * Get User Email ID
         */
        public static function get_user_emai_id() {

            if ( ! is_object( self::get_user() ) )
                return self::$user_email_id ;

            self::$user_email_id = self::get_user()->user_email ;

            return self::$user_email_id ;
        }

        /**
         * Get User Phone
         */
        public static function get_user_phone() {

            if ( ! is_object( self::get_user() ) )
                return self::$user_phone ;

            self::$user_phone = get_user_meta( self::get_user_id() , 'hrw_phone_number' , true ) ;

            return self::$user_phone ;
        }

        /**
         * Get Wallet ID
         */
        public static function get_wallet_id() {
            return self::$wallet_id ;
        }

        /**
         * Get Wallet Object
         */
        public static function get_wallet_object() {
            return self::$wallet_object ;
        }

        /**
         * Get Wallet status
         */
        public static function get_wallet_status() {
            if ( ! self::wallet_exists() )
                return 'hrw_active' ;

            return self::get_wallet_object()->get_status() ;
        }

        /**
         * Get Wallet Available balance
         */
        public static function get_available_balance() {
            if ( ! self::wallet_exists() )
                return 0 ;

            return self::get_wallet_object()->get_available_balance() ;
        }

        /**
         * Get Wallet total balance
         */
        public static function get_total_balance() {
            if ( ! self::wallet_exists() )
                return 0 ;

            return self::get_wallet_object()->get_total_balance() ;
        }

        /**
         * Get formatted expiry date
         */
        public static function get_formatted_expiry_date() {
            if ( ! self::wallet_exists() )
                return '-' ;

            return self::get_wallet_object()->get_formatted_expired_date() ;
        }

        /**
         * Get expiry date
         */
        public static function get_expiry_date() {
            if ( ! self::wallet_exists() )
                return '' ;

            return self::get_wallet_object()->get_expired_date() ;
        }

        /**
         * Check if user reached low funds
         */
        public static function maybe_reached_low_funds() {

            if ( ! self::wallet_exists() )
                return false ;

            return apply_filters( 'hrw_reached_low_fund_threshold_value' , false ) ;
        }

        /**
         * Check if user is valid to use wallet
         */
        public static function is_valid() {

            if ( ! self::wallet_exists() )
                return false ;

            if ( self::$wallet_object->get_status() != 'hrw_active' )
                return false ;

            if ( ! self::validate_minimum_maximum_cart() )
                return false ;

            if ( ! self::validate_from_to_date() )
                return false ;

            if ( ! self::validate_day() )
                return false ;

            return apply_filters( 'hrw_validate_user_wallet_usage' , true ) ;
        }

        /**
         * validate minimum cart
         */
        public static function validate_minimum_maximum_cart() {
            $max_value = ( float ) get_option( 'hrw_general_wallet_usage_maximum_amount' , 0 ) ;
            $min_value = ( float ) get_option( 'hrw_general_wallet_usage_minimum_amount' , 0 ) ;

            if ( $max_value && $min_value ) {
                if ( $min_value > WC()->cart->get_subtotal() || $max_value < WC()->cart->get_subtotal() )
                    return false ;
            }elseif ( $min_value && ! $max_value ) {
                if ( $min_value > WC()->cart->get_subtotal() )
                    return false ;
            } elseif ( ! $min_value && $max_value ) {
                if ( $max_value < WC()->cart->get_subtotal() )
                    return false ;
            }

            return true ;
        }

        /**
         * validate from/to date
         */
        public static function validate_from_to_date() {
            $current_date = current_time( 'timestamp' ) ;
            $from_date    = get_option( 'hrw_general_wallet_usage_from_date_restriction' ) ;
            $to_date      = get_option( 'hrw_general_wallet_usage_to_date_restriction' ) ;

            if ( $from_date && $to_date ) {
                $from_date_object = HRW_Date_Time::get_tz_date_time_object( $from_date ) ;
                $to_date_object   = HRW_Date_Time::get_tz_date_time_object( $to_date ) ;
                $to_date_object->modify( '+1 days' ) ;

                if ( $from_date_object->getTimestamp() <= $current_date && $to_date_object->getTimestamp() >= $current_date )
                    return false ;
            }

            return true ;
        }

        /**
         * validate day
         */
        public static function validate_day() {
            $date_object = HRW_Date_Time::get_tz_date_time_object( 'now' ) ;

            $day_of_weeks = array( 0 => 'sunday' , 1 => 'monday' , 2 => 'tuesday' , 3 => 'wednesday' , 4 => 'thursday' , 5 => 'friday' , 6 => 'saturday' ) ;
            foreach ( $day_of_weeks as $key => $day ) {

                if ( get_option( "hrw_general_wallet_usage_{$day}_restriction" ) != 'yes' )
                    continue ;

                if ( $date_object->format( 'w' ) == $key )
                    return false ;
            }

            return true ;
        }

        /**
         * Reset
         */
        public static function reset() {
            self::$user_id       = NULL ;
            self::$wallet_id     = NULL ;
            self::$user_email_id = NULL ;
            self::$user          = NULL ;
            self::$wallet_object = NULL ;

            self::populate_data() ;
        }

    }

}
