<?php

/**
 * Cron Handler
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Cron_Handler' ) ) {

    /**
     * Class.
     */
    class HRW_Cron_Handler {

        /**
         *  Class initialization.
         */
        public static function init() {
            //custom schedules
            add_filter ( 'cron_schedules' , array ( __CLASS__ , 'custom_schedules' ) ) ;
            //custom cron
            self::custom_cron () ;
            //handle cron functionality
            add_filter ( 'hrw_cron_job' , array ( __CLASS__ , 'trigger_cron' ) ) ;
            //handle Wallet block and unblock actions
            add_action ( 'hrw_do_block_unblock_action' , array ( __CLASS__ , 'maybe_block_wallet' ) , 10 , 2 ) ;
        }

        /*
         * May be Block or Unblock Wallet
         */

        public static function maybe_block_wallet( $post_id , $cron_flag ) {

            $wallet = hrw_get_wallet ( $post_id ) ;

            if ( ! $wallet->exists () )
                return ;

            if ( $cron_flag == 'from' && wp_next_scheduled ( 'hrw_do_block_unblock_action' , array ( $post_id , 'to' ) ) == false ) {
                hrw_update_wallet ( $post_id , array () , array ( 'post_status' => 'hrw_blocked' ) ) ;
                wp_schedule_single_event ( strtotime ( $wallet->get_schedule_block_to_date () ) , 'hrw_do_block_unblock_action' , array ( $post_id , 'to' ) ) ;
                do_action ( 'hrw_wallet_lock_notification' , $post_id ) ;
            } else if ( $cron_flag == 'to' ) {
                hrw_update_wallet ( $post_id , array () , array ( 'post_status' => 'hrw_active' ) ) ;
                do_action ( 'hrw_wallet_unlock_notification' , $post_id ) ;
            }
        }

        /**
         * custom schedules
         * */
        public static function custom_schedules( $schedules ) {
            $interval = hrw_get_cron_interval ( 'hrw_advanced_cron_time_value' , 'hrw_advanced_cron_time_type' ) ;

            $schedules[ 'hrw_cron_interval' ] = array (
                'interval' => $interval ,
                'display'  => 'X Hourly'
                    ) ;

            return $schedules ;
        }

        /**
         * custom cron
         * */
        public static function custom_cron() {
            if ( wp_next_scheduled ( 'hrw_cron_job' ) == false )
                wp_schedule_event ( time () , 'hrw_cron_interval' , 'hrw_cron_job' ) ;
        }

        /**
         * Handle the cron
         * */
        public static function trigger_cron() {

            //Change wallet status as expired when reached expiry date
            $args = array (
                'post_type'      => HRW_Register_Post_Types::WALLET_POSTTYPE ,
                'post_status'    => 'hrw_active' ,
                'meta_query'     => array (
                    'relation' => 'AND' ,
                    array (
                        'key'     => 'hrw_expired_date' ,
                        'value'   => current_time ( 'mysql' , true ) ,
                        'compare' => '<=' ,
                    ) ,
                    array (
                        'key'     => 'hrw_expired_date' ,
                        'value'   => '' ,
                        'compare' => '!=' ,
                    )
                ) ,
                'posts_per_page' => '-1' ,
                'fields'         => 'ids' ,
                    ) ;

            $wallet_ids = get_posts ( $args ) ;

            if ( ! hrw_check_is_array ( $wallet_ids ) )
                return ;

            foreach ( $wallet_ids as $wallet_id ) {
                $wallet_object = hrw_get_wallet ( $wallet_id ) ;

                hrw_update_wallet ( $wallet_id , array ( 'hrw_available_balance' => 0 , 'hrw_total_balance' => 0 ) , array ( 'post_status' => 'hrw_expired' ) ) ;

                //Insert Transaction log
                $transaction_meta_args = array (
                    'hrw_user_id'  => $wallet_object->get_user_id () ,
                    'hrw_event'    => get_option ( 'hrw_localizations_wallet_expired_log' ) ,
                    'hrw_amount'   => $wallet_object->get_available_balance () ,
                    'hrw_total'    => 0 ,
                    'hrw_currency' => get_woocommerce_currency () ,
                    'hrw_date'     => current_time ( 'mysql' , true ) ,
                        ) ;

                $transaction_log_id = hrw_create_new_transaction_log ( $transaction_meta_args , array ( 'post_parent' => $wallet_id , 'post_status' => 'hrw_debit' ) ) ;

                do_action ( 'hrw_wallet_cron_job' , $wallet_id ) ;
                
                //send notification to user/admin
                do_action ( 'hrw_wallet_expired_notification' , $wallet_id ) ;
            }
        }


    }

    HRW_Cron_Handler::init () ;
}
