<?php

/**
 * Refund Request Background Process.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
if ( ! class_exists( 'HRR_Refund_Request_Background_Process' ) ) {

	/**
	 * Class.
	 */
	class HRR_Refund_Request_Background_Process extends WP_Background_Process {

		/**
		 * Limit.
		 */
		protected $limit = 1000 ;

		/**
		 * Assign action name.
		 * 
		 * @var string
		 */
		protected $action = 'hrr_refund_request_background_updater' ;

		/**
		 * Trigger.
		 */
		public function trigger() {
			if ( $this->is_process_running() ) {
				return ;
			}

			$posts = $this->get_posts() ;

			$this->handle_push_to_queue( $posts ) ;
		}

		/**
		 * Is process running.
		 *
		 * Check whether the current process is already running in a background process.
		 */
		public function is_process_running() {
			if ( get_site_transient( $this->identifier . '_process_lock' ) ) {
				//Process already running.
				return true ;
			}

			return false ;
		}

		/**
		 * Handle push to queue.
		 */
		protected function handle_push_to_queue( $posts, $offset = 0 ) {

			if ( hrr_check_is_array( $posts ) ) {
				foreach ( $posts as $post_id ) {
					$this->push_to_queue( $post_id ) ;
				}
			} else {
				$this->push_to_queue( 'no_old_data' ) ;
			}

			//Update offset.
			set_transient( 'hrr_refund_request_background_updater_offset' , $this->limit + $offset , 360 ) ;

			if ( 0 == $offset ) {
				hrr()->background_process()->update_progress_count( 5 ) ;
				HRR_WooCommerce_Log::log( 'Refund Request Upgrade Started' ) ;
			}

			$this->save()->dispatch() ;
		}

		/**
		 * Posts.
		 */
		protected function get_posts( $offset = 0 ) {
			$args = array(
				'post_type'      => 'hr_refund_request' ,
				'post_status'    => array( 'hr-refund-new', 'hr-refund-accept', 'hr-refund-reject', 'hr-refund-processing', 'hr-refund-on-hold', 'hr-request-replied' ) ,
				'posts_per_page' => $this->limit ,
				'offset'         => $offset ,
				'sort_order'     => 'ASC' ,
				'fields'         => 'ids'
					) ;

			return get_posts( $args ) ;
		}

		/**
		 * Task.
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param mixed $item Queue item to iterate over
		 *
		 * @return mixed
		 */
		protected function task( $request_id ) {
			if ( 'no_old_data' == $request_id) {
				return false ;
			}

			$post    = get_post( $request_id ) ;

			if ( 'hr-refund-new' == $post->post_status ) {
				$status = 'hrr-new' ;
			} elseif ( 'hr-refund-accept' == $post->post_status) {
				$status = 'hrr-accept' ;
			} elseif ( 'hr-refund-reject' == $post->post_status) {
				$status = 'hrr-reject' ;
			} elseif ( 'hr-refund-processing' == $post->post_status) {
				$status = 'hrr-processing' ;
			} elseif ( 'hr-refund-on-hold' == $post->post_status) {
				$status = 'hrr-on-hold' ;
			} elseif ( 'hr-request-replied' == $post->post_status) {
				$status = 'hrr-replied' ;
			}

			if ( 'hrr-replied' == $status ) {
				$post_args = array(
					'ID'          => $request_id ,
					'post_parent' => $request_id ,
					'post_status' => $status ,
					'post_type'   => HRR_Register_Post_Type::CONVERSATION_POSTTYPE
						) ;

				wp_update_post( $post_args ) ;

				$meta_args = array(
					'hrr_attachments'   => get_post_meta( $request_id, 'hr_conversation_attachment' , true ) ,
						) ;

				hrr_update_conversation( $request_id , $meta_args ) ;
			} else {
				
				$order_id = get_post_meta($request_id, 'hr_refund_order_id', true);
			
				$post_args = array(
					'ID'          => $request_id ,
					'post_parent' => absint( $order_id ) ,
					'post_status' => $status ,
					'post_type'   => HRR_Register_Post_Type::REQUEST_POSTTYPE
						) ;

				wp_update_post( $post_args ) ;

				$meta_args = array(
					'hrr_order_id'      => absint( $order_id ) ,
					'hrr_user_id'       => get_post_meta($request_id, 'hr_refund_user_details', true) ,
					'hrr_mode'          => get_post_meta($request_id, 'hr_refund_request_as', true) ,
					'hrr_type'          => get_post_meta($request_id, 'hr_refund_request_type', true) ,
					'hrr_total'         => get_post_meta($request_id, 'hr_refund_request_total', true) ,
					'hrr_line_item'     => get_post_meta($request_id, 'hr_refund_line_items', true) ,
					'hrr_line_item_ids' => get_post_meta($request_id, 'hr_refund_line_item_ids', true) ,
					'hrr_old_status'    => get_post_meta($request_id, 'hr_refund_request_old_status', true) ,
					'hrr_currency'      => get_post_meta($request_id, 'hr_refund_current_language', true) ,
					'hrr_attachments'   => get_post_meta($request_id, 'hr_conversation_attachment', true) ,
						) ;

				hrr_update_request( $request_id , $meta_args ) ;
			}

			return false ;
		}

		/**
		 * Complete
		 */
		protected function complete() {
			parent::complete() ;

			$offset = get_transient( 'hrr_refund_request_background_updater_offset' ) ;
			$posts  = $this->get_posts( $offset ) ;

			if ( hrr_check_is_array( $posts ) ) {
				$this->handle_push_to_queue( $posts , $offset ) ;
				HRR_WooCommerce_Log::log( 'Refund Request Upgrade upto ' . $offset ) ;
			} else {
				hrr()->background_process()->update_progress_count( 30 ) ;
				delete_transient( 'hrr_refund_request_background_updater_offset' ) ;
				HRR_WooCommerce_Log::log( 'Refund Request Upgrade Completed' ) ;
				hrr()->background_process()->get_background_process_by_id( 'settings' )->trigger() ;
			}
		}

	}

}
