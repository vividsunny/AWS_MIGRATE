<?php

/**
 * Admin Ajax.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRR_Admin_Ajax' ) ) {

	/**
	 * HRR_Admin_Ajax Class.
	 */
	class HRR_Admin_Ajax {

		/**
		 * HRR_Admin_Ajax Class initialization.
		 */
		public static function init() {

			$actions = array(
				'product_search'   => false ,
				'customers_search' => false ,
					) ;

			foreach ( $actions as $action => $nopriv ) {
				add_action( 'wp_ajax_hrr_' . $action , array( __CLASS__ , $action ) ) ;

				if ( $nopriv ) {
					add_action( 'wp_ajax_nopriv_hrr_' . $action , array( __CLASS__ , $action ) ) ;
				}
			}
		}

		/**
		 * Product search.
		 */
		public static function product_search() {
			check_ajax_referer( 'hrr-search-nonce' , 'hrr_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? ( string ) wc_clean(wp_unslash( $_GET[ 'term' ] )) : '' ;

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Product(s) found' , 'refund' ) ) ;
				}

				$data_store = WC_Data_Store::load( 'product' ) ;
				$ids        = $data_store->search_products( $term , '' , false ) ;

				$product_objects = array_filter( array_map( 'wc_get_product' , $ids ) , 'wc_products_array_filter_readable' ) ;
				$products        = array() ;

				foreach ( $product_objects as $product_object ) {
					if ( $product_object->is_type( 'simple' ) ) {
						$products[ $product_object->get_id() ] = rawurldecode( $product_object->get_formatted_name() ) ;
					}
				}
				wp_send_json( $products ) ;
			} catch ( Exception $ex ) {
				wp_die() ;
			}
		}

		/**
		 * Customer search.
		 */
		public static function customers_search() {
			check_ajax_referer( 'hrr-search-nonce' , 'hrr_security' ) ;

			try {
				$term = isset( $_GET[ 'term' ] ) ? ( string ) wc_clean(wp_unslash( $_GET[ 'term' ] )) : '' ;

				if ( empty( $term ) ) {
					throw new exception( esc_html__( 'No Customer(s) found' , 'refund' ) ) ;
				}

				$exclude = isset( $_GET[ 'exclude' ] ) ? ( string ) wc_clean(wp_unslash( $_GET[ 'exclude' ] )) : '' ;
				$exclude = ! empty( $exclude ) ? array_map( 'intval' , explode( ',' , $exclude ) ) : array() ;

				$found_customers = array() ;

				$customers_query = new WP_User_Query( array(
					'fields'         => 'all' ,
					'orderby'        => 'display_name' ,
					'search'         => '*' . $term . '*' ,
					'search_columns' => array( 'ID' , 'user_login' , 'user_email' , 'user_nicename' )
						) ) ;

				$customers = $customers_query->get_results() ;

				if ( hrr_check_is_array( $customers ) ) {
					foreach ( $customers as $customer ) {
						if ( ! in_array( $customer->ID , $exclude ) ) {
							$found_customers[ $customer->ID ] = $customer->display_name . ' (#' . $customer->ID . ' &ndash; ' . sanitize_email( $customer->user_email ) . ')' ;
						}
					}
				}

				wp_send_json( $found_customers ) ;
			} catch ( Exception $ex ) {
				wp_die() ;
			}
		}

	}

	HRR_Admin_Ajax::init() ;
}
