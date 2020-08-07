<?php

/*
 * Premium Functions.
 */

if ( ! function_exists( 'hrr_is_premium' ) ) {

	/**
	 * Check if is Premium or Free Version.
	 *
	 * @return bool
	 */
	function hrr_is_premium() {
		$premium_folder = HRR_ABSPATH . '/premium' ;
		if ( ! is_dir( $premium_folder ) ) {
			return true ;
		}

		return false ;
	}

}

if ( ! function_exists( 'is_valid_for_refund' ) ) {

	/**
	 * Check if Request is already sent for this order.
	 *
	 * @return bool
	 */
	function is_valid_for_refund( $order_id ) {
		$already_send = get_post_meta( $order_id , 'hr_refund_request_already_send' , true ) ;

		if ( ! empty( $already_send ) ) {
			return false ;
		}

		return apply_filters( 'hrr_is_valid_refund' , true , $order_id ) ;
	}

}
