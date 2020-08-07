<?php

/**
 *  Wallet Withdrawal Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Withdrawal_Handler' ) ) {

    /**
     * Class
     */
    class HRWP_Withdrawal_Handler {
        /*
         * Withdrawal the wallet amount
         */

        public static function process_wallet_withdrawal( $args ) {

            $default_args = array(
                'user_id'        => 0 ,
                'wallet_id'      => 0 ,
                'amount'         => 0 ,
                'fee'            => 0 ,
                'reason'         => '' ,
                'payment_method' => '' ,
                'paypal_details' => '' ,
                'bank_details'   => '' ,
                'currency'       => get_woocommerce_currency() ,
                'requested_date' => current_time( 'mysql' , true ) ,
                'type'           => '1'
                    ) ;

            $args = wp_parse_args( $args , $default_args ) ;

            $meta_args = array(
                'hrw_withdrawal_amount' => $args[ 'amount' ] ,
                'hrw_withdrawal_fee'    => $args[ 'fee' ] ,
                'hrw_withdrawal_reason' => $args[ 'reason' ] ,
                'hrw_payment_method'    => $args[ 'payment_method' ] ,
                'hrw_bank_details'      => $args[ 'bank_details' ] ,
                'hrw_paypal_details'    => $args[ 'paypal_details' ] ,
                'hrw_requested_date'    => $args[ 'requested_date' ] ,
                    ) ;

            $withdrawal_id = hrw_create_new_wallet_withdrawal( $meta_args , array( 'post_parent' => $args[ 'wallet_id' ] ) ) ;

            //handle wallet debit
            self::handle_wallet_debit ( $args ) ;

            do_action ( 'hrw_withdrawal_request_notification' , $withdrawal_id ) ;

            return $withdrawal_id ;
        }

        /*
         * Handle Wallet Debit
         */

        public static function handle_wallet_debit( $args ) {

            $debit_args = array(
                'user_id' => $args[ 'user_id' ] ,
                'amount'    => $args[ 'amount' ] + $args[ 'fee' ] ,
                'event'     => 'Wallet Debit from Withdrawal' ,
                'currency'  => $args[ 'currency' ]
                    ) ;

            HRW_Credit_Debit_Handler::debit_amount_from_wallet( $debit_args ) ;
        }

        /*
         * Handle Wallet Credit
         */

        public static function handle_wallet_credit( $id , $args = array() ) {

            $withdrawal_obj = hrw_get_wallet_withdrawal( $id ) ;

            if( $withdrawal_obj->get_status() != 'hrw_cancelled' )
                return ;

            $credit_args = array(
                'user_id' => $withdrawal_obj->get_user_id() ,
                'amount'    => $withdrawal_obj->get_amount() + $withdrawal_obj->get_fee() ,
                'event'     => 'Wallet Credit from Withdrawal' ,
                'currency'  => get_woocommerce_currency() ,
                    ) ;

            $args = wp_parse_args( $credit_args , $args ) ;
            
            HRW_Credit_Debit_Handler::credit_amount_to_wallet( $args ) ;
        }

    }

}
