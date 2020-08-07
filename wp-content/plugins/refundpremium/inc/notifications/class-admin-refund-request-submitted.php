<?php

/**
 * Admin- Refund Request Submitted
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Admin_Refund_Request_Submitted_Notification' ) ) {

	/**
	 * Class HRR_Admin_Refund_Request_Submitted_Notification
	 */
	class HRR_Admin_Refund_Request_Submitted_Notification extends HRR_Notifications {

		/**
		 * Class Constructor
		 */
		public function __construct() {

			$this->id      = 'admin_refund_request_submitted' ;
			$this->section = 'general' ;
			$this->title   = esc_html__( 'Admin - Refund Request Submitted' , 'refund' ) ;

			// Triggers for this email.
			add_action( sanitize_key( $this->plugin_slug . '_refund_request_created' ) , array( $this , 'trigger' ) , 10 , 1 ) ;

			parent::__construct() ;
		}

		/*
		 * Default Subject
		 */

		public function get_default_subject() {

			return 'New Refund Request on {hrr-refund.sitename}' ;
		}

		/*
		 * Default Message
		 */

		public function get_default_message() {

			return 'Customer {hrr-refund.customername} has submitted a Refund Request {hrr-refund.requestid} for Order {hrr-refund.orderid} on {hrr-refund.sitename} at {hrr-refund.date} {hrr-refund.time}' ;
		}

		/**
		 * Get subject.
		 */
		public function get_subject() {

			return get_option( 'hrr_refund_new_request_subject_for_admin_notification' , $this->get_default_subject() ) ;
		}

		/**
		 * Get Message.
		 */
		public function get_message() {

			return get_option( 'hrr_refund_new_request_msg_for_admin_notification' , $this->get_default_message() ) ;
		}

		/**
		 * Get Enabled.
		 */
		public function get_enabled() {

			return get_option( 'hrr_refund_new_request_admin_notification' , 'no' ) ;
		}

		/**
		 * Trigger the sending of this email.
		 */
		public function trigger( $request_id, $request_object = false ) {

			if ( $request_id && ! is_a( $request_object , 'HRR_Request' ) ) {
				$request_object = hrr_get_request( $request_id ) ;
			}

			if ( is_object( $request_object ) ) {
				$this->recipient                                   = $this->get_from_address() ;
				$this->placeholders[ '{hrr-refund.customername}' ] = $request_object->get_user()->user_login ;
				$this->placeholders[ '{hrr-refund.requestid}' ]    = '#' . $request_object->get_id() ;
				$this->placeholders[ '{hrr-refund.orderid}' ]      = '#' . $request_object->get_order_id() ;
				$this->placeholders[ '{hrr-refund.date}' ]         = $request_object->get_formatted_created_date() ;
				$this->placeholders[ '{hrr-refund.time}' ]         = $request_object->get_formatted_created_date( 'time' ) ;
			}

			if ( $this->is_enabled() && $this->get_recipient() ) {
				$this->send_email( $this->get_recipient() , $this->get_formatted_subject() , $this->get_formatted_message() , $this->get_headers() , $this->get_attachments() ) ;
			}
		}

	}

}
