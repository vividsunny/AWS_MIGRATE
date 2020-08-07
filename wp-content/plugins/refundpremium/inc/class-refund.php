<?php

/**
 * Initialize the plugin.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HR_Refund' ) ) {

	/**
	 * Main HR_Refund Class.
	 */
	final class HR_Refund {

		/**
		 * HR_Refund Version.
		 */
		public $version = '2.1' ;

		/**
		 * Background Process.
		 */
		protected $background_process ;

		/**
		 * Notifications
		 * */
		protected $notifications ;

		/**
		 * License.
		 */
		protected $license ;

		/**
		 * Update checker.
		 */
		protected $update_checker ;

		/**
		 * The single instance of the class.
		 */
		protected static $_instance = null ;

		/**
		 * Load HR_Refund Class in Single Instance.
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self() ;
			}

			return self::$_instance ;
		}

		/**
		 * Cloning has been forbidden.
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , $this->version ) ;
		}

		/**
		 * Unserialize the class data has been forbidden.
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__ , 'You are not allowed to perform this action!!!' , $this->version ) ;
		}

		/**
		 * HR_Refund Class Constructor.
		 */
		public function __construct() {
			/* Include once will help to avoid fatal error by load the files when you call init hook */
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' ) ;

			$this->header_already_sent_problem() ;
			$this->define_constants() ;
			$this->translate_file() ;
			$this->include_files() ;
			$this->init_hooks() ;
		}

		/**
		 * Function to Prevent Header Error that says You have already sent the header.
		 */
		private function header_already_sent_problem() {
			ob_start() ;
		}

		/**
		 * Initialize the Translate Files.
		 */
		private function translate_file() {
			load_plugin_textdomain( 'refund' , false , dirname( plugin_basename( HRR_PLUGIN_FILE ) ) . '/languages' ) ;
		}

		/**
		 * Prepare the Constants value array.
		 */
		private function define_constants() {
			$protocol = 'http://' ;

			if ( isset( $_SERVER[ 'HTTPS' ] ) && ( 'on' == $_SERVER[ 'HTTPS' ] || 1 == $_SERVER[ 'HTTPS' ] ) || isset( $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) && 'https' == $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ] ) {
				$protocol = 'https://' ;
			}

			global $wpdb ;
			$constant_array = array(
				'HRR_VERSION'        => $this->version ,
				'HRR_PLUGIN_FILE'    => __FILE__ ,
				'HRR_FOLDER_NAME'    => 'refundpremium' ,
				'HRR_PROTOCOL'       => $protocol ,
				'HRR_ABSPATH'        => dirname( HRR_PLUGIN_FILE ) . '/' ,
				'HRR_ADMIN_URL'      => admin_url( 'admin.php' ) ,
				'HRR_ADMIN_AJAX_URL' => admin_url( 'admin-ajax.php' ) ,
				'HRR_PLUGIN_SLUG'    => plugin_basename( HRR_PLUGIN_FILE ) ,
				'HRR_PLUGIN_PATH'    => untrailingslashit( plugin_dir_path( HRR_PLUGIN_FILE ) ) ,
				'HRR_PLUGIN_URL'     => untrailingslashit( plugins_url( '/' , HRR_PLUGIN_FILE ) ) ,
					) ;

			$constant_array = apply_filters( 'hrr_define_constants' , $constant_array ) ;

			if ( is_array( $constant_array ) && ! empty( $constant_array ) ) {
				foreach ( $constant_array as $name => $value ) {
					$this->define_constant( $name , $value ) ;
				}
			}
		}

		/**
		 * Define the Constants value.
		 */
		private function define_constant( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name , $value ) ;
			}
		}

		/**
		 * Include required files.
		 */
		private function include_files() {
			//Function
			include_once(HRR_ABSPATH . 'inc/hrr-common-functions.php') ;

			//Abstract
			include_once(HRR_ABSPATH . 'inc/abstracts/class-hrr-post.php') ;

			//Class
			include_once(HRR_ABSPATH . 'inc/notifications/class-hrr-notification-instances.php') ;

			include_once(HRR_ABSPATH . 'inc/class-hrr-register-post-type.php') ;
			include_once(HRR_ABSPATH . 'inc/class-hrr-register-post-status.php') ;

			include_once(HRR_ABSPATH . 'inc/class-hrr-wc-log.php') ;
			include_once(HRR_ABSPATH . 'inc/class-hrr-date-time.php') ;

			//Entity
			include_once(HRR_ABSPATH . 'inc/entity/class-hrr-request.php') ;
			include_once(HRR_ABSPATH . 'inc/entity/class-hrr-conversation.php') ;

			//Update
			include_once(HRR_ABSPATH . 'inc/class-hrr-updates.php') ;

			include_once(HRR_ABSPATH . 'inc/class-hrr-install.php') ;
			include_once(HRR_ABSPATH . 'inc/privacy/class-hrr-privacy.php') ;

			if ( ! hrr_is_premium() ) {
				include_once HRR_ABSPATH . 'premium/class-refund-premium.php' ;
			} else {
				include_once(HRR_ABSPATH . 'inc/class-hrr-premium-info-handler.php') ;
			}

			if ( is_admin() ) {
				$this->include_admin_files() ;
			}

			if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
				$this->include_frontend_files() ;
			}
		}

		/**
		 * Include Admin End files.
		 */
		private function include_admin_files() {
			include_once(HRR_ABSPATH . 'inc/admin/class-hrr-request-view.php') ;
			include_once(HRR_ABSPATH . 'inc/admin/menu/class-hrr-request-post-type.php') ;
			include_once(HRR_ABSPATH . 'inc/admin/class-hrr-admin-assets.php') ;
			include_once(HRR_ABSPATH . 'inc/admin/class-hrr-admin-ajax.php') ;
			include_once(HRR_ABSPATH . 'inc/admin/menu/class-hrr-menu-management.php') ;
		}

		/**
		 * Include Front End files.
		 */
		private function include_frontend_files() {
			include_once(HRR_ABSPATH . 'inc/frontend/class-hrr-myaccount-handler.php') ;
			include_once(HRR_ABSPATH . 'inc/frontend/class-hrr-frontend-assets.php') ;
		}

		/**
		 * Define the hooks.
		 */
		private function init_hooks() {

			add_action( 'plugins_loaded' , array( $this , 'plugins_loaded' ) ) ;

			//Register the plugin.
			register_activation_hook( HRR_PLUGIN_FILE , array( 'HRR_Install' , 'install' ) ) ;
		}

		/**
		 * Plugins Loaded.
		 */
		public function plugins_loaded() {
			do_action( 'hrr_before_plugin_loaded' ) ;

			$this->maybe_deactivate_free_plugin() ;

			//Background process.
			include_once(HRR_ABSPATH . 'inc/background-updater/hrr-background-process.php') ;
			//Upgrade.
			include_once(HRR_ABSPATH . 'inc/upgrade/class-hrr-license-handler.php') ;
			include_once(HRR_ABSPATH . 'inc/upgrade/class-hrr-plugin-update-checker.php') ;

			$this->license        = new HRR_License_Handler( HRR_VERSION , HRR_PLUGIN_SLUG ) ;
			$this->update_checker = new HRR_Plugin_Update_Checker( HRR_VERSION , HRR_PLUGIN_SLUG , $this->license->license_key() ) ;

			$this->background_process = new HRR_Background_Process() ;

			$this->notifications = HRR_Notification_Instances::get_notifications() ;

			do_action( 'hrr_before_plugin_loaded' ) ;
		}

		/**
		 * May be deactivate the free plugin.
		 */
		public function maybe_deactivate_free_plugin() {
			//Return if free plugin is not activated.
			if ( ! is_plugin_active( 'refund/refund.php' ) ) {
				return ;
			}

			//Deactivate the free plugin.
			deactivate_plugins( plugin_basename( 'refund/refund.php' ) ) ;

			//Add notice.
			add_action( 'admin_notices' , array( $this , 'deactivation_notice' ) ) ;
		}

		/**
		 * Display Notice.
		 */
		public function deactivation_notice() {
			echo '<div class="error">' ;
			/* translators: 1:Name for Free Version 2:Name for Premium Version */
			echo '<p>' . sprintf( esc_html__( 'You cannot Activate Both %1$s And %2$s at the same time' , 'refund' ) , '<b>' . esc_html__( 'Refund' , 'refund' ) . '</b>' , '<b>' . esc_html__( 'Refund Premium' , 'refund' ) . '</b>' ) . '</p>' ;
			echo '</div>' ;
		}

		/**
		 * Templates.
		 */
		public function templates() {
			return HRR_PLUGIN_PATH . '/templates/' ;
		}

		/**
		 * License.
		 */
		public function license() {
			return $this->license ;
		}

		/**
		 * Update Checker.
		 */
		public function update_checker() {
			return $this->update_checker ;
		}

		/**
		 * Background Process.
		 */
		public function background_process() {
			return $this->background_process ;
		}

		/**
		 * Notifications instances
		 * */
		public function notifications() {
			return $this->notifications ;
		}

	}

}
