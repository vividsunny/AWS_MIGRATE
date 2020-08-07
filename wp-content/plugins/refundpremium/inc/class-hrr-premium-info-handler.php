<?php

/**
 *  Premium Info Handler.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRR_Premium_Info_Handler' ) ) {

	/**
	 * Class.
	 */
	class HRR_Premium_Info_Handler {

		/**
		 * Class Initialization.
		 */
		public static function init() {
			//Premium info related css and js files.
			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'enqueue_scripts' ) ) ;
			//Display the premium banner.
			add_action( 'hrr_before_tab_sections' , array( __CLASS__ , 'premium_banner' ) ) ;
			//Display the compatibility premium banner.
			add_action( 'hrr_after_compatibility_content' , array( __CLASS__ , 'compatibility_premium_banner' ) ) ;
		}

		/**
		 * Enqueue CSS and JS files.
		 */

		public static function enqueue_scripts() {
			$screen_ids   = hrr_page_screen_ids() ;
			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			if ( ! in_array( $screenid , $screen_ids ) ) {
				return ;
			}

			//CSS.
			wp_enqueue_style( 'hrr-premium-info' , HRR_PLUGIN_URL . '/assets/css/backend/premium-info.css' , array() , HRR_VERSION ) ;

			//JS.
			wp_enqueue_script( 'hrr-premium-info' , HRR_PLUGIN_URL . '/assets/js/admin/premium-info.js' , array( 'jquery' ) , HRR_VERSION ) ;
			wp_localize_script(
					'hrr-premium-info' , 'hrr_premium_info_params' , array(
											/* translators: %s: Premium Version URL */
				'premium_info_msg' => sprintf( esc_html__( 'This feature is available in %s' , 'refund' ) , '<a href="https://hoicker.com/plugins/refund" target="_blank">' . esc_html__( 'Refund Premium Version' , 'refund' ) . '</a>' ) ,
					)
			) ;
		}

		/*
		 * Display the compatibility Premium Banner.
		 */

		public static function compatibility_premium_banner() {
						/* translators: %s: Premium Version URL */
			$message = sprintf( esc_html__( 'Compatiblity is available in %s' , 'refund' ) , '<a href="https://hoicker.com/plugins/refund" target="_blank">' . esc_html__( 'Refund Premium Version' , 'refund' ) . '</a>' ) ;
			echo '<div class="hrr-premium-info-message"><p><i class="fa fa-info-circle"></i> ' . $message . '</p></div>' ;
		}

		/*
		 * Display the Premium Banner.
		 */

		public static function premium_banner() {
			include_once HRR_ABSPATH . 'inc/admin/menu/views/premium-banner.php' ;
		}

	}

	HRR_Premium_Info_Handler::init() ;
}
