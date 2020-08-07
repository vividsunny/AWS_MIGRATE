<?php

/*
 *  Cron Handler
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRWP_Cron_Handler' ) ) {

    /**
     * HRWP_Cron_Handler Class.
     */
    class HRWP_Cron_Handler {

        public static function init() {
            //Initiate Gift cron cron
            self::gift_cron_schedule () ;
            //handle gift card expiry reminder cron functionality
            add_filter ( 'hrw_gift_card_cron_job' , array ( __CLASS__ , 'trigger_daily_cron' ) ) ;
            //handle gift card expiry cron functionality
            add_action ( 'hrw_wallet_cron_job' , array ( __CLASS__ , 'hrwp_process_gift_card_expiry_query' ) ) ;
        }

        /**
         * custom cron
         * */
        public static function gift_cron_schedule() {
            if ( wp_next_scheduled ( 'hrw_gift_card_cron_job' ) == false )
                wp_schedule_event ( time () , 'daily' , 'hrw_gift_card_cron_job' ) ;
        }

        /**
         * Trigger Daily Cron
         * */
        public static function trigger_daily_cron() {
            $remainder_days = date ( 'Y-m-d' , strtotime ( current_time ( 'mysql' , true ) . ' + ' . get_option ( 'hrw_gift_card_expiry_days' , 1 ) . ' days' ) ) ;

            //Change wallet status as expired when reached expiry date
            $args = array (
                'post_type'      => HRWP_Register_Post_Types::GIFT_CARD_POSTTYPE ,
                'post_status'    => 'hrw_created' ,
                'meta_query'     => array (
                    'relation' => 'AND' ,
                    array (
                        'key'     => 'expiry_date' ,
                        'value'   => $remainder_days ,
                        'compare' => '<=' ,
                    ) ,
                    array (
                        'key'     => 'expiry_date' ,
                        'value'   => '' ,
                        'compare' => '!=' ,
                    ) ,
                    'relation' => 'AND' ,
                    array (
                        'key'     => 'expiry_remainder_bool' ,
                        'compare' => 'NOT EXISTS' ,
                    ) ,
                ) ,
                'posts_per_page' => -1 ,
                'fields'         => 'ids' ,
                    ) ;

            $gift_ids = get_posts ( $args ) ;

            if ( ! hrw_check_is_array ( $gift_ids ) )
                return ;

            foreach ( $gift_ids as $gift_id ) {
                do_action ( 'hrw_after_gift_card_expired' , $gift_id ) ;
                hrw_update_gift ( $gift_id , array ( 'hrw_expiry_remainder_bool' => 'yes' ) ) ;
            }
        }

        /**
         * Process Gift card query for expiry
         */
        public static function hrwp_process_gift_card_expiry_query() {
            //Change wallet status as expired when reached expiry date
            $args = array (
                'post_type'      => HRWP_Register_Post_Types::GIFT_CARD_POSTTYPE ,
                'post_status'    => 'hrw_created' ,
                'meta_query'     => array (
                    'relation' => 'AND' ,
                    array (
                        'key'     => 'expiry_date' ,
                        'value'   => current_time ( 'mysql' , true ) ,
                        'compare' => '<=' ,
                    ) ,
                    array (
                        'key'     => 'expiry_date' ,
                        'value'   => '' ,
                        'compare' => '!=' ,
                    )
                ) ,
                'posts_per_page' => '-1' ,
                'fields'         => 'ids' ,
                    ) ;

            $gift_ids = get_posts ( $args ) ;

            if ( ! hrw_check_is_array ( $gift_ids ) )
                return ;

            foreach ( $gift_ids as $gift_id ) {
                //updating this code as expired
                hrw_update_gift ( $gift_id , array () , array ( 'post_status' => 'hrw_expired' ) ) ;
            }
        }

    }

    HRWP_Cron_Handler::init () ;
}