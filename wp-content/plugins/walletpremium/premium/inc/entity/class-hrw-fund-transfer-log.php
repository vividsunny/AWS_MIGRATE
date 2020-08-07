<?php

/*
 * Fund Transfer Log
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_Fund_Transfer_Log' ) ) {

    /**
     * HRW_Fund_Transfer_Log Class.
     */
    class HRW_Fund_Transfer_Log extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRWP_Register_Post_Types::FUND_TRANSFER_LOG_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'hrw_transfered' ;

        /**
         * Sender ID
         */
        protected $sender_id ;

        /**
         * Receiver ID
         */
        protected $hrw_receiver_id ;

        /**
         * Sender Transaction ID
         */
        protected $sender_transaction_id ;

        /**
         * Receiver Transaction ID
         */
        protected $receiver_transaction_id ;

        /**
         * Receiver Log ID
         */
        protected $hrw_receiver_log_id ;

        /**
         * Amount
         */
        protected $hrw_amount ;

        /**
         * Fee
         */
        protected $hrw_fee ;

        /**
         * Reason
         */
        protected $hrw_reason ;

        /**
         * Currency
         */
        protected $hrw_currency ;

        /**
         * Sent From
         */
        protected $hrw_sent_from ;

        /**
         * Date
         */
        protected $hrw_date ;

        /**
         * Sender
         */
        protected $sender ;

        /**
         * Receiver
         */
        protected $receiver ;

        /**
         * Sender Transaction
         */
        protected $sender_transaction ;

        /**
         * Receiver Transaction
         */
        protected $receiver_transaction ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'hrw_fee'                     => '' ,
            'hrw_receiver_id'             => '' ,
            'hrw_receiver_transaction_id' => '' ,
            'hrw_receiver_log_id'         => '' ,
            'hrw_amount'                  => '' ,
            'hrw_currency'                => '' ,
            'hrw_reason'                  => '' ,
            'hrw_sent_from'               => '' ,
            'hrw_date'                    => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->sender_id             = $this->post->post_author ;
            $this->sender_transaction_id = $this->post->post_parent ;
        }

        /**
         * Get Formatted date
         */
        public function get_formatted_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_date() ) ;
        }

        /**
         * Get Sender
         */
        public function get_sender() {

            if ( $this->sender )
                return $this->sender ;

            $this->sender = get_userdata( $this->get_sender_id() ) ;

            return $this->sender ;
        }

        /**
         * Get Receiver
         */
        public function get_receiver() {

            if ( $this->receiver )
                return $this->receiver ;

            $this->receiver = get_userdata( $this->get_receiver_id() ) ;

            return $this->receiver ;
        }

        /**
         * Get Sender Transaction
         */
        public function get_sender_transaction() {

            if ( $this->sender_transaction )
                return $this->sender_transaction ;

            $this->sender_transaction = hrw_get_fund_transfer( $this->get_sender_transaction_id() ) ;

            return $this->sender_transaction ;
        }

        /**
         * Get Receiver Transaction
         */
        public function get_receiver_transaction() {

            if ( $this->receiver_transaction )
                return $this->receiver_transaction ;

            $this->receiver_transaction = hrw_get_fund_transfer( $this->get_receiver_transaction_id() ) ;

            return $this->receiver_transaction ;
        }

        /**
         * Setters and Getters
         */

        /**
         * Set Sender ID
         */
        public function set_sender_id( $value ) {

            return $this->sender_id = $value ;
        }

        /**
         * Set Receiver ID
         */
        public function set_receiver_id( $value ) {

            return $this->hrw_receiver_id = $value ;
        }

        /**
         * Set Sender Transaction ID
         */
        public function set_sender_transaction_id( $value ) {

            return $this->sender_transaction_id = $value ;
        }

        /**
         * Set Receiver Transaction ID
         */
        public function set_receiver_transaction_id( $value ) {

            return $this->hrw_receiver_transaction_id = $value ;
        }

        /**
         * Set Receiver Log ID
         */
        public function set_receiver_log_id( $value ) {

            return $this->hrw_receiver_log_id = $value ;
        }

        /**
         * Set Amount
         */
        public function set_amount( $value ) {

            return $this->hrw_amount = $value ;
        }

        /**
         * Set Fee
         */
        public function set_fee( $value ) {

            return $this->hrw_fee = $value ;
        }

        /**
         * Set Reason
         */
        public function set_reason( $value ) {

            return $this->hrw_reason = $value ;
        }

        /**
         * Set currency
         */
        public function set_currency( $value ) {

            return $this->hrw_currency = $value ;
        }

        /**
         * Set Sent From
         */
        public function set_sent_from( $value ) {

            return $this->hrw_sent_from = $value ;
        }

        /**
         * Set date
         */
        public function set_date( $value ) {

            return $this->hrw_date = $value ;
        }

        /**
         * Get Sender ID
         */
        public function get_sender_id() {

            return $this->sender_id ;
        }

        /**
         * Get Receiver ID
         */
        public function get_receiver_id() {

            return $this->hrw_receiver_id ;
        }

        /**
         * Get Sender Transaction ID
         */
        public function get_sender_transaction_id() {

            return $this->sender_transaction_id ;
        }

        /**
         * Get Receiver Transaction ID
         */
        public function get_receiver_transaction_id() {

            return $this->hrw_receiver_transaction_id ;
        }

        /**
         * Get Receiver Log ID
         */
        public function get_receiver_log_id() {

            return $this->hrw_receiver_log_id ;
        }

        /**
         * Get Amount
         */
        public function get_amount() {

            return $this->hrw_amount ;
        }

        /**
         * Get Fee
         */
        public function get_fee() {

            return $this->hrw_fee ;
        }

        /**
         * Get Reason
         */
        public function get_reason() {

            return $this->hrw_reason ;
        }

        /**
         * Get currency
         */
        public function get_currency() {

            return $this->hrw_currency ;
        }

        /**
         * Get Sent From
         */
        public function get_sent_from() {

            return $this->hrw_sent_from ;
        }

        /**
         * Get date
         */
        public function get_date() {

            return $this->hrw_date ;
        }

    }

}
    