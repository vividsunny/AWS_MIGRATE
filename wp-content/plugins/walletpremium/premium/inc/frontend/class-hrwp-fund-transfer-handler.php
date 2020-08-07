<?php

/**
 *  Fund Transfer Handler
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRWP_Fund_Transfer_Handler' ) ) {

    /**
     * Class
     */
    class HRWP_Fund_Transfer_Handler {
        /*
         * Process fund transaction
         */

        public static function process_fund_transaction( $args ) {

            $default_args = array(
                'sender_id'   => 0 ,
                'receiver_id' => 0 ,
                'amount'      => 0 ,
                'fee'         => 0 ,
                'reason'      => '' ,
                'currency'    => get_woocommerce_currency() ,
                'type'        => 'transfer'
                    ) ;

            $args = wp_parse_args( $args , $default_args ) ;

            //status
            if ( $args[ 'type' ] == 'transfer' ) {
                $args[ 'sender_status' ]   = 'hrw_transfered' ;
                $args[ 'receiver_status' ] = 'hrw_received' ;
            } else {
                $args[ 'sender_status' ]   = 'hrw_requested' ;
                $args[ 'receiver_status' ] = 'hrw_new_requested' ;
            }

            //handle sender transaction
            $sender_transaction_id   = self::handle_sender_transaction( $args ) ;
            //handle receiver transaction
            $receiver_transaction_id = self::handle_receiver_transaction( $args ) ;

            //handle sender transaction log
            $sender_log_id   = self::handle_sender_transaction_log( $args , $sender_transaction_id , $receiver_transaction_id ) ;
            //handle receiver transaction log
            $receiver_log_id = self::handle_receiver_transaction_log( $args , $receiver_transaction_id , $sender_transaction_id ) ;

            //Update receiver log id in sender and receiver
            update_post_meta( $sender_log_id , 'hrw_receiver_log_id' , $receiver_log_id ) ;
            update_post_meta( $receiver_log_id , 'hrw_receiver_log_id' , $sender_log_id ) ;

            if ( $args[ 'type' ] != 'request' ) {
                //handle sender wallet debit
                self::handle_sender_wallet_debit( $args ) ;
                //handle receiver wallet credit
                self::handle_receiver_wallet_credit( $args ) ;
            }

            do_action( 'hrw_after_fund_transaction' , $args ) ;

            do_action( 'hrw_after_fund_' . $args[ 'type' ] , $args ) ;
        }

        /*
         * Process Response Fund Request
         */

        public static function process_response_fund_request( $args ) {

            $default_args = array(
                'sender_id'       => 0 ,
                'receiver_id'     => 0 ,
                'amount'          => 0 ,
                'fee'             => 0 ,
                'sender_log_id'   => 0 ,
                'receiver_log_id' => 0 ,
                'currency'        => get_woocommerce_currency() ,
                'type'            => 'transfer'
                    ) ;

            $args = wp_parse_args( $args , $default_args ) ;

            //status
            if ( $args[ 'type' ] == 'transfer' ) {
                $args[ 'sender_status' ]   = 'hrw_transfered' ;
                $args[ 'receiver_status' ] = 'hrw_received' ;
            } elseif ( $args[ 'type' ] == 'decline' ) {
                $args[ 'sender_status' ]   = 'hrw_declined' ;
                $args[ 'receiver_status' ] = 'hrw_request_declined' ;
            } else {
                $args[ 'sender_status' ]   = 'hrw_cancelled' ;
                $args[ 'receiver_status' ] = 'hrw_request_cancel' ;
            }

            //process sender fund transfer
            $sender_transaction_id   = self::process_sender_fund_request( $args ) ;
            //process receiver fund transfer
            $receiver_transaction_id = self::process_receiver_fund_request( $args ) ;

            if ( $args[ 'type' ] == 'transfer' ) {

                //handle sender wallet debit
                self::handle_sender_wallet_debit( $args ) ;
                //handle receiver wallet credit
                self::handle_receiver_wallet_credit( $args ) ;
            }

            //Update sender/receiver transaction log status
            hrw_update_fund_transfer_log( $args[ 'sender_log_id' ] , array() , array( 'post_status' => $args[ 'sender_status' ] ) ) ;
            hrw_update_fund_transfer_log( $args[ 'receiver_log_id' ] , array() , array( 'post_status' => $args[ 'receiver_status' ] ) ) ;

            do_action( 'hrw_after_fund_request_response' , $args ) ;

            do_action( 'hrw_after_fund_request_' . $args[ 'type' ] . '_response' , $args ) ;
        }

        /*
         * Handle Sender fund transfer
         */

        public static function process_sender_fund_request( $args ) {
            $sender_transaction_id = hrw_get_fund_transfer_id_by_sender_id( $args[ 'sender_id' ] , $args[ 'receiver_id' ] ) ;

            //check if sender having transaction id
            if ( $sender_transaction_id ) {

                $meta_args = array( 'hrw_last_activity' => current_time( 'mysql' , true ) ) ;

                if ( $args[ 'type' ] == 'transfer' ) {
                    $transfered_amount                   = ( float ) get_post_meta( $sender_transaction_id , 'hrw_total_transfered' , true ) ;
                    $meta_args[ 'hrw_total_transfered' ] = $transfered_amount + $args[ 'amount' ] ;
                }

                //update the sender transaction details
                $sender_transaction_id = hrw_update_fund_transfer( $sender_transaction_id , $meta_args , array( 'post_status' => $args[ 'sender_status' ] ) ) ;
            }

            return $sender_transaction_id ;
        }

        /*
         * Handle Receiver fund transfer
         */

        public static function process_receiver_fund_request( $args ) {
            $receiver_transaction_id = hrw_get_fund_transfer_id_by_sender_id( $args[ 'receiver_id' ] , $args[ 'sender_id' ] ) ;

            //check if receiver having transaction id
            if ( $receiver_transaction_id ) {
                $meta_args = array( 'hrw_last_activity' => current_time( 'mysql' , true ) ) ;

                if ( $args[ 'type' ] == 'transfer' ) {
                    $recevied_amount                   = ( float ) get_post_meta( $receiver_transaction_id , 'hrw_total_received' , true ) ;
                    $meta_args[ 'hrw_total_received' ] = $recevied_amount + $args[ 'amount' ] ;
                }

                //update the Receiver transaction details
                $receiver_transaction_id = hrw_update_fund_transfer( $receiver_transaction_id , $meta_args , array( 'post_status' => $args[ 'receiver_status' ] ) ) ;
            }

            return $receiver_transaction_id ;
        }

        /*
         * Handle Sender transaction
         */

        public static function handle_sender_transaction( $args ) {
            $sender_transaction_id = hrw_get_fund_transfer_id_by_sender_id( $args[ 'sender_id' ] , $args[ 'receiver_id' ] ) ;

            $meta_args = array( 'hrw_last_activity' => current_time( 'mysql' , true ) ) ;
            $post_args = array( 'post_status' => $args[ 'sender_status' ] ) ;

            //check if already sender transaction id is created
            if ( $sender_transaction_id ) {

                if ( $args[ 'type' ] == 'request' ) {
                    $requested_amount                   = ( float ) get_post_meta( $sender_transaction_id , 'hrw_total_requested' , true ) ;
                    $meta_args[ 'hrw_total_requested' ] = $requested_amount + $args[ 'amount' ] ;
                } else {
                    $transfered_amount                   = ( float ) get_post_meta( $sender_transaction_id , 'hrw_total_transfered' , true ) ;
                    $meta_args[ 'hrw_total_transfered' ] = $transfered_amount + $args[ 'amount' ] ;
                }

                //Update the Transaction details
                $sender_transaction_id = hrw_update_fund_transfer( $sender_transaction_id , $meta_args , $post_args ) ;
            } else {

                $meta_args[ 'hrw_currency' ]      = $args[ 'currency' ] ;
                $meta_args[ 'hrw_date' ]          = current_time( 'mysql' , true ) ;
                $meta_args[ 'hrw_last_activity' ] = current_time( 'mysql' , true ) ;

                if ( $args[ 'type' ] == 'request' ) {
                    $meta_args[ 'hrw_total_requested' ] = $args[ 'amount' ] ;
                } else {
                    $meta_args[ 'hrw_total_transfered' ] = $args[ 'amount' ] ;
                }

                $post_args[ 'post_author' ] = $args[ 'sender_id' ] ;
                $post_args[ 'post_parent' ] = $args[ 'receiver_id' ] ;

                //Create the new sender transaction
                $sender_transaction_id = hrw_create_new_fund_transfer( $meta_args , $post_args ) ;
            }

            return $sender_transaction_id ;
        }

        /*
         * Handle Sender transaction
         */

        public static function handle_receiver_transaction( $args ) {
            $receiver_transaction_id = hrw_get_fund_transfer_id_by_sender_id( $args[ 'receiver_id' ] , $args[ 'sender_id' ] ) ;

            $meta_args = array( 'hrw_last_activity' => current_time( 'mysql' , true ) ) ;
            $post_args = array( 'post_status' => $args[ 'receiver_status' ] ) ;

            //check if already receiver transaction id is created
            if ( $receiver_transaction_id ) {

                if ( $args[ 'type' ] != 'request' ) {
                    $received_amount                   = ( float ) get_post_meta( $receiver_transaction_id , 'hrw_total_received' , true ) ;
                    $meta_args[ 'hrw_total_received' ] = $received_amount + $args[ 'amount' ] ;
                }

                //Update the Receiver transaction details
                $receiver_transaction_id = hrw_update_fund_transfer( $receiver_transaction_id , $meta_args , $post_args ) ;
            } else {

                $meta_args[ 'hrw_currency' ]      = $args[ 'currency' ] ;
                $meta_args[ 'hrw_last_activity' ] = current_time( 'mysql' , true ) ;
                $meta_args[ 'hrw_date' ]          = current_time( 'mysql' , true ) ;

                if ( $args[ 'type' ] != 'request' ) {
                    $meta_args[ 'hrw_total_received' ] = $args[ 'amount' ] ;
                }

                $post_args[ 'post_author' ] = $args[ 'receiver_id' ] ;
                $post_args[ 'post_parent' ] = $args[ 'sender_id' ] ;

                //Create the new receiver transaction
                $receiver_transaction_id = hrw_create_new_fund_transfer( $meta_args , $post_args ) ;
            }

            return $receiver_transaction_id ;
        }

        /*
         * Handle Sender Transaction Log
         */

        public static function handle_sender_transaction_log( $args , $sender_transaction_id , $receiver_transaction_id ) {

            $meta_args = array(
                'hrw_fee'                     => $args[ 'fee' ] ,
                'hrw_receiver_id'             => $args[ 'receiver_id' ] ,
                'hrw_receiver_transaction_id' => $receiver_transaction_id ,
                'hrw_amount'                  => $args[ 'amount' ] ,
                'hrw_currency'                => $args[ 'currency' ] ,
                'hrw_reason'                  => $args[ 'reason' ] ,
                'hrw_sent_from'               => 'yes' ,
                'hrw_date'                    => current_time( 'mysql' , true )
                    ) ;

            $post_args = array(
                'post_author' => $args[ 'sender_id' ] ,
                'post_parent' => $sender_transaction_id ,
                'post_status' => $args[ 'sender_status' ]
                    ) ;

            return hrw_create_new_fund_transfer_log( $meta_args , $post_args ) ;
        }

        /*
         * Handle Receiver Transaction Log
         */

        public static function handle_receiver_transaction_log( $args , $receiver_transaction_id , $sender_transaction_id ) {

            $meta_args = array(
                'hrw_fee'                     => $args[ 'fee' ] ,
                'hrw_receiver_id'             => $args[ 'sender_id' ] ,
                'hrw_receiver_transaction_id' => $sender_transaction_id ,
                'hrw_amount'                  => $args[ 'amount' ] ,
                'hrw_currency'                => $args[ 'currency' ] ,
                'hrw_reason'                  => $args[ 'reason' ] ,
                'hrw_sent_from'               => 'no' ,
                'hrw_date'                    => current_time( 'mysql' , true )
                    ) ;

            $post_args = array(
                'post_author' => $args[ 'receiver_id' ] ,
                'post_parent' => $receiver_transaction_id ,
                'post_status' => $args[ 'receiver_status' ]
                    ) ;

            return hrw_create_new_fund_transfer_log( $meta_args , $post_args ) ;
        }

        /*
         * Handle Sender Wallet Debit
         */

        public static function handle_sender_wallet_debit( $args ) {

            $receiver_user_data = get_userdata( $args[ 'receiver_id' ] ) ;
            $search_array       = array( '[amount]' , '[user_name]' ) ;
            $replace_array      = array( hrw_price( $args[ 'amount' ] ) , $receiver_user_data->display_name ) ;

            $event = str_replace( $search_array , $replace_array , HRW_Module_Instances::get_module_by_id( 'fund_transfer' )->fund_debit_localization ) ;

            $debit_args = array(
                'user_id'  => $args[ 'sender_id' ] ,
                'amount'   => $args[ 'amount' ] + $args[ 'fee' ] ,
                'event'    => $event ,
                'currency' => $args[ 'currency' ]
                    ) ;

            HRW_Credit_Debit_Handler::debit_amount_from_wallet( $debit_args ) ;
        }

        /*
         * Handle Receiver Wallet Debit
         */

        public static function handle_receiver_wallet_credit( $args ) {
            $sender_user_data = get_userdata( $args[ 'sender_id' ] ) ;
            $search_array     = array( '[amount]' , '[user_name]' ) ;
            $replace_array    = array( hrw_price( $args[ 'amount' ] ) , $sender_user_data->display_name ) ;

            $event = str_replace( $search_array , $replace_array , HRW_Module_Instances::get_module_by_id( 'fund_transfer' )->fund_credit_localization ) ;

            $debit_args = array(
                'user_id'  => $args[ 'receiver_id' ] ,
                'amount'   => $args[ 'amount' ] ,
                'event'    => $event ,
                'currency' => $args[ 'currency' ]
                    ) ;

            HRW_Credit_Debit_Handler::credit_amount_to_wallet( $debit_args ) ;
        }

    }

}
