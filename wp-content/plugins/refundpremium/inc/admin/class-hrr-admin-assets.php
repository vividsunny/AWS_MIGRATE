<?php

/**
 * Enqueue Admin Assets Files.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRR_Admin_Assets' ) ) {

	/**
	 * HRR_Admin_Assets Class.
	 */
	class HRR_Admin_Assets {

		/**
		 * HRR_Admin_Assets Class Initialization.
		 */
		public static function init() {
			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) ) ;
			add_action( 'admin_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
		}

		/**
		 * Enqueue external css files.
		 */
		public static function external_css_files() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			$screen_ids   = hrr_page_screen_ids() ;
			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			if ( ! in_array( $screenid , $screen_ids ) ) {
				return ;
			}

			wp_enqueue_style( 'hrr-admin' , HRR_PLUGIN_URL . '/assets/css/backend/admin.css' , array() , HRR_VERSION ) ;
						wp_enqueue_style( 'hrr-admin-submenu' , HRR_PLUGIN_URL . '/assets/css/backend/submenu.css' , array() , HRR_VERSION ) ;
						wp_enqueue_style( 'hrr-admin-post-table' , HRR_PLUGIN_URL . '/assets/css/backend/post-table.css' , array() , HRR_VERSION ) ;
						wp_enqueue_style( 'hrr-admin-premium-info' , HRR_PLUGIN_URL . '/assets/css/backend/premium-info.css' , array() , HRR_VERSION ) ;
			wp_enqueue_style( 'hrr-font-awesome' , HRR_PLUGIN_URL . '/assets/css/font-awesome.min.css' , array() , HRR_VERSION ) ;
						do_action( 'hrr_admin_after_enqueue_css' ) ;
		}

		/**
		 * Enqueue Admin end required JS files.
		 */
		public static function external_js_files() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			$screen_ids   = hrr_page_screen_ids() ;
			$newscreenids = get_current_screen() ;
			$screenid     = str_replace( 'edit-' , '' , $newscreenids->id ) ;

			$enqueue_array = array(
				'hrr-toggle'          => array(
					'callable' => array( 'HRR_Admin_Assets' , 'toggle_section' ) ,
					'restrict' => in_array( $screenid , $screen_ids ) ,
				) ,
				'hrr-select2'         => array(
					'callable' => array( 'HRR_Admin_Assets' , 'select2' ) ,
					'restrict' => in_array( $screenid , $screen_ids ) ,
				) ,
				'hrr-refund'    => array(
					'callable' => array( 'HRR_Admin_Assets' , 'hrr_refund' ) ,
					'restrict' => isset( $_GET[ 'page' ] ) && 'hrr_settings' == wc_clean($_GET[ 'page' ]) ,
				) ,
								'hrr-license-handler'    => array(
					'callable' => array( 'HRR_Admin_Assets' , 'hrr_license_handler' ) ,
					'restrict' => isset( $_GET[ 'tab' ] ) && 'license' == wc_clean($_GET[ 'tab' ]) ,
				) ,
				'hrr-refund-button'     => array(
					'callable' => array( 'HRR_Admin_Assets' , 'hrr_button_enqueue_scripts' ) ,
					'restrict' => true ,
				) ,
				'hrr-refund-datepicker' => array(
					'callable' => array( 'HRR_Admin_Assets' , 'hrr_datepicker_enqueue_scripts' ) ,
					'restrict' => isset( $_GET[ 'post_type' ] ) && 'hrr_request' == wc_clean($_GET[ 'post_type' ]),
				) ,
								'hrr-upgrade' => array(
									'callable' => array( 'HRR_Admin_Assets' , 'upgrade' ) ,
									'restrict' => true ,
								) ,
					) ;

			$enqueue_array = apply_filters( 'hrr_admin_enqueue_scripts' , $enqueue_array ) ;
			if ( ! hrr_check_is_array( $enqueue_array ) ) {
				return ;
			}

			foreach ( $enqueue_array as $key => $enqueue ) {
				if ( ! hrr_check_is_array( $enqueue ) ) {
					continue ;
				}

				if ( $enqueue[ 'restrict' ] ) {
					call_user_func_array( $enqueue[ 'callable' ] , array( $suffix ) ) ;
				}
			}
						
						do_action( 'hrr_admin_after_enqueue_js' ) ;
		}

		/**
		 * Enqueue Section Toggle scripts.
		 */
		public static function toggle_section() {
			wp_enqueue_script( 'hrr-toggle-section' , HRR_PLUGIN_URL . '/assets/js/admin/toggle-section.js' , array( 'jquery' ) , HRR_VERSION ) ;
		}
				
				/**
				 * Enqueue upgrade.
				 */
		public static function upgrade( $suffix ) {
			//Block UI.
			wp_register_script( 'blockUI' , HRR_PLUGIN_URL . '/assets/js/blockUI/jquery.blockUI.js' , array( 'jquery' ) , '2.70.0' ) ;

			//Upgrade.
			wp_enqueue_script( 'hrr-upgrade' , HRR_PLUGIN_URL . '/assets/js/admin/upgrade.js' , array( 'jquery' , 'blockUI' ) , HRR_VERSION ) ;
			wp_localize_script(
					'hrr-upgrade' , 'hrr_upgrade_params' , array(
				'upgrade_nonce' => wp_create_nonce( 'hrr-upgrade-nonce' ) ,
					)
			) ;
		}

		/**
		 * Enqueue select2 scripts.
		 */
		public static function select2() {
			wp_enqueue_script( 'hrr-enhanced' , HRR_PLUGIN_URL . '/assets/js/hrr-enhanced.js' , array( 'jquery' , 'select2' , 'jquery-ui-datepicker' ) , HRR_VERSION ) ;
			wp_localize_script(
					'hrr-enhanced' , 'hrr_enhanced_select_params' , array(
				'search_nonce' => wp_create_nonce( 'hrr-search-nonce' ) ,
				'ajaxurl'      => HRR_ADMIN_AJAX_URL
					)
			) ;
		}

		/**
		 * Enqueue Refund Tab.
		 */
		public static function hrr_refund() {
			wp_enqueue_script( 'hrr_refund_tab' , HRR_PLUGIN_URL . '/assets/js/admin/hrr-refund-tab.js' , array( 'jquery' ) , HRR_VERSION ) ;
		}
				
				/**
		 * Enqueue License Tab.
		 */
		public static function hrr_license_handler() {
			wp_enqueue_script( 'hrr_license_handler' , HRR_PLUGIN_URL . '/assets/js/admin/license-handler.js' , array( 'jquery' ,'jquery-blockui') , HRR_VERSION ) ;
						
						wp_localize_script( 'hrr_license_handler' , 'hrr_license_handler_params' , array(
				'license_security'      => wp_create_nonce ( 'hrr-license-security' ) ,
								'license_empty_message' => esc_html__( 'Please Enter the License Key' , 'refund') ,
								'ajaxurl'               => HRR_ADMIN_AJAX_URL
						) ) ;
		}

		/**
		 * Enqueue Refund Request Button Scripts.
		 */
		public static function hrr_button_enqueue_scripts() {
			wp_enqueue_script( 'hrr_refund_request' , HRR_PLUGIN_URL . '/assets/js/hrr-request.js' , array( 'jquery' , 'jquery-blockui' , 'jquery-tiptip' , 'accounting' ) , HRR_VERSION ) ;

			wp_localize_script( 'hrr_refund_request' , 'hrr_request_params' , array(
				'mon_decimal_point'       => wc_get_price_decimal_separator() ,
				'status_nonce'            => wp_create_nonce( 'hrr-status-nonce' ) ,
				'button_nonce' => wp_create_nonce( 'hrr-button-nonce' ) ,
				'refund_product_message'  => __( 'Please select atleast one product' , 'refund' ) ,
				'refund_request_message'  => __( 'Are you sure you wish to process this refund? This action cannot be undone.' , 'refund' ) ,
			) ) ;
		}

		/**
		 * Enqueue Refund Request Datepicker Scripts.
		 */
		public static function hrr_datepicker_enqueue_scripts() {
			wp_enqueue_script( 'hrr_refund_datepicker' , HRR_PLUGIN_URL . '/assets/js/hrr-datepicker.js' , array( 'jquery' , 'jquery-ui-datepicker' ) , HRR_VERSION ) ;
		}

	}

	HRR_Admin_Assets::init() ;
}
