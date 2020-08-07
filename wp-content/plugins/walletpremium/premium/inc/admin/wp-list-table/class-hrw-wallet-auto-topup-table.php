<?php

/**
 * Auto Topup Post Table
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if( ! class_exists( 'HRW_Auto_Topup_Table' ) ) {

    /**
     * HRW_Auto_Topup_Table Class.
     * */
    class HRW_Auto_Topup_Table extends WP_List_Table {

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
        private $post_type = 'hrw_auto_topup' ;

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
            $this->base_url = add_query_arg( array( 'page' => 'hrw_settings' , 'tab' => 'modules' , 'section' => 'general' , 'subsection' => 'auto_topup' ) , admin_url( 'admin.php' ) ) ;

            add_filter( sanitize_key( $this->table_slug . '_query_orderby' ) , array( $this , 'query_orderby' ) ) ;

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
                'username'         => esc_html__( 'Username and email' , HRW_LOCALE ) ,
                'status'           => esc_html__( 'Status' , HRW_LOCALE ) ,
                'topup_amt'        => esc_html__( 'Auto Top-up Amount' , HRW_LOCALE ) ,
                'threshold_amt'    => esc_html__( 'Threshold Value' , HRW_LOCALE ) ,
                'last_order'       => esc_html__( 'Last Order' , HRW_LOCALE ) ,
                'last_charge_date' => esc_html__( 'Last Charge Date' , HRW_LOCALE ) ,
                'actions'          => esc_html__( 'Actions' , HRW_LOCALE ) ,
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
                'topup_amt'        => array( 'topup_amt' , false ) ,
                'threshold_amt'    => array( 'threshold_amt' , false ) ,
                'last_order'       => array( 'last_order' , false ) ,
                'last_charge_date' => array( 'last_charge_date' , false ) ,
                    ) ;
        }

        /**
         * get current url
         * */
        private function prepare_current_url() {
            //Build row actions
            if( isset( $_GET[ 'status' ] ) )
                $args[ 'status' ] = sanitize_title( $_GET[ 'status' ] ) ;

            $pagenum         = $this->get_pagenum() ;
            $args[ 'paged' ] = $pagenum ;
            $url             = add_query_arg( $args , $this->base_url ) ;

            $this->current_url = $url ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch( $column_name ) {
                case 'username':
                    $username = '' ;

                    if( $user = $item->get_user() ) {
                        $username = "<a href='user-edit.php?user_id='" . absint( $user->ID ) . "'>" . esc_html( $user->display_name ) . "</br>" . esc_html( $user->user_email ) . "</a>" ;
                    }
                    return $username ;
                    break ;
                case 'status':
                    return hrw_display_status( $item->get_status() ) ;
                    break ;
                case 'topup_amt':
                    return wc_price( $item->get_topup_amount() , array( 'currency' => $item->get_currency() ) ) ;
                    break ;
                case 'threshold_amt':
                    return wc_price( $item->get_threshold_amount() , array( 'currency' => $item->get_currency() ) ) ;
                    break ;
                case 'last_order':
                    return "<a href='" . admin_url( "post.php?post={$item->get_last_order()}&action=edit" ) . "'>#{$item->get_last_order()}</a>" ;
                    break ;
                case 'last_charge_date':
                    return $item->get_formatted_last_charge_date() ;
                    break ;
                case 'actions':
                    $actions = array() ;

                    if( 'hrw_cancelled' !== $item->get_status() ) {
                        $actions[ 'cancelled' ] = hrw_display_action( 'cancelled' , $item->get_id() , $this->current_url ) ;
                    } else {
                        $actions = array( '--' ) ;
                    }

                    end( $actions ) ;

                    $last_key = key( $actions ) ;
                    foreach( $actions as $key => $action ) {
                        echo $action ;

                        if( $last_key == $key )
                            break ;

                        echo ' | ' ;
                    }
                    break ;
            }
        }

        /**
         * Get Current Page Items
         * */
        private function get_current_page_items() {
            global $wpdb ;

            $status = isset( $_GET[ 'status' ] ) ? ' IN("' . sanitize_title( $_GET[ 'status' ] ) . '")' : ' NOT IN("trash")' ;

            if( ! empty( $_REQUEST[ 's' ] ) || ! empty( $_REQUEST[ 'orderby' ] ) ) {
                $where = " INNER JOIN " . $wpdb->postmeta . " pm ON ( pm.post_id = p.ID ) where post_type='" . $this->post_type . "' and post_status " . $status ;
            } else {
                $where = " where post_type='" . $this->post_type . "' and post_status " . $status ;
            }

            $where   = apply_filters( $this->table_slug . '_query_where' , $where ) ;
            $limit   = apply_filters( $this->table_slug . '_query_limit' , $this->perpage ) ;
            $offset  = apply_filters( $this->table_slug . '_query_offset' , $this->offset ) ;
            $orderby = apply_filters( $this->table_slug . '_query_orderby' , $this->orderby ) ;

            $count_items       = $wpdb->get_results( "SELECT DISTINCT ID FROM " . $wpdb->posts . " AS p $where $orderby" ) ;
            $this->total_items = count( $count_items ) ;

            $prepare_query = $wpdb->prepare( "SELECT DISTINCT ID FROM " . $wpdb->posts . " AS p $where $orderby LIMIT %d,%d" , $offset , $limit ) ;
            $items         = $wpdb->get_results( $prepare_query , ARRAY_A ) ;

            $this->prepare_item_object( $items ) ;
        }

        /**
         * Prepare item Object
         * */
        private function prepare_item_object( $items ) {
            $prepare_items = array() ;
            if( hrw_check_is_array( $items ) ) {
                foreach( $items as $item ) {
                    $prepare_items[] = hrw_get_wallet_auto_topup( $item[ 'ID' ] ) ;
                }
            }

            $this->items = $prepare_items ;
        }

        /**
         * Display the list of views available on this table.
         * */
        public function get_views() {
            $args        = array() ;
            $status_link = array() ;

            $status_link_array = array(
                ''              => esc_html__( 'All' , HRW_LOCALE ) ,
                'hrw_active'    => esc_html__( 'Active' , HRW_LOCALE ) ,
                'hrw_cancelled' => esc_html__( 'Cancelled' , HRW_LOCALE ) ,
                    ) ;

            foreach( $status_link_array as $status_name => $status_label ) {
                $status_count = $this->get_total_item_for_status( $status_name ) ;

                if( ! $status_count )
                    continue ;

                if( $status_name )
                    $args[ 'status' ] = $status_name ;

                $label                       = $status_label . ' (' . $status_count . ')' ;
                $class                       = (isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == $status_name ) ? 'current' : '' ;
                $class                       = ( ! isset( $_GET[ 'status' ] ) && '' == $status_name ) ? 'current' : $class ;
                $status_link[ $status_name ] = $this->get_edit_link( $args , $label , $class ) ;
            }

            return $status_link ;
        }

        /**
         * get total item for status
         * */
        private function get_total_item_for_status( $status = '' ) {
            global $wpdb ;
            $where  = "WHERE post_type='" . $this->post_type . "' and post_status " ;
            $status = ($status == '') ? "NOT IN('trash')" : "IN('" . $status . "')" ;

            $data = $wpdb->get_results( "SELECT ID FROM " . $wpdb->posts . " AS p $where $status" , ARRAY_A ) ;

            return count( $data ) ;
        }

        /**
         * Edit link for status
         * */
        private function get_edit_link( $args , $label , $class = '' ) {
            $url = add_query_arg( $args , $this->base_url ) ;

            $class_html = '' ;
            if( ! empty( $class ) ) {
                $class_html = sprintf(
                        ' class="%s"' , esc_attr( $class )
                        ) ;
            }

            return sprintf(
                    '<a href="%s"%s>%s</a>' , esc_url( $url ) , $class_html , $label
                    ) ;
        }

        /**
         * Sort
         * */
        public function query_orderby( $orderby ) {

            if( empty( $_REQUEST[ 'orderby' ] ) ) {
                return $orderby ;
            }

            $order = 'DESC' ;
            if( ! empty( $_REQUEST[ 'order' ] ) && is_string( $_REQUEST[ 'order' ] ) ) {
                if( 'ASC' === strtoupper( $_REQUEST[ 'order' ] ) ) {
                    $order = 'ASC' ;
                }
            }

            switch( $_REQUEST[ 'orderby' ] ) {
                case 'ID':
                    $orderby = " ORDER BY p.ID " . $order ;
                    break ;
                case 'last_order':
                    $orderby = " AND pm.meta_key='hrw_last_order' ORDER BY pm.meta_value " . $order ;
                    break ;
                case 'topup_amt':
                    $orderby = " AND pm.meta_key='hrw_topup_amount' ORDER BY pm.meta_value " . $order ;
                    break ;
                case 'threshold_amt':
                    $orderby = " AND pm.meta_key='hrw_threshold_amount' ORDER BY pm.meta_value " . $order ;
                    break ;
                case 'last_charge_date':
                    $orderby = " AND pm.meta_key='hrw_last_charge_date' ORDER BY pm.meta_value " . $order ;
                    break ;
                case 'date':
                    $orderby = " ORDER BY p.post_date " . $order ;
                    break ;
            }
            return $orderby ;
        }

    }

}