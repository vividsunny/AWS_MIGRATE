<?php

/*
 * Transaction Log
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_Transaction_Log' ) ) {

    /**
     * HRW_Transaction_Log Class.
     */
    class HRW_Transaction_Log extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'hrw_credit' ;

        /**
         * User ID
         */
        protected $hrw_user_id ;

        /**
         * Event
         */
        protected $hrw_event ;

        /**
         * Amount
         */
        protected $hrw_amount ;

        /**
         * Total
         */
        protected $hrw_total ;

        /**
         * Currency
         */
        protected $hrw_currency ;

        /**
         * Date
         */
        protected $hrw_date ;

        /**
         * User
         */
        protected $user ;

        /**
         * Phone
         */
        protected $phone ;

        /**
         * User
         */
        protected $wallet_id ;

        /**
         * Wallet Object
         */
        protected $wallet ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'hrw_user_id'  => '' ,
            'hrw_event'    => '' ,
            'hrw_amount'   => '' ,
            'hrw_total'    => '' ,
            'hrw_currency' => '' ,
            'hrw_date'     => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->wallet_id = $this->post->post_parent ;
        }

        /**
         * Get Formatted datetime
         */
        public function get_formatted_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_date() ) ;
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
         * Get Phone
         */
        public function get_phone() {

            if ( isset( $this->phone ) )
                return $this->phone ;

            $this->phone = get_user_meta( $this->get_user_id() , 'hrw_phone_number' , true ) ;

            return $this->phone ;
        }

        /**
         * Get Wallet
         */
        public function get_wallet() {

            if ( isset( $this->wallet ) )
                return $this->wallet ;

            $this->wallet = hrw_get_wallet( $this->get_wallet_id() ) ;

            return $this->wallet ;
        }

        /**
         * Setters and Getters
         */

        /**
         * Set Wallet Id
         */
        public function set_wallet_id( $value ) {

            return $this->hrw_wallet_id = $value ;
        }

        /**
         * Set User Id
         */
        public function set_user_id( $value ) {

            return $this->hrw_user_id = $value ;
        }

        /**
         * Set Event
         */
        public function set_event( $value ) {

            return $this->hrw_event = $value ;
        }

        /**
         * Set Amount
         */
        public function set_amount( $value ) {

            return $this->hrw_amount = $value ;
        }

        /**
         * Set Total
         */
        public function set_total( $value ) {

            return $this->hrw_total = $value ;
        }

        /**
         * Set currency
         */
        public function set_currency( $value ) {

            return $this->hrw_currency = $value ;
        }

        /**
         * Set date
         */
        public function set_date( $value ) {

            return $this->hrw_date = $value ;
        }

        /**
         * Get Wallet ID
         */
        public function get_wallet_id() {

            return $this->wallet_id ;
        }

        /**
         * Get User ID
         */
        public function get_user_id() {

            return $this->hrw_user_id ;
        }

        /**
         * Get Event
         */
        public function get_event() {

            return $this->hrw_event ;
        }

        /**
         * Get Amount
         */
        public function get_amount() {

            return $this->hrw_amount ;
        }

        /**
         * Get Total
         */
        public function get_total() {

            return $this->hrw_total ;
        }

        /**
         * Get currency
         */
        public function get_currency() {

            return $this->hrw_currency ;
        }

        /**
         * Get date
         */
        public function get_date() {

            return $this->hrw_date ;
        }

    }

}
    