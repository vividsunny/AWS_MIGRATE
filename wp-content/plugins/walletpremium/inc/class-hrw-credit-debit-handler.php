<?php

/**
 *  Credit Debit Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Credit_Debit_Handler' ) ) {

    /**
     * Class
     */
    class HRW_Credit_Debit_Handler {
        /*
         * Credit Top-up price to user wallet
         */

        public static function credit_amount_to_wallet( $credit_args ) {

            $default_args = array(
                'user_id'  => get_current_user_id() ,
                'amount'   => 0 ,
                'event'    => '' ,
                'currency' => get_woocommerce_currency()
                    ) ;

            $credit_args = wp_parse_args( $credit_args , $default_args ) ;

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $credit_args[ 'user_id' ] ) ;

            //Insert/update Wallet  
            if ( $wallet_id ) {
                $wallet_object = hrw_get_wallet( $wallet_id ) ;

                $meta_args = array(
                    'hrw_available_balance' => $wallet_object->get_available_balance() + $credit_args[ 'amount' ] ,
                    'hrw_expired_date'      => hrw_get_wallet_expires_day() ,
                    'hrw_last_expired_date' => $wallet_object->get_expiry_date() ,
                        ) ;

                hrw_update_wallet( $wallet_id , $meta_args ) ;
            } else {
                $meta_args = array(
                    'hrw_available_balance' => $credit_args[ 'amount' ] ,
                    'hrw_expired_date'      => hrw_get_wallet_expires_day() ,
                    'hrw_date'              => current_time( 'mysql' , true ) ,
                    'hrw_currency'          => get_woocommerce_currency()
                        ) ;

                $wallet_id = hrw_create_new_wallet( $meta_args , array( 'post_parent' => $credit_args[ 'user_id' ] ) ) ;
            }

            //Insert Transaction log
            $transaction_meta_args = array(
                'hrw_user_id'  => $credit_args[ 'user_id' ] ,
                'hrw_event'    => $credit_args[ 'event' ] ,
                'hrw_amount'   => $credit_args[ 'amount' ] ,
                'hrw_total'    => $meta_args[ 'hrw_available_balance' ] ,
                'hrw_currency' => $credit_args[ 'currency' ] ,
                'hrw_date'     => current_time( 'mysql' , true ) ,
                    ) ;

            $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $wallet_id , 'post_status' => 'hrw_credit' ) ) ;

            do_action( 'hrw_after_wallet_amount_credited' , $wallet_id , $transaction_log_id ) ;

            return $wallet_id ;
        }

        /*
         * Debit Top-up price from user wallet
         */

        public static function debit_amount_from_wallet( $debit_args ) {

            $default_args = array(
                'user_id'  => get_current_user_id() ,
                'amount'   => 0 ,
                'event'    => '' ,
                'currency' => get_woocommerce_currency()
                    ) ;

            $debit_args = wp_parse_args( $debit_args , $default_args ) ;

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $debit_args[ 'user_id' ] ) ;

            if ( ! $wallet_id )
                throw new exception( esc_html__( 'Wallet not found' , HRW_LOCALE ) ) ;

            $wallet_object = hrw_get_wallet( $wallet_id ) ;

            if ( $wallet_object->get_available_balance() < $debit_args[ 'amount' ] )
                throw new exception( esc_html__( 'Insufficient Balance in Wallet' , HRW_LOCALE ) ) ;

            //Update Wallet
            $meta_args = array(
                'hrw_available_balance' => $wallet_object->get_available_balance() - $debit_args[ 'amount' ] ,
                    ) ;

            hrw_update_wallet( $wallet_id , $meta_args ) ;

            //Insert Transaction log
            $transaction_meta_args = array(
                'hrw_user_id'  => $debit_args[ 'user_id' ] ,
                'hrw_event'    => $debit_args[ 'event' ] ,
                'hrw_amount'   => $debit_args[ 'amount' ] ,
                'hrw_total'    => $wallet_object->get_available_balance() - $debit_args[ 'amount' ] ,
                'hrw_currency' => $debit_args[ 'currency' ] ,
                'hrw_date'     => current_time( 'mysql' , true ) ,
                    ) ;

            $transaction_log_id = hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $wallet_id , 'post_status' => 'hrw_debit' ) ) ;

            do_action( 'hrw_after_wallet_amount_debited' , $wallet_id , $transaction_log_id ) ;

            return $wallet_id ;
        }

    }

}
