<?php

/*
 * Wallet Background Process
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ;
}
if ( ! class_exists( 'HRW_Wallet_Background_Process' ) ) {

    /**
     * HRW_Wallet_Background_Process Class.
     */
    class HRW_Wallet_Background_Process extends WP_Background_Process {

        /**
         * Limit
         */
        protected $limit = 1000 ;

        /**
         * @var string
         */
        protected $action = 'hrw_wallet_background_updater' ;

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
            set_transient( 'hrw_wallet_background_updater_offset' , $this->limit + $offset , 360 ) ;

            if ( $offset == 0 ) {
                hrw()->background_process()->update_progress_count( 5 ) ;
                HRW_WooCommerce_Log::log( 'Wallet Upgrade Started' ) ;
            }

            $this->save()->dispatch() ;
        }

        /**
         * Posts
         */
        protected function get_posts( $offset = 0 ) {
            $args = array(
                'post_type'      => 'hr_balance_log' ,
                'post_status'    => array( 'hr_blocked' , 'hr_expired' , 'hr_active' ) ,
                'posts_per_page' => $this->limit ,
                'offset'         => $offset ,
                'sort_order'     => 'ASC' ,
                'fields'         => 'ids'
                    ) ;

            return get_posts( $args ) ;
        }

        /**
         * Task
         *
         * Override this method to perform any actions required on each
         * queue item. Return the modified item for further processing
         * in the next pass through. Or, return false to remove the
         * item from the queue.
         *
         * @param mixed $item Queue item to iterate over
         *
         * @return mixed
         */
        protected function task( $wallet_id ) {
            if ( $wallet_id == 'no_old_data' )
                return false ;

            $post    = get_post( $wallet_id ) ;
            $user_id = get_post_meta( $wallet_id , 'balance_log_user_id' , true ) ;

            if ( $post->post_status == 'hr_blocked' ) {
                $status = 'hrw_blocked' ;
            } elseif ( $post->post_status == 'hr_expired' ) {
                $status = 'hrw_expired' ;
            } else {
                $status = 'hrw_active' ;
            }

            $schedule_from         = get_user_meta( $user_id , 'wallet_block_from_date' , true ) ;
            $schedule_to           = get_user_meta( $user_id , 'wallet_block_to_date' , true ) ;
            $schedule_block_type   = get_user_meta( $user_id , 'wallet_block_type' , true ) ;
            $schedule_block_reason = get_user_meta( $user_id , 'wallet_block_reson' , true ) ;
            $schedule_block_status = get_user_meta( $user_id , 'wallet_schedule_block' , true ) ;

            $expired_date      = date( 'Y-m-d H:i:s' , get_user_meta( $user_id , 'hr_wallet_exp_date' , true ) ) ;
            $last_expired_date = get_user_meta( $user_id , 'hr_wallet_prev_exp_date' , true ) ;

            if ( $schedule_from ) {
                if ( $schedule_from > time() ) {
                    wp_schedule_single_event( $schedule_from , 'hrw_do_block_unblock_action' , array( $wallet_id , 'from' ) ) ;
                } elseif ( $schedule_from < time() && $schedule_to > time() ) {
                    wp_schedule_single_event( $schedule_to , 'hrw_do_block_unblock_action' , array( $wallet_id , 'to' ) ) ;
                    $status = 'hrw_blocked' ;
                } else {
                    $status = 'hrw_active' ;
                }
            }

            if ( get_user_meta( $user_id , 'hr_wallet_exp_date' , true ) <= time() ) {
                $status = 'hrw_expired' ;
            }

            $post_args = array(
                'ID'          => $wallet_id ,
                'post_parent' => absint( $user_id ) ,
                'post_status' => $status ,
                'post_type'   => HRW_Register_Post_Types::WALLET_POSTTYPE
                    ) ;

            wp_update_post( $post_args ) ;

            $meta_args = array(
                'hrw_available_balance'        => floatval( get_user_meta( $user_id , 'balance_log_avail_balance' , true ) ) ,
                'hrw_total_balance'            => floatval( get_user_meta( $user_id , 'hr_wallet_total_balance' , true ) ) ,
                'hrw_expired_date'             => $expired_date ,
                'hrw_last_expired_date'        => empty( $last_expired_date ) ? $expired_date : date( 'Y-m-d H:i:s' , $last_expired_date ) ,
                'hrw_date'                     => $post->post_date_gmt ,
                'hrw_currency'                 => get_woocommerce_currency() ,
                'hrw_schedule_block_type'      => $schedule_block_type ,
                'hrw_schedule_block_from_date' => date( 'Y-m-d H:i:s' , $schedule_from ) ,
                'hrw_schedule_block_to_date'   => date( 'Y-m-d H:i:s' , $schedule_to ) ,
                'hrw_schedule_block_reason'    => $schedule_block_reason ,
                'hrw_schedule_block_status'    => $schedule_block_status ,
                    ) ;

            $wallet_id = hrw_update_wallet( $wallet_id , $meta_args ) ;

            return false ;
        }

        /**
         * Complete
         */
        protected function complete() {
            parent::complete() ;

            $offset = get_transient( 'hrw_wallet_background_updater_offset' ) ;
            $posts  = $this->get_posts( $offset ) ;

            if ( hrw_check_is_array( $posts ) ) {
                $this->handle_push_to_queue( $posts , $offset ) ;
                HRW_WooCommerce_Log::log( 'Wallet Upgrade upto ' . $offset ) ;
            } else {
                hrw()->background_process()->update_progress_count( 30 ) ;
                delete_transient( 'hrw_wallet_background_updater_offset' ) ;
                HRW_WooCommerce_Log::log( 'Wallet Upgrade Completed' ) ;
                hrw()->background_process()->get_background_process_by_id( 'transaction-logs' )->trigger() ;
            }
        }

    }

}