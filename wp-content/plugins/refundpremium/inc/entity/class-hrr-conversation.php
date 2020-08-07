<?php

/**
 * Conversations.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Conversation' ) ) {

	/**
	 * HRR_Conversation Class.
	 */
	class HRR_Conversation extends HRR_Post {

		/**
		 * Post Type.
		 */
		protected $post_type = HRR_Register_Post_Type::CONVERSATION_POSTTYPE ;

		/**
		 * Attachments.
		 */
		protected $hrr_attachments ;

		/**
		 * Post Status.
		 */
		protected $post_status = 'hrr-replied' ;

		/**
		 * Refund Request.
		 */
		protected $refund_request ;

		/**
		 * Created Date.
		 */
		protected $created_date ;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'hrr_attachments' => ''
				) ;

		/**
		 * Prepare extra post data
		 */
		protected function load_extra_postdata() {
			$this->hrr_request_id = $this->post->post_parent ;
			$this->hrr_message    = $this->post->post_content ;
			$this->hrr_user_id    = $this->post->post_author ;
			$this->created_date   = $this->post->post_date_gmt ;
		}

		/**
		 * Set Id.
		 */
		public function set_id( $value ) {

			$this->id = $value ;
		}

		/**
		 * Get User.
		 */
		public function get_refund_request() {

			if ( $this->refund_request ) {
				return $this->refund_request ;
			}

			$this->refund_request = hrr_get_request( $this->get_request_id() ) ;

			return $this->refund_request ;
		}

		/**
		 * Get Formatted created datetime
		 */
		public function get_formatted_created_date( $format = 'date' ) {

			return HRR_Date_Time::get_wp_format_datetime( $this->get_created_date() , $format ) ;
		}

		/**
		 * Get Created Date.
		 */
		public function get_created_date() {

			return $this->created_date ;
		}

		/**
		 * Get Id.
		 */
		public function get_id() {

			return $this->id ;
		}

		/**
		 * Get Request Id.
		 */
		public function get_request_id() {

			return $this->hrr_request_id ;
		}

		/**
		 * Get Message.
		 */
		public function get_message() {

			return $this->hrr_message ;
		}

		/**
		 * Get User Id.
		 */
		public function get_user_id() {

			return $this->hrr_user_id ;
		}

		/**
		 * Get Attachments.
		 */
		public function get_attachment() {

			return $this->hrr_attachments ;
		}

	}

}
	
