<?php

/**
 * Register Custom Post Status.
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRWP_Register_Post_Status' ) ) {

    /**
     * HRWP_Register_Post_Status Class.
     */
    class HRWP_Register_Post_Status {

        /**
         * Class initialization.
         */
        public static function init() {
            add_filter ( 'hrw_add_custom_post_status' , array ( __CLASS__ , 'register_custom_post_status' ) ) ;
        }

        public static function register_custom_post_status( $custom_post_statuses ) {

            $custom_post_statuses[ 'hrw_transfered' ]       = array ( 'HRWP_Register_Post_Status' , 'transfered_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_received' ]         = array ( 'HRWP_Register_Post_Status' , 'received_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_requested' ]        = array ( 'HRWP_Register_Post_Status' , 'requested_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_new_requested' ]    = array ( 'HRWP_Register_Post_Status' , 'new_requested_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_declined' ]         = array ( 'HRWP_Register_Post_Status' , 'declined_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_request_declined' ] = array ( 'HRWP_Register_Post_Status' , 'request_declined_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_request_cancel' ]   = array ( 'HRWP_Register_Post_Status' , 'request_cancelled_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_paid' ]             = array ( 'HRWP_Register_Post_Status' , 'paid_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_unpaid' ]           = array ( 'HRWP_Register_Post_Status' , 'unpaid_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_in_progress' ]      = array ( 'HRWP_Register_Post_Status' , 'in_progress_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_cancelled' ]        = array ( 'HRWP_Register_Post_Status' , 'cancelled_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_created' ]          = array ( 'HRWP_Register_Post_Status' , 'created_post_status_args' ) ;
            $custom_post_statuses[ 'hrw_redeemed' ]         = array ( 'HRWP_Register_Post_Status' , 'redeemed_post_status_args' ) ;

            return apply_filters ( 'hrw_add_premium_custom_post_status' , $custom_post_statuses ) ;
        }

        public static function paid_post_status_args() {
            $args = apply_filters ( 'hrw_paid_post_status_args' , array (
                'label'                     => _x ( 'Paid' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Paid <span class="count">(%s)</span>' , 'Paid <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function transfered_post_status_args() {
            $args = apply_filters ( 'hrw_transfered_post_status_args' , array (
                'label'                     => _x ( 'Transferred' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Transfered <span class="count">(%s)</span>' , 'Transfered <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function unpaid_post_status_args() {
            $args = apply_filters ( 'hrw_unpaid_post_status_args' , array (
                'label'                     => _x ( 'Unpaid' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Unpaid <span class="count">(%s)</span>' , 'Unpaid <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function new_requested_post_status_args() {
            $args = apply_filters ( 'hrw_new_requested_post_status_args' , array (
                'label'                     => _x ( 'New Request' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'New Request <span class="count">(%s)</span>' , 'New Request <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function requested_post_status_args() {
            $args = apply_filters ( 'hrw_requested_post_status_args' , array (
                'label'                     => _x ( 'Requested' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Requested <span class="count">(%s)</span>' , 'Requested <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function received_post_status_args() {
            $args = apply_filters ( 'hrw_received_post_status_args' , array (
                'label'                     => _x ( 'Received' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Received <span class="count">(%s)</span>' , 'Received <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function declined_post_status_args() {
            $args = apply_filters ( 'hrw_declined_post_status_args' , array (
                'label'                     => _x ( 'Declined' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Declined <span class="count">(%s)</span>' , 'Declined <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function request_declined_post_status_args() {
            $args = apply_filters ( 'hrw_request_declined_post_status_args' , array (
                'label'                     => _x ( 'Request Declined' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Request Declined <span class="count">(%s)</span>' , 'Request Declined <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function in_progress_post_status_args() {
            $args = apply_filters ( 'hrw_in_progress_post_status_args' , array (
                'label'                     => _x ( 'In-progress' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Inprogress <span class="count">(%s)</span>' , 'Inprogress <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function cancelled_post_status_args() {
            $args = apply_filters ( 'hrw_cancelled_post_status_args' , array (
                'label'                     => _x ( 'Cancelled' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Cancelled <span class="count">(%s)</span>' , 'Cancelled <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function request_cancelled_post_status_args() {
            $args = apply_filters ( 'hrw_request_cancel_post_status_args' , array (
                'label'                     => _x ( 'Request Cancelled' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Request Cancelled <span class="count">(%s)</span>' , 'Request Cancelled <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function created_post_status_args() {
            $args = apply_filters ( 'hrw_ceaated_post_status_args' , array (
                'label'                     => _x ( 'Created' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Created <span class="count">(%s)</span>' , 'Created <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

        public static function redeemed_post_status_args() {
            $args = apply_filters ( 'hrw_redeemed_post_status_args' , array (
                'label'                     => _x ( 'Redeemed' , HRW_LOCALE ) ,
                'public'                    => true ,
                'exclude_from_search'       => false ,
                'show_in_admin_all_list'    => true ,
                'show_in_admin_status_list' => true ,
                'label_count'               => _n_noop ( 'Redeemed <span class="count">(%s)</span>' , 'Redeemed <span class="count">(%s)</span>' ) ,
                    )
                    ) ;

            return $args ;
        }

    }

    HRWP_Register_Post_Status::init () ;
}