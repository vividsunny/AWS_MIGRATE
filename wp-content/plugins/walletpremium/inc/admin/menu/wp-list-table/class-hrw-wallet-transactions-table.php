<?php

/**
 * Wallet Transactions Log Post Table
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists( 'HRW_User_Transaction_Table' ) ) {

    /**
     * HRW_User_Transaction_Table Class.
     * */
    class HRW_User_Transaction_Table extends WP_List_Table {

        /**
         * Total Count of Table
         * */
        private $total_items ;

        /**
         * Per page count
         * */
        private $perpage ;

        /**
         * Offset
         * */
        private $offset ;

        /**
         * Order BY
         * */
        private $orderby = 'ORDER BY id DESC' ;

        /**
         * Post type
         * */
        private $post_type = HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE ;

        /**
         * Base URL
         * */
        private $base_url ;

        /**
         * Current URL
         * */
        private $current_url ;

        /**
         * Plugin slug.
         */
        protected $plugin_slug = 'hrw' ;

        /**
         * Prepare the table Data to display table based on pagination.
         * */
        public function prepare_items() {
            $this->base_url = add_query_arg( array( 'post' => $_GET[ 'post' ] , 'action' => 'edit' ) , admin_url() ) ;

            $this->prepare_current_url() ;
            $this->get_perpage_count() ;
            $this->get_current_pagenum() ;
            $this->get_current_page_items() ;
            $this->prepare_pagination_args() ;
            //display header columns
            $this->prepare_column_headers() ;
        }

        /**
         * get per page count
         * */
        private function get_perpage_count() {
            $this->perpage = 5 ;
        }

        /**
         * Prepare pagination
         * */
        private function prepare_pagination_args() {

            $this->set_pagination_args( array(
                'total_items' => $this->total_items ,
                'per_page'    => $this->perpage
            ) ) ;
        }

        /**
         * get current page number
         * */
        private function get_current_pagenum() {
            $this->offset = 5 * ($this->get_pagenum() - 1) ;
        }

        /**
         * Prepare header columns
         * */
        private function prepare_column_headers() {
            $columns               = $this->get_columns() ;
            $hidden                = $this->get_hidden_columns() ;
            $sortable              = $this->get_sortable_columns() ;
            $this->_column_headers = array( $columns , $hidden , $sortable ) ;
        }

        /**
         * Initialize the columns
         * */
        public function get_columns() {
            $columns = array(
                'hrw_event'  => esc_html__( 'Event' , HRW_LOCALE ) ,
                'hrw_amount' => esc_html__( 'Amount' , HRW_LOCALE ) ,
                'hrw_status'  => esc_html__( 'Status' , HRW_LOCALE ) ,
                'hrw_total'  => esc_html__( 'Total' , HRW_LOCALE ) ,
                'hrw_date'   => esc_html__( 'Date' , HRW_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the hidden columns
         * */
        public function get_hidden_columns() {
            return array() ;
        }

        /**
         * Prepare sortable columns
         * */
        protected function get_sortable_columns() {
            return array(
                'hrw_amount' => array( 'hrw_amount' , true ) ,
                'hrw_status' => array( 'hrw_status' , true ) ,
                'hrw_total'  => array( 'hrw_total' , true ) ,
                'hrw_date'   => array( 'hrw_date' , true ) ,
                    ) ;
        }

        /**
         * get current url
         * */
        private function prepare_current_url() {

            $pagenum         = $this->get_pagenum() ;
            $args[ 'paged' ] = $pagenum ;
            $url             = add_query_arg( $args , $this->base_url ) ;

            $this->current_url = $url ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'hrw_event':
                    return $item->get_event() ;
                    break ;
                case 'hrw_amount':
                    return hrw_price( $item->get_amount() ) ;
                    break ;
                case 'hrw_status':
                    return hrw_display_status( $item->get_status() ) ;
                    break ;
                case 'hrw_total':
                    return hrw_price( $item->get_total() ) ;
                    break ;
                case 'hrw_date':
                    return $item->get_formatted_date() ;
                    break ;
            }
        }

        /**
         * Get Current Page Items
         * */
        private function get_current_page_items() {
            global $wpdb , $post ;

            $status = isset( $_GET[ 'status' ] ) ? ' IN("' . $_GET[ 'status' ] . '")' : ' NOT IN("trash")' ;
            $where  = " where post_type='" . $this->post_type . "' and post_parent='" . $post->ID . "' and post_status" . $status ;

            $where   = apply_filters( $this->table_slug . '_query_where' , $where ) ;
            $limit   = apply_filters( $this->table_slug . '_query_limit' , $this->perpage ) ;
            $offset  = apply_filters( $this->table_slug . '_query_offset' , $this->offset ) ;
            $orderby = apply_filters( $this->table_slug . '_query_orderby' , $this->orderby ) ;

            $count_items       = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " $where $orderby" ) ;
            $this->total_items = count( $count_items ) ;

            $prepare_query = $wpdb->prepare( "SELECT ID FROM " . $wpdb->posts . " $where $orderby LIMIT %d,%d" , $offset , $limit ) ;
            $items         = $wpdb->get_results( $prepare_query , ARRAY_A ) ;

            $this->prepare_item_object( $items ) ;
        }

        /**
         * Prepare item Object
         * */
        private function prepare_item_object( $items ) {
            $prepare_items = array() ;
            if ( hrw_check_is_array( $items ) ) {
                foreach ( $items as $item ) {
                    $prepare_items[] = new HRW_Transaction_Log( $item[ 'ID' ] ) ;
                }
            }

            $this->items = $prepare_items ;
        }

    }

}