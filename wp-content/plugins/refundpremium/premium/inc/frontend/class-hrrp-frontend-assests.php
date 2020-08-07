<?php

/**
 * Frontend Assets Premium.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRRP_Frontend_Assets' ) ) {

	/**
	 * Class.
	 */
	class HRRP_Frontend_Assets {

		/**
		 * Class Initialization.
		 */
		public static function init() {

						add_action( 'hrr_frontend_after_enqueue_js' , array( __CLASS__ , 'external_js_files' ) , 10 , 1 ) ;
						add_action( 'hrr_before_frontend_refund_request_table' , array( __CLASS__ , 'localize_form_params' ) , 10 , 1 ) ;
						add_action( 'hrr_before_frontend_refund_request_view' , array( __CLASS__ , 'localize_form_params' ) , 10 , 1 ) ;
		}

		/**
		 * Enqueue External JS files.
		 */
		public static function external_js_files() {

			$enqueue_array = array(
								'hrr-refund-button' => array(
					'callable' => array( 'HRRP_Frontend_Assets' , 'request_form' ) ,
					'restrict' => true ,
				) ,
					) ;

			$enqueue_array = apply_filters( 'hrrp_refund_frontend_enqueue_scripts' , $enqueue_array ) ;
			if ( hrr_check_is_array( $enqueue_array ) ) {
				foreach ( $enqueue_array as $key => $enqueue ) {
					if ( hrr_check_is_array( $enqueue ) ) {
						if ( $enqueue[ 'restrict' ] ) {
							call_user_func_array( $enqueue[ 'callable' ] , array() ) ;
						}
					}
				}
			}
		}
				
				/**
		 * Enqueue Refund Request form Scripts
		 */
		public static function request_form() {

			wp_register_script( 'hrrp_refund_form' , HRR_PLUGIN_URL . '/premium/assets/js/hrrp-refund-request.js' , array( 'jquery' , 'jquery-blockui' , 'accounting' ) , HRR_VERSION ) ;
		}
				
				/**
		 * Enqueue Refund Request form & Localize form params Scripts.
		 */
		public static function localize_form_params( $order ) {
					
						wp_enqueue_script( 'hrrp_refund_form' ) ;
						
			//Localize script.
			wp_localize_script( 'hrrp_refund_form' , 'hrrp_form_params' , array(
				'mon_decimal_point'   => wc_get_price_decimal_separator() ,
				'price_decimals'      => wc_get_price_decimals() ,
				'currency_symbol'     => get_woocommerce_currency_symbol( $order->get_currency() ) ,
				'decimal_seperator'   => esc_attr( wc_get_price_decimal_separator() ) ,
				'thousand_seperator'  => esc_attr( wc_get_price_thousand_separator() ) ,
								'refund_save_message' => esc_html__( 'Are you sure you want to reply to the user?' , 'refund' ),
								'request_message_security' => wp_create_nonce( 'hrr-refund-message' ) ,
								'ajaxurl'      => HRR_ADMIN_AJAX_URL
			) ) ;
		}

	}

	HRRP_Frontend_Assets::init() ;
}
