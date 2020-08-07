<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit ;
}
if ( ! class_exists( 'HRW_Transaction_Logs_Background_Process' ) ) {

    /**
     * HRW_Transaction_Logs_Background_Process Class.
     */
    class HRW_Transaction_Logs_Background_Process extends WP_Background_Process {

        /**
         * Limit
         */
        protected $limit = 1000 ;

        /**
         * @var string
         */
        protected $action = 'hrw_transaction_logs_background_updater' ;

        /**
         * Trigger
         */
        public function trigger() {

            if ( $this->is_process_running() )
                return ;

            $posts = $this->get_posts() ;

            $this->handle_push_to_queue( $posts ) ;
        }

        /**
         * Is process running
         *
         * Check whether the current process is already running
         * in a background process.
         */
        public function is_process_running() {
            if ( get_site_transient( $this->identifier . '_process_lock' ) ) {
                // Process already running.
                return true ;
            }

            return false ;
        }

        /**
         * Handle push to queue
         */
        protected function handle_push_to_queue( $posts , $offset = 0 ) {

            if ( hrw_check_is_array( $posts ) ) {
                foreach ( $posts as $post_id ) {
                    $this->push_to_queue( $post_id ) ;
                }
            } else {
                $this->push_to_queue( 'no_old_data' ) ;
            }

            //update offset 
            set_transient( 'hrw_transaction_log_background_updater_offset' , $this->limit + $offset , 360 ) ;

            if ( $offset == 0 ) {
                hrw()->background_process()->update_progress_count( 40 ) ;
                HRW_WooCommerce_Log::log( 'Transaction Logs Upgrade Started' ) ;
            }

            $this->save()->dispatch() ;
        }

        /**
         * Posts
         */
        protected function get_posts( $offset = 0 ) {
            $args = array(
                'post_type'      => 'hr_transactions_log' ,
                'post_status'    => 'hr_transacted' ,
                'posts_per_page' => $this->limit ,
                'offset'         => $offset ,
                'sort_order'     => 'ASC' ,
                'fields'         => 'ids'
                    ) ;

            return get_posts( $args ) ;
        }

        /**
         * Task
         */
        protected function task( $transaction_log_id ) {
            if ( $transaction_log_id == 'no_old_data' )
                return false ;

            $post    = get_post( $transaction_log_id ) ;
            $user_id = get_post_meta( $transaction_log_id , 'trans_log_user_id' , true ) ;

            $amount = get_post_meta( $transaction_log_id , 'trans_log_current_debit' , true ) ;

            if ( ! $amount || $amount != '-' ) {
                $status = 'hrw_debit' ;
            } else {
                $amount = get_post_meta( $transaction_log_id , 'trans_log_current_credit' , true ) ;
                $status = 'hrw_credit' ;
            }

            //Check if already wallet for this user
            $wallet_id = hrw_get_wallet_id_by_user_id( $user_id ) ;

            //Update Post
            $post_args = array(
                'ID'          => $transaction_log_id ,
                'post_parent' => absint( $wallet_id ) ,
                'post_status' => $status ,
                'post_type'   => HRW_Register_Post_Types::TRANSACTION_LOG_POSTTYPE
                    ) ;

            wp_update_post( $post_args ) ;

            //Update Post meta
            $meta_args = array(
                'hrw_user_id'  => $user_id ,
                'hrw_amount'   => floatval( $amount ) ,
                'hrw_event'    => get_post_meta( $transaction_log_id , 'trans_log_event' , true ) ,
                'hrw_total'    => floatval( get_post_meta( $transaction_log_id , 'trans_log_total_credit' , true ) ) ,
                'hrw_date'     => $post->post_date_gmt ,
                'hrw_currency' => get_woocommerce_currency()
                    ) ;

            hrw_update_transaction_log( $transaction_log_id , $meta_args ) ;

            return false ;
        }

        /**
         * Complete
         */
        protected function complete() {
            parent::complete() ;

            $offset = get_transient( 'hrw_transaction_log_background_updater_offset' ) ;
            $posts  = $this->get_posts( $offset ) ;

            if ( hrw_check_is_array( $posts ) ) {
                $this->handle_push_to_queue( $posts , $offset ) ;
                HRW_WooCommerce_Log::log( 'Transaction Logs Upgrade upto ' . $offset ) ;
            } else {
                hrw()->background_process()->update_progress_count( 80 ) ;
                delete_transient( 'hrw_transaction_log_background_updater_offset' ) ;
                HRW_WooCommerce_Log::log( 'Transaction Logs Upgrade Completed' ) ;
                hrw()->background_process()->get_background_process_by_id( 'settings' )->trigger() ;
            }
        }

    }

}