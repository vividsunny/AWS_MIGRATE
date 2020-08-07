<?php

/*
 * Fund Transfer
 */
if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_Fund_Transfer' ) ) {

    /**
     * HRW_Fund_Transfer Class.
     */
    class HRW_Fund_Transfer extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRWP_Register_Post_Types::FUND_TRANSFER_POSTTYPE ;

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
        protected $receiver_id ;

        /**
         * Total Transfered
         */
        protected $hrw_total_transfered ;

        /**
         * Total Received
         */
        protected $hrw_total_received ;

        /**
         * Total Requested
         */
        protected $hrw_total_requested ;

        /**
         * Currency
         */
        protected $hrw_currency ;

        /**
         * Date
         */
        protected $hrw_last_activity ;

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
         * Meta data keys
         */
        protected $meta_data_keys = array(
            'hrw_total_transfered' => '' ,
            'hrw_total_received'   => '' ,
            'hrw_total_requested'  => '' ,
            'hrw_currency'         => '' ,
            'hrw_last_activity'    => '' ,
            'hrw_date'             => '' ,
                ) ;

        /**
         * Prepare extra post data
         */
        protected function load_extra_postdata() {
            $this->sender_id   = $this->post->post_author ;
            $this->receiver_id = $this->post->post_parent ;
        }

        /**
         * Get Formatted date
         */
        public function get_formatted_date() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_date() ) ;
        }

        /**
         * Get Formatted last Activity
         */
        public function get_formatted_last_activity() {

            return HRW_Date_Time::get_date_object_format_datetime( $this->get_last_activity() ) ;
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
         * Setters and Getters
         */

        /**
         * Set Receiver ID
         */
        public function set_receiver_id( $value ) {

            return $this->receiver_id = $value ;
        }

        /**
         * Set Sender ID
         */
        public function set_sender_id( $value ) {

            return $this->sender_id = $value ;
        }

        /**
         * Set Total Transfered
         */
        public function set_total_transfered( $value ) {

            return $this->hrw_total_transfered = $value ;
        }

        /**
         * Set Total Received
         */
        public function set_total_received( $value ) {

            return $this->hrw_total_received = $value ;
        }

        /**
         * Set Total Requested
         */
        public function set_total_requested( $value ) {

            return $this->hrw_total_requested = $value ;
        }

        /**
         * Set currency
         */
        public function set_currency( $value ) {

            return $this->hrw_currency = $value ;
        }

        /**
         * Set Last Activity
         */
        public function set_last_activity( $value ) {

            return $this->hrw_last_activity = $value ;
        }

        /**
         * Set date
         */
        public function set_date( $value ) {

            return $this->hrw_date = $value ;
        }

        /**
         * Get Receiver ID
         */
        public function get_receiver_id() {

            return $this->receiver_id ;
        }

        /**
         * Get Sender ID
         */
        public function get_sender_id() {

            return $this->sender_id ;
        }

        /**
         * Get Total Transfered
         */
        public function get_total_transfered() {

            return $this->hrw_total_transfered ;
        }

        /**
         * Get Total Received
         */
        public function get_total_received() {

            return $this->hrw_total_received ;
        }

        /**
         * Get Total Requested
         */
        public function get_total_requested() {

            return $this->hrw_total_requested ;
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
         * Get Last Activity
         */
        public function get_last_activity() {

            return $this->hrw_last_activity ;
        }

    }

}
    