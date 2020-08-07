<?php

/**
 * Gift Card Post Table
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ;
}

if ( ! class_exists ( 'HRW_Gift_Card_Table' ) ) {

    /**
     * HRW_Gift_Card_Table Class.
     * */
    class HRW_Gift_Card_Table extends WP_List_Table {

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
        private $orderby = 'ORDER BY ID DESC' ;

        /**
         * Post type
         * */
        private $post_type = HRWP_Register_Post_Types::GIFT_CARD_POSTTYPE ;

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
            $this->base_url = add_query_arg ( array ( 'page' => 'hrw_settings' , 'tab' => 'modules' , 'section' => 'general' , 'subsection' => 'gift_card' ) , admin_url ( 'admin.php' ) ) ;

            $this->prepare_current_url () ;
            $this->get_perpage_count () ;
            $this->get_current_pagenum () ;
            $this->get_current_page_items () ;
            $this->prepare_pagination_args () ;
            $this->process_bulk_action () ;
            //display header columns
            $this->prepare_column_headers () ;
        }

        /**
         * get current url
         * */
        private function prepare_current_url() {

            $pagenum           = $this->get_pagenum () ;
            $args[ 'paged' ]   = $pagenum ;
            $url               = add_query_arg ( $args , $this->base_url ) ;
            $this->current_url = $url ;
        }

        /**
         * get per page count
         * */
        private function get_perpage_count() {
            $this->perpage = 5 ;
        }

        /**
         * get current page number
         * */
        private function get_current_pagenum() {
            $this->offset = 5 * ($this->get_pagenum () - 1) ;
        }

        /**
         * Prepare pagination
         * */
        private function prepare_pagination_args() {

            $this->set_pagination_args ( array (
                'total_items' => $this->total_items ,
                'per_page'    => $this->perpage
            ) ) ;
        }

        /**
         * Prepare header columns
         * */
        private function prepare_column_headers() {
            $columns               = $this->get_columns () ;
            $hidden                = $this->get_hidden_columns () ;
            $sortable              = $this->get_sortable_columns () ;
            $this->_column_headers = array ( $columns , $hidden , $sortable ) ;
        }

        /**
         * Initialize the columns
         * */
        public function get_columns() {
            $columns = array (
                'cb'                 => '<input type="checkbox" />' , //Render a checkbox instead of text
                'hrw_buyername'      => esc_html__ ( 'Buyer Name' , HRW_LOCALE ) ,
                'hrw_recipients'     => esc_html__ ( 'Receiver Name' , HRW_LOCALE ) ,
                'hrw_giftcard'       => esc_html__ ( 'Gift Card' , HRW_LOCALE ) ,
                'hrw_amount'         => esc_html__ ( 'Amount' , HRW_LOCALE ) ,
                'hrw_reason'         => esc_html__ ( 'Reason' , HRW_LOCALE ) ,
                'hrw_purchased_date' => esc_html__ ( 'Purchased Date' , HRW_LOCALE ) ,
                'hrw_status'         => esc_html__ ( 'Status' , HRW_LOCALE ) ,
                'hrw_redeemed_date'  => esc_html__ ( 'Redeemed Date' , HRW_LOCALE ) ,
                'hrw_expiry_date'    => esc_html__ ( 'Expiry Date' , HRW_LOCALE ) ,
                    ) ;

            return $columns ;
        }

        /**
         * Initialize the hidden columns
         * */
        public function get_hidden_columns() {
            return array () ;
        }

        /**
         * Prepare sortable columns
         * */
        protected function get_sortable_columns() {


            return array (
                'hrw_amount'         => array ( 'hrw_amount' , true ) ,
                'hrw_purchased_date' => array ( 'hrw_purchased_date' , true ) ,
                'hrw_redeemed_date'  => array ( 'hrw_redeemed_date' , true ) ,
                'hrw_expiry_date'    => array ( 'hrw_expiry_date' , true ) ,
                    ) ;
        }

        /**
         * Display the list of views available on this table.
         * */
        public function get_views() {
            $args        = array () ;
            $status_link = array () ;

            $status_link_array = hrw_get_gift_card_statuses () ;

            foreach ( $status_link_array as $status_name => $status_label ) {
                $status_count = $this->get_total_item_for_status ( $status_name ) ;

                if ( ! $status_count )
                    continue ;

                if ( $status_name )
                    $args[ 'status' ] = $status_name ;

                $label                       = $status_label . ' (' . $status_count . ')' ;
                $class                       = (isset ( $_GET[ 'status' ] ) && $_GET[ 'status' ] == $status_name ) ? 'current' : '' ;
                $class                       = ( ! isset ( $_GET[ 'status' ] ) && '' == $status_name ) ? 'current' : $class ;
                $status_link[ $status_name ] = $this->get_edit_link ( $args , $label , $class ) ;
            }

            return $status_link ;
        }

        /**
         * Edit link for status
         * */
        private function get_edit_link( $args , $label , $class = '' ) {
            $url = add_query_arg ( $args , $this->base_url ) ;

            $class_html = '' ;
            if ( ! empty ( $class ) ) {
                $class_html = sprintf (
                        ' class="%s"' , esc_attr ( $class )
                        ) ;
            }

            return sprintf (
                    '<a href="%s"%s>%s</a>' , esc_url ( $url ) , $class_html , $label
                    ) ;
        }

        /**
         * get total item for status
         * */
        private function get_total_item_for_status( $status = '' ) {
            global $wpdb ;
            $where  = "WHERE post_type='" . $this->post_type . "' and post_status " ;
            $status = ($status == '') ? "NOT IN('trash')" : "IN('" . $status . "')" ;

            $data = $wpdb->get_results ( "SELECT ID FROM " . $wpdb->posts . " AS p $where $status" , ARRAY_A ) ;

            return count ( $data ) ;
        }

        /**
         * Checkbox Column
         * */
        protected function column_cb( $item ) {

            return sprintf (
                    '<input type="checkbox" class="hrw_approval_checkbox" name="request_id[]" value="%s" />' , $item->get_id ()
                    ) ;
        }

        /**
         * Prepare each column data
         * */
        protected function column_default( $item , $column_name ) {

            switch ( $column_name ) {
                case 'hrw_buyername':
                    return $item->get_user_display () ;
                    break ;
                case 'hrw_recipients':
                    return $item->get_receiver_display () ;
                    break ;
                case 'hrw_giftcard':
                    return $item->get_gift_code () ;
                    break ;
                case 'hrw_amount':
                    return hrw_price ( $item->get_amount () ) ;
                    break ;
                case 'hrw_reason':
                    return esc_html ( $item->get_gift_reason () ) ;
                    break ;
                case 'hrw_purchased_date':
                    return $item->get_formatted_created_date () ;
                    break ;
                case 'hrw_status':
                    return hrw_display_status ( $item->get_Status () ) ;
                    break ;
                case 'hrw_redeemed_date':
                    return $item->get_formatted_redeemed_date () ;
                    break ;
                case 'hrw_expiry_date':
                    return $item->get_formatted_expired_date () ;
                    break ;
            }
        }

        /**
         * Get Current Page Items
         * */
        private function get_current_page_items() {
            global $wpdb ;

            $status = isset ( $_GET[ 'status' ] ) ? ' IN("' . $_GET[ 'status' ] . '")' : ' NOT IN("trash")' ;

            $where = " where post_type='" . $this->post_type . "' and post_status" . $status ;

            $where   = apply_filters ( $this->table_slug . '_query_where' , $where ) ;
            $limit   = apply_filters ( $this->table_slug . '_query_limit' , $this->perpage ) ;
            $offset  = apply_filters ( $this->table_slug . '_query_offset' , $this->offset ) ;
            $orderby = apply_filters ( $this->table_slug . '_query_orderby' , $this->orderby ) ;

            $count_items       = $wpdb->get_results ( "SELECT DISTINCT ID FROM " . $wpdb->posts . " AS p $where $orderby" ) ;
            $this->total_items = count ( $count_items ) ;

            $items = $wpdb->get_results ( "SELECT DISTINCT ID FROM " . $wpdb->posts . " AS p $where $orderby LIMIT $offset,$limit" , ARRAY_A ) ;
            $this->prepare_item_object ( $items ) ;
        }

        /**
         * Prepare item Object
         * */
        private function prepare_item_object( $items ) {
            $prepare_items = array () ;
            if ( hrw_check_is_array ( $items ) ) {
                foreach ( $items as $request ) {
                    $prepare_items[] = hrw_get_gift ( $request[ 'ID' ] ) ;
                }
            }

            $this->items = $prepare_items ;
        }

    }

}