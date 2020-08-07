<?php

/**
 * Notifications Instances Class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Notification_Instances' ) ) {

	/**
	 * Class HRR_Notification_Instances.
	 */
	class HRR_Notification_Instances {

		/**
		 * Notifications.
		 */
		private static $notifications = array() ;

		/**
		 * Get Notifications.
		 */
		public static function get_notifications() {

			if ( ! self::$notifications ) {
				self::load_notifications() ;
			}

			return self::$notifications ;
		}

		/**
		 * Load all Notifications.
		 */
		public static function load_notifications() {

			if ( ! class_exists( 'HRR_Notifications' ) ) {
				include HRR_ABSPATH . 'inc/abstracts/class-hrr-notifications.php' ;
			}

			$default_notification_classes = array(
				'customer-refund-request-submitted'      => 'HRR_Customer_Refund_Request_Submitted_Notification' ,
				'admin-refund-request-submitted'         => 'HRR_Admin_Refund_Request_Submitted_Notification' ,
				'customer-refund-request-conversation'   => 'HRR_Customer_Refund_Request_Conversation_Notification' ,
				'admin-refund-request-conversation'      => 'HRR_Admin_Refund_Request_Conversation_Notification' ,
				'customer-refund-request-accepted'       => 'HRR_Customer_Refund_Request_Accepted_Notification' ,
				'admin-refund-request-accepted'          => 'HRR_Admin_Refund_Request_Accepted_Notification' ,
				'customer-refund-request-rejected'       => 'HRR_Customer_Refund_Request_Rejected_Notification' ,
				'admin-refund-request-rejected'          => 'HRR_Admin_Refund_Request_Rejected_Notification' ,
				'customer-refund-request-status-changed' => 'HRR_Customer_Refund_Request_Status_Changed_Notification' ,
				'admin-refund-request-status-changed'    => 'HRR_Admin_Refund_Request_Status_Changed_Notification' ,
					) ;

			foreach ( $default_notification_classes as $file_name => $notification_class ) {

				// include file
				include 'class-' . $file_name . '.php' ;

				//add notification
				self::add_notification( new $notification_class() ) ;
			}
		}

		/**
		 * Add a Module.
		 */
		public static function add_notification( $notification ) {

			self::$notifications[ $notification->get_id() ] = $notification ;

			return new self() ;
		}

		/**
		 * Get notification by id.
		 */
		public static function get_notification_by_id( $notification_id ) {
			$notifications = self::get_notifications() ;

			return isset( $notifications[ $notification_id ] ) ? $notifications[ $notification_id ] : false ;
		}

	}

}
	
