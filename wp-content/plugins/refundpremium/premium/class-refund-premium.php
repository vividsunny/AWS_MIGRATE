<?php

/**
 * Refund Premium Main Class.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Refund_Premium' ) ) {

	/**
	 * Main HRR_Refund_Premium Class.
	 */
	final class HRR_Refund_Premium {

		/**
		 * HRR_Refund_Premium Version.
		 */
		public $version = '2.0' ;

		/**
		 * The single instance of the class.
		 */
		protected static $_instance = null ;

		/**
		 * Load HRR_Refund_Premium Class in Single Instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self() ;
			}

			return self::$_instance ;
		}

		/* Cloning has been forbidden */

		public function __clone() {
			_doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , $this->version ) ;
		}

		/**
		 * Unserialize the class data has been forbidden
		 * */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , $this->version ) ;
		}

		/**
		 * Class Constructor.
		 */
		public function __construct() {
			$this->include_files() ;
		}

		/**
		 * Include required files.
		 */
		private function include_files() {
						//Filters.
			include_once(HRR_ABSPATH . 'premium/inc/class-hrrp-filters.php') ;
						
						//Conversations.
			include_once(HRR_ABSPATH . 'premium/inc/class-hrrp-request-conversation.php') ;
						
						//File Uploader.
						include_once(HRR_ABSPATH . 'premium/inc/class-hrrp-file-uploader.php') ;

			if ( is_admin() ) {
				$this->include_admin_files() ;
			}

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				$this->include_frontend_files() ;
			}
		}

		/**
		 * Include Admin files.
		 */
		private function include_admin_files() {
			include_once(HRR_ABSPATH . 'premium/inc/admin/class-hrrp-admin-assets.php') ;
		}

		/**
		 * Include Frontend files.
		 */
		private function include_frontend_files() {
			include_once(HRR_ABSPATH . 'premium/inc/frontend/class-hrrp-shortcodes.php') ;
			include_once(HRR_ABSPATH . 'premium/inc/frontend/class-hrrp-restrict-button.php') ;
			include_once(HRR_ABSPATH . 'premium/inc/frontend/class-hrrp-frontend-assests.php') ;
		}

	}

}

if ( ! function_exists( 'HRRDP' ) ) {

	function HRRDP() {
		if ( class_exists( 'HRR_Refund_Premium' ) ) {
			return HRR_Refund_Premium::instance() ;
		}

		return false ;
	}

}

//Initialize the plugin.
HRRDP() ;
