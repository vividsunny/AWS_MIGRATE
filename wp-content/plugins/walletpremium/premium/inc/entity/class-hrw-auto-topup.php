<?php

/*
 * Wallet Auto Top-up
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_Auto_Topup' ) ) {

    /**
     * HRW_Auto_Top-up Class.
     */
    class HRW_Auto_Topup extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = 'hrw_auto_topup' ;

        /**
         * Post Status
         */
        protected $post_status = 'hrw_active' ;

        /**
         * User ID
         */
        protected $user_id ;

        /**
         * Wallet ID
         */
        protected $wallet_id ;

        /**
         * User
         */
        protected $user ;

        /**
         * User
         */
        protected $wallet ;

        /**
         * Topup Amount
         */
        protected $hrw_topup_amount ;

        /**
         * Threshold Amount
         */
        protected $hrw_threshold_amount ;

        /**
         * Payment Method
         */
        protected $hrw_payment_method ;

        /**
         * Last Order ID
         */
        protected $hrw_last_order ;

        /**
         * Currency
         */
        protected $hrw_currency ;

        /**
         * Last Charge Date
         */
        protected $hrw_last_charge_date ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'hrw_topup_amount'     => '' ,
            'hrw_threshold_amount' => '' ,
            'hrw_payment_method'   => '' ,
            'hrw_last_order'       => '' ,
            'hrw_currency'         => '' ,
            'hrw_last_charge_date' => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->user_id   = $this->post->post_author ;
            $this->wallet_id = $this->post->post_parent ;
        }

        /**
         * Get User
         */
        public function get_user() {

            if ( $this->user )
                return $this->user ;

            $this->user = get_userdata( $this->get_user_id() ) ;

            return $this->user ;
        }

        /**
         * Get User
         */
        public function get_wallet() {

            if ( $this->wallet )
                return $this->wallet ;

            $this->wallet = hrw_get_wallet( $this->get_wallet_id() ) ;

            return $this->wallet ;
        }

        /**
         * Get Formatted last charge datetime
         */
        public function get_formatted_last_charge_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_last_charge_date() ) ;
        }

        /**
         * Setters and Getters
         */

        /**
         * Set Top-up Amount
         */
        public function set_topup_amount( $value ) {

            return $this->hrw_topup_amount = $value ;
        }

        /**
         * Set Threshold Amount
         */
        public function set_threshold_amount( $value ) {

            return $this->hrw_threshold_amount = $value ;
        }

        /**
         * Set Payment method
         */
        public function set_payment_method( $value ) {

            return $this->hrw_payment_method = $value ;
        }

        /**
         * Set Last Order ID
         */
        public function set_last_order( $value ) {

            return $this->hrw_last_order = $value ;
        }

        /**
         * Set currency
         */
        public function set_currency( $value ) {

            return $this->hrw_currency = $value ;
        }

        /**
         * Set Last Charge Date
         */
        public function set_last_charge_date( $value ) {

            return $this->hrw_last_charge_date = $value ;
        }

        /**
         * Get user id
         */
        public function get_user_id() {

            return $this->user_id ;
        }

        /**
         * Get wallet id
         */
        public function get_wallet_id() {

            return $this->wallet_id ;
        }

        /**
         * Get Top-up Amount
         */
        public function get_topup_amount() {

            return $this->hrw_topup_amount ;
        }

        /**
         * Get Threshold Amount
         */
        public function get_threshold_amount() {

            return $this->hrw_threshold_amount ;
        }

        /**
         * Get Payment method
         */
        public function get_payment_method() {

            return $this->hrw_payment_method ;
        }

        /**
         * Get Last Order ID
         */
        public function get_last_order() {

            return $this->hrw_last_order ;
        }

        /**
         * Get currency
         */
        public function get_currency() {

            return $this->hrw_currency ;
        }

        /**
         * Get Last Charge Date
         */
        public function get_last_charge_date() {

            return $this->hrw_last_charge_date ;
        }

    }

}
    