<?php

/**
 * Admin Custom Post Status.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Register_Post_Status' ) ) {

	/**
	 * Class.
	 */
	class HRR_Register_Post_Status {

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action( 'init' , array( __CLASS__ , 'register_custom_post_status' ) ) ;
		}

		/**
		 * Register All Custom Post Status.
		 */
		public static function register_custom_post_status() {
			$custom_post_statuses = array(
				'hrr-new'        => array( 'HRR_Register_Post_Status' , 'new_post_status_args' ) ,
				'hrr-accept'     => array( 'HRR_Register_Post_Status' , 'accept_post_status_args' ) ,
				'hrr-reject'     => array( 'HRR_Register_Post_Status' , 'reject_post_status_args' ) ,
				'hrr-processing' => array( 'HRR_Register_Post_Status' , 'processing_post_status_args' ) ,
				'hrr-on-hold'    => array( 'HRR_Register_Post_Status' , 'on_hold_post_status_args' ) ,
				'hrr-replied'    => array( 'HRR_Register_Post_Status' , 'replied_post_status_args' ) ,
					) ;

			$custom_post_statuses = apply_filters( 'hrr_add_custom_post_status' , $custom_post_statuses ) ;

			if ( ! hrr_check_is_array( $custom_post_statuses ) ) {
				return ;
			}

			foreach ( $custom_post_statuses as $post_status => $args_function ) {
				$args = call_user_func_array( $args_function , array() ) ;

				// Register post status.
				register_post_status( $post_status , $args ) ;
			}
		}

		/**
		 * New Custom Post Status arguments.
		 */
		public static function new_post_status_args() {
			return apply_filters( 'hrr_new_post_status_args' , array(
				'label'                     => esc_html__( 'New' , 'refund' ) ,
				'public'                    => true ,
				'exclude_from_search'       => true ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
								/* translators: %s: number of request */
				'label_count'               => _n_noop( 'New <span class="count">(%s)</span>' , 'New <span class="count">(%s)</span>' ) ,
					)
					) ;
		}

		/**
		 * Accept Custom Post Status arguments.
		 */
		public static function accept_post_status_args() {
			return apply_filters( 'hrr_accept_post_status_args' , array(
				'label'                     => esc_html__( 'Accepted' , 'refund' ) ,
				'public'                    => true ,
				'exclude_from_search'       => true ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
								/* translators: %s: number of request */
				'label_count'               => _n_noop( 'Accept <span class="count">(%s)</span>' , 'Accept <span class="count">(%s)</span>' ) ,
					)
					) ;
		}

		/**
		 * Reject Custom Post Status arguments.
		 */
		public static function reject_post_status_args() {
			return apply_filters( 'hrr_reject_post_status_args' , array(
				'label'                     => esc_html__( 'Rejected' , 'refund' ) ,
				'public'                    => true ,
				'exclude_from_search'       => true ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
								/* translators: %s: number of request */
				'label_count'               => _n_noop( 'Reject <span class="count">(%s)</span>' , 'Reject <span class="count">(%s)</span>' ) ,
					)
					) ;
		}

		/**
		 * Processing Custom Post Status arguments.
		 */
		public static function processing_post_status_args() {
			return apply_filters( 'hrr_processing_post_status_args' , array(
				'label'                     => esc_html__( 'Processing' , 'refund' ) ,
				'public'                    => true ,
				'exclude_from_search'       => true ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
								/* translators: %s: number of request */
				'label_count'               => _n_noop( 'Processing <span class="count">(%s)</span>' , 'Processing <span class="count">(%s)</span>' ) ,
					)
					) ;
		}

		/**
		 * On Hold Custom Post Status arguments.
		 */
		public static function on_hold_post_status_args() {
			return apply_filters( 'hrr_on-hold_post_status_args' , array(
				'label'                     => esc_html__( 'On Hold' , 'refund' ) ,
				'public'                    => true ,
				'exclude_from_search'       => true ,
				'show_in_admin_all_list'    => true ,
				'show_in_admin_status_list' => true ,
								/* translators: %s: number of request */
				'label_count'               => _n_noop( 'On Hold <span class="count">(%s)</span>' , 'On Hold <span class="count">(%s)</span>' ) ,
					)
					) ;
		}

		/**
		 * Replied Custom Post Status arguments.
		 */
		public static function replied_post_status_args() {
			return apply_filters( 'hrr_replied_post_status_args' , array(
				'label'                     => esc_html__( 'Replied' , 'refund' ) ,
				'public'                    => false ,
				'exclude_from_search'       => true ,
				'show_in_admin_all_list'    => false ,
				'show_in_admin_status_list' => false ,
								/* translators: %s: number of request */
				'label_count'               => _n_noop( 'Replied <span class="count">(%s)</span>' , 'Replied <span class="count">(%s)</span>' ) ,
					)
					) ;
		}

	}

	HRR_Register_Post_Status::init() ;
}
