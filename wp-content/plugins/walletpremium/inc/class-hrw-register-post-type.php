<?php

/**
 * Custom Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Register_Post_Types' ) ) {

    /**
     * HRW_Register_Post_Types Class.
     */
    class HRW_Register_Post_Types {
        /*
         * Wallet Post Type
         */

        const WALLET_POSTTYPE = 'hrw_wallet' ;

        /*
         * Transaction Log Post Type
         */
        const TRANSACTION_LOG_POSTTYPE = 'hrw_transactions_log' ;

        /**
         * HRW_Register_Post_Types Class initialization.
         */
        public static function init() {

            add_action( 'init' , array( __CLASS__ , 'register_custom_post_types' ) ) ;
        }

        /*
         * Register Custom Post types
         */

        public static function register_custom_post_types() {
            if ( ! is_blog_installed() )
                return ;

            $custom_post_types = array(
                self::WALLET_POSTTYPE          => array( 'HRW_Register_Post_Types' , 'wallet_post_type_args' ) ,
                self::TRANSACTION_LOG_POSTTYPE => array( 'HRW_Register_Post_Types' , 'transaction_log_post_type_args' ) ,
                    ) ;

            $custom_post_types = apply_filters( 'hrw_add_custom_post_types' , $custom_post_types ) ;

            // return if no post type to register
            if ( ! hrw_check_is_array( $custom_post_types ) )
                return ;

            foreach ( $custom_post_types as $post_type => $args_function ) {

                $args = array() ;
                if ( $args_function )
                    $args = call_user_func_array( $args_function , $args ) ;

                //Register custom post type
                register_post_type( $post_type , $args ) ;
            }
        }

        /*
         * Prepare Wallet Post type arguments
         */

        public static function wallet_post_type_args() {

            return apply_filters( 'hrw_wallet_post_type_args' , array(
                'labels'              => array(
                    'name'          => esc_html__( 'All Wallet' , HRW_LOCALE ) ,
                    'singular_name' => esc_html__( 'All Wallet' , HRW_LOCALE ) ,
                    'all_items'     => esc_html__( 'All Wallet' , HRW_LOCALE ) ,
                    'menu_name'     => esc_html_x( 'All Wallet' , 'Admin menu name' , HRW_LOCALE ) ,
                    'add_new'       => esc_html__( 'Add Wallet' , HRW_LOCALE ) ,
                    'add_new_item'  => esc_html__( 'Add New Wallet' , HRW_LOCALE ) ,
                    'edit'          => esc_html__( 'Edit' , HRW_LOCALE ) ,
                    'edit_item'     => esc_html__( 'Edit Wallet' , HRW_LOCALE ) ,
                    'new_item'      => esc_html__( 'New Wallet' , HRW_LOCALE ) ,
                    'view'          => esc_html__( 'View Wallet' , HRW_LOCALE ) ,
                    'view_item'     => esc_html__( 'View Wallet' , HRW_LOCALE ) ,
                    'view_items'    => esc_html__( 'View Wallet' , HRW_LOCALE ) ,
                    'search_items'  => esc_html__( 'Search Wallet' , HRW_LOCALE ) ,
                ) ,
                'description'         => esc_html__( 'Here you can able to see list of Wallet' , HRW_LOCALE ) ,
                'public'              => true ,
                'show_ui'             => true ,
                'capability_type'     => 'post' ,
                'publicly_queryable'  => true ,
                'exclude_from_search' => false ,
                'hierarchical'        => false , // Hierarchical causes memory issues - WP loads all records!
                'show_in_nav_menus'   => false ,
                'show_in_menu'        => 'hrw_wallet' ,
                'menu_icon'           => HRW_PLUGIN_URL . '/assets/images/dash-icon.png' ,
                'supports'            => false ,
                'query_var'           => true ,
                'map_meta_cap'        => true ,
                'rewrite'             => false ,
                'capabilities'        => array(
                    'create_posts' => 'do_not_allow' ,
                )
                    )
                    ) ;
        }

        /*
         * Prepare Transaction Log Post type arguments
         */

        public static function transaction_log_post_type_args() {

            return apply_filters( 'hrw_transaction_log_post_type_args' , array(
                'labels'              => array(
                    'name'               => esc_html__( 'Transaction Log' , HRW_LOCALE ) ,
                    'singular_name'      => esc_html__( 'Transaction Log' , HRW_LOCALE ) ,
                    'all_items'          => esc_html__( 'Transaction Log' , HRW_LOCALE ) ,
                    'menu_name'          => esc_html_x( 'Transaction Log' , 'Admin menu name' , HRW_LOCALE ) ,
                    'add_new'            => esc_html__( 'Add Transaction Log' , HRW_LOCALE ) ,
                    'add_new_item'       => esc_html__( 'Add New Transaction Log' , HRW_LOCALE ) ,
                    'edit'               => esc_html__( 'Edit' , HRW_LOCALE ) ,
                    'edit_item'          => esc_html__( 'Edit Transaction Log' , HRW_LOCALE ) ,
                    'new_item'           => esc_html__( 'New Transaction Log' , HRW_LOCALE ) ,
                    'view'               => esc_html__( 'View Transaction Log' , HRW_LOCALE ) ,
                    'view_item'          => esc_html__( 'View Transaction Log' , HRW_LOCALE ) ,
                    'view_items'         => esc_html__( 'View Transaction Log' , HRW_LOCALE ) ,
                    'search_items'       => esc_html__( 'Search Users' , HRW_LOCALE ) ,
                    'not_found'          => esc_html__( 'No Transaction Log found' , HRW_LOCALE ) ,
                    'not_found_in_trash' => esc_html__( 'No Transaction Log found in trash' , HRW_LOCALE ) ,
                ) ,
                'description'         => esc_html__( 'Here you can able to see list of Transaction Log' , HRW_LOCALE ) ,
                'public'              => true ,
                'show_ui'             => true ,
                'capability_type'     => 'post' ,
                'publicly_queryable'  => true ,
                'exclude_from_search' => false ,
                'hierarchical'        => false , // Hierarchical causes memory issues - WP loads all records!
                'show_in_nav_menus'   => false ,
                'show_in_menu'        => 'hrw_wallet' ,
                'menu_icon'           => HRW_PLUGIN_URL . '/assets/images/dash-icon.png' ,
                'supports'            => false ,
                'query_var'           => true ,
                'map_meta_cap'        => true ,
                'rewrite'             => false ,
                'capabilities'        => array(
                    'create_posts' => 'do_not_allow' ,
                )
                    )
                    ) ;
        }

    }

    HRW_Register_Post_Types::init() ;
}