<?php

/**
 * Enqueue Front End Enqueue Files
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Frontend_Scripts' ) ) {

	/**
	 * HRR_Frontend_Scripts Class.
	 */
	class HRR_Frontend_Scripts {

		/**
		 * HRR_Frontend_Scripts Class Initialization.
		 */
		public static function init() {

			add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_js_files' ) , 99 ) ;
			add_action( 'wp_enqueue_scripts' , array( __CLASS__ , 'external_css_files' ) ) ;
		}

		/**
		 * Enqueue external css files.
		 */
		public static function external_css_files() {
					
						$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			wp_enqueue_style( 'hrr-frontend' , HRR_PLUGIN_URL . '/assets/css/frontend/request-form.css' , array() , HRR_VERSION ) ;
						
						do_action( 'hrr_frontend_after_enqueue_css' , $suffix ) ;
		}

		/**
		 * Enqueue Front end required JS files.
		 */
		public static function external_js_files() {
					
						$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ;

			$enqueue_array = array(
				'hrrefund-form' => array(
					'callable' => array( 'HRR_Frontend_Scripts' , 'form_scripts' ) ,
					'restrict' => true ,
				) ,
					) ;

			$enqueue_array = apply_filters( 'hrr_frontend_enqueue_scripts' , $enqueue_array ) ;

			if ( ! hrr_check_is_array( $enqueue_array ) ) {
				return ;
			}

			foreach ( $enqueue_array as $key => $enqueue ) {
				if ( ! hrr_check_is_array( $enqueue ) ) {
					continue ;
				}

				if ( $enqueue[ 'restrict' ] ) {
					call_user_func_array( $enqueue[ 'callable' ] , array() ) ;
				}
			}
						
						do_action( 'hrr_frontend_after_enqueue_js' , $suffix ) ;
		}

		/**
		 * Enqueue Refund Request Form Scripts.
		 */
		public static function form_scripts() {

			wp_enqueue_script( 'hrr_refund_form' , HRR_PLUGIN_URL . '/assets/js/frontend/hrr-refund-request-form.js' , array( 'jquery' , 'accounting' ) , HRR_VERSION ) ;

			wp_localize_script( 'hrr_refund_form' , 'hrr_form_params' , array(
				'request_form_security'   => wp_create_nonce( 'hrr-refund-request' ) ,
				'ajax_url'                => HRR_ADMIN_AJAX_URL ,
				'redirect_url'            => wc_get_endpoint_url( 'hrr-refund-requests' ) ,
				'refund_request_message'  => esc_html__( 'Are you sure you want to request for a refund?' , 'refund' ) ,
				'refund_reason_message'   => esc_html__( 'Please give reason in detail' , 'refund' ) ,
								'mandatory_reason_field'  => apply_filters( 'hrr_is_reason_field_mandatory' , false ) ,
			) ) ;
		}

	}

	HRR_Frontend_Scripts::init() ;
}
