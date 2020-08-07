<?php

/**
 * Register Custom Post Status.
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Register_Post_Status' ) ) {

    /**
     * HRW_Register_Post_Status Class.
     */
    class HRW_Register_Post_Status {

        /**
         * Class initialization.
         */
        public static function init() {
            add_action ( 'init' , array ( __CLASS__ , 'register_custom_post_status' ) ) ;
        }

        public static function register_custom_post_status() {
            $custom_post_statuses = array (
                'hrw_active'   => array ( 'HRW_Register_Post_Status' , 'active_post_status_args' ) ,
                'hrw_expired'  => array ( 'HRW_Register_Post_Status' , 'expired_post_status_args' ) ,
                'hrw_blocked'  => array ( 'HRW_Register_Post_Status' , 'blocked_post_status_args' ) ,
                'hrw_credit'   => array ( 'HRW_Register_Post_Status' , 'credit_post_status_args' ) ,
                'hrw_debit'    => array ( 'HRW_Register_Post_Status' , 'debit_post_status_args' ) ,            
                    ) ;

            $custom_post_statuses = apply_filters ( 'hrw_add_custom_post_status' , $custom_post_statuses ) ;

            // return if no post status to register
            if ( ! hrw_check_is_array ( $custom_post_statuses ) )
                return ;

            foreach ( $custom_post_statuses as $post_status => $args_function ) {

                $args = call_user_func_array ( $args_function , array () ) ;

                register_post_status ( $post_status , $args ) ;
            }
        }

        public static function active_post_status_args() {
            $args = apply_filters ( 'hrw_active_post_status_args' , array (
                'label'                     => _x ( 'Active' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Active <span class="count">(%s)</span>' , 'Active <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function expired_post_status_args() {
            $args = apply_filters ( 'hrw_expired_post_status_args' , array (
                'label'                     => _x ( 'Expired' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Expired <span class="count">(%s)</span>' , 'Expired <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function blocked_post_status_args() {
            $args = apply_filters ( 'hrw_blocked_post_status_args' , array (
                'label'                     => _x ( 'Blocked' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Blocked <span class="count">(%s)</span>' , 'Blocked <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function credit_post_status_args() {
            $args = apply_filters ( 'hrw_active_post_status_args' , array (
                'label'                     => _x ( 'Credit' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Credit <span class="count">(%s)</span>' , 'Credit <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function debit_post_status_args() {
            $args = apply_filters ( 'hrw_active_post_status_args' , array (
                'label'                     => _x ( 'Debit' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Debit <span class="count">(%s)</span>' , 'Debit <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

    }

    HRW_Register_Post_Status::init () ;
}