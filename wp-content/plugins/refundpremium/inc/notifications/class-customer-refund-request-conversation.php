<?php

/**
 * Customer- Refund Request Conversation
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Customer_Refund_Request_Conversation_Notification' ) ) {

	/**
	 * Class HRR_Customer_Refund_Request_Conversation_Notification
	 */
	class HRR_Customer_Refund_Request_Conversation_Notification extends HRR_Notifications {

		/**
		 * Class Constructor
		 */
		public function __construct() {

			$this->id      = 'customer_refund_request_conversation' ;
			$this->section = 'general' ;
			$this->title   = esc_html__( 'Customer - Refund Request Conversation' , 'refund' ) ;

			// Triggers for this email.
			add_action( sanitize_key( $this->plugin_slug . '_conversation_created' ) , array( $this , 'trigger' ) , 10 , 1 ) ;

			parent::__construct() ;
		}

		/*
		 * is plugin enabled
		 */

		public function is_plugin_enabled() {

			return !hrr_is_premium() ;
		}

		/*
		 * Default Subject
		 */

		public function get_default_subject() {

			return 'Reply regarding with Refund Request on {hrr-refund.sitename}' ;
		}

		/*
		 * Default Message
		 */

		public function get_default_message() {

			return 'You have got a reply from site Admin on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time} regarding with your Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid}' ;
		}

		/**
		 * Get subject.
		 */
		public function get_subject() {

			return get_option( 'hrr_refund_refund_conversation_subject_for_user_notification' , $this->get_default_subject() ) ;
		}

		/**
		 * Get Message.
		 */
		public function get_message() {

			return get_option( 'hrr_refund_refund_conversation_msg_for_user_notification' , $this->get_default_message() ) ;
		}

		/**
		 * Get Enabled.
		 */
		public function get_enabled() {

			return get_option( 'hrr_refund_refund_conversation_user_notification' , 'no' ) ;
		}

		/**
		 * Trigger the sending of this email.
		 */
		public function trigger( $conversation_id, $conversation_object = false ) {

			if ( $conversation_id && ! is_a( $conversation_object , 'HRR_Conversation' ) ) {
				$conversation_object = hrr_get_conversation( $conversation_id ) ;
			}

			if ( is_object( $conversation_object ) && $conversation_object->get_refund_request()->has_email_subscribed_user() ) {
				$this->recipient                                   = $conversation_object->get_refund_request()->get_user()->user_email ;
				$this->placeholders[ '{hrr-refund.customername}' ] = $conversation_object->get_refund_request()->get_user()->user_login ;
				$this->placeholders[ '{hrr-refund.requestid}' ]    = '#' . $conversation_object->get_refund_request()->get_id() ;
				$this->placeholders[ '{hrr-refund.orderid}' ]      = '#' . $conversation_object->get_refund_request()->get_order_id() ;
				$this->placeholders[ '{hrr-refund.date}' ]         = $conversation_object->get_formatted_created_date() ;
				$this->placeholders[ '{hrr-refund.time}' ]         = $conversation_object->get_formatted_created_date( 'time' ) ;
			}

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send_email( $this->get_recipient() , $this->get_formatted_subject() , $this->get_formatted_message() , $this->get_headers() , $this->get_attachments() ) ;
			}
		}

	}

}
