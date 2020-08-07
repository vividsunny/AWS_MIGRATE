<?php

/*
 * Admin Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( ! class_exists( 'HRW_Admin_Ajax' ) ) {

    /**
     * HRW_Admin_Ajax Class
     */
    class HRW_Admin_Ajax {

        /**
         * HRW_Admin_Ajax Class initialization
         */
        public static function init() {

            $actions = array(
                'product_search'       => false ,
                'customers_search'     => false ,
                'toggle_module'        => false ,
                'toggle_notifications' => false ,
                'create_topup_product' => false ,
                'wallet_credit_debit'  => false ,
                    ) ;

            foreach ( $actions as $action => $nopriv ) {
                add_action( 'wp_ajax_hrw_' . $action , array( __CLASS__ , $action ) ) ;

                if ( $nopriv )
                    add_action( 'wp_ajax_nopriv_hrw_' . $action , array( __CLASS__ , $action ) ) ;
            }
        }

        /**
         * Product search
         */
        public static function product_search() {
            check_ajax_referer( 'hrw-search-nonce' , 'hrw_security' ) ;

            try {
                $term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

                if ( empty( $term ) )
                    throw new exception( esc_html__( 'No Products found' , HRW_LOCALE ) ) ;

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

        public static function customers_search() {
            check_ajax_referer( 'hrw-search-nonce' , 'hrw_security' ) ;

            try {
                $term = isset( $_GET[ 'term' ] ) ? ( string ) wp_unslash( $_GET[ 'term' ] ) : '' ;

                if ( empty( $term ) )
                    throw new exception( esc_html__( 'No Customer found' , HRW_LOCALE ) ) ;

                $include       = isset( $_GET[ 'include' ] ) ? wp_unslash( $_GET[ 'include' ] ) : array() ;
                $include_roles = isset( $_GET[ 'include_roles' ] ) ? wp_unslash( $_GET[ 'include_roles' ] ) : array() ;

                $exclude       = isset( $_GET[ 'exclude' ] ) ? wp_unslash( $_GET[ 'exclude' ] ) : array() ;
                $exclude_roles = isset( $_GET[ 'exclude_roles' ] ) ? wp_unslash( $_GET[ 'exclude_roles' ] ) : array() ;

                $found_customers = array() ;
                $customers_query = new WP_User_Query( array(
                    'fields'         => 'all' ,
                    'orderby'        => 'display_name' ,
                    'search'         => '*' . $term . '*' ,
                    'include'        => $include ,
                    'role__in'       => $include_roles ,
                    'exclude'        => $exclude ,
                    'role__not_in'   => $exclude_roles ,
                    'search_columns' => array( 'ID' , 'user_login' , 'user_email' , 'user_nicename' )
                        ) ) ;
                $customers       = $customers_query->get_results() ;

                if ( hrw_check_is_array( $customers ) ) {
                    foreach ( $customers as $customer ) {
                        $found_customers[ $customer->ID ] = $customer->display_name . ' (#' . $customer->ID . ' &ndash; ' . sanitize_email( $customer->user_email ) . ')' ;
                    }
                }

                wp_send_json( $found_customers ) ;
            } catch ( Exception $ex ) {
                wp_die() ;
            }
        }

        /**
         * Toggle Module
         */
        public static function toggle_module() {

            check_ajax_referer( 'hrw-module-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'module_name' ] ) )
                    throw new exception( __( 'Invalid Request' , HRW_LOCALE ) ) ;

                // return if current user not have permission to disable / enable Notification
                if ( ! current_user_can( 'edit_posts' ) )
                    throw new exception( esc_html__( "You don't have permission to do this action" , HRW_LOCALE ) ) ;

                $module_object = HRW_Module_Instances::get_module_by_id( hrw_sanitize_text_field( $_REQUEST[ 'module_name' ] ) ) ;
                if ( is_object( $module_object ) ) {
                    $value = (hrw_sanitize_text_field( $_REQUEST[ 'enabled' ] ) == 'true') ? 'yes' : 'no' ;
                    $module_object->update_option( 'enabled' , $value ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Toggle Notifications
         */
        public static function toggle_notifications() {

            check_ajax_referer( 'hrw-notification-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'notification_name' ] ) )
                    throw new exception( __( 'Invalid Request' , HRW_LOCALE ) ) ;

                // return if current user not have permission to disable / enable Notification
                if ( ! current_user_can( 'edit_posts' ) )
                    throw new exception( esc_html__( "You don't have permission to do this action" , HRW_LOCALE ) ) ;

                $notification_object = HRW_Notification_Instances::get_notification_by_id( hrw_sanitize_text_field( $_REQUEST[ 'notification_name' ] ) ) ;
                if ( is_object( $notification_object ) ) {
                    $value = (hrw_sanitize_text_field( $_REQUEST[ 'enabled' ] ) == 'true') ? 'yes' : 'no' ;
                    $notification_object->update_option( 'enabled' , $value ) ;
                }

                wp_send_json_success() ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Top-up Product Creation
         */
        public static function create_topup_product() {

            check_ajax_referer( 'hrw-wallet-product-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'wallet_product_name' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                // return if current user not have permission to disable / enable Notification
                if ( ! current_user_can( 'edit_posts' ) )
                    throw new exception( esc_html__( "You don't have permission to do this action" , HRW_LOCALE ) ) ;

                $product_id = hrw_create_new_wallet_product( hrw_sanitize_text_field( $_POST[ 'wallet_product_name' ] ) ) ;

                update_option( 'hrw_general_topup_product_type' , '2' ) ;
                update_option( 'hrw_general_topup_product_id' , array( $product_id ) ) ;
                update_post_meta( $product_id , 'hrw_topup_product' , 'yes' ) ;

                wp_send_json_success( array( 'content' => 'success' ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /**
         * Top-up Product Creation
         */
        public static function wallet_credit_debit() {
            check_ajax_referer( 'hrw-wallet-credit-debit-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                if ( ! isset( $_REQUEST[ 'wallet_id' ] ) || empty( $_REQUEST[ 'wallet_id' ] ) )
                    throw new exception( esc_html__( 'Please select a User' , HRW_LOCALE ) ) ;

                if ( ! isset( $_REQUEST[ 'amount' ] ) || empty( $_REQUEST[ 'amount' ] ) )
                    throw new exception( esc_html__( 'Please enter some funds' , HRW_LOCALE ) ) ;

                // return if current user not have permission to disable / enable Notification
                if ( ! current_user_can( 'edit_posts' ) )
                    throw new exception( esc_html__( "You don't have permission to do this action" , HRW_LOCALE ) ) ;

                $msg  = '' ;
                $args = array(
                    'user_id' => absint( $_REQUEST[ 'wallet_id' ] ) ,
                    'amount'  => hrw_sanitize_text_field( $_REQUEST[ 'amount' ] ) ,
                    'event'   => hrw_sanitize_text_field( $_REQUEST[ 'event' ] )
                        ) ;

                if ( $_REQUEST[ 'type' ] == '1' ) {
                    HRW_Credit_Debit_Handler::credit_amount_to_wallet( $args ) ;
                    $msg = esc_html__( 'Credited Successfully' , HRW_VERSION ) ;
                }

                if ( $_REQUEST[ 'type' ] == '2' ) {
                    HRW_Credit_Debit_Handler::debit_amount_from_wallet( $args ) ;
                    $msg = esc_html__( 'Debited Successfully' , HRW_VERSION ) ;
                }

                wp_send_json_success( array( 'msg' => $msg ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

    }

    HRW_Admin_Ajax::init() ;
}