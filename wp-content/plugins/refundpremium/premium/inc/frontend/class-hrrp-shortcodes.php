<?php

/**
 * Shortcodes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

if ( ! class_exists( 'HRRP_Shortcodes' ) ) {

	/**
	 * Class.
	 */
	class HRRP_Shortcodes {

		/**
		 * Class initialization.
		 */
		public static function init() {
						//Replace Shortcodes in Refund Request Table.
			add_shortcode( 'hrr-refund-requests' , array( __CLASS__ , 'shortcode_for_request_table' ) ) ;
		}

		/**
		 * Display Refund Request Table in Shortcode.
		 */
		public static function shortcode_for_request_table( $atts ) {
			if ( ! is_user_logged_in() ) {
				return ;
			}

			$args = array(
				'posts_per_page' => -1 ,
				'post_type'      => 'hrr_request' ,
				'post_status'    => array( 'hrr-new' , 'hrr-accept' , 'hrr-reject' ) ,
				'order'          => 'DESC' ,
				'fields'         => 'ids'
					) ;

			$request_data = get_posts( $args ) ;

			ob_start() ;
			//Display refund request table
			hrr_get_template( 'myaccount/refund-request-table.php' , array( 'request_data' => $request_data ) ) ;
						
						$content = ob_get_contents();
						ob_end_clean() ;

			return '<div class="hrr_refund_request_shortcode">' . $content . '</div>' ;
		}

	}

	HRRP_Shortcodes::init() ;
}
