<?php

/**
 * Transaction Log Post Type
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Transaction_Log_Post_Type' ) ) {

    /**
     * HRW_Transaction_Log_Post_Type Class.
     */
    class HRW_Transaction_Log_Post_Type {
        /*
         * Object
         */

        private static $object ;
        /*
         * Post type
         */
        private static $post_type = HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ;

        /**
         * Class initialization.
         */
        public static function init() {
            add_filter( 'admin_body_class' , array( __CLASS__ , 'custom_class' ) , 10 , 1 ) ;
            add_filter( 'post_row_actions' , array( __CLASS__ , 'handle_post_row_actions' ) , 10 , 2 ) ;
            add_filter( 'disable_months_dropdown' , array( __CLASS__ , 'remove_month_dropdown' ) , 10 , 2 ) ;
            add_action( 'views_edit-' . self::$post_type , array( __CLASS__ , 'remove_views' ) ) ;
            add_filter( 'bulk_actions-edit-' . self::$post_type , array( __CLASS__ , 'handle_bulk_actions' ) , 10 , 1 ) ;
            //Table Join
            add_action ( 'posts_join' , array ( __CLASS__ , 'table_join_query' ) , 10 , 2 ) ;
            //display result column value in unique
            add_action ( 'posts_distinct', array(__CLASS__, 'distinct_post'), 10, 2);
            //search
            add_filter ( 'posts_search' , array( __CLASS__, 'search_action' ) );
            //Adding Filters option
            add_action ( 'restrict_manage_posts' , array ( __CLASS__ , 'filter_options' ) ) ;
            //Perform Filters actions
            add_action ( 'posts_where' , array ( __CLASS__ , 'filter_action' ) , 10 , 2 ) ;
            //define column header
            add_filter ( 'manage_' . self::$post_type . '_posts_columns' , array( __CLASS__ , 'define_columns' ) ) ;
            //display column value
            add_action ( 'manage_' . self::$post_type . '_posts_custom_column' , array( __CLASS__ , 'render_columns' ) , 10 , 2 ) ;
            //define sortable column
            add_filter ( 'manage_edit-'.self::$post_type .'_sortable_columns' , array ( __CLASS__ , 'sortable_columns' ) ) ;
            add_filter ( 'parse_query', array(__CLASS__, 'orderby_filter_query'));
            add_action ( 'posts_orderby', array(__CLASS__, 'orderby_columns'), 10, 2);
        }
 /*
         * Add custom class
         */

        public static function custom_class( $class ) {
            global $post ;

            if ( ! is_object( $post ) )
                return $class ;

            if ( $post->post_type == self::$post_type )
                return $class . ' hrw_body_content' ;

            return $class ;
        }

        /*
         * Handle Row Actions
         */

        public static function handle_post_row_actions( $actions , $post ) {

            if ( $post->post_type != self::$post_type )
                return $actions ;

            unset( $actions[ 'view' ] ) ; // Remove View
            unset( $actions[ 'edit' ] ) ; // Remove Edit
            unset( $actions[ 'inline hide-if-no-js' ] ) ; // Remove Quick Edit

            return $actions ;
        }

        /*
         * Remove views
         */

        public static function remove_views( $views ) {

            unset( $views[ 'mine' ] ) ;

            return $views ;
        }

        /**
         * Remove month dropdown 
         */
        public static function remove_month_dropdown( $bool , $post_type ) {
            return $post_type == self::$post_type ? true : $bool ;
        }

        /*
         * Handle Bulk Actions
         */

        public static function handle_bulk_actions( $actions ) {
            global $post ;
            if ( $post->post_type != self::$post_type )
                return $actions ;

            unset( $actions[ 'edit' ] ) ; // Remove Edit

            return $actions ;
        }

        /**
         * Define custom columns
         */
        public static function define_columns( $columns ) {

            if ( ! hrw_check_is_array( $columns ) ) {
                $columns = array() ;
            }

            $columns = array(
                'cb'          => '<input type="checkbox" />' , //Render a checkbox instead of text
                'hrw_user_id' => esc_html__( 'Username' , HRW_LOCALE ) ,
                'hrw_event'   => esc_html__( 'Event' , HRW_LOCALE ) ,
                'hrw_amount'  => esc_html__( 'Amount' , HRW_LOCALE ) ,
                'hrw_status'  => esc_html__( 'Status' , HRW_LOCALE ) ,
                'hrw_total'   => esc_html__( 'Available Balance' , HRW_LOCALE ) ,
                'hrw_date'    => esc_html__( 'Date' , HRW_LOCALE )
                    ) ;

            return $columns ;
        }

        /*
         * Remove views
         */

        public static function prepare_row_data( $postid ) {

            if ( empty( self::$object ) || self::$object->get_id() != $postid ) {
                self::$object = hrw_get_transaction_log( $postid ) ;
            }

            return self::$object ;
        }

        /**
         * Render each column
         */
        public static function render_columns( $column , $postid ) {

            self::prepare_row_data( $postid ) ;
            $function = 'render_' . $column . '_cloumn' ;

            if ( method_exists( __CLASS__ , $function ) ) {
                self::$function() ;
            }
        }

        /**
         * Render User Name column
         */
        public static function render_hrw_user_id_cloumn() {
            echo self::$object->get_user()->display_name ;
        }

        /**
         * Render Event column
         */
        public static function render_hrw_event_cloumn() {
            echo self::$object->get_event() ;
        }

        /**
         * Render Amount column
         */
        public static function render_hrw_amount_cloumn() {
            echo hrw_price( self::$object->get_amount() ) ;
        }
        
        /**
         * Render Status column
         */
        public static function render_hrw_status_cloumn() {
            echo hrw_display_status(self::$object->get_status()) ;
        }

        /**
         * Render Total column
         */
        public static function render_hrw_total_cloumn() {
            echo hrw_price( self::$object->get_total() ) ;
        }

        /**
         * Render Date column
         */
        public static function render_hrw_date_cloumn() {
            echo self::$object->get_formatted_date() ;
        }

        /**
         * Search Functionality
         */
        public static function search_action( $where ) {
            global $wpdb , $wp_query ;

            if ( ! is_search() || ! isset( $_REQUEST[ 's' ] ) || $wp_query->query_vars[ 'post_type' ] != self::$post_type )
                return $where ;

            $search_ids = array() ;
            $terms      = explode( ',' , hrw_sanitize_text_field( $_REQUEST[ 's' ] ) ) ;

            foreach ( $terms as $term ) {
                $term       = $wpdb->esc_like( wc_clean( $term ) ) ;
                $post_query = new HRW_Query( $wpdb->posts , 'p' ) ;
                $post_query->select( 'DISTINCT `pm`.meta_value' )
                        ->leftJoin( $wpdb->postmeta , 'pm' , '`p`.`ID` = `pm`.`post_id`' )
                        ->where( '`p`.post_type' , self::$post_type )
                        ->whereIn( '`p`.post_status' , array('hrw_credit', 'hrw_debit') )
                        ->where( '`pm`.meta_key' , 'hrw_user_id' )
                        ->orderBy( '`pm`.meta_value' ) ;

                $search_ids = $post_query->fetchCol( 'meta_value' ) ;

                $post_query = new HRW_Query( $wpdb->postmeta , 'pm' ) ;
                $post_query->select( 'DISTINCT `pm`.post_id' )
                        ->where( '`pm`.meta_key' , 'hrw_user_id' )
                        ->leftJoin( $wpdb->users , 'u' , '`u`.ID = `pm`.meta_value' )
                        ->leftJoin( $wpdb->usermeta , 'um' , '`pm`.meta_value = `um`.user_id' )
                        ->whereIn( '`u`.ID' , $search_ids )
                        ->whereIn( '`um`.meta_key' , array( 'first_name', 'last_name', 'billing_email' , 'nickname' , 'display_name' ,'hrw_status' ) )
                        ->wherelike( '`um`.meta_value' , '%' . $term . '%' )
                        ->orderBy( '`pm`.post_id' ) ;

                $search_ids = $post_query->fetchCol( 'post_id' ) ;
            }

            $search_ids = array_filter( array_unique( $search_ids ) ) ;

            if ( sizeof( $search_ids ) > 0 )
                $where = str_replace( 'AND (((' , "AND ( ({$wpdb->posts}.ID IN (" . implode( ',' , $search_ids ) . ")) OR ((" , $where ) ;

            return $where ;
        }

        /**
         * Table join
         */
        public static function table_join_query( $join ) {
            global $wpdb, $wp_query ;

            if ( is_admin() && ! isset( $_GET[ 'post' ] ) && isset( $wp_query->query_vars[ 'post_type' ] ) && ( $wp_query->query_vars[ 'post_type' ] == self::$post_type ) ) {
                if ( ( isset( $_REQUEST[ 's' ] ) || isset( $_REQUEST[ 'filter_action' ] ) ) && ( $_REQUEST[ 'post_type' ] == self::$post_type ) ) {
                    global $wpdb ;
                    $join .= " INNER JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)" ;
                }
            }

            return $join ;
        }

        /**
         * Define Sortable columns
         */
        public static function sortable_columns( $columns ) {

            $array = array(
                'hrw_event'  => array('hrw_event', true) ,
                'hrw_amount' => array('hrw_amount', true) ,
                'hrw_status' => array('hrw_status', true) ,
                'hrw_total'  => array('hrw_total', true) ,
                'hrw_date'   => array('hrw_date', true)
                    ) ;
            return wp_parse_args( $array , $columns ) ;
        }

        /**
         * Set Order by column via data-type
         */
        public static function orderby_columns( $order_by , $wp_query ) {
            global $wpdb ;

            if ( isset( $wp_query->query[ 'post_type' ] ) && $wp_query->query[ 'post_type' ] == self::$post_type ) {
                if ( ! isset( $_REQUEST[ 'order' ] ) && ! isset( $_REQUEST[ 'orderby' ] ) ) {
                    $order_by = "{$wpdb->posts}.ID " . 'DESC' ;
                } else {
                    $decimal_column = array( 'hrw_credit' , 'hrw_debit' , 'hrw_total' ) ;
                    if ( in_array( hrw_sanitize_text_field( $_REQUEST[ 'orderby' ] ) , $decimal_column ) ) {
                        $order_by = "CAST({$wpdb->postmeta}.meta_value AS DECIMAL) " . hrw_sanitize_text_field( $_REQUEST[ 'order' ] ) ;
                    } elseif ( hrw_sanitize_text_field( $_REQUEST[ 'orderby' ] ) == "post_status" ) {
                        $order_by = "{$wpdb->posts}.post_status " . hrw_sanitize_text_field( $_REQUEST[ 'order' ] ) ;
                    }
                }
            }

            return $order_by ;
        }

        /**
         *  Define order-by filter query based column type
         */
        public static function orderby_filter_query($query) {

            if ( isset( $_REQUEST[ 'post_type' ] ) && hrw_sanitize_text_field( $_REQUEST[ 'post_type' ] ) == self::$post_type && self::$post_type == $query->query[ 'post_type' ] && isset( $_GET[ 'orderby' ] ) ) {
                $excerpt_array                   = array( 'ID' , 'post_status' ) ;
                if ( ! in_array( hrw_sanitize_text_field( $_GET[ 'orderby' ] ) , $excerpt_array ) )
                    $query->query_vars[ 'meta_key' ] = hrw_sanitize_text_field( $_GET[ 'orderby' ] ) ;
            }
        }

        /**
         *  Display result action in unique based
         */
        public static function distinct_post( $distinct ) {

            if ( ( isset( $_REQUEST[ 'filter_action' ] ) || isset( $_REQUEST[ 'orderby' ] ) || isset( $_REQUEST[ 's' ] ) ) && hrw_sanitize_text_field( $_REQUEST[ 'post_type' ] ) == self::$post_type )
                $distinct .= empty( $distinct ) ? 'DISTINCT' : $distinct ;

            return $distinct ;
        }

        /**
         * Filter field placing
         */
        public static function filter_options( $post_type ) {
            
            if ( $post_type ==  self::$post_type) {
                $from_date = isset ( $_REQUEST[ 'hrw_transaction_from_date' ] ) ? hrw_sanitize_text_field($_REQUEST[ 'hrw_transaction_from_date' ]) : null ;
                $to_date   = isset ( $_REQUEST[ 'hrw_transaction_to_date' ] ) ? hrw_sanitize_text_field($_REQUEST[ 'hrw_transaction_to_date' ]) : null ;
            
                if ( !$to_date &&  isset ( $_REQUEST[ 'filter_action' ] ) )
                    $to_date = date ( 'Y-m-d' ) ;
                
                $args = array(
                        'id'            => 'hrw_transaction_from_date' ,
                        'wp_zone'       => false ,
                        'placeholder'   => esc_html__('From Date', HRW_LOCALE),
                        'value'         => $from_date,
                            ) ;
                hrw_get_datepicker_html( $args ) ;
                
                $args = array(
                        'id'            => 'hrw_transaction_to_date' ,
                        'wp_zone'       => false ,
                        'placeholder'   => esc_html__('To Date', HRW_LOCALE),
                        'value'         => $to_date,
                            ) ;
                hrw_get_datepicker_html( $args ) ;
            }
        }
        
        /**
         * Filter action
         */
        public static function filter_action( $where , $wp_query ) {
            global $wpdb ;
            
            if ( isset ( $_REQUEST[ 'filter_action' ] ) && isset ( $_REQUEST[ 'post_type' ] ) ) {
                $from_date = isset ( $_REQUEST[ 'hrw_transaction_from_date' ] ) ? hrw_sanitize_text_field($_REQUEST[ 'hrw_transaction_from_date' ]). " 00:00:00" : null ;
                $to_date   = isset ( $_REQUEST[ 'hrw_transaction_to_date' ] ) ? hrw_sanitize_text_field($_REQUEST[ 'hrw_transaction_to_date' ]). " 23:59:59" : date ( 'Y-m-d' ) . " 23:59:59" ;
                $converted_from_date = strtotime ( $from_date ) ;
                $converted_to_date   = strtotime ( $to_date ) ;
                
                if ( $wp_query->query_vars[ 'post_type' ] ==  self::$post_type ) {
                    if ( $from_date ) {
                        $where .= " AND $wpdb->postmeta.meta_key = 'hrw_date' AND $wpdb->postmeta.meta_value > '$converted_from_date' " ;
                    }
                    $where .= " AND $wpdb->postmeta.meta_key = 'hrw_date' AND $wpdb->postmeta.meta_value <= '$converted_to_date'" ;
                }
            }

            return $where ;
        }

    }

    HRW_Transaction_Log_Post_Type::init() ;
}