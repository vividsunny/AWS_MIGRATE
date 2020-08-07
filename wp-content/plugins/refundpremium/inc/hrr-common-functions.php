<?php

/*
 * Common functions. 
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

include_once('hrr-layout-functions.php') ;
include_once('hrr-post-functions.php') ;
include_once('hrr-premium-functions.php') ;

if ( ! function_exists( 'hrr_check_is_array' ) ) {

	/**
	 * Check if resource is array.
	 *
	 * @return bool
	 */
	function hrr_check_is_array( $array ) {
		return ( is_array( $array ) && ! empty( $array ) ) ;
	}

}

if ( ! function_exists( 'hrr_get_wc_categories' ) ) {

	/**
	 * Get WC Categories.
	 *
	 * @return array
	 */
	function hrr_get_wc_categories() {
		$categories    = array() ;
		$wc_categories = get_terms( 'product_cat' ) ;

		if ( ! hrr_check_is_array( $wc_categories ) ) {
			return $categories ;
		}

		foreach ( $wc_categories as $category ) {
			$categories[ $category->term_id ] = $category->name ;
		}

		return $categories ;
	}

}

if ( ! function_exists( 'hrr_get_user_roles' ) ) {

	/**
	 * Get WordPress User Roles.
	 *
	 * @return array
	 */
	function hrr_get_user_roles() {
		global $wp_roles ;
		$user_roles = array() ;

		if ( ! isset( $wp_roles->roles ) || ! hrr_check_is_array( $wp_roles->roles ) ) {
			return $user_roles ;
		}

		foreach ( $wp_roles->roles as $slug => $role ) {
			$user_roles[ $slug ] = $role[ 'name' ] ;
		}

		return $user_roles ;
	}

}

if ( ! function_exists( 'hrr_get_wc_order_statuses' ) ) {

	/**
	 * Get WC Order statuses.
	 *
	 * @return array
	 */
	function hrr_get_wc_order_statuses() {

		$order_statuses_keys   = array_keys( wc_get_order_statuses() ) ;
		$order_statuses_keys   = str_replace( 'wc-' , '' , $order_statuses_keys ) ;
		$order_statuses_values = array_values( wc_get_order_statuses() ) ;

		return array_combine( $order_statuses_keys , $order_statuses_values ) ;
	}

}

if ( ! function_exists( 'hrr_get_product_image' ) ) {

	/**
	 * Get Product Image.
	 *
	 * @return string
	 */
	function hrr_get_product_image( $product ) {
		$productid  = ( ! empty( $product[ 'variation_id' ] ) ) ? $product[ 'variation_id' ] : $product[ 'product_id' ] ;
		$image_urls = wp_get_attachment_image_src( get_post_thumbnail_id( $productid ) ) ;
		$imageurl   = ( isset( $image_urls[ 0 ] ) && ! empty( $image_urls[ 0 ] ) ) ? $image_urls[ 0 ] : wc_placeholder_img_src() ;

		return '<img src="' . esc_url( $imageurl ) . '" alt="' . esc_attr( get_the_title( $productid ) ) . '" height="90" width="90" />' ;
	}

}
if ( ! function_exists( 'hrr_get_product_name' ) ) {

	/**
	 * Get Product Name.
	 *
	 * @return string
	 */
	function hrr_get_product_name( $product ) {
		$post = get_post( $product[ 'product_id' ] ) ;
		if ( ! is_object( $post ) ) {
			return '' ;
		}

		$product_name = $post->post_title ;
		if ( isset( $product[ 'variation_id' ] ) && ( ! empty( $product[ 'variation_id' ] ) ) ) {
			$product_name = $product_name . '<br />' . hrr_get_formatted_variation( $product ) ;
		}

		return $product_name ;
	}

}

if ( ! function_exists( 'hrr_get_formatted_variation' ) ) {

	/**
	 * Get Formatted Variations.
	 *
	 * @return string
	 */
	function hrr_get_formatted_variation( $variations ) {
		$product    = wc_get_product( $variations[ 'variation_id' ] ) ;
		$attributes = explode( ',' , wc_get_formatted_variation( $product , true ) ) ;
		if ( ! hrr_check_is_array( $attributes ) ) {
			return '' ;
		}

		$formatted_attributes = '' ;
		foreach ( $attributes as $each_attribute ) {
			$explode_data = explode( ':' , $each_attribute ) ;

			if ( isset( $explode_data[ 0 ] ) && isset( $explode_data[ 1 ] ) ) {
				$formatted_attributes .= wc_attribute_label( $explode_data[ 0 ] , $product ) . ':' . $explode_data[ 1 ] . '<br />' ;
			}
		}
		return $formatted_attributes ;
	}

}

if ( ! function_exists( 'hrr_page_screen_ids' ) ) {

	/**
	 * Get page screen IDs.
	 *
	 * @return array
	 */
	function hrr_page_screen_ids() {
		return apply_filters( 'hrr_page_screen_ids' , array(
			HRR_Register_Post_Type::REQUEST_POSTTYPE ,
			'refund-premium_page_hrr_settings'
				) ) ;
	}

}

if ( ! function_exists( 'hrr_get_allowed_setting_tabs' ) ) {

	/**
	 * Get setting tabs.
	 * 
	 * @return array
	 */
	function hrr_get_allowed_setting_tabs() {

		return apply_filters( 'hrr_settings_tabs_array' , array() ) ;
	}

}

if ( ! function_exists( 'hrr_get_settings_page_url' ) ) {

	/**
	 * Get Settings page URL.
	 * 
	 * @return array
	 */
	function hrr_get_settings_page_url( $args = array() ) {

		$url = add_query_arg( array( 'page' => 'hrr_settings' ) , admin_url( 'admin.php' ) ) ;

		if ( hrr_check_is_array( $args ) ) {
			$url = add_query_arg( $args , $url ) ;
		}

		return $url ;
	}

}
