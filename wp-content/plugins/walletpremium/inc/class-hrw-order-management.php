<?php

/**
 *  Order Management
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Order_Management' ) ) {

    /**
     * Class
     */
    class HRW_Order_Management {

        /**
         * Class Initialization.
         */
        public static function init() {
            //May be credit topup price for user based on order status
            $topup_order_statuses = get_option( 'hrw_general_topup_order_status' ) ;
            if ( hrw_check_is_array( $topup_order_statuses ) ) {
                foreach ( $topup_order_statuses as $order_status )
                    add_action( "woocommerce_order_status_{$order_status}" , array( __CLASS__ , 'maybe_credit_topup_price' ) , 1 ) ;
            }

            //Order Status refuned/cancel Top-up actions
            add_action( 'woocommerce_order_status_refunded' , array( __CLASS__ , 'debit_topup_amount_from_order' ) ) ;
            add_action( 'woocommerce_order_status_cancelled' , array( __CLASS__ , 'debit_topup_amount_from_order' ) ) ;
            add_action( 'woocommerce_order_status_failed' , array( __CLASS__ , 'debit_topup_amount_from_order' ) ) ;

            //Order Status refuned wallet actions
            add_action( 'woocommerce_order_status_cancelled' , array( __CLASS__ , 'credit_wallet_amount_from_order' ) ) ;
            add_action( 'woocommerce_order_status_failed' , array( __CLASS__ , 'credit_wallet_amount_from_order' ) ) ;

            //Order Status refuned partial actions
            add_action( 'woocommerce_order_status_refunded' , array( __CLASS__ , 'credit_partial_amount_from_order' ) ) ;
            add_action( 'woocommerce_order_status_cancelled' , array( __CLASS__ , 'credit_partial_amount_from_order' ) ) ;
            add_action( 'woocommerce_order_status_failed' , array( __CLASS__ , 'credit_partial_amount_from_order' ) ) ;
        }

        /*
         * May be credit Top-up price to user wallet
         */

        public static function maybe_credit_topup_price( $order_id ) {

            if ( ! apply_filters( 'hrw_topup_wallet_amount_validation' , true , $order_id ) ) {
                return ;
            }

            //return if Top-up process completed for this order.
            $already_process_completed = get_post_meta( $order_id , 'hr_wallet_credited_once_flag' , true ) ;
            if ( $already_process_completed == 'yes' )
                return ;

            //return if order is not placed by Top-up
            $topup_product_id = get_post_meta( $order_id , 'hr_wallet_topup_product' , true ) ;
            if ( ! $topup_product_id )
                return ;

            $order       = wc_get_order( $order_id ) ;
            $order_items = $order->get_items() ;
            $topup_price = get_post_meta( $order_id , 'hr_wallet_topup_fund' , true ) ;

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $order->get_user_id() ) ;

            foreach ( $order_items as $eachitem ) {
                if ( $topup_product_id != $eachitem[ 'product_id' ] )
                    continue ;

                //Insert/update Wallet  
                if ( $wallet_id ) {
                    $wallet_object = hrw_get_wallet( $wallet_id ) ;

                    $meta_args = array(
                        'hrw_available_balance' => $wallet_object->get_available_balance() + $topup_price ,
                        'hrw_expired_date'      => hrw_get_wallet_expires_day() ,
                        'hrw_last_expired_date' => $wallet_object->get_expiry_date() ,
                            ) ;

                    hrw_update_wallet( $wallet_id , $meta_args , array( 'post_status' => 'hrw_active' ) ) ;
                } else {
                    $meta_args = array(
                        'hrw_available_balance' => $topup_price ,
                        'hrw_expired_date'      => hrw_get_wallet_expires_day() ,
                        'hrw_date'              => current_time( 'mysql' , true ) ,
                        'hrw_currency'          => get_woocommerce_currency()
                            ) ;

                    $wallet_id = hrw_create_new_wallet( $meta_args , array( 'post_parent' => $order->get_user_id() ) ) ;
                }

                //Insert Transaction log
                $transaction_meta_args = array(
                    'hrw_user_id'  => $order->get_user_id() ,
                    'hrw_event'    => get_option( 'hrw_localizations_wallet_topup_success_log' , 'Wallet Top-up Successfull' ) ,
                    'hrw_amount'   => $topup_price ,
                    'hrw_total'    => $meta_args[ 'hrw_available_balance' ] ,
                    'hrw_currency' => $order->get_currency() ,
                    'hrw_date'     => current_time( 'mysql' , true ) ,
                        ) ;

                $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $wallet_id , 'post_status' => 'hrw_credit' , ) ) ;

                do_action( 'hrw_after_wallet_amount_credited' , $wallet_id , $transaction_log_id ) ;
                //update custom meta for order
                update_post_meta( $order_id , 'hr_wallet_credited_once_flag' , 'yes' ) ;
            }
        }

        /*
         * Debit fund from user wallet when placing order using Wallet payment
         */

        public static function process_wallet_debit( $order ) {

            if ( HRW_Wallet_User::get_available_balance() < $order->get_total() )
                throw new Exception( esc_html__( 'Insufficient Balance in Wallet' , HRW_LOCALE ) ) ;

            //Update Wallet
            $meta_args = array(
                'hrw_available_balance' => HRW_Wallet_User::get_available_balance() - $order->get_total() ,
                'hrw_total_balance'     => HRW_Wallet_User::get_total_balance() + $order->get_total() ,
                    ) ;

            hrw_update_wallet( HRW_Wallet_User::get_wallet_id() , $meta_args ) ;

            $debit_message = get_option( 'hrw_localizations_wallet_usage_through_gateway_log' ) ;
            $log_message   = str_replace( '{orderid}' , '#' . $order->get_id() , $debit_message ) ;

            //Insert Transaction log
            $transaction_meta_args = array(
                'hrw_user_id'  => $order->get_user_id() ,
                'hrw_event'    => $log_message ,
                'hrw_amount'   => $order->get_total() ,
                'hrw_total'    => HRW_Wallet_User::get_available_balance() - $order->get_total() ,
                'hrw_currency' => $order->get_currency() ,
                'hrw_date'     => current_time( 'mysql' , true ) ,
                    ) ;

            $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => HRW_Wallet_User::get_wallet_id() , 'post_status' => 'hrw_debit' ) ) ;

            //update custom meta for order
            update_post_meta( $order->get_id() , 'hr_wallet_full_debit_flag' , 'yes' ) ;

            do_action( 'hrw_after_wallet_amount_debited' , HRW_Wallet_User::get_wallet_id() , $transaction_log_id ) ;
        }

        /*
         * Debit wallet Topu-up amount from the order.
         */

        public static function debit_topup_amount_from_order( $order_id ) {
            //return if Top-up refund process completed for this order.
            $already_process_completed = get_post_meta( $order_id , 'hrw_topup_amount_refunded' , true ) ;
            if ( $already_process_completed == 'yes' )
                return ;

            //return if order should not be placed by Top-up product.
            $topup_order = get_post_meta( $order_id , 'hr_wallet_credited_once_flag' , true ) ;
            if ( $topup_order != 'yes' )
                return ;

            //return if order is not placed by Top-up
            $topup_product_id = get_post_meta( $order_id , 'hr_wallet_topup_product' , true ) ;
            if ( ! $topup_product_id )
                return ;

            $order       = wc_get_order( $order_id ) ;
            $debit_price = get_post_meta( $order_id , 'hr_wallet_topup_fund' , true ) ;

            if ( $order->get_status() == 'cancelled' ) {
                $log_message = get_option( 'hrw_localizations_order_cancel_debit_amount_log' ) ;
            } elseif ( $order->get_status() == 'failed' ) {
                $log_message = get_option( 'hrw_localizations_order_failed_debit_amount_log' ) ;
            } else {
                $log_message = get_option( 'hrw_localizations_order_refund_debit_amount_log' ) ;
            }

            self::debit_amount_from_user( $order , $log_message , $debit_price ) ;

            update_post_meta( $order_id , 'hrw_topup_amount_refunded' , 'yes' ) ;
        }

        /*
         * Credit wallet amount when refunding the order.
         */

        public static function credit_wallet_amount_from_order( $order_id ) {
            //return if Top-up refund process completed for this order.
            $already_process_completed = get_post_meta( $order_id , 'hr_wallet_is_fully_refunded' , true ) ;
            if ( $already_process_completed == 'yes' )
                return ;

            //return if order should not be placed by wallet amount.
            $wallet_order = get_post_meta( $order_id , 'hr_wallet_full_debit_flag' , true ) ;
            if ( $wallet_order != 'yes' )
                return ;

            // return if order is not placed by wallet payment gateway
            $order = wc_get_order( $order_id ) ;
            if ( $order->get_payment_method() != 'HR_Wallet_Gateway' )
                return ;

            if ( $order->get_status() == 'cancelled' ) {
                $log_message = get_option( 'hrw_localizations_order_cancel_debit_amount_log' ) ;
            } elseif ( $order->get_status() == 'failed' ) {
                $log_message = get_option( 'hrw_localizations_order_failed_debit_amount_log' ) ;
            }

            self::credit_amount_to_user( $order , $log_message , $order->get_total() ) ;

            update_post_meta( $order_id , 'hr_wallet_is_fully_refunded' , 'yes' ) ;
        }

        /*
         * Credit partial amount when refunding the order.
         */

        public static function credit_partial_amount_from_order( $order_id ) {
            //return if Top-up refund process completed for this order.
            $already_process_completed = get_post_meta( $order_id , 'hr_wallet_is_partialy_refunded' , true ) ;
            if ( $already_process_completed == 'yes' )
                return ;

            //return if order should not be placed by partial amount.
            $partial_order = get_post_meta( $order_id , 'hr_wallet_partial_credit_flag' , true ) ;
            if ( $partial_order != 'yes' )
                return ;

            //return if order is not placed by partial amount
            $credit_price = get_post_meta( $order_id , 'partial_applied_amount' , true ) ;
            if ( ! $credit_price )
                return ;

            $order = wc_get_order( $order_id ) ;

            if ( $order->get_status() == 'cancelled' ) {
                $log_message = get_option( 'hrw_localizations_order_cancel_debit_amount_log' ) ;
            } elseif ( $order->get_status() == 'failed' ) {
                $log_message = get_option( 'hrw_localizations_order_failed_debit_amount_log' ) ;
            } else {
                $log_message = get_option( 'hrw_localizations_order_refund_debit_amount_log' ) ;
            }

            self::credit_amount_to_user( $order , $log_message , $credit_price ) ;

            update_post_meta( $order_id , 'hr_wallet_is_partialy_refunded' , 'yes' ) ;
        }

        /*
         * Debit wallet amount from user.
         */

        public static function debit_amount_from_user( $order , $log_message , $debit_price ) {

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $order->get_user_id() ) ;
            if ( ! $wallet_id )
                return ;

            $wallet_object = hrw_get_wallet( $wallet_id ) ;

            //Update Wallet
            $meta_args = array(
                'hrw_available_balance' => $wallet_object->get_available_balance() - $debit_price ,
                    ) ;

            hrw_update_wallet( $wallet_id , $meta_args ) ;

            $log_message = str_replace( '{orderid}' , '#' . $order->get_id() , $log_message ) ;

            //Insert Transaction log
            $transaction_meta_args = array(
                'hrw_user_id'  => $order->get_user_id() ,
                'hrw_event'    => $log_message ,
                'hrw_amount'   => $debit_price ,
                'hrw_total'    => $wallet_object->get_available_balance() - $debit_price ,
                'hrw_currency' => $order->get_currency() ,
                'hrw_date'     => current_time( 'mysql' , true ) ,
                    ) ;

            $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $wallet_id , 'post_status' => 'hrw_debit' ) ) ;

            do_action( 'hrw_after_wallet_amount_debited' , $wallet_id , $transaction_log_id ) ;
        }

        /*
         * Credit wallet amount to user.
         */

        public static function credit_amount_to_user( $order , $log_message , $credit_price ) {

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $order->get_user_id() ) ;
            if ( ! $wallet_id )
                return ;

            $wallet_object = hrw_get_wallet( $wallet_id ) ;

            //Update Wallet
            $meta_args = array(
                'hrw_available_balance' => $wallet_object->get_available_balance() + $credit_price ,
                'hrw_total_balance'     => $wallet_object->get_total_balance() - $credit_price ,
                    ) ;

            hrw_update_wallet( $wallet_id , $meta_args ) ;

            $log_message = str_replace( '{orderid}' , '#' . $order->get_id() , $log_message ) ;

            //Insert Transaction log
            $transaction_meta_args = array(
                'hrw_user_id'  => $order->get_user_id() ,
                'hrw_event'    => $log_message ,
                'hrw_amount'   => $credit_price ,
                'hrw_total'    => $wallet_object->get_available_balance() + $credit_price ,
                'hrw_currency' => $order->get_currency() ,
                'hrw_date'     => current_time( 'mysql' , true ) ,
                    ) ;

            $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $wallet_id , 'post_status' => 'hrw_credit' ) ) ;

            do_action( 'hrw_after_wallet_amount_credited' , $wallet_id , $transaction_log_id ) ;
        }

    }

    HRW_Order_Management::init() ;
}
