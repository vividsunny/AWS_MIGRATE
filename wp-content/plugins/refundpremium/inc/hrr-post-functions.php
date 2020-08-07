<?php

/*
 * Post Function.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'hrr_create_new_request' ) ) {

	/**
	 * Create New Request.
	 *
	 * @return Object
	 */
	function hrr_create_new_request( $meta_args, $post_args = array() ) {

		$object = new HRR_Request() ;

		return $object->create( $meta_args , $post_args ) ;
	}

}

if ( ! function_exists( 'hrr_get_request' ) ) {

	/**
	 * Get Request.
	 *
	 * @return Object
	 */
	function hrr_get_request( $id ) {

		return new HRR_Request( $id ) ;
	}

}

if ( ! function_exists( 'hrr_update_request' ) ) {

	/**
	 * Update Request.
	 *
	 * @return Object
	 */
	function hrr_update_request( $id, $meta_args, $post_args = array() ) {

		$object = new HRR_Request( $id ) ;

		return $object->update( $meta_args , $post_args ) ;
	}

}

if ( ! function_exists( 'hrr_delete_request' ) ) {

	/**
	 * Delete Request.
	 *
	 * @return Object
	 */
	function hrr_delete_request( $id, $force = true ) {

		wp_delete_post( $id , $force ) ;

		return true ;
	}

}

if ( ! function_exists( 'hrr_create_new_conversation' ) ) {

	/**
	 * Create New Conversation.
	 *
	 * @return Object
	 */
	function hrr_create_new_conversation( $meta_args, $post_args = array() ) {

		$object = new HRR_Conversation() ;

		return $object->create( $meta_args , $post_args ) ;
	}

}

if ( ! function_exists( 'hrr_get_conversation' ) ) {

	/**
	 * Get Conversation.
	 *
	 * @return Object
	 */
	function hrr_get_conversation( $id ) {

		return new HRR_Conversation( $id ) ;
	}

}

if ( ! function_exists( 'hrr_get_conversation_ids' ) ) {

	/**
	 * Get Conversation Ids
	 *
	 * @return array
	 */
	function hrr_get_conversation_ids( $request_id = 0 ) {

		$args = array(
			'posts_per_page' => -1 ,
			'post_type'      => HRR_Register_Post_Type::CONVERSATION_POSTTYPE ,
			'post_status'    => 'hrr-replied' ,
			'order'          => 'DESC' ,
			'fields'         => 'ids'
				) ;
				
		if ( ! empty( $request_id ) ) {
			$args['post_parent'] = $request_id;
		}

		return get_posts( $args ) ;
	}

}

if ( ! function_exists( 'hrr_update_conversation' ) ) {

	/**
	 * Update Conversation.
	 *
	 * @return Object
	 */
	function hrr_update_conversation( $id, $meta_args, $post_args = array() ) {

		$object = new HRR_Conversation( $id ) ;

		return $object->update( $meta_args , $post_args ) ;
	}

}
