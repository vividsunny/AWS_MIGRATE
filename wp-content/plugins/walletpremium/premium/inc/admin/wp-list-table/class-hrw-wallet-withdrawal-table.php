<?php

/**
 * Wallet Withdrawal Post Table
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists( 'HRW_Wallet_Withdrawal_Table' ) ) {

    /**
     * HRW_Wallet_Withdrawal_Table Class.
     * */
    class HRW_Wallet_Withdrawal_Table extends WP_List_Table {

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
        private $post_type = HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ;

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

            $this->base_url = add_query_arg( array( 'page' => 'hrw_settings' , 'tab' => 'modules' , 'section' => 'general' , 'subsection' => 'wallet_withdrawal' ) , HRW_ADMIN_URL ) ;

            $this->prepare_current_url() ;
            $this->get_perpage_count() ;
            $this->process_bulk_action() ;
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
                'cb'                  => '<input type="checkbox" />' ,
                'hrw_id'              => esc_html__( 'Withdrawal ID' , HRW_LOCALE ) ,
                'hrw_username'        => esc_html__( 'Username' , HRW_LOCALE ) ,
                'hrw_email'           => esc_html__( 'Email' , HRW_LOCALE ) ,
                'hrw_withdraw_amount' => esc_html__( 'Withdraw Amount' , HRW_LOCALE ) ,
                'hrw_payment_method'  => esc_html__( 'Payment Method' , HRW_LOCALE ) ,
                'hrw_requested_date'  => esc_html__( 'Requested Date' , HRW_LOCALE ) ,
                'hrw_processed_date'  => esc_html__( 'Last Activity Date' , HRW_LOCALE ) ,
                'hrw_status'          => esc_html__( 'Status' , HRW_LOCALE ) ,
                'hrw_action'          => esc_html__( 'Actions' , HRW_LOCALE ) ,
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
         * Initialize the bulk actions
         * */
        protected function get_bulk_actions() {

            $action = array() ;

            if ( isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == 'trash' ) {
                $action[ 'restore' ] = __( 'Restore' , HRW_LOCALE ) ;
                $action[ 'delete' ]  = __( 'Delete' , HRW_LOCALE ) ;
            } else {
                $action[ 'trash' ] = __( 'Move to Trash' , HRW_LOCALE ) ;
            }

            return $action ;
        }

        /**
         * Display the list of views available on this table.
         * */
        public function get_views() {
            $args        = array() ;
            $status_link = array() ;

            $status_link_array = array(
                ''              => __( 'All' , HRW_LOCALE ) ,
                'hrw_unpaid'    => __( 'Un-Paid' , HRW_LOCALE ) ,
                'hrw_paid'      => __( 'Paid' , HRW_LOCALE ) ,
                'hrw_cancelled' => __( 'Cancelled' , HRW_LOCALE ) ,
                    ) ;

            foreach ( $status_link_array as $status_name => $status_label ) {
                $status_count = $this->get_total_item_for_status( $status_name ) ;

                if ( ! $status_count )
                    continue ;

                if ( $status_name )
                    $args[ 'status' ] = $status_name ;

                $label                       = $status_label . ' (' . $status_count . ')' ;
                $class                       = (isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == $status_name ) ? 'current' : '' ;
                $class                       = ( ! isset( $_GET[ 'status' ] ) && '' == $status_name ) ? 'current' : $class ;
                $status_link[ $status_name ] = $this->get_edit_link( $args , $label , $class ) ;
            }

            return $status_link ;
        }

        /**
         * Edit link for status 
         * */
        private function get_edit_link( $args , $label , $class = '' ) {

            $url        = add_query_arg( $args , $this->base_url ) ;
            $class_html = '' ;
            if ( ! empty( $class ) ) {
                $class_html = sprintf(
                        ' class="%s"' , esc_attr( $class )
                        ) ;
            }

            return sprintf(
                    '<a href="%s"%s>%s</a>' , esc_url( $url ) , $class_html , $label
                    ) ;
        }

        /**
         * Prepare sortable columns
         * */
        protected function get_sortable_columns() {
            return array() ;
        }

        /**
         * get current url
         * */
        private function prepare_current_url() {

            //Build row actions
            if ( isset( $_GET[ 'status' ] ) )
                $args[ 'status' ] = $_GET[ 'status' ] ;

            $pagenum         = $this->get_pagenum() ;
            $args[ 'paged' ] = $pagenum ;
            $url             = add_query_arg( $args , $this->base_url ) ;

            $this->current_url = $url ;
        }

        /**
         * add row actions
         * */
        public function column_name( $item ) {
            $actions = array() ;
            if ( isset( $_GET[ 'status' ] ) && $_GET[ 'status' ] == 'trash' ) {
                $actions = array(
                    'delete'  => sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Delete' , HRW_LOCALE ) . '</a>' , 'delete' , $item->get_id() ) ,
                    'restore' => sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Restore' , HRW_LOCALE ) . '</a>' , 'restore' , $item->get_id() ) ,
                        ) ;
            } else {
                $actions[ 'edit' ]   = sprintf( '<a href="' . $this->base_url . '&section=%s&id=%s">' . __( 'Edit' , HRW_LOCALE ) . '</a>' , 'edit' , $item->get_id() ) ;
                $actions [ 'trash' ] = sprintf( '<a href="' . $this->current_url . '&action=%s&id=%s">' . __( 'Trash' , HRW_LOCALE ) . '</a>' , 'trash' , $item->get_id() ) ;
            }

            //Return the title contents
            return sprintf( '%1$s %2$s' ,
                    /* $1%s */ $item->name ,
                    /* $3%s */ $this->row_actions( $actions )
                    ) ;
        }

        /**
         * bulk action functionality
         * */
        public function process_bulk_action() {

            $ids = isset( $_REQUEST[ 'id' ] ) ? $_REQUEST[ 'id' ] : array() ;
            $ids = ! is_array( $ids ) ? explode( ',' , $ids ) : $ids ;

            if ( ! hrw_check_is_array( $ids ) )
                return ;

            $action = $this->current_action() ;

            foreach ( $ids as $id ) {

                if ( ! current_user_can( 'edit_post' , $id ) )
                    wp_die( '<p class="hrw_warning_notice">' . __( 'Sorry, you are not allowed to edit this item.' , HRW_LOCALE ) . '</p>' ) ;

                if ( 'delete' === $action ) {
                    wp_delete_post( $id , true ) ;
                } elseif ( 'trash' === $action ) {
                    wp_trash_post( $id ) ;
                } elseif ( 'restore' === $action ) {
                    wp_untrash_post( $id ) ;
                }
            }

            wp_safe_redirect( $this->current_url ) ;
            exit() ;
        }

        /**
         * Prepare cb column data
         * */
        protected function column_cb( $item ) {
            return sprintf(
                    '<input type="checkbox" name="id[]" value="%s" />' , $item->get_id()
                    ) ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'hrw_id':
                    return $item->get_id() ;
                    break ;
                case 'hrw_username':
                    return $item->get_user()->display_name ;
                    break ;
                case 'hrw_email':
                    return $item->get_user()->user_email ;
                    break ;
                case 'hrw_withdraw_amount':
                    return hrw_price( $item->get_amount() ) ;
                    break ;
                case 'hrw_payment_method':
                    return hrw_display_payment_method( $item->get_payment_method() ) ;
                    break ;
                case 'hrw_requested_date':
                    return $item->get_formatted_requested_date() ;
                    break ;
                case 'hrw_processed_date':
                    return $item->get_formatted_processed_date() ;
                    break ;
                case 'hrw_status':
                    return hrw_display_status( $item->get_status() ) ;
                    break ;
                case 'hrw_action':
                    $actions             = array() ;
                    $actions[ 'edit' ]   = hrw_display_action( 'edit' , $item->get_id() , $this->base_url , true ) ;
                    $actions[ 'delete' ] = hrw_display_action( 'delete' , $item->get_id() , $this->base_url ) ;
                    end( $actions ) ;

                    $last_key = key( $actions ) ;

                    foreach ( $actions as $key => $action ) {
                        echo $action ;

                        if ( $last_key == $key )
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
            global $wpdb , $post ;

            $status = isset( $_GET[ 'status' ] ) ? ' IN("' . $_GET[ 'status' ] . '")' : ' NOT IN("trash")' ;
            $where  = " where post_type='" . $this->post_type . "' and post_status" . $status ;

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
                    $prepare_items[] = hrw_get_wallet_withdrawal( $item[ 'ID' ] ) ;
                }
            }

            $this->items = $prepare_items ;
        }

    }

}