<?php

/*
 * User Wallet
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_User_Wallet' ) ) {

    /**
     * HRW_User_Wallet Class.
     */
    class HRW_User_Wallet extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRW_Register_Post_Types::WALLET_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'hrw_active' ;

        /**
         * User ID
         */
        protected $hrw_user_id ;

        /**
         * Available Balance
         */
        protected $hrw_available_balance ;

        /**
         * Expired Date
         */
        protected $hrw_expired_date ;

        /**
         * Last Expired Date
         */
        protected $hrw_last_expired_date ;

        /**
         * Total Balance
         */
        protected $hrw_total_balance ;

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
         * Schedule block type
         */
        protected $hrw_schedule_block_type ;

        /**
         * Schedule block from date
         */
        protected $hrw_schedule_block_from_date ;

        /**
         * Schedule block to date
         */
        protected $hrw_schedule_block_to_date ;

        /**
         * Schedule block reason
         */
        protected $hrw_schedule_block_reason ;

        /**
         * Schedule block status
         */
        protected $hrw_schedule_block_status ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'hrw_available_balance'        => '' ,
            'hrw_expired_date'             => '' ,
            'hrw_total_balance'            => '' ,
            'hrw_last_expired_date'        => '' ,
            'hrw_currency'                 => '' ,
            'hrw_date'                     => '' ,
            'hrw_schedule_block_type'      => '' ,
            'hrw_schedule_block_from_date' => '' ,
            'hrw_schedule_block_to_date'   => '' ,
            'hrw_schedule_block_reason'    => '' ,
            'hrw_schedule_block_status'    => 'no' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->user_id = $this->post->post_parent ;
        }

        /**
         * Get Formatted created datetime
         */
        public function get_formatted_created_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_date() ) ;
        }

        /**
         * Get Formatted Expired datetime
         */
        public function get_formatted_expired_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_expiry_date() ) ;
        }

        /**
         * Get Formatted Block From Date
         */
        public function get_formatted_block_from_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_schedule_block_from_date() , 'date' ) ;
        }

        /**
         * Get Formatted Expired Block To Date
         */
        public function get_formatted_block_to_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_schedule_block_to_date() , 'date' ) ;
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
         * Setters and Getters
         */

        /**
         * Set User Id
         */
        public function set_user_id( $value ) {

            return $this->user_id = $value ;
        }

        /**
         * Set Available Balance
         */
        public function set_available_balance( $value ) {

            return $this->hrw_available_balance = $value ;
        }

        /**
         * Set Total balance
         */
        public function set_total_balance( $value ) {

            return $this->hrw_total_balance = $value ;
        }

        /**
         * Set expired date
         */
        public function set_expiry_date( $value ) {

            return $this->hrw_expired_date = $value ;
        }

        /**
         * Set last expired date
         */
        public function set_last_expired_date( $value ) {

            return $this->hrw_last_expired_date = $value ;
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
         * Set schedule block type
         */
        public function set_schedule_block_type( $value ) {

            return $this->hrw_schedule_block_type = $value ;
        }

        /**
         * Set schedule block from date
         */
        public function set_schedule_block_from_date( $value ) {

            return $this->hrw_schedule_block_from_date = $value ;
        }

        /**
         * Set schedule block to date
         */
        public function set_schedule_block_to_date( $value ) {

            return $this->hrw_schedule_block_to_date = $value ;
        }

        /**
         * Set schedule block reason
         */
        public function set_schedule_block_reason( $value ) {

            return $this->hrw_schedule_block_reason = $value ;
        }

        /**
         * Set schedule block status
         */
        public function set_schedule_block_status( $value ) {

            return $this->hrw_schedule_block_status = $value ;
        }

        /**
         * Get User Id
         */
        public function get_formatted_status() {

            return hrw_get_status_label( $this->get_status() ) ;
        }

        /**
         * Get User Id
         */
        public function get_user_id() {

            return $this->user_id ;
        }

        /**
         * Get Available Balance
         */
        public function get_available_balance() {

            return $this->hrw_available_balance ;
        }

        /**
         * Get Total balance
         */
        public function get_total_balance() {

            return $this->hrw_total_balance ;
        }

        /**
         * Get Expiry Date
         */
        public function get_expiry_date() {

            return $this->hrw_expired_date ;
        }

        /**
         * Get last expired date
         */
        public function get_last_expired_date() {

            return $this->hrw_last_expired_date ;
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

        /**
         * Get schedule block type
         */
        public function get_schedule_block_type() {

            return $this->hrw_schedule_block_type ;
        }

        /**
         * Get schedule block from date
         */
        public function get_schedule_block_from_date() {

            return $this->hrw_schedule_block_from_date ;
        }

        /**
         * Get schedule block to date
         */
        public function get_schedule_block_to_date() {

            return $this->hrw_schedule_block_to_date ;
        }

        /**
         * Get schedule block reason
         */
        public function get_schedule_block_reason() {

            return $this->hrw_schedule_block_reason ;
        }

        /**
         * Get schedule block status
         */
        public function get_schedule_block_status() {

            return $this->hrw_schedule_block_status ;
        }

    }

}
    