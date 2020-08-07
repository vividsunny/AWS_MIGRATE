<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}
if ( ! class_exists( 'HRR_Settings_Background_Process' ) ) {

	/**
	 * Class.
	 */
	class HRR_Settings_Background_Process extends WP_Background_Process {

		/**
				 * Assign action name.
				 * 
		 * @var string
		 */
		protected $action = 'hrr_settings_background_updater' ;

		/**
		 * Trigger.
		 */
		public function trigger() {

			if ( $this->is_process_running() ) {
				return ;
			}

			$this->push_to_queue( 'no_old_data' ) ;

			hrr()->background_process()->update_progress_count( 90 ) ;
			HRR_WooCommerce_Log::log( 'Settings Upgrade Started' ) ;

			$this->save()->dispatch() ;
		}

		/**
		 * Is process running.
		 *
		 * Check whether the current process is already running in a background process.
		 */
		public function is_process_running() {
			if ( get_site_transient( $this->identifier . '_process_lock' ) ) {
				// Process already running.
				return true ;
			}

			return false ;
		}

		/**
		 * Task.
		 */
		protected function task( $item ) {

			$options = array(
				'hr_refund_enable_refund_request'                  => 'hrr_refund_refund_request' ,
								'hr_refund_enable_partial_refund_request'          => 'hrr_refund_partial_refund',
								'hr_refund_enable_for_sale_items'                  => 'hrr_refund_refund_for_sale_items',
								'hr_refund_request_include_tax'                    => 'hrr_refund_refund_tax',
								'hr_refund_buttons_by_order_status'                => 'hrr_refund_order_status',
								'hr_refund_min_order_amount'                       => 'hrr_refund_minimum_order_amount',
								'hr_refund_request_time_period'                    => 'hrr_refund_request_time_period',
								'hr_refund_request_no_of_days'                     => 'hrr_refund_request_time_period_value',
								'hr_refund_prevent_refund_request'                 => 'hrr_refund_refundanable_product',
								'hr_refund_include_products_srch'                  => 'hrr_refund_included_product',
								'hr_refund_include_categories_srch'                => 'hrr_refund_included_category',
								'hr_refund_request_prevent_users'                  => 'hrr_refund_refundable_user',
								'hr_refund_include_user_srch'                      => 'hrr_refund_included_user',
								'hr_refund_include_user_role_srch'                 => 'hrr_refund_included_user_role',
								'hr_refund_enable_refund_method'                   => 'hrr_refund_refund_method',
								'hr_refund_preloaded_reason'                       => 'hrr_refund_refund_reason',
								'hr_refund_enable_refund_reason_field'             => 'hrr_refund_enable_reason_in_detail',
								'hr_refund_enable_refund_reason_field_mandatory'   => 'hrr_refund_mandatory_reason_field',
								'hr_refund_enable_conversation'                    => 'hrr_refund_enable_conversation',
								'hr_refund_enable_conversation_attachment'         => 'hrr_refund_enable_attachment',
								'hr_refund_conversation_attachment_priority'       => 'hrr_refund_upload_mandatory',
								'hr_refund_conversation_attachment_size'           => 'hrr_refund_file_size',
								'hr_refund_conversation_attachment_type'           => 'hrr_refund_file_type',
								'hr_refund_conversation_attachment_mandataory_error' => 'hrr_refund_error_message_for_mandatory',
								'hr_refund_conversation_attachment_size_error' => 'hrr_refund_error_message_for_size',
								'hr_refund_conversation_attachment_type_error' => 'hrr_refund_error_message_for_type',
								'hr_refund_request_email_type'                     => 'hrr_refund_email_type',
								'hr_refund_request_email_from_name'                => 'hrr_refund_from_name',
								'hr_refund_request_email_from_email'               => 'hrr_refund_from_email',
								'hr_refund_request_enable_unsubscription'          => 'hrr_refund_enable_unsubscribe_option',
								'hr_refund_request_customize_unsub_heading'        => 'hrr_refund_unsub_heading',
								'hr_refund_request_customize_unsub_text'           => 'hrr_refund_unsub_label',
								'hr_refund_request_sent_user_enable'               => 'hrr_refund_new_request_user_notification',
								'hr_refund_request_sent_user_sub'                  => 'hrr_refund_new_request_subject_for_user_notification',
								'hr_refund_request_sent_user_msg'                  => 'hrr_refund_new_request_msg_for_user_notification',
								'hr_refund_request_sent_admin_enable' => 'hrr_refund_new_request_admin_notification',
								'hr_refund_request_sent_admin_sub' => 'hrr_refund_new_request_subject_for_admin_notification',
								'hr_refund_request_sent_admin_msg' => 'hrr_refund_new_request_msg_for_admin_notification',
								'hr_refund_request_reply_receive_user_enable' => 'hrr_refund_refund_conversation_user_notification',
								'hr_refund_request_reply_receive_user_sub' => 'hrr_refund_refund_conversation_subject_for_user_notification',
								'hr_refund_request_reply_receive_user_msg' => 'hrr_refund_refund_conversation_msg_for_user_notification',
								'hr_refund_request_reply_receive_admin_enable' => 'hrr_refund_refund_conversation_admin_notification',
								'hr_refund_request_reply_receive_admin_sub' => 'hrr_refund_refund_conversation_subject_for_admin_notification',
								'hr_refund_request_reply_receive_admin_msg' => 'hrr_refund_refund_conversation_msg_for_admin_notification',
								'hr_refund_request_accept_user_enable' => 'hrr_refund_request_accepted_user_notification',
								'hr_refund_request_accept_user_sub' => 'hrr_refund_request_accepted_subject_for_user_notification',
								'hr_refund_request_accept_user_msg' => 'hrr_refund_request_accepted_msg_for_user_notification',
								'hr_refund_request_accept_admin_enable' => 'hrr_refund_request_accepted_admin_notification',
								'hr_refund_request_accept_admin_sub' => 'hrr_refund_request_accepted_subject_for_admin_notification',
								'hr_refund_request_accept_admin_msg' => 'hrr_refund_request_accepted_msg_for_admin_notification',
								'hr_refund_request_reject_user_enable' => 'hrr_refund_request_rejected_user_notification',
								'hr_refund_request_reject_user_sub' => 'hrr_refund_request_rejected_subject_for_user_notification',
								'hr_refund_request_reject_user_msg' => 'hrr_refund_request_rejected_msg_for_user_notification',
								'hr_refund_request_reject_admin_enable' => 'hrr_refund_request_rejected_admin_notification',
								'hr_refund_request_reject_admin_sub' => 'hrr_refund_request_rejected_subject_for_admin_notification',
								'hr_refund_request_reject_admin_msg' => 'hrr_refund_request_rejected_msg_for_admin_notification',
								'hr_refund_request_status_change_user_enable' => 'hrr_refund_request_status_update_user_notification',
								'hr_refund_request_status_change_user_sub' => 'hrr_refund_request_status_update_subject_for_user_notification',
								'hr_refund_request_status_change_user_msg' => 'hrr_refund_request_status_update_msg_for_user_notification',
								'hr_refund_request_status_change_admin_enable' => 'hrr_refund_request_status_update_admin_notification',
								'hr_refund_request_status_change_admin_sub' => 'hrr_refund_request_status_update_subject_for_admin_notification',
								'hr_refund_request_status_change_admin_msg' => 'hrr_refund_request_status_update_msg_for_admin_notification',
								'hr_refund_full_order_button_label' => 'hrr_refund_full_order_button_label',
								'hr_refund_table_request_title_label' => 'hrr_refund_request_title',
								'hr_refund_table_request_id_label' => 'hrr_refund_request_id',
								'hr_refund_table_orderid_label' => 'hrr_refund_order_id',
								'hr_refund_table_status_label' => 'hrr_refund_status',
								'hr_refund_table_type_label' => 'hrr_refund_type',
								'hr_refund_table_request_as_label' => 'hrr_refund_mode',
								'hr_refund_table_user_total_label' => 'hrr_refund_amount',
								'hr_refund_table_view_label' => 'hrr_refund_view',
								'hr_refund_form_general_reason_label' => 'hrr_refund_request_reason',
								'hr_refund_form_request_as_label' => 'hrr_refund_refund_mode',
								'hr_refund_form_details_label' => 'hrr_refund_detail_request_reason',
								'hr_refund_form_submit_button_label' => 'hrr_refund_submit_button'
					) ;

			foreach ( $options as $old_key => $new_key ) {
				update_option( $new_key , get_option( $old_key ) ) ;
			}

			return false ;
		}

		/**
		 * Complete.
		 */
		protected function complete() {
			parent::complete() ;

			hrr()->background_process()->update_progress_count( 100 ) ;
			HRR_WooCommerce_Log::log( 'Settings Upgrade Completed' ) ;
			update_option( 'hrr_upgrade_success' , 'yes' ) ;
			update_option( 'hrr_update_version' , HRR_VERSION ) ;
		}

	}

}
