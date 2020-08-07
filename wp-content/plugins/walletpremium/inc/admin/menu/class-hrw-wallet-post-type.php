<?php

/**
 * Wallet Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Wallet_Post_Type' ) ) {

    /**
     * HRW_Wallet_Post_Type Class.
     */
    class HRW_Wallet_Post_Type {
        /*
         * Object
         */

        private static $object ;
        /*
         * Post type
         */
        private static $post_type = HRW_Register_Post_Types::WALLET_POSTTYPE ;

        /*
         * Plugin Slug
         */
        private static $plugin_slug = 'hrw' ;

        /**
         * Class initialization.
         */
        public static function init() {
            //body class 
            add_filter( 'admin_body_class' , array( __CLASS__ , 'custom_body_class' ) , 10 , 1 ) ;

            add_filter( 'post_row_actions' , array( __CLASS__ , 'handle_post_row_actions' ) , 10 , 2 ) ;
            add_filter( 'disable_months_dropdown' , array( __CLASS__ , 'remove_month_dropdown' ) , 10 , 2 ) ;
            add_action( 'views_edit-' . self::$post_type , array( __CLASS__ , 'remove_views' ) ) ;
            add_filter( 'bulk_actions-edit-' . self::$post_type , array( __CLASS__ , 'handle_bulk_actions' ) , 10 , 1 ) ;

            add_action( 'add_meta_boxes' , array( __CLASS__ , 'add_meta_boxes' ) , 1 ) ;
            add_action( 'save_post_' . self::$post_type , array( __CLASS__ , 'save' ) , 10 , 2 ) ;
            //Table Join
            add_action( 'posts_join' , array( __CLASS__ , 'table_join_query' ) , 10 , 2 ) ;
            //Display result column value in unique
            add_action( 'posts_distinct' , array( __CLASS__ , 'distinct_post' ) , 10 , 2 ) ;
            //search
            add_filter( 'posts_search' , array( __CLASS__ , 'search_action' ) ) ;
            //define column header
            add_filter( 'manage_' . self::$post_type . '_posts_columns' , array( __CLASS__ , 'define_columns' ) ) ;
            //display column value
            add_action( 'manage_' . self::$post_type . '_posts_custom_column' , array( __CLASS__ , 'render_columns' ) , 10 , 2 ) ;
            //define sortable column
            add_filter( 'manage_edit-' . self::$post_type . '_sortable_columns' , array( __CLASS__ , 'sortable_columns' ) ) ;
            add_action( 'posts_orderby' , array( __CLASS__ , 'orderby_columns' ) , 10 , 2 ) ;
            add_filter( 'parse_query' , array( __CLASS__ , 'orderby_filter_query' ) ) ;
        }

        /*
         * Add custom class in body
         */

        public static function custom_body_class( $class ) {
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

            if ( $post->post_type == self::$post_type )
                return array() ;

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
            if ( $post->post_type == self::$post_type )
                return array() ;

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
                'hrw_user_id'           => esc_html__( 'Username' , HRW_LOCALE ) ,
                'hrw_available_balance' => esc_html__( 'Available Balance' , HRW_LOCALE ) ,
                'hrw_expired_date'      => esc_html__( 'Expiry Date' , HRW_LOCALE ) ,
                'hrw_total_balance'     => esc_html__( 'Total Wallet Amount Spent' , HRW_LOCALE ) ,
                'hrw_date'              => esc_html__( 'Created Date' , HRW_LOCALE ) ,
                'status'                => esc_html__( 'Status' , HRW_LOCALE ) ,
                'actions'               => esc_html__( 'Actions' , HRW_LOCALE )
                    ) ;

            return $columns ;
        }

        /*
         * Remove views
         */

        public static function prepare_row_data( $postid ) {

            if ( empty( self::$object ) || self::$object->get_id() != $postid ) {
                self::$object = hrw_get_wallet( $postid ) ;
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
         * Render Available Balance column
         */
        public static function render_hrw_available_balance_cloumn() {
            echo hrw_price( self::$object->get_available_balance() ) ;
        }

        /**
         * Render Expired date column
         */
        public static function render_hrw_expired_date_cloumn() {
            echo self::$object->get_formatted_expired_date() ;
        }

        /**
         * Render Total column
         */
        public static function render_hrw_total_balance_cloumn() {
            echo hrw_price( self::$object->get_total_balance() ) ;
        }

        /**
         * Render Date column
         */
        public static function render_hrw_date_cloumn() {
            echo self::$object->get_formatted_created_date() ;
        }

        /**
         * Render Status column
         */
        public static function render_status_cloumn() {
            echo hrw_display_status( self::$object->get_status() ) ;
        }

        /**
         * Render Actions column
         */
        public static function render_actions_cloumn() {
            echo $actions[ 'edit' ] = sprintf(
            '<a href="%s" class="%s">%s</a>'
            , esc_url( get_edit_post_link( self::$object->get_id() ) )
            , esc_attr( 'edit_view' )
            , esc_html( 'Edit' , HRW_LOCALE )
            ) ;
        }

        /*
         * Add Custom meta boxes.
         */

        public static function add_meta_boxes() {
            //Remove post submit metabox
            remove_meta_box( 'submitdiv' , self::$post_type , 'side' ) ;

            add_meta_box( 'hrw-details' , esc_html__( 'Settings' , HRW_LOCALE ) , array( __CLASS__ , 'display_details' ) , self::$post_type , 'normal' , 'high' ) ;
            add_meta_box( 'hrw-credit-debit' , esc_html__( 'Funds and Transactions' , HRW_LOCALE ) , array( __CLASS__ , 'display_credit_debit_funds' ) , self::$post_type , 'normal' , 'high' ) ;
            add_meta_box( 'hrw-transactions' , esc_html__( 'Transactions' , HRW_LOCALE ) , array( __CLASS__ , 'display_transactions' ) , self::$post_type , 'normal' , 'high' ) ;
            add_meta_box( 'hrw-wallet-submit' , esc_html__( 'Wallet Actions' , HRW_LOCALE ) , array( __CLASS__ , 'display_submit' ) , self::$post_type , 'side' , 'core' ) ;

            //Custom meta box for wallet post type
            do_action( 'hrw_wallet_meta_boxes' , self::$post_type ) ;
        }

        /*
         * Display details and status
         */

        public static function display_details( $post ) {
            if ( ! isset( $post->ID ) )
                return ;

            $wallet = hrw_get_wallet( $post->ID ) ;

            include_once HRW_PLUGIN_PATH . '/inc/admin/menu/views/edit-post/details.php' ;
        }

        /*
         * Display Transactions
         */

        public static function display_transactions( $post ) {
            if ( ! isset( $post->ID ) )
                return ;

            $wallet = hrw_get_wallet( $post->ID ) ;

            if ( ! class_exists( 'HRW_User_Transaction_Table' ) )
                require_once( HRW_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-hrw-wallet-transactions-table.php' ) ;

            echo '<div class="' . self::$plugin_slug . '_table_wrap">' ;
            $post_table = new HRW_User_Transaction_Table( array( 'hrw_id' => $post->ID ) ) ;
            $post_table->prepare_items() ;
            $post_table->display() ;
            wp_nonce_field( 'update-post_' . $post->ID , '_wpnonce' ) ;
            echo '</div>' ;
        }

        /*
         * Display Credit Debit Funds
         */

        public static function display_credit_debit_funds( $post ) {
            if ( ! isset( $post->ID ) )
                return ;

            $wallet = hrw_get_wallet( $post->ID ) ;

            include_once HRW_PLUGIN_PATH . '/inc/admin/menu/views/edit-post/credit-debit.php' ;
        }

        /*
         * Save Submit
         */

        public static function display_submit( $post ) {

            if ( ! isset( $post->ID ) )
                return ;

            $wallet = hrw_get_wallet( $post->ID ) ;

            include_once HRW_PLUGIN_PATH . '/inc/admin/menu/views/edit-post/display-submit.php' ;
        }

        /*
         * Save Meta Boxes
         */

        public static function save( $post_id , $post ) {

            // $post_id and $post are required
            if ( empty( $post_id ) || empty( $post ) ) {
                return ;
            }

            //Verifying save Nonce
            if ( isset( $_REQUEST[ 'hrw_save_nonce' ] ) && ! wp_verify_nonce( $_REQUEST[ 'hrw_save_nonce' ] , 'hrw_save_wallet_data' ) ) {
                return ;
            }

            // Check user has permission to edit
            if ( ! current_user_can( 'edit_post' , $post_id ) ) {
                return ;
            }

            remove_action( 'save_post_' . self::$post_type , array( __CLASS__ , 'save' ) , 10 , 2 ) ;

            $prepare_metas = array() ;
            $post_args     = array() ;

            //Status Update
            if ( isset( $_REQUEST[ 'hrw_wallet_status' ] ) ) {
                $current_date  = current_time( 'timestamp' ) ;
                $wallet_object = hrw_get_wallet( $post_id ) ;

                if ( $wallet_object->get_status() != hrw_sanitize_text_field( $_REQUEST[ 'hrw_wallet_status' ] ) && hrw_sanitize_text_field( $_REQUEST [ 'hrw_wallet_status' ] ) != 'hrw_blocked' || hrw_sanitize_text_field( $_REQUEST [ 'hrw_schedule_block_type' ] ) == 1 ) {

                    if ( $wallet_object->get_status() == 'hrw_expired' && (strtotime( $_REQUEST[ 'hrw_wallet_bal_expiry_date' ] ) > $current_date ) ) {
                        $post_args = array( 'post_status' => 'hrw_active' ) ;
                    } else if ( hrw_sanitize_text_field( $_REQUEST [ 'hrw_wallet_status' ] ) == 'hrw_expired' || strtotime( $_REQUEST[ 'hrw_wallet_bal_expiry_date' ] ) < $current_date ) {
                        $post_args     = array( 'post_status' => 'hrw_expired' ) ;
                        $prepare_metas = array( 'hrw_available_balance' => 0 , 'hrw_total_balance' => 0 ) ;

                        //Insert Transaction log
                        $transaction_meta_args = array(
                            'hrw_user_id'  => $wallet_object->get_user_id() ,
                            'hrw_event'    => get_option( 'hrw_wallet_expired_log' ) ,
                            'hrw_amount'   => $wallet_object->get_available_balance() ,
                            'hrw_total'    => 0 ,
                            'hrw_currency' => get_woocommerce_currency() ,
                            'hrw_date'     => current_time( 'mysql' , true ) ,
                                ) ;
                        hrw_create_new_transaction_log( $transaction_meta_args , array( 'post_parent' => $post_id , 'post_status' => 'hrw_debit' ) ) ;
                    } else {
                        $post_args = array( 'post_status' => hrw_sanitize_text_field( $_REQUEST[ 'hrw_wallet_status' ] ) ) ;
                    }
                } else if ( hrw_sanitize_text_field( $_REQUEST [ 'hrw_wallet_status' ] ) == 'hrw_blocked' && $wallet_object->get_status() != 'hrw_blocked' ) {

                    $prepare_metas = array(
                        'hrw_schedule_block_status'    => 'yes' ,
                        'hrw_schedule_block_type'      => hrw_sanitize_text_field( $_REQUEST[ 'hrw_schedule_block_type' ] ) ,
                        'hrw_schedule_block_from_date' => hrw_sanitize_text_field( $_REQUEST[ 'hrw_schedule_block_from_date' ] ) ,
                        'hrw_schedule_block_to_date'   => hrw_sanitize_text_field( $_REQUEST[ 'hrw_schedule_block_to_date' ] ) ,
                        'hrw_schedule_block_reason'    => hrw_sanitize_text_field( $_REQUEST[ 'hrw_schedule_block_reason' ] ) ,
                            ) ;

                    if ( wp_next_scheduled( 'hrw_do_block_unblock_action' , array( $post_id , 'from' ) ) ) {
                        wp_clear_scheduled_hook( 'hrw_do_block_unblock_action' , array( $post_id , 'from' ) ) ;
                        wp_schedule_single_event( strtotime( hrw_sanitize_text_field( $_REQUEST[ 'hrw_schedule_block_from_date' ] ) ) , 'hrw_do_block_unblock_action' , array( $post_id , 'from' ) ) ;
                    }
                }
            }

            if ( isset( $_REQUEST[ 'hrw_wallet_bal_expiry_date' ] ) ) {
                $prepare_metas[ 'hrw_expired_date' ] = hrw_sanitize_text_field( $_REQUEST[ 'hrw_wallet_bal_expiry_date' ] ) ;
            }

            if ( isset( $_REQUEST[ 'hrw_clear_schedule' ] ) || ( isset( $_REQUEST [ 'hrw_wallet_status' ] ) && hrw_sanitize_text_field( $_REQUEST [ 'hrw_wallet_status' ] ) == 'hrw_blocked' && hrw_sanitize_text_field( $_REQUEST [ 'hrw_schedule_block_type' ] ) == 1 ) ) {
                if ( wp_next_scheduled( 'hrw_do_block_unblock_action' , array( $post_id , 'from' ) ) ) {
                    wp_clear_scheduled_hook( 'hrw_do_block_unblock_action' , array( $post_id , 'from' ) ) ;
                }
                $prepare_metas = array( 'hrw_schedule_block_status' => 'no' ) ;
            }

            //Data Update
            if ( hrw_check_is_array ( $prepare_metas ) || hrw_check_is_array ( $post_args ) ) {
                //Do Wallet Block and Unblock Notifications
                if ( hrw_sanitize_text_field ( $_REQUEST [ 'hrw_wallet_status' ] ) == 'hrw_blocked' ) {
                    do_action ( 'hrw_wallet_lock_notification' , $post_id ) ;
                } elseif ( $wallet_object->get_status () == 'hrw_blocked' && hrw_sanitize_text_field ( $_REQUEST [ 'hrw_wallet_status' ] ) != 'hrw_blocked' ) {
                    do_action ( 'hrw_wallet_unlock_notification' , $post_id ) ;
                }
                hrw_update_wallet ( $post_id , $prepare_metas , $post_args ) ;
            }
        }

        /**
         * sortable columns
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
                        ->where( '`p`.post_status' , 'hrw_active' )
                        ->where( '`pm`.meta_key' , 'balance_log_user_id' )
                        ->orderBy( '`pm`.meta_value' ) ;

                $search_ids = $post_query->fetchCol( 'meta_value' ) ;

                $post_query = new HRW_Query( $wpdb->postmeta , 'pm' ) ;
                $post_query->select( 'DISTINCT `pm`.post_id' )
                        ->where( '`pm`.meta_key' , 'balance_log_user_id' )
                        ->leftJoin( $wpdb->users , 'u' , '`u`.ID = `pm`.meta_value' )
                        ->leftJoin( $wpdb->usermeta , 'um' , '`pm`.meta_value = `um`.user_id' )
                        ->whereIn( '`u`.ID' , $search_ids )
                        ->whereIn( '`um`.meta_key' , array( "'first_name', 'last_name', 'billing_email' , 'nickname'" ) )
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
         * join query
         */
        public static function table_join_query( $join ) {
            global $wp_query ;

            if ( is_admin() && ! isset( $_GET[ 'post' ] ) && isset( $wp_query->query_vars[ 'post_type' ] ) && $wp_query->query_vars[ 'post_type' ] == self::$post_type ) {
                if ( isset( $_REQUEST[ 's' ] ) && hrw_sanitize_text_field( $_REQUEST[ 'post_type' ] ) == self::$post_type ) {
                    global $wpdb ;
                    $join .= " INNER JOIN $wpdb->usermeta ON ($wpdb->posts.post_author = $wpdb->usermeta.user_id)" ;
                }
            }

            return $join ;
        }

        /**
         * Sortable columns
         */
        public static function sortable_columns( $columns ) {

            $columns = array(
                'hrw_available_balance' => 'hrw_available_balance' ,
                'hrw_expired_date'      => 'hrw_expired_date' ,
                'hrw_total_balance'     => 'hrw_total_balance' ,
                'hrw_date'              => 'hrw_date' ,
                'status'                => 'post_status' ,
                    ) ;

            return $columns ;
        }

        /**
         * sortable columns
         */
        public static function orderby_columns( $order_by , $wp_query ) {
            global $wpdb ;
            if ( isset( $wp_query->query[ 'post_type' ] ) && $wp_query->query[ 'post_type' ] == self::$post_type ) {
                if ( ! isset( $_REQUEST[ 'order' ] ) && ! isset( $_REQUEST[ 'orderby' ] ) ) {
                    $order_by = "{$wpdb->posts}.ID " . 'DESC' ;
                } else {
                    $decimal_column = array( 'hrw_available_balance' , 'hrw_total_balance' ) ;

                    if ( in_array( hrw_sanitize_text_field( $_REQUEST[ 'orderby' ] ) , $decimal_column ) ) {
                        $order_by = "CAST({$wpdb->postmeta}.meta_value AS DECIMAL) " . hrw_sanitize_text_field( $_REQUEST[ 'order' ] ) ;
                    } elseif ( hrw_sanitize_text_field( $_REQUEST[ 'orderby' ] ) == "post_status" ) {
                        $order_by = "{$wpdb->posts}.post_status " . hrw_sanitize_text_field( $_REQUEST[ 'order' ] ) ;
                    } else {
                        $order_by = "{$wpdb->postmeta}.meta_value " . hrw_sanitize_text_field( $_REQUEST[ 'order' ] ) ;
                    }
                }
            }

            return $order_by ;
        }

        /**
         *  Sorting Functionality
         */
        public static function orderby_filter_query( $query ) {
            if ( isset( $_REQUEST[ 'post_type' ] ) && hrw_sanitize_text_field( $_REQUEST[ 'post_type' ] ) == self::$post_type && self::$post_type == $query->query[ 'post_type' ] ) {
                if ( isset( $_GET[ 'orderby' ] ) ) {
                    $excerpt_array                   = array( 'ID' , 'post_status' ) ;
                    if ( ! in_array( hrw_sanitize_text_field( $_GET[ 'orderby' ] ) , $excerpt_array ) )
                        $query->query_vars[ 'meta_key' ] = hrw_sanitize_text_field( $_GET[ 'orderby' ] ) ;
                }
            }
        }

        /**
         *  Distinct Functionality
         */
        public static function distinct_post( $distinct ) {

            if ( ( isset( $_REQUEST[ 's' ] ) || isset( $_REQUEST[ 'orderby' ] ) ) && hrw_sanitize_text_field( $_REQUEST[ 'post_type' ] ) == self::$post_type )
                $distinct .= empty( $distinct ) ? 'DISTINCT' : $distinct ;

            return $distinct ;
        }

    }

    HRW_Wallet_Post_Type::init() ;
}