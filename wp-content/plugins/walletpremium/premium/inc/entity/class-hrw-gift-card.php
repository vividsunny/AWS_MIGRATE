<?php

/*
 * Gift Card
 */
if ( ! defined ( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists ( 'HRW_Gift_Card' ) ) {

    /**
     * HRW_Gift_Card Class.
     */
    class HRW_Gift_Card extends HRW_Post {

        /**
         * Post Type
         */
        protected $post_type = HRWP_Register_Post_Types::GIFT_CARD_POSTTYPE ;

        /**
         * Post Status
         */
        protected $post_status = 'hrw_created' ;

        /**
         * User
         */
        protected $user ;

        /**
         * Phone
         */
        protected $phone ;

        /**
         * Gift Amount
         */
        protected $amount ;

        /**
         * Gift Code
         */
        protected $hrw_gift_code ;

        /**
         * Gift Sent By
         */
        protected $hrw_sender_id ;

        /**
         * Gift Received By
         */
        protected $hrw_receiver_id ;

        /**
         * Gift Received Name
         */
        protected $hrw_receiver_name ;

        /**
         * Gift Received Name
         */
        protected $hrw_gift_reason ;

        /**
         * Gift Created Order Id
         */
        protected $hrw_order_id ;

        /**
         * Gift Created Date
         */
        protected $hrw_created_date ;

        /**
         * Gift Redeemed Date
         */
        protected $hrw_redeemed_date ;

        /**
         * Gift Expired Date
         */
        protected $hrw_expiry_date ;

        /**
         * Gift Gift Attachment
         */
        protected $hrw_gift_attachment ;

        /**
         * Gift Gift Attachment
         */
        protected $hrw_expiry_remainder_bool ;

        /**
         * Meta data keys
         */
        protected $meta_data_keys = array (
            'hrw_gift_code'             => '' ,
            'hrw_amount'                => '' ,
            'hrw_sender_id'             => '' ,
            'hrw_receiver_id'           => '' , 
            'hrw_receiver_name'         => '' ,
            'hrw_gift_reason'           => '' ,
            'hrw_order_id'              => '' ,
            'hrw_created_date'          => '' ,
            'hrw_redeemed_date'         => '' ,
            'hrw_expiry_date'           => '' ,
            'hrw_gift_attachment'       => '' ,
            'hrw_expiry_remainder_bool' => '' ,
                ) ;

        /**
         * Get User
         */
        public function get_user() {

            if ( $this->user )
                return $this->user ;

            $this->user = get_userdata ( $this->get_sender_id () ) ;

            return $this->user ;
        }

        /**
         * Get User
         */
        public function get_user_display() {
            return $this->get_user ()->display_name . '(' . $this->get_user ()->user_email . ')' ;
        }

        /**
         * Get Phone
         */
        public function get_phone() {

            if ( isset ( $this->phone ) )
                return $this->phone ;

            $this->phone = get_user_meta ( $this->get_sender_id () , 'hrw_phone_number' , true ) ;

            return $this->phone ;
        }

        /**
         * Get Gift Code
         */
        public function get_gift_code() {

            return $this->hrw_gift_code ;
        }

        /**
         * Get Gift Amount
         */
        public function get_amount() {

            return $this->hrw_amount ;
        }

        /**
         * Get Gift Sender
         */
        public function get_sender_id() {

            return $this->hrw_sender_id ;
        }

        /**
         * Get Gift Receiver 
         */
        public function get_receiver_id() {

            return $this->hrw_receiver_id ;
        }

        /**
         * Get Gift Receiver Name 
         */
        public function get_receiver_name() {

            return $this->hrw_receiver_name ;
        }

        /**
         * Get Gift Receiver Reason 
         */
        public function get_gift_reason() {

            return $this->hrw_gift_reason ;
        }

        public function get_receiver_display() {

            return $this->hrw_receiver_name . '(' . $this->hrw_receiver_id . ')' ;
        }

        /**
         * Get Gift Created Date
         */
        public function get_created_date() {

            return $this->hrw_created_date ;
        }

        /**
         * Get Formatted Created datetime
         */
        public function get_formatted_created_date() {

            if ( empty ( $this->get_created_date () ) ) {
                return '-' ;
            }

            return HRW_Date_Time::get_date_object_format_datetime ( $this->get_created_date () ) ;
        }

        /**
         * Get Gift Redeemed Date
         */
        public function get_redeemed_date() {

            return $this->hrw_redeemed_date ;
        }

        /**
         * Get Formatted Created datetime
         */
        public function get_formatted_redeemed_date() {

            if ( empty ( $this->get_redeemed_date () ) ) {
                return '-' ;
            }

            return HRW_Date_Time::get_date_object_format_datetime ( $this->get_redeemed_date () ) ;
        }

        /**
         * Get Gift Expired Date
         */
        public function get_expiry_date() {

            return $this->hrw_expiry_date ;
        }

        /**
         * Get Formatted Expired datetime
         */
        public function get_formatted_expired_date( $display_type = '' ) {

            if ( empty ( $this->get_expiry_date () ) ) {
                return '-' ;
            }


            if ( $display_type == 'listing' && $this->get_Status () == 'hrw_redeemed' ) {
                return '-' ;
            }

            return HRW_Date_Time::get_date_object_format_datetime ( $this->get_expiry_date () ) ;
        }

        /**
         * Get Gift Order Id
         */
        public function get_order_id() {

            return $this->hrw_order_id ;
        }

        /**
         * Get Gift Attachment
         */
        public function get_gift_attachment() {

            return $this->hrw_gift_attachment ;
        }

        /**
         * Get Gift Expiry Remainder bool
         */
        public function get_expiry_remainder_bool() {

            return $this->hrw_expiry_remainder_bool ;
        }

        /**
         * Setters and Getters
         */

        /**
         * Set Gift Code
         */
        public function set_gift_code( $value ) {

            return $this->hrw_gift_code = $value ;
        }

        /**
         * Set Gift Amount
         */
        public function set_amount( $value ) {

            return $this->hrw_amount = $value ;
        }

        /**
         * Set Gift Sender
         */
        public function set_sender_id( $value ) {

            return $this->hrw_sender_id = $value ;
        }

        /**
         * Set Gift Receiver 
         */
        public function set_receiver_id( $value ) {

            return $this->hrw_receiver_id = $value ;
        }

        /**
         * Set Gift Receiver Name 
         */
        public function set_receiver_name( $value ) {

            return $this->hrw_receiver_name = $value ;
        }

        /**
         * Set Gift Receiver Reason 
         */
        public function set_gift_reason( $value ) {

            return $this->hrw_gift_reason = $value ;
        }

        /**
         * Set Gift Order id 
         */
        public function set_order_id( $value ) {

            return $this->hrw_order_id = $value ;
        }

        /**
         * Set Gift Created Date
         */
        public function set_created_date( $value ) {

            return $this->hrw_created_date = $value ;
        }

        /**
         * Set Gift Redeemed Date
         */
        public function set_redeemed_date( $value ) {

            return $this->hrw_redeemed_date = $value ;
        }

        /**
         * Set Gift Expired Date
         */
        public function set_expiry_date( $value ) {

            return $this->hrw_expiry_date = $value ;
        }

        /**
         * Set Gift Attachment
         */
        public function set_gift_attachment( $value ) {

            return $this->hrw_gift_attachment = $value ;
        }

        /**
         * Set Gift Expiry Remainder bool
         */
        public function set_expiry_remainder_bool( $value ) {

            return $this->hrw_expiry_remainder_bool = $value ;
        }

    }

}
    