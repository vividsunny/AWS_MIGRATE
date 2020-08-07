<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit ;
}
if ( ! class_exists( 'HRW_Settings_Background_Process' ) ) {

    /**
     * HRW_Settings_Background_Process Class.
     */
    class HRW_Settings_Background_Process extends WP_Background_Process {

        /**
         * @var string
         */
        protected $action = 'hrw_settings_background_updater' ;

        /**
         * Trigger
         */
        public function trigger() {

            if ( $this->is_process_running() )
                return ;

            $this->push_to_queue( 'no_old_data' ) ;

            hrw()->background_process()->update_progress_count( 90 ) ;
            HRW_WooCommerce_Log::log( 'Settings Upgrade Started' ) ;

            $this->save()->dispatch() ;
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
         * Task
         */
        protected function task( $item ) {

            $options = array(
                'hr_wallet_allow_users_to_fund'                  => 'hrw_general_enable_topup' ,
                'hr_wallet_topup_product_type'                   => 'hrw_general_topup_product_type' ,
                'hr_wallet_topup_product_name'                   => 'hrw_general_topup_product_name' ,
                'hr_wallet_topup_min_fund'                       => 'hrw_general_topup_minimum_amount' ,
                'hr_wallet_topup_max_fund'                       => 'hrw_general_topup_maximum_amount' ,
                'hr_wallet_topup_max_fund_user'                  => 'hrw_general_topup_maximum_wallet_balance' ,
                'hr_wallet_topup_gatways_hide'                   => 'hrw_general_topup_hide_wc_gateways' ,
                'hr_wallet_topup_order_status'                   => 'hrw_general_topup_order_status' ,
                'hr_wallet_usage_order_status'                   => 'hrw_general_wallet_usage_order_status' ,
                'hr_wallet_usage_min_fund'                       => 'hrw_general_wallet_usage_minimum_amount' ,
                'hr_wallet_usage_max_fund'                       => 'hrw_general_wallet_usage_maximum_amount' ,
                'hr_wallet_usage_restriction_from'               => 'hrw_general_wallet_usage_from_date_restriction' ,
                'hr_wallet_usage_restriction_to'                 => 'hrw_general_wallet_usage_to_date_restriction' ,
                'hr_wallet_restrict_sun'                         => 'hrw_general_wallet_usage_sunday_restriction' ,
                'hr_wallet_restrict_mon'                         => 'hrw_general_wallet_usage_monday_restriction' ,
                'hr_wallet_restrict_tue'                         => 'hrw_general_wallet_usage_tuesday_restriction' ,
                'hr_wallet_restrict_wed'                         => 'hrw_general_wallet_usage_wednesday_restriction' ,
                'hr_wallet_restrict_thu'                         => 'hrw_general_wallet_usage_Thursday_restriction' ,
                'hr_wallet_restrict_fri'                         => 'hrw_general_wallet_usage_Friday_restriction' ,
                'hr_wallet_restrict_sat'                         => 'hrw_general_wallet_usage_Saturday_restriction' ,
                'hr_wallet_email_cron_trigger_type'              => 'hrw_advanced_cron_time_type' ,
                'hr_wallet_email_cron_trigger_value'             => 'hrw_advanced_cron_time_value' ,
                'hrw_email_low_wallet_user_threshold_value'      => 'hrw_advanced_low_wallet_amount_limit' ,
                'hr_wallet_fund_restriction_days'                => 'hrw_general_wallet_expiry_limit' ,
                'hr_wallet_topup_allowed_for'                    => 'hrw_advanced_topup_user_restriction_type' ,
                'hr_wallet_topup_allowed_users_restriction'      => 'hrw_advanced_topup_user_restriction' ,
                'hr_wallet_topup_restrict_user_roles'            => 'hrw_advanced_topup_user_role_restriction' ,
                'hr_wallet_restrict_product_category_type'       => 'hrw_advanced_wallet_usage_product_restriction_type' ,
                'hr_wallet_usage_product_restriction'            => 'hrw_advanced_wallet_usage_product_restriction' ,
                'hr_wallet_restrict_product_category'            => 'hrw_advanced_wallet_usage_category_restriction' ,
                'hr_wallet_usage_allowed_for'                    => 'hrw_advanced_wallet_usage_user_restriction_type' ,
                'hr_wallet_usage_allowed_users_restriction'      => 'hrw_advanced_wallet_usage_user_restriction' ,
                'hr_wallet_usage_restrict_user_roles'            => 'hrw_advanced_wallet_usage_user_role_restriction' ,
                'hr_wallet_partial_payment_enable'               => 'hrw_general_enable_partial_payment' ,
                'hr_wallet_partial_payment_mode'                 => 'hrw_general_partial_payment_restriction_type' ,
                'hr_wallet_partial_usage_maximum_funds'          => 'hrw_general_partial_payment_maximum_amount_limit' ,
                'hr_wallet_partial_field_cart'                   => 'hrw_general_enable_partial_payment_in_cart' ,
                'hr_wallet_partial_field_checkout'               => 'hrw_general_enable_partial_payment_in_checkout' ,
                'hrw_hide_other_gateways'                        => 'hrw_general_hide_other_wc_gateways' ,
                'hrw_insuff_error_message'                       => 'hrw_general_insufficient_product_purchase_restriction_msg' ,
                'hr_wallet_fund_roundoff_type'                   => 'hrw_advanced_round_off_type' ,
                'hr_wallet_fund_roundoff_method'                 => 'hrw_advanced_round_off_method' ,
                'hrw_fund_type'                                  => 'hrw_general_topup_amount_type' ,
                'hrw_prefilled_fund'                             => 'hrw_general_topup_prefilled_amount' ,
                'hr_wallet_balance_display_cart'                 => 'hrw_advanced_enable_cart_wallet_balance' ,
                'hr_wallet_balance_display_checkout'             => 'hrw_advanced_enable_checkout_wallet_balance' ,
                'hrw_custom_css'                                 => 'hrw_advanced_custom_css' ,
                'hr_wallet_localalize_topup_form_title'          => 'hrw_localizations_topup_form_title_label' ,
                'hr_wallet_localalize_topup_form_amount'         => 'hrw_localizations_topup_form_amount_label' ,
                'hr_wallet_localalize_topup_form_placeholder'    => 'hrw_localizations_topup_form_amount_placeholder' ,
                'hr_wallet_localalize_topup_form_button_text'    => 'hrw_localizations_topup_form_button_label' ,
                'hr_wallet_localalize_transaction_wallet_status' => 'hrw_localizations_wallet_balance_status_label' ,
                'hr_wallet_localalize_transaction_wallet_bal'    => 'hrw_localizations_wallet_balance_label' ,
                'hr_wallet_localalize_transaction_exp_date'      => 'hrw_localizations_wallet_balance_expiry_date_label' ,
                'hr_wallet_localalize_transaction_s_no'          => 'hrw_localizations_transaction_log_sno_label' ,
                'hr_wallet_localalize_transaction_event'         => 'hrw_localizations_transaction_log_event_label' ,
                'hr_wallet_localalize_transaction_total'         => 'hrw_localizations_transaction_log_total_label' ,
                'hr_wallet_localalize_transaction_date'          => 'hrw_localizations_transaction_log_date_label' ,
                'hr_wallet_log_topup_success'                    => 'hrw_localizations_wallet_topup_success_log' ,
                'hr_wallet_log_through_gateway'                  => 'hrw_localizations_wallet_usage_through_gateway_log' ,
                'hr_wallet_log_partial_wallet_usage'             => 'hrw_localizations_partial_wallet_usage_log' ,
                'hr_wallet_log_funds_credited'                   => 'hr_wallet_log_funds_credited' ,
                'hr_wallet_log_funds_debited'                    => 'hr_wallet_log_funds_debited' ,
                'hr_wallet_actions_order_cancel'                 => 'hrw_localizations_order_cancel_debit_amount_log' ,
                'hr_wallet_actions_order_refund'                 => 'hrw_localizations_order_refund_debit_amount_log' ,
                'hr_wallet_msg_min_fund_topup'                   => 'hrw_messages_minimum_topup_amount_msg' ,
                'hr_wallet_msg_max_fund_topup'                   => 'hrw_messages_maximum_topup_amount_msg' ,
                'hr_wallet_msg_max_wallet_balance'               => 'hrw_messages_topup_maximum_wallet_balance_msg' ,
                'hr_wallet_msg_min_cart_total'                   => 'hrw_messages_wallet_usage_minimum_cart_total_msg' ,
                'hr_wallet_msg_max_wallet_balance'               => 'hrw_messages_wallet_usage_maximum_cart_total_msg' ,
                'hr_wallet_msg_usage_restriction_purchase'       => 'hr_wallet_msg_usage_restriction_purchase' ,
                'hr_wallet_msg_topup_restriction_user'           => 'hrw_messages_topup_user_restriction_msg' ,
                'hr_wallet_msg_usage_restriction_user'           => 'hr_wallet_msg_usage_restriction_user' ,
                'hr_wallet_msg_insuff_funds'                     => 'hr_wallet_msg_insuff_funds' ,
                'hr_wallet_topup_msg_cart_enable'                => 'hrw_messages_enable_topup_cart_msg' ,
                'hr_wallet_msg_cart_topup'                       => 'hrw_messages_topup_cart_msg' ,
                'hr_wallet_topup_msg_checkout_enable'            => 'hrw_messages_enable_topup_checkout_msg' ,
                'hr_wallet_msg_checkout_topup'                   => 'hrw_messages_topup_checkout_msg' ,
                'hr_wallet_low_balance_notify_enable'            => 'hrw_messages_enable_low_wallet_balance_msg' ,
                'hr_wallet_msg_low_balance_notify'               => 'hrw_messages_low_wallet_balance_msg' ,
                'hr_wallet_balance_partial_usage_enable'         => 'hr_wallet_balance_partial_usage_enable' ,
                'hr_wallet_msg_balance_partial_usage'            => 'hr_wallet_msg_balance_partial_usage'
                    ) ;

            foreach ( $options as $old_key => $new_key ) {
                update_option( $new_key , get_option( $old_key ) ) ;
            }

            // product id update
            $product_id = get_option( 'hr_wallet_product' ) ;
            update_option( 'hrw_general_topup_product_id' , array_filter( array( $product_id ) ) ) ;

            return false ;
        }

        /**
         * Complete
         */
        protected function complete() {
            parent::complete() ;

            hrw()->background_process()->update_progress_count( 100 ) ;
            HRW_WooCommerce_Log::log( 'Settings Upgrade Completed' ) ;
            update_option( 'hrw_upgrade_success' , 'yes' ) ;
            update_option( 'hrw_update_version' , HRW_VERSION ) ;
        }

    }

}