<?php

/**
 * Refund Request.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Request' ) ) {

	/**
	 * HRR_Request Class.
	 */
	class HRR_Request extends HRR_Post {

		/**
		 * Post Type.
		 */
		protected $post_type = HRR_Register_Post_Type::REQUEST_POSTTYPE ;

		/**
		 * Post Status.
		 */
		protected $post_status = 'hrr-new' ;

		/**
		 * Order ID.
		 */
		protected $hrr_order_id ;

		/**
		 * User ID.
		 */
		protected $hrr_user_id ;

		/**
		 * Reason.
		 */
		protected $hrr_reason ;

		/**
		 * User.
		 */
		protected $user ;

		/**
		 * Refund Mode.
		 */
		protected $hrr_mode ;

		/**
		 * Request Type.
		 */
		protected $hrr_type ;

		/**
		 * Refund Total.
		 */
		protected $hrr_total ;

		/**
		 * Line Item.
		 */
		protected $hrr_line_item ;

		/**
		 * Line Item Ids.
		 */
		protected $hrr_line_item_ids ;

		/**
		 * Currency.
		 */
		protected $hrr_currency ;

		/**
		 * Old Status.
		 */
		protected $hrr_old_status ;

		/**
		 * Attachments.
		 */
		protected $hrr_attachments ;

		/**
		 * Created Date.
		 */
		protected $created_date ;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'hrr_order_id'      => '' ,
			'hrr_user_id'       => '' ,
			'hrr_mode'          => '' ,
			'hrr_type'          => '' ,
			'hrr_total'         => '' ,
			'hrr_line_item'     => '' ,
			'hrr_line_item_ids' => '' ,
			'hrr_old_status'    => '' ,
			'hrr_currency'      => '' ,
			'hrr_attachments'   => ''
				) ;

		/**
		 * Prepare extra post data
		 */
		protected function load_extra_postdata() {
			$this->hrr_order_id = $this->post->post_parent ;
			$this->created_date = $this->post->post_date_gmt ;
			$this->hrr_reason   = $this->post->post_title . '-' . $this->post->post_content ;
		}

		/**
		 * Set Id.
		 */
		public function set_id( $value ) {

			$this->id = $value ;
		}

		/**
		 * Set User Id.
		 */
		public function set_user_id( $value ) {

			$this->hrr_user_id = $value ;
		}

		/**
		 * Set Order Id.
		 */
		public function set_order_id( $value ) {

			$this->hrr_order_id = $value ;
		}

		/**
		 * Set Refund Mode.
		 */
		public function set_mode( $value ) {

			$this->hrr_mode = $value ;
		}

		/**
		 * Set Request Type.
		 */
		public function set_type( $value ) {

			$this->hrr_type = $value ;
		}

		/**
		 * Set Refund Total.
		 */
		public function set_total( $value ) {

			$this->hrr_total = $value ;
		}

		/**
		 * Set Line Item.
		 */
		public function set_line_item( $value ) {

			$this->hrr_line_item = $value ;
		}

		/**
		 * Set Line Item Ids.
		 */
		public function set_line_item_ids( $value ) {

			$this->hrr_line_item_ids = $value ;
		}

		/**
		 * Set Old Status.
		 */
		public function set_old_status( $value ) {

			$this->hrr_old_status = $value ;
		}

		/**
		 * Set currency.
		 */
		public function set_currency( $value ) {

			$this->hrr_currency = $value ;
		}

		/**
		 * Get Id.
		 */
		public function get_id() {

			return $this->id ;
		}

		/**
		 * Get User Id.
		 */
		public function get_user_id() {
			return $this->hrr_user_id ;
		}

		/**
		 * Get User.
		 */
		public function get_user() {

			if ( $this->user ) {
				return $this->user ;
			}

			$this->user = get_userdata( $this->get_user_id() ) ;

			return $this->user ;
		}

		/**
		 * Get Formatted created datetime.
		 */
		public function get_formatted_created_date( $format = 'date' ) {

			return HRR_Date_Time::get_wp_format_datetime( $this->get_created_date() , $format ) ;
		}

		/**
		 * Has Email Subscribed User.
		 */
		public function has_email_subscribed_user() {

			return get_user_meta( $this->get_user_id() , 'hr_refund_unsubscribed_id' , true ) !== 'yes' ;
		}

		/**
		 * Get Created Date.
		 */
		public function get_created_date() {
			return $this->created_date ;
		}

		/**
		 * Get Order Id.
		 */
		public function get_order_id() {
			return $this->hrr_order_id ;
		}

		/**
		 * Get Refund Mode.
		 */
		public function get_mode() {

			return $this->hrr_mode ;
		}

		/**
		 * Get Request Type.
		 */
		public function get_type() {

			return $this->hrr_type ;
		}

		/**
		 * Get Refund Total.
		 */
		public function get_total() {

			return $this->hrr_total ;
		}

		/**
		 * Get Line Item.
		 */
		public function get_line_item() {

			return $this->hrr_line_item ;
		}

		/**
		 * Get Line Item Ids.
		 */
		public function get_line_item_ids() {

			return $this->hrr_line_item_ids ;
		}

		/**
		 * Get Old Status.
		 */
		public function get_old_status() {

			return $this->hrr_old_status ;
		}

		/**
		 * Get currency.
		 */
		public function get_currency() {

			return $this->hrr_currency ;
		}

		/**
		 * Get Reason.
		 */
		public function get_reason() {

			return $this->hrr_reason ;
		}

		/**
		 * Get Attachments.
		 */
		public function get_attachment() {

			return $this->hrr_attachments ;
		}

	}

}
	
