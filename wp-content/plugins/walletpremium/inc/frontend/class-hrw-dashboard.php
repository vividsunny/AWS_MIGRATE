<?php

/**
 * Dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Dashboard' ) ) {

    /**
     *  Class.
     */
    class HRW_Dashboard {

        /**
         * Init
         */
        public static function init() {
            add_action( 'hrw_frontend_dashboard_content' , array ( __CLASS__ , 'render_dashboard_navigation' ) , 8 ) ;
            add_action( 'hrw_frontend_dashboard_content' , array ( __CLASS__ , 'render_dashboard_menu_content' ) , 8 ) ;
            add_action( 'hrw_frontend_pagination' , array ( __CLASS__ , 'render_pagination' ) , 10 , 1 ) ;

            //Menu content Hooks
            add_action( 'hrw_frontend_dashboard_menu_content_overview' , array ( __CLASS__ , 'render_overview' ) ) ;
            add_action( 'hrw_frontend_dashboard_menu_content_activity' , array ( __CLASS__ , 'render_activity' ) ) ;
            add_action( 'hrw_frontend_dashboard_menu_content_topup' , array ( __CLASS__ , 'render_topup' ) ) ;
            add_action( 'hrw_frontend_dashboard_menu_content_profile' , array ( __CLASS__ , 'render_profile' ) ) ;
        }

        /**
         * output the dashboard
         */
        public static function output() {

            if ( apply_filters('hrw_wallet_usage_user_roles_restriction',true) ) {
                self::populate_menu() ;
                hrw_get_template( 'dashboard/dashboard.php' ) ;
            } else {
                HRW_Form_Handler::show_info( esc_html__( get_option( 'hrw_wallet_msg_usage_restriction_user' , 'You are restricted to use your wallet.' ) , HRW_LOCALE ) ) ;
            }
        }

        /**
         * Populate Menu
         */
        public static function populate_menu() {
            global $hrw_current_menu , $hrw_current_submenu ;

            $menus = hrw_get_dashboard_menus() ;

            $hrw_current_submenu = isset( $_REQUEST[ 'hrw_section' ] ) ? wp_unslash( $_REQUEST[ 'hrw_section' ] ) : key( $menus ) ;

            if ( ! isset( $_REQUEST[ 'hrw_nonce' ] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST[ 'hrw_nonce' ] ) , 'hrw-' . HRW_Wallet_User::get_user_id() ) ) {
                $hrw_current_submenu = key( $menus ) ;
            }

            $hrw_current_menu = $hrw_current_submenu ;

            foreach ( $menus as $menu_key => $menu ) {
                $sub_menus = apply_filters( 'hrw_frontend_dashboard_' . $menu_key . '_submenus' , array () ) ;

                if ( ! hrw_check_is_array( $sub_menus ) )
                    continue ;

                if ( array_key_exists( $hrw_current_submenu , $sub_menus ) ) {
                    $hrw_current_menu = $menu_key ;
                } elseif ( $hrw_current_submenu == $menu_key ) {
                    $hrw_current_menu    = $menu_key ;
                    $hrw_current_submenu = key( $sub_menus ) ;
                }
            }
        }

        /**
         * Render Dashboard Navigation
         */
        public static function render_dashboard_navigation() {

            hrw_get_template( 'dashboard/navigation.php' ) ;
        }

        /**
         * Render Pagination
         */
        public static function render_pagination( $pagination ) {

            $default_args = array (
                'page_count'      => 1 ,
                'current_page'    => 1 ,
                'next_page_count' => 2 ,
                    ) ;

            $pagination = wp_parse_args( $pagination , $default_args ) ;

            if ( $pagination[ 'page_count' ] <= 1 )
                return ;

            hrw_get_template( 'pagination.php' , false , $pagination ) ;
        }

        /**
         * Render Dashboard Menu content
         */
        public static function render_dashboard_menu_content() {

            hrw_get_template( 'dashboard/menu-content.php' ) ;
        }

        /*
         * Get current page permalink
         */

        public static function get_current_page_permalink() {

            $permalink = HRW_PROTOCOL . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] ;

            return self::remove_query_args( $permalink ) ;
        }

        /*
         * Remove Query arguments
         */

        public static function remove_query_args( $permalink ) {

            return esc_url( remove_query_arg( self::get_remove_query_args() , $permalink ) ) ;
        }

        /*
         * Remove Query arguments
         */

        public static function get_remove_query_args() {
            return apply_filters( 'hrw_dashboard_remove_query_args' , array ( 'hrw_nonce' , 'page_no' ) ) ;
        }

        /*
         * Get current page url
         */

        public static function get_current_page_url() {
            global $hrw_current_submenu ;

            $query_nonce = wp_create_nonce( 'hrw-' . HRW_Wallet_User::get_user_id() ) ;
            $permalink   = self::get_current_page_permalink() ;

            return esc_url_raw( add_query_arg( array ( 'hrw_section' => $hrw_current_submenu , 'hrw_nonce' => $query_nonce ) , $permalink ) ) ;
        }

        /*
         * prepare menu link
         */

        public static function prepare_menu_url( $action = false , $extra_query_args = array () ) {
            global $hrw_current_submenu ;

            $query_nonce = wp_create_nonce( 'hrw-' . HRW_Wallet_User::get_user_id() ) ;
            $permalink   = self::get_current_page_permalink() ;

            if ( ! $action )
                $action = $hrw_current_submenu ;

            if ( $action == '' )
                return $permalink ;

            $query_args = array_merge( $extra_query_args , array ( 'hrw_section' => $action , 'hrw_nonce' => $query_nonce ) ) ;

            return esc_url_raw( add_query_arg( $query_args , $permalink ) ) ;
        }

        /*
         * Display Overview menu content
         */

        public static function render_overview() {
            hrw_get_template( 'dashboard/wallet-balance.php' , false ) ;
        }

        /*
         * Display Activity menu content
         */

        public static function render_activity() {
            $per_page         = 5 ;
            $current_page     = self::get_current_page_number() ;
            $data_args = array('transaction_logs' => '');
            
            if ( HRW_Wallet_User::get_wallet_id() ) {
                $default_args = array (
                    'post_type'      => HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ,
                    'post_status'    => hrw_get_transaction_log_statuses() ,
                    'order'          => 'DESC' ,
                    'post_parent'    => HRW_Wallet_User::get_wallet_id() ,
                    'fields'         => 'ids' ,
                    'posts_per_page' => '-1'
                        ) ;
                
                /* Calculate Page Count */
                $overall_count                    = get_posts( $default_args ) ;
                $page_count                       = ceil( count( $overall_count ) / $per_page ) ;
                
                $default_args[ 'offset' ]         = ($current_page - 1) * $per_page ;
                $default_args[ 'posts_per_page' ] = $per_page ;
                
                $data_args = array (
                    'transaction_logs' => get_posts( $default_args ) ,
                    'serial_number'    => ( $current_page * $per_page ) - $per_page + 1 ,
                    'pagination'       => array (
                        'page_count'      => $page_count ,
                        'current_page'    => $current_page ,
                        'next_page_count' => (($current_page + 1) > ($page_count - 1)) ? ($current_page) : ($current_page + 1) ,
                    ) ) ;
                
            }
            
            hrw_get_template( 'transaction-log-details.php' , false , $data_args ) ;
        }

        /*
         * Display Top-up menu content
         */

        public static function render_topup() {
            HRW_Topup_Handler::render_form() ;
        }

        /*
         * Display Profile menu content
         */

        public static function render_profile() {
            hrw_get_template( 'dashboard/profile.php' , false ) ;
        }

        /**
         * Get current page number
         */
        public static function get_current_page_number() {

            return isset( $_REQUEST[ 'page_no' ] ) && absint( $_REQUEST[ 'page_no' ] ) ? absint( $_REQUEST[ 'page_no' ] ) : 1 ;
        }

    }

    HRW_Dashboard::init() ;
}
