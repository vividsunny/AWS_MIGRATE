<?php

/**
 *  Order Management Premium
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Order_Management_Premium' ) ) {

    /**
     * Class
     */
    class HRW_Order_Management_Premium extends HRW_Order_Management {

        /**
         * Class Initialization.
         */
        public static function init() {
            //May be debit partial price from user based on order status
            $wallet_usage_order_statuses = get_option( 'hrw_general_wallet_usage_order_status' ) ;
            if ( hrw_check_is_array( $wallet_usage_order_statuses ) ) {
                foreach ( $wallet_usage_order_statuses as $order_status )
                    add_action( "woocommerce_order_status_{$order_status}" , array( __CLASS__ , 'maybe_debit_partial_price' ) , 1 ) ;
            }
        }

        /*
         * May be debit partial price from user wallet
         */

        public static function maybe_debit_partial_price( $order_id ) {

            //return if partial usage process completed for this order.
            $already_process_completed = get_post_meta( $order_id , 'hr_wallet_partial_credit_flag' , true ) ;
            if ( $already_process_completed == 'yes' )
                return ;

            //return if order is not partial usage
            $partial_price = get_post_meta( $order_id , 'partial_applied_amount' , true ) ;
            if ( ! $partial_price )
                return ;

            $order       = wc_get_order( $order_id ) ;
            $order_items = $order->get_items() ;

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $order->get_user_id() ) ;

            //return if User not having wallet 
            if ( ! $wallet_id )
                return ;

            $wallet_object = hrw_get_wallet( $wallet_id ) ;
            //return if User not having enough wallet balance 
            if ( $wallet_object->get_available_balance() < $partial_price )
                return ;

            //Update Wallet
            $meta_args = array(
                'hrw_available_balance' => $wallet_object->get_available_balance() - $partial_price ,
                'hrw_total_balance'     => $wallet_object->get_total_balance() + $partial_price ,
                    ) ;

            hrw_update_wallet( $wallet_id , $meta_args ) ;

            $debit_message = get_option( 'hrw_localizations_partial_wallet_usage_log' ) ;
            $log_message   = str_replace( '{orderid}' , '#' . $order->get_id() , $debit_message ) ;

            //Insert Transaction log
            $transaction_meta_args = array(
                'hrw_user_id'  => $order->get_user_id() ,
                'hrw_event'    => $log_message ,
                'hrw_amount'   => $partial_price ,
                'hrw_total'    => $wallet_object->get_available_balance() - $partial_price ,
                'hrw_currency' => $order->get_currency() ,
                'hrw_date'     => current_time( 'mysql' , true ) ,
                    ) ;

            $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $wallet_id , 'post_status' => 'hrw_debit' ) ) ;

            //update custom meta for order
            update_post_meta( $order_id , 'hr_wallet_partial_credit_flag' , 'yes' ) ;
        }

    }

    HRW_Order_Management_Premium::init() ;
}
