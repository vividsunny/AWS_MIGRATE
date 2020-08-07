<?php

/**
 *  Handles Top-up Premium
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Topup_Handler' ) ) {

    /**
     * Class
     */
    class HRWP_Topup_Handler extends HRW_Topup_Handler {

        /**
         * Class Initialization.
         */
        public static function init() {
            //validate the display of Top-up form
            add_action( 'hrw_validate_topup_form_display' , array( __CLASS__ , 'validate_topup_form_display' ) ) ;
            //Customize the Top-up form
            add_action( 'hrw_after_topup_form_field' , array( __CLASS__ , 'customize_topup_form' ) ) ;
            //Disable the Top-up form
            add_filter( 'hrw_display_topup_field' , '__return_false' ) ;
            //Validate the Top-up form
            add_filter( 'hrw_validate_topup_amount' , array( __CLASS__ , 'validate_topup_amount' ) ) ;
        }

        /**
         * validate the display of Top-up form
         * */
        public static function validate_topup_form_display() {

            // Top-up user restriction
            if ( ! hrw_topup_user_roles_restriction( HRW_Wallet_User::get_user_id() ) ) {
                throw new Exception( get_option( 'hrw_messages_topup_user_restriction_msg' ) ) ;
            }
        }

        /**
         * Customize the Top-up form
         * */
        public static function customize_topup_form() {

            hrw_get_template( 'topup-form.php' , true ) ;
        }

        /**
         * Validate Top-up amount
         * */
        public static function validate_topup_amount( $topup_amount ) {
            if ( ! $topup_amount )
                return $topup_amount ;

            $topup_field_type = get_option( 'hrw_general_topup_amount_type' ) ;
            if ( $topup_field_type == '3' )
                return ( float ) get_option( 'hrw_general_topup_prefilled_amount' ) ;

            return $topup_amount ;
        }

    }

    HRWP_Topup_Handler::init() ;
}
