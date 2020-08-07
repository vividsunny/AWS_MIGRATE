<?php

/*
 * Cashback Log
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_Cashback_Log' ) ) {
    
    /**
     * HRW_Cashback_Log Class.
     */
    class HRW_Cashback_Log extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRWP_Register_Post_Types::CASHBACK_LOG_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'publish' ;

        /**
         * Event
         */
        protected $hrw_event ;

        /**
         * Amount Credited
         */
        protected $hrw_amount_credited ;

        /**
         * Amount Debited
         */
        protected $hrw_amount_debited ;

        /**
         * Date
         */
        protected $hrw_date ;

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
            'hrw_event'           => '' ,
            'hrw_amount_credited' => 0 ,
            'hrw_amount_debited'  => 0 ,
            'hrw_date'            => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->wallet_id = $this->post->post_parent ;
        }

        /**
         * Set Wallet Id
         */
        public function set_wallet_id( $value ) {

            return $this->hrw_wallet_id = $value ;
        }

        /**
         * Set Event
         */
        public function set_event( $value ) {

            return $this->hrw_event = $value ;
        }

        /**
         * Set Credit Amount
         */
        public function set_credit_amount( $value ) {

            return $this->hrw_amount_credited = $value ;
        }

        /**
         * Set Debit Amount
         */
        public function set_debit_amount( $value ) {

            return $this->hrw_amount_debited = $value ;
        }

        /**
         * Set date
         */
        public function set_date( $value ) {

            return $this->hrw_date = $value ;
        }

        /**
         * Get Formatted datetime
         */
        public function get_formatted_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_date() ) ;
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
         * Get Wallet ID
         */
        public function get_wallet_id() {

            return $this->wallet_id ;
        }

        /**
         * Get Event
         */
        public function get_event() {

            return $this->hrw_event ;
        }

        /**
         * Get Credit Amount
         */
        public function get_credit_amount() {

            return $this->hrw_amount_credited ;
        }

        /**
         * Get Debit Amount
         */
        public function get_debit_amount() {

            return $this->hrw_amount_debited ;
        }

        /**
         * Get date
         */
        public function get_date() {

            return $this->hrw_date ;
        }

    }

}
    