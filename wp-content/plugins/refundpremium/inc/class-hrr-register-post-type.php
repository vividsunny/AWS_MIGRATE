<?php

/**
 * Admin Custom Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Register_Post_Type' ) ) {

	/**
	 * Class.
	 */
	class HRR_Register_Post_Type {

		/**
		 * Refund Request Post Type.
		 */
		const REQUEST_POSTTYPE = 'hrr_request' ;

		/**
		 * Conversation Post Type.
		 */
		const CONVERSATION_POSTTYPE = 'hrr_conversation' ;

		/**
		 * Class initialization.
		 */
		public static function init() {
			add_action( 'init' , array( __CLASS__ , 'register_custom_post_types' ) , 5 ) ;
		}

		/**
		 * Register Custom Post types.
		 */
		public static function register_custom_post_types() {
			if ( ! is_blog_installed() ) {
				return ;
			}

			$custom_post_type = array(
				self::REQUEST_POSTTYPE      => array( 'HRR_Register_Post_Type' , 'request_post_type_args' ) ,
				self::CONVERSATION_POSTTYPE => array( 'HRR_Register_Post_Type' , 'conversation_post_type_args' ) ,
					) ;

			$custom_post_type = apply_filters( 'hrr_add_custom_post_type' , $custom_post_type ) ;

			if ( ! hrr_check_is_array( $custom_post_type ) ) {
				return ;
			}

			foreach ( $custom_post_type as $post_type => $args_function ) {
				$args = array() ;
				if ( $args_function ) {
					$args = call_user_func_array( $args_function , $args ) ;
				}

				if ( ! post_type_exists( $post_type ) ) {

					// Register custom post type.
					register_post_type( $post_type , $args ) ;
				}
			}
		}

		/**
		 * Prepare Request Post Type Arguments.
		 */
		public static function request_post_type_args() {
			return apply_filters( 'hrr_request_post_type_args' , array(
				'labels'              => array(
					'name'               => esc_html__( 'Refund Requests' , 'refund' ) ,
					'singular_name'      => esc_html__( 'Refund Requests' , 'refund' ) ,
					'menu_name'          => esc_html__( 'Refund Requests' , 'refund' ) ,
					'add_new'            => esc_html__( 'Add New Refund Request' , 'refund' ) ,
					'add_new_item'       => esc_html__( 'Add New Refund Request' , 'refund' ) ,
					'edit'               => esc_html__( 'Edit Refund Request' , 'refund' ) ,
					'edit_item'          => esc_html__( 'View Refund Request' , 'refund' ) ,
					'new_item'           => esc_html__( 'New Refund Request' , 'refund' ) ,
					'view'               => esc_html__( 'View Refund Request' , 'refund' ) ,
					'view_item'          => esc_html__( 'View Refund Request' , 'refund' ) ,
					'search_items'       => esc_html__( 'Search Refund Request' , 'refund' ) ,
					'not_found'          => esc_html__( 'No Refund Request found' , 'refund' ) ,
					'not_found_in_trash' => esc_html__( 'No Refund Request found in trash' , 'refund' ) ,
				) ,
				'description'         => esc_html__( 'Here you can able to see list of Refund Requests' , 'refund' ) ,
				'public'              => true ,
				'show_ui'             => true ,
				'capability_type'     => 'post' ,
				'show_in_menu'        => 'hrr_request' ,
				'publicly_queryable'  => false ,
				'exclude_from_search' => true ,
				'hierarchical'        => false , // Hierarchical causes memory issues - WP loads all records!
				'show_in_nav_menus'   => false ,
				'capabilities'        => array(
					'publish_posts'       => 'publish_posts' ,
					'edit_posts'          => 'edit_posts' ,
					'edit_others_posts'   => 'edit_others_posts' ,
					'delete_posts'        => 'delete_posts' ,
					'delete_others_posts' => 'delete_others_posts' ,
					'read_private_posts'  => 'read_private_posts' ,
					'edit_post'           => 'edit_post' ,
					'delete_post'         => 'delete_post' ,
					'read_post'           => 'read_post' ,
					'create_posts'        => 'do_not_allow' ,
				) ,
				'map_meta_cap'        => true ,
					)
					) ;
		}

		/**
		 * Prepare Conversation Post Type Arguments.
		 */
		public static function conversation_post_type_args() {
			return apply_filters(
					'hrr_conversation_post_type_args' , array(
				'label'           => esc_html__( 'Converstion' , 'refund' ) ,
				'public'          => false ,
				'hierarchical'    => false ,
				'supports'        => false ,
				'capability_type' => 'post' ,
				'rewrite'         => false ,
					)
					) ;
		}

	}

	HRR_Register_Post_Type::init() ;
}
