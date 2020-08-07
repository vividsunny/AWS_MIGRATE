<?php

/*
 * Wallet Withdrawal
 */
if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists ( 'HRW_Wallet_Withdrawal' ) ) {

    /**
     * HRW_Wallet_Withdrawal Class.
     */
    class HRW_Wallet_Withdrawal extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'hrw_unpaid' ;

        /**
         * User ID
         */
        protected $user_id ;

        /**
         * Wallet ID
         */
        protected $wallet_id ;

        /**
         * Amount
         */
        protected $hrw_withdrawal_amount ;

        /**
         * Fee
         */
        protected $hrw_withdrawal_fee ;

        /**
         * Reason
         */
        protected $hrw_withdrawal_reason ;

        /**
         * Payment Method
         */
        protected $hrw_payment_method ;

        /**
         * Bank Details
         */
        protected $hrw_bank_details ;

        /**
         * Paypal Details
         */
        protected $hrw_paypal_details ;

        /**
         * Requested Date
         */
        protected $hrw_requested_date ;

        /**
         * Processed Date
         */
        protected $hrw_processed_date ;

        /**
         * Currency
         */
        protected $hrw_currency ;

        /**
         * Wallet
         */
        protected $wallet ;

        /**
         * User
         */
        protected $user ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array (
            'hrw_withdrawal_amount' => 0 ,
            'hrw_withdrawal_fee'    => 0 ,
            'hrw_withdrawal_reason' => '' ,
            'hrw_payment_method'    => '' ,
            'hrw_bank_details'      => '' ,
            'hrw_paypal_details'    => '' ,
            'hrw_requested_date'    => '' ,
            'hrw_processed_date'    => '' ,
            'hrw_currency'          => ''
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->user_id   = $this->post->post_author ;
            $this->wallet_id = $this->post->post_parent ;
        }

        /**
         * Get formatted requested date
         */
        public function get_formatted_requested_date() {

            return HRW_Date_Time::get_date_object_format_datetime ( $this->get_requested_date () ) ;
        }

        /**
         * Get formatted processed date
         */
        public function get_formatted_processed_date() {

            if ( ! $this->get_processed_date () )
                return '-' ;

            return HRW_Date_Time::get_date_object_format_datetime ( $this->get_processed_date () ) ;
        }

        /**
         * Get User
         */
        public function get_user() {

            if ( $this->user )
                return $this->user ;

            $this->user = get_userdata ( $this->get_user_id () ) ;

            return $this->user ;
        }

        /**
         * Get Wallet
         */
        public function get_wallet() {

            if ( $this->wallet )
                return $this->wallet ;

            $this->wallet = hrw_get_wallet ( $this->get_wallet_id () ) ;

            return $this->wallet ;
        }

        /**
         * Setters and Getters
         */

        /**
         * Set Amount
         */
        public function set_amount( $value ) {

            return $this->hrw_withdrawal_amount = $value ;
        }

        /**
         * Set Fee
         */
        public function set_fee( $value ) {

            return $this->hrw_withdrawal_fee = $value ;
        }

        /**
         * Set Reason
         */
        public function set_reason( $value ) {

            return $this->hrw_withdrawal_reason = $value ;
        }

        /**
         * Set Payment Method
         */
        public function set_payment_method( $value ) {

            return $this->hrw_payment_method = $value ;
        }

        /**
         * Set Bank Details
         */
        public function set_bank_details( $value ) {

            return $this->hrw_payment_details = $value ;
        }

        /**
         * Set Paypal Details
         */
        public function set_paypal_details( $value ) {

            return $this->hrw_paypal_details = $value ;
        }

        /**
         * Set Processed Date
         */
        public function set_processed_date( $value ) {

            return $this->hrw_processed_date = $value ;
        }

        /**
         * Set Requested Date
         */
        public function set_requested_date( $value ) {

            return $this->hrw_requested_date = $value ;
        }

        /**
         * Set Currency
         */
        public function set_currency( $value ) {

            return $this->hrw_currency = $value ;
        }

        /**
         * Get User ID
         */
        public function get_user_id() {

            return $this->user_id ;
        }

        /**
         * Get Wallet ID
         */
        public function get_wallet_id() {

            return $this->wallet_id ;
        }

        /**
         * Get Amount
         */
        public function get_amount() {

            return $this->hrw_withdrawal_amount ;
        }

        /**
         * Get Fee
         */
        public function get_fee_included_amount() {

            return $this->hrw_withdrawal_amount + $this->hrw_withdrawal_fee ;
        }

        /**
         * Get Fee
         */
        public function get_fee() {

            return $this->hrw_withdrawal_fee ;
        }

        /**
         * Get Reason
         */
        public function get_reason() {

            return $this->hrw_withdrawal_reason ;
        }

        /**
         * Get Payment Method
         */
        public function get_payment_method() {

            return $this->hrw_payment_method ;
        }

        /**
         * Get Bank Details
         */
        public function get_bank_details() {

            return $this->hrw_bank_details ;
        }

        /**
         * Get Paypal Details
         */
        public function get_paypal_details() {

            return $this->hrw_paypal_details ;
        }

        /**
         * Get Requested Date
         */
        public function get_requested_date() {

            return $this->hrw_requested_date ;
        }

        /**
         * Get Processed Date
         */
        public function get_processed_date() {
            return $this->hrw_processed_date ;
        }

        /**
         * Get Currency
         */
        public function get_currency() {

            return $this->hrw_currency ;
        }

    }

}
    