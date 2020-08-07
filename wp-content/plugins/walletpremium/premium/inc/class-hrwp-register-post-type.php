<?php

/**
 * Custom Post Type.
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRWP_Register_Post_Types' ) ) {

    /**
     * HRW_Register_Post_Types Class.
     */
    class HRWP_Register_Post_Types {
        /*
         * Fund Transfer Post Type
         */

        const FUND_TRANSFER_POSTTYPE     = 'hrw_fund_transfer' ;
        /*
         * Fund Transfer Log Post Type
         */
        const FUND_TRANSFER_LOG_POSTTYPE = 'hrw_fundtransfer_log' ;

        /*
         * Wallet Withdrawal Log Post Type
         */
        const WALLET_WITHDRAWAL_POSTTYPE = 'hrw_withdrawal' ;

        /*
         * Cashback Post Type
         */
        const CASHBACK_POSTTYPE = 'hrw_cashback' ;

        /*
         * Cashback Log Post Type
         */
        const CASHBACK_LOG_POSTTYPE = 'hrw_cashback_log' ;

        /*
         * Gift Caed Post Type
         */
        const GIFT_CARD_POSTTYPE = 'hrw_gift_card' ;


        /*
         * Discount Post Type
         */
        const DISCOUNT_POSTTYPE = 'hrw_discount' ;

        /**
         * HRW_Register_Post_Types Class initialization.
         */
        public static function init() {

            add_filter ( 'hrw_add_custom_post_types' , array ( __CLASS__ , 'register_custom_post_types' ) ) ;
        }

        /*
         * Register Custom Post types
         */

        public static function register_custom_post_types( $custom_post_types ) {

            $custom_post_types[ self::FUND_TRANSFER_POSTTYPE ]     = array ( 'HRWP_Register_Post_Types' , 'fund_transfer_post_type_args' ) ;
            $custom_post_types[ self::FUND_TRANSFER_LOG_POSTTYPE ] = array ( 'HRWP_Register_Post_Types' , 'fund_transfer_log_post_type_args' ) ;
            $custom_post_types[ self::WALLET_WITHDRAWAL_POSTTYPE ] = array ( 'HRWP_Register_Post_Types' , 'wallet_withdrawal_post_type_args' ) ;
            $custom_post_types[ self::CASHBACK_POSTTYPE ]          = array ( 'HRWP_Register_Post_Types' , 'cashback_post_type_args' ) ;
            $custom_post_types[ self::CASHBACK_LOG_POSTTYPE ]      = array ( 'HRWP_Register_Post_Types' , 'cashback_log_post_type_args' ) ;
            $custom_post_types[ self::GIFT_CARD_POSTTYPE ]         = array ( 'HRWP_Register_Post_Types' , 'gift_card_post_type_args' ) ;
 			$custom_post_types[ self::DISCOUNT_POSTTYPE ]          = array( 'HRWP_Register_Post_Types' , 'discount_post_type_args' ) ;
            return apply_filters ( 'hrw_add_premium_custom_post_types' , $custom_post_types ) ;
        }

        /*
         * Prepare Fund Transfer Post type arguments
         */

        public static function fund_transfer_post_type_args() {

            return apply_filters ( 'hrw_fund_transfer_post_type_args' , array (
                'label'           => esc_html__ ( 'Fund Transfer' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Prepare Fund Transfer Log Post type arguments
         */

        public static function fund_transfer_log_post_type_args() {

            return apply_filters ( 'hrw_fund_transfer_log_post_type_args' , array (
                'label'           => esc_html__ ( 'Fund Transfer Log' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Prepare Wallet Withdrawal Log Post type arguments
         */

        public static function wallet_withdrawal_post_type_args() {

            return apply_filters ( 'hrw_wallet_withdrawal_post_type_args' , array (
                'label'           => esc_html__ ( 'Wallet Withdrawal' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Prepare Cashback arguments
         */

        public static function cashback_post_type_args() {

            return apply_filters ( 'hrw_cashback_post_type_args' , array (
                'label'           => esc_html__ ( 'Cashback' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Prepare Cashback Log arguments
         */

        public static function cashback_log_post_type_args() {

            return apply_filters ( 'hrw_cashbacklog_post_type_args' , array (
                'label'           => esc_html__ ( 'Cashback Log' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

        /*
         * Prepare Gift Card type arguments
         */

        public static function gift_card_post_type_args() {

            return apply_filters ( 'hrw_gift_card_post_type_args' , array (
                'label'           => esc_html__ ( 'Gift Card' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }


        /*
         * Prepare Discount arguments
         */

        public static function discount_post_type_args() {

            return apply_filters( 'hrw_discount_post_type_args' , array(
                'label'           => esc_html__( 'Discount' , HRW_LOCALE ) ,
                'public'          => false ,
                'hierarchical'    => false ,
                'supports'        => false ,
                'capability_type' => 'post' ,
                'rewrite'         => false ,
                    )
                    ) ;
        }

    }

    HRWP_Register_Post_Types::init () ;
}