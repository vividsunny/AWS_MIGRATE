<?php

/**
 * Abstract Notifications Class
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Notifications' ) ) {

	/**
	 * HRR_Notifications Class.
	 */
	class HRR_Notifications {

		/**
		 * ID
		 */
		protected $id ;

		/**
		 * Subject.
		 */
		protected $subject = '' ;

		/**
		 * Message.
		 */
		protected $message = '' ;

		/**
		 * Template HTML.
		 */
		protected $template_html ;

		/**
		 * Data.
		 */
		protected $data = array() ;

		/**
		 * Placeholder.
		 */
		protected $placeholders = array() ;

		/**
		 * Plugin slug.
		 */
		protected $plugin_slug = 'hrr' ;

		/**
		 * Class Constructor.
		 */
		public function __construct() {
			$this->enabled = $this->get_enabled() ;

			if ( empty( $this->placeholders ) ) {
				$this->placeholders = array(
					'{hrr-refund.sitename}' => $this->get_blogname() ,
						) ;
			}
		}

		/**
		 * Get id.
		 */
		public function get_id() {
			return $this->id ;
		}

		/**
		 * Get Enabled.
		 */
		public function get_enabled() {

			return 'no' ;
		}

		/*
		 * Is enabled?
		 */

		public function is_enabled() {

			return $this->is_plugin_enabled() && 'yes' === $this->enabled ;
		}

		/*
		 * Is plugin enabled?
		 */

		public function is_plugin_enabled() {

			return true ;
		}

		/**
		 * Default Subject.
		 */
		public function get_default_subject() {

			return '' ;
		}

		/**
		 * Default Message.
		 */
		public function get_default_message() {

			return '' ;
		}

		/**
		 * Get subject.
		 */
		public function get_subject() {

			return $this->get_default_subject() ;
		}

		/**
		 * Get Message.
		 */
		public function get_message() {

			return $this->get_default_message() ;
		}

		/**
		 * Get formatted Subject.
		 */
		public function get_formatted_subject() {
			$subject = $this->format_string( $this->get_subject() ) ;
			$subject = wp_specialchars_decode( html_entity_decode( $subject ) ) ;

			return $subject ;
		}

		/**
		 * Get formatted Message.
		 */
		public function get_formatted_message() {
			$message = $this->format_string( $this->get_message() ) ;
			$message = wpautop( $message ) ;

			$email_type = get_option( 'hrr_refund_email_type' , 'woo' ) ;
			if ( 'woo' == $email_type ) {
				ob_start() ;
				wc_get_template( 'emails/email-header.php' , array( 'email_heading' => $this->get_formatted_subject() ) ) ;
				echo $message ;
				wc_get_template( 'emails/email-footer.php' ) ;
				$message = ob_get_clean() ;
			}

			return $message ;
		}

		/**
		 * Get email headers.
		 */
		public function get_headers() {

			return 'Content-Type: ' . $this->get_content_type() . "\r\n" ;
		}

		/**
		 * Get attachments.
		 */
		public function get_attachments() {

			return array() ;
		}

		/**
		 * Get content type.
		 */
		public function get_content_type() {

			return 'text/html' ;
		}

		/**
		 * Get WordPress blog name.
		 */
		public function get_blogname() {
			return wp_specialchars_decode( get_option( 'blogname' ) , ENT_QUOTES ) ;
		}

		/**
		 * Get valid recipients.
		 */
		public function get_recipient() {
			$recipients = array_map( 'trim' , explode( ',' , $this->recipient ) ) ;
			$recipients = array_filter( $recipients , 'is_email' ) ;

			return implode( ', ' , $recipients ) ;
		}

		/**
		 * Format String.
		 */
		public function format_string( $string ) {
			$find    = array_keys( $this->placeholders ) ;
			$replace = array_values( $this->placeholders ) ;

			return str_replace( $find , $replace , $string ) ;
		}

		/**
		 * Custom CSS.
		 */
		public function custom_css() {
			return '' ;
		}

		/**
		 * Send an email.
		 */
		public function send_email( $to, $subject, $message, $headers = false, $attachments = array() ) {
			if ( ! $headers ) {
				$headers = $this->get_headers() ;
			}

			add_filter( 'wp_mail_from' , array( $this , 'get_from_address' ) , 12 ) ;
			add_filter( 'wp_mail_from_name' , array( $this , 'get_from_name' ) , 12 ) ;
			add_filter( 'wp_mail_content_type' , array( $this , 'get_content_type' ) , 12 ) ;

			$email_type = get_option( 'hrr_refund_email_type' , 'woo' ) ;
			if ( 'woo' == $email_type ) {
				$mailer = WC()->mailer() ;
				$return = $mailer->send( $to , $subject , $message , $headers , $attachments ) ;
			} else {
				$return = wp_mail( $to , $subject , $message , $headers , $attachments ) ;
			}

			remove_filter( 'wp_mail_from' , array( $this , 'get_from_address' ) ) ;
			remove_filter( 'wp_mail_from_name' , array( $this , 'get_from_name' ) ) ;
			remove_filter( 'wp_mail_content_type' , array( $this , 'get_content_type' ) ) ;

			return $return ;
		}

		/**
		 * Get the from name.
		 */
		public function get_from_name() {

			$from_name = ( '' != get_option( 'hrr_refund_from_name' ) ) ? get_option( 'hrr_refund_from_name' ) : get_option( 'blogname' ) ;

			return wp_specialchars_decode( esc_html( $from_name ) , ENT_QUOTES ) ;
		}

		/**
		 * Get the from address.
		 */
		public function get_from_address() {

			$from_address = ( '' != get_option( 'hrr_refund_from_email' ) ) ? get_option( 'hrr_refund_from_email' ) : get_option( 'woocommerce_email_from_address' ) ;

			return sanitize_email( $from_address ) ;
		}

	}

}
