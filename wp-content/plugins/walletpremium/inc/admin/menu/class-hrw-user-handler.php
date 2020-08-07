<?php

/*
 *  User Handler
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'HRW_User_Handler' ) ) {

    /**
     * HRW_User_Handler Class.
     */
    class HRW_User_Handler {

        /**
         * Init.
         */
        public static function init() {

            add_action( 'user_new_form' , array( __CLASS__ , 'add_custom_register_data' ) ) ;
            add_action( 'show_user_profile' , array( __CLASS__ , 'add_custom_user_data' ) ) ;
            add_action( 'edit_user_profile' , array( __CLASS__ , 'add_custom_user_data' ) ) ;

            add_action('user_register', array(__CLASS__, 'register_user_data'));
            add_action('profile_update', array(__CLASS__, 'update_user_data'), 10, 1);
            add_action('delete_user', array(__CLASS__, 'delete_user_data'), 10, 1);
        }

        /**
         * Add Custom Register Data.
         */
        public static function add_custom_register_data( $action ) {

            if( 'add-new-user' !== $action )
                return ;

            include HRW_PLUGIN_PATH . '/inc/admin/menu/views/user-profile-extra-info.php' ;
        }

        /**
         * Add Custom User Data.
         */
        public static function add_custom_user_data( $user ) {

            include HRW_PLUGIN_PATH . '/inc/admin/menu/views/user-profile-extra-info.php' ;
        }

        /**
         * Register User Data.
         */
        public static function register_user_data( $user_id ) {

            if( ! isset( $_POST[ 'hrw_ph_no_field' ] ) )
                return ;

            update_user_meta( $user_id , 'hrw_phone_number' , $_POST[ 'hrw_ph_no_field' ] ) ;
        }

        /**
         * Update User Data.
         */
        public static function update_user_data($user_id) {

            if (!isset($_POST['hrw_ph_no_field']))
                return;

            update_user_meta($user_id, 'hrw_phone_number', hrw_sanitize_text_field($_POST['hrw_ph_no_field']));
        }

        /**
         * Delete User Data.
         */
        public static function delete_user_data($user_id) {

            if (!($wallet_id = hrw_get_wallet_id_by_user_id($user_id)))
                return;

            // delete wallet post
            wp_delete_post($wallet_id, true);

            $transaction_log_ids = hrw_get_transaction_logs_by_wallet_id($wallet_id);
           
            if (hrw_check_is_array($transaction_log_ids)) {
                foreach ($transaction_log_ids as $transaction_log_id) {
                    // delete wallet transaction log posts
                    wp_delete_post($transaction_log_id, true);
                }
            }
            
            $withdrawal_ids = hrw_get_withdrawal_id($wallet_id);
                    
            if (hrw_check_is_array($withdrawal_ids)) {
                foreach ($withdrawal_ids as $withdrawal_id) {
                    // delete withdrawal log posts
                    wp_delete_post($withdrawal_id, true);
                }
            }
            
        }

    }

    HRW_User_Handler::init() ;
}
