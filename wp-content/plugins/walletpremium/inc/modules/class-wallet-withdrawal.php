<?php

/**
 * Wallet Withdrawal
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Wallet_Withdrawal_Module' ) ) {

    /**
     * Class HRW_Wallet_Withdrawal_Module
     */
    class HRW_Wallet_Withdrawal_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                   => 'no' ,
            'withdrawal_type'           => '' ,
            'withdrawal_minimum_value'  => '' ,
            'withdrawal_maximum_value'  => '' ,
            'enable_withdrawal_fee'     => 'no' ,
            'withdrawal_fee_type'       => '' ,
            'withdrawal_fee_value'      => '' ,
            'enable_email_otp'          => '' ,
            'otp_email_subject'         => '' ,
            'otp_email_message'         => '' ,
            'enable_sms_otp'            => '' ,
            'otp_sms_message'           => '' ,
            'otp_character_count'       => '' ,
            'otp_validity'              => '' ,
            'user_registered_day_count' => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'wallet_withdrawal' ;
            $this->title = esc_html__ ( 'Wallet Withdrawal' , HRW_LOCALE ) ;

            parent::__construct () ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium () ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf ( esc_html__ ( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__ ( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            $section_fields[] = array () ;

            global $current_action ;

            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'General Settings' , HRW_LOCALE ) ,
                'id'    => 'wallet_withdrawal_general_options' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Minimum Amount for Wallet Withdrawal' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'withdrawal_minimum_value' ) ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                'desc'              => esc_html__ ( 'The minimum amount which the user can request for withdrawal' , HRW_LOCALE )
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Maximum Amount for Wallet Withdrawal' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'withdrawal_maximum_value' ) ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                'desc'              => esc_html__ ( 'The maximum amount which the user can request for withdrawal' , HRW_LOCALE )
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Charge Withdrawal Fee' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'enable_withdrawal_fee' ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                'desc'    => esc_html__ ( 'When enabled, users can be charged a withdrawal fee' , HRW_LOCALE )
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Fee Type' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'withdrawal_fee_type' ) ,
                'class'   => 'hrw_withdrawal_fee_options' ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => array (
                    '1' => esc_html__ ( 'Fixed' , HRW_LOCALE ) ,
                    '2' => esc_html__ ( 'Percentage' , HRW_LOCALE ) ,
                ) ,
                'desc'    => esc_html__ ( 'Choose whether the withdrawal fee should be fixed fee or a percentage of the withdrawal amount' , HRW_LOCALE )
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Fee' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'withdrawal_fee_value' ) ,
                'class'             => 'hrw_withdrawal_fee_options' ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_withdrawal_general_options' ,
                    ) ;
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Form Fields' , HRW_LOCALE ) ,
                'id'    => 'wallet_withdrawal_form_fields' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Current Wallet Balance Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'current_balance_label' ) ,
                'type'    => 'text' ,
                'default' => 'Current Wallet Balance' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Enter the Amount for Withdrawal Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'amount_label' ) ,
                'type'    => 'text' ,
                'default' => 'Enter the Amount for Withdraw' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Withdrawal Fee Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'fee_label' ) ,
                'type'    => 'text' ,
                'default' => 'Withdrawal Fee' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Reason for Withdrawal Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'reason_label' ) ,
                'type'    => 'text' ,
                'default' => 'Reason for Withdrawal' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Payment Method Label' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'payment_method_label' ) ,
                'type'    => 'text' ,
                'default' => 'Payment Method' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_withdrawal_form_fields' ,
                    ) ;
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Payment Settings' , HRW_LOCALE ) ,
                'id'    => 'wallet_withdrawal_payment_settings' ,
                    ) ;
            $section_fields[] = array (
                'id'   => 'hrw_sorted_payments_status' ,
                'type' => 'withdrawal_payment_preferrence_table' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_withdrawal_payment_settings' ,
                    ) ;
            //OTP section start
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'OTP Settings' , HRW_LOCALE ) ,
                'id'    => 'wallet_withdrawal_security_options' ,
                    ) ;
            if ( HRW_Module_Instances::get_module_by_id ( 'sms' )->is_enabled () ) {
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Send OTP via Email' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'enable_email_otp' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'yes' ,
                        ) ;
            }
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Email Subject' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'otp_email_subject' ) ,
                'class'   => 'hrw_withdrawal_otp_email' ,
                'type'    => 'text' ,
                'default' => '{site_name} One Time Password for Wallet Fund Transfer' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Email Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'otp_email_message' ) ,
                'class'   => 'hrw_withdrawal_otp_email' ,
                'type'    => 'textarea' ,
                'default' => 'Hi {user_name},

{otp} is the One Time Password for Wallet Fund Transfer of {transfer_amount} to {receiver_email} on {site_name}.

Note:

This One Time Password is valid only for {otp_validity} minutes.' ,
                    ) ;
            if ( HRW_Module_Instances::get_module_by_id ( 'sms' )->is_enabled () ) {
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Send OTP via SMS' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'enable_sms_otp' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'SMS Message' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'otp_sms_message' ) ,
                    'class'   => 'hrw_withdrawal_otp_sms' ,
                    'type'    => 'textarea' ,
                    'default' => 'OTP to approve wallet transfer of {transfer_amount} to {receiver_email} on {site_name} is {otp}.' ,
                        ) ;
            }
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Number of Characters' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'otp_character_count' ) ,
                'type'              => 'number' ,
                'custom_attributes' => array ( 'min' => 4 ) ,
                'default'           => '4' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'OTP Validity in Minutes' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'otp_validity' ) ,
                'custom_attributes' => array ( 'min' => 5 , 'max' => 60 ) ,
                'type'              => 'number' ,
                'default'           => '5' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_withdrawal_security_options' ,
                    ) ;
            //OTP section end

            if ( $current_action === "edit" ) {

                unset ( $section_fields ) ;

                $section_fields[] = array (
                    'id'   => 'hrw_withdrawal_info' ,
                    'type' => $this->edit_wallet_withdrawal_page () ,
                        ) ;
            }

            return $section_fields ;
        }

        /*
         * Output Payment Preferrence Table
         */

        public function output_payment_preference_table() {

            $status = hrw_payment_method_preference_status () ;

            $available_payments = is_array ( get_option ( 'hrw_sorted_payments_status' , array ( 'bank_transfer' => 'enable' , 'paypal' => 'enable' ) ) ) ? get_option ( 'hrw_sorted_payments_status' , array ( 'bank_transfer' => 'enable' , 'paypal' => 'enable' ) ) : $status ;

            include HRW_PLUGIN_PATH . '/inc/admin/menu/views/sort-payments.php' ;
        }

        /*
         * Edit Wallet Withdrawal Page
         */

        public function edit_wallet_withdrawal_page() {

            $withdrawal_obj = hrw_get_wallet_withdrawal ( absint ( $_GET[ 'id' ] ) ) ;

            if ( ! is_object ( $withdrawal_obj ) || ! $withdrawal_obj->exists () )
                return ;

            include HRW_PLUGIN_PATH . '/inc/modules/views/edit-withdrawal.php' ;
        }

        /*
         * Update Wallet Withdrawal Status
         */

        public function update_withdrawal_status() {
            check_admin_referer ( $this->plugin_slug . '_edit_withdrawal' , '_' . $this->plugin_slug . '_withdrawal_nonce' ) ;

            try {
                $id = isset ( $_REQUEST[ 'id' ] ) ? absint ( $_REQUEST[ 'id' ] ) : '' ;

                $status = isset ( $_REQUEST[ 'hrw_edit_status' ] ) ? $_REQUEST[ 'hrw_edit_status' ] : 'hrw_unpaid' ;

                if ( empty ( $id ) || get_post_status ( $id ) == 'hrw_paid' || get_post_status ( $id ) == 'hrw_cancelled' )
                    throw new Exception ( esc_html__ ( 'Cannot Update Status' , HRW_LOCALE ) ) ;

                $meta_args = array () ;

                if ( $status != 'hrw_unpaid' || $status != 'hrw_cancelled' ) {

                    $meta_args = array (
                        'hrw_processed_date' => current_time ( 'mysql' , true ) ,
                            ) ;
                }

                if ( $status == 'hrw_paid' ) {
                    do_action ( 'hrw_withdrawal_success_notification' , $id ) ;
                } else if ( $status == 'hrw_cancelled' ) {
                    do_action ( 'hrw_withdrawal_rejected_notification' , $id ) ;
                }

                $post_args = array (
                    'post_status' => hrw_sanitize_text_field ( $status ) ,
                        ) ;

                hrw_update_wallet_withdrawal ( $id , $meta_args , $post_args ) ;

                if ( $status == 'hrw_cancelled' && ! get_post_meta ( $id , 'hrw_refund_wihtdrawal' , true ) ) {
                    HRWP_Withdrawal_Handler::handle_wallet_credit ( $id ) ;
                    update_post_meta ( $id , 'hrw_refund_wihtdrawal' , '1' ) ;
                }
            } catch ( Exception $ex ) {
                HRW_Form_Handler::add_error ( $ex->getMessage () ) ;
            }
        }

        /*
         * Frontend external js files
         */

        public function frontend_external_js_files() {

            //Enqueue Withdrawal script
            wp_enqueue_script ( 'hrw_withdrawal' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/withdrawal.js' , array ( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;

            wp_localize_script (
                    'hrw_withdrawal' , 'hrw_withdrawal_params' , array (
                'ajax_url'              => admin_url ( 'admin-ajax.php' ) ,
                'withdrawal_nonce'      => wp_create_nonce ( 'hrw-withdrawal_nonce' ) ,
                'user_id'               => get_current_user_id () ,
                'withdrawal_alert_msg'  => esc_html__ ( 'Are you sure you want to cancel?' , HRW_LOCALE ) ,
                'enable_withdrawal_fee' => get_option ( 'hrw_wallet_withdrawal_enable_withdrawal_fee' , 'no' ) ,
                'withdrawal_fee_type'   => get_option ( 'hrw_wallet_withdrawal_withdrawal_fee_type' , '1' ) ,
                'withdrawal_fee_value'  => get_option ( 'hrw_wallet_withdrawal_withdrawal_fee_value' , '' ) ,
                    )
            ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {

            add_action ( 'hrw_admin_field_withdrawal_payment_preferrence_table' , array ( $this , 'output_payment_preference_table' ) ) ;

            add_action ( 'wp_ajax_hrw_cancel_withdrawal' , array ( $this , 'cancel_withdrawal' ) ) ;
        }

        /*
         * Frontend action
         */

        public function frontend_action() {
            //Add Wallet Withdrawal dashboard menu
            add_filter ( 'hrw_frontend_dashboard_menu' , array ( $this , 'add_dashboard_menu' ) , 10 , 1 ) ;
            //Add fund transfer dashboard submenu
            add_filter ( 'hrw_frontend_dashboard_wallet_withdrawal_submenus' , array ( $this , 'add_dashboard_submenu' ) , 10 , 1 ) ;
            //Display Wallet Withdrawal form
            add_action ( 'hrw_add_shortcodes' , array ( $this , 'render_wallet_withdrawal_form' ) , 10 , 1 ) ;
            //Display Wallet Withdrawal Transaction submenu content
            add_action ( 'hrw_frontend_dashboard_menu_content_withdrawal_transactions' , array ( $this , 'render_wallet_withdrawal_transactions' ) ) ;
            //Display Wallet Withdrawal submenu content
            add_action ( 'hrw_frontend_dashboard_menu_content_wallet_withdrawal_form' , array ( $this , 'render_wallet_withdrawal_form' ) ) ;
            //Process Wallet Withdrawal form
            add_action ( 'wp_loaded' , array ( $this , 'process_wallet_withdrawal' ) ) ;
        }

        /*
         * Add Wallet Withdrawal Dashboard Menu
         */

        public function add_dashboard_menu( $menus ) {

            $menus[ 'wallet_withdrawal' ] = array (
                'label' => get_option ( 'hrw_dashboard_customization_withdrawal_label' , 'Wallet Withdrawal' ) ,
                'code'  => 'fa fa-money' ,
                    ) ;

            return $menus ;
        }

        /*
         * Add Wallet Withdrawal Dashboard Submenu
         */

        public function add_dashboard_submenu( $submenus ) {
            $submenus[ 'withdrawal_transactions' ] = get_option ( 'hrw_dashboard_customization_appointments_label' , 'Transactions' ) ;
            $submenus[ 'wallet_withdrawal_form' ]  = get_option ( 'hrw_dashboard_customization_appointments_label' , 'Withdrawal' ) ;

            return $submenus ;
        }

        /*
         * Display Wallet Withdrawal Transactions
         */

        public function render_wallet_withdrawal_transactions() {

            $per_page     = 5 ;
            $current_page = HRW_Dashboard::get_current_page_number () ;

            $default_args = array (
                'post_type'      => HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ,
                'post_status'    => hrw_get_withdrawal_log_statuses () ,
                'author'         => HRW_Wallet_User::get_user_id () ,
                'fields'         => 'ids' ,
                'posts_per_page' => '-1'
                    ) ;

            /* Calculate Page Count */
            $overall_count = get_posts ( $default_args ) ;
            $page_count    = ceil ( count ( $overall_count ) / $per_page ) ;

            $default_args[ 'offset' ]         = ($current_page - 1) * $per_page ;
            $default_args[ 'posts_per_page' ] = $per_page ;

            $data_args = array (
                'withdrawal_ids' => get_posts ( $default_args ) ,
                'serial_number'  => ( $current_page * $per_page ) - $per_page + 1 ,
                'pagination'     => array (
                    'page_count'      => $page_count ,
                    'current_page'    => $current_page ,
                    'next_page_count' => (($current_page + 1) > ($page_count - 1)) ? ($current_page) : ($current_page + 1) ,
                ) ) ;
            
            hrw_get_template ( 'dashboard/wallet-withdrawal-transactions.php' , true , $data_args ) ;
        }

        /*
         * Render Wallet Withdrawal Form
         */

        public function render_wallet_withdrawal_form() {
            global $hrw_otp_enabled ;

            $amount         = isset ( $_POST[ 'hrw_withdrawal' ][ 'amount' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw_withdrawal' ][ 'amount' ] ) : 0 ;
            $reason         = isset ( $_POST[ 'hrw_withdrawal' ][ 'reason' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw_withdrawal' ][ 'reason' ] ) : '' ;
            $fee            = isset ( $_POST[ 'hrw_withdrawal' ][ 'fee' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw_withdrawal' ][ 'fee' ] ) : 0 ;
            $payment_method = isset ( $_POST[ 'hrw_withdrawal' ][ 'payment_method' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw_withdrawal' ][ 'payment_method' ] ) : '' ;
            $bank_details   = isset ( $_POST[ 'hrw_withdrawal' ][ 'bank_details' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw_withdrawal' ][ 'bank_details' ] ) : '' ;
            $paypal_details = isset ( $_POST[ 'hrw_withdrawal' ][ 'paypal_details' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw_withdrawal' ][ 'paypal_details' ] ) : '' ;

            $fields_data = array (
                'amount'         => $amount ,
                'reason'         => $reason ,
                'fee'            => $fee ,
                'payment_method' => $payment_method ,
                'bank_details'   => $bank_details ,
                'paypal_details' => $paypal_details
                    ) ;

            try {
                if ( $hrw_otp_enabled ) {
                    hrw_get_template ( 'dashboard/wallet-withdrawal-confirmation.php' , true , $fields_data ) ;
                } else {
                    hrw_get_template ( 'dashboard/wallet-withdrawal.php' , true , $fields_data ) ;
                }
            } catch ( Exception $ex ) {
                HRW_Form_Handler::show_info ( $ex->getMessage () ) ;
            }
        }

        /*
         * Process Wallet Withdrawal form
         */

        public function process_wallet_withdrawal() {

            global $hrw_otp_enabled ;
            $nonce_value = isset ( $_POST[ 'hrw-withdrawal-nonce' ] ) ? hrw_sanitize_text_field ( $_POST[ 'hrw-withdrawal-nonce' ] ) : null ;
            if ( ! isset ( $_POST[ 'hrw-action' ] ) || empty ( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce ( $nonce_value , 'hrw-withdrawal' ) )
                return ;

            try {
                if ( ! isset ( $_POST[ 'hrw_withdrawal' ] ) )
                    throw new Exception ( esc_html__ ( 'Invalid Request' , HRW_LOCALE ) ) ;

                $withdrawal = $_POST[ 'hrw_withdrawal' ] ;

                if ( empty ( $withdrawal[ 'amount' ] ) )
                    throw new Exception ( esc_html__ ( 'Please enter the amount to withdraw' , HRW_LOCALE ) ) ;

                $fee_amount = isset ( $withdrawal[ 'fee' ] ) ? floatval ( $withdrawal[ 'fee' ] ) : 0 ;

                $amount = floatval ( $withdrawal[ 'amount' ] ) ;

                if ( HRW_Wallet_User::get_available_balance () < ($amount + $fee_amount) )
                    throw new Exception ( esc_html__ ( 'Insufficient balance to withdraw funds' , HRW_LOCALE ) ) ;

                do_action ( 'hrw_do_withdrawal_validation' , $amount , $fee_amount ) ;

                if ( $this->withdrawal_minimum_value && $this->withdrawal_minimum_value > $amount )
                    throw new Exception ( sprintf ( esc_html__ ( 'Please enter amount more than %s to withdraw' , HRW_LOCALE ) , hrw_price ( $this->withdrawal_minimum_value ) ) ) ;

                if ( $this->withdrawal_maximum_value && $this->withdrawal_maximum_value < $amount )
                    throw new Exception ( sprintf ( esc_html__ ( 'Please enter amount less than %s to withdraw' , HRW_LOCALE ) , hrw_price ( $this->withdrawal_maximum_value ) ) ) ;

                //Validate the payment method
                $bank_details   = '' ;
                $paypal_details = '' ;
                $payment_method = hrw_sanitize_text_field ( $withdrawal[ 'payment_method' ] ) ;

                if ( $payment_method == 'paypal' ) {
                    if ( empty ( $withdrawal[ 'paypal_details' ] ) )
                        throw new Exception ( esc_html__ ( 'Please enter your PayPal Account Email ID' , HRW_LOCALE ) ) ;

                    $paypal_details = hrw_sanitize_text_field ( $withdrawal[ 'paypal_details' ] ) ;

                    if ( ! is_email ( $paypal_details ) )
                        throw new Exception ( esc_html__ ( 'Please enter a valid PayPal Email ID' , HRW_LOCALE ) ) ;
                } else {
                    if ( empty ( $withdrawal[ 'bank_details' ] ) )
                        throw new Exception ( esc_html__ ( 'Please enter your Bank Account details' , HRW_LOCALE ) ) ;

                    $bank_details = hrw_sanitize_text_field ( $withdrawal[ 'bank_details' ] ) ;
                }


                if ( isset ( $withdrawal[ 'verify_otp' ] ) ) {
                    $hrw_otp_enabled = true ;
                    $verify_otp      = hrw_sanitize_text_field ( $withdrawal[ 'verify_otp' ] ) ;
                    $saved_otp       = get_post_meta ( HRW_Wallet_User::get_wallet_id () , 'hrw_withdrawal_otp' , true ) ;

                    if ( $this->otp_validity ) {
                        $validity = get_post_meta ( HRW_Wallet_User::get_wallet_id () , 'hrw_withdrawal_otp_validity' , true ) ;

                        if ( time () >= $validity )
                            throw new Exception ( esc_html__ ( 'OTP Expired' , HRW_LOCALE ) ) ;
                    }

                    if ( $verify_otp != $saved_otp )
                        throw new Exception ( esc_html__ ( 'The OTP which you have given is incorrect. Please enter correct OTP' , HRW_LOCALE ) ) ;

                    $fee    = 0 ;
                    $reason = hrw_sanitize_text_field ( $withdrawal[ 'reason' ] ) ;

                    //Prepare argument to transfer amount
                    if ( $this->enable_withdrawal_fee == 'yes' ) {
                        $fee_value = ( float ) $this->withdrawal_fee_value ;
                        if ( $this->withdrawal_fee_type == '2' ) {
                            $fee = ($fee_value) ? ($fee_value / 100) * $amount : $fee_value ;
                        } else {
                            $fee = $fee_value ;
                        }
                    }

                    $args = array (
                        'user_id'        => HRW_Wallet_User::get_user_id () ,
                        'wallet_id'      => HRW_Wallet_User::get_wallet_id () ,
                        'amount'         => $amount ,
                        'fee'            => $fee ,
                        'reason'         => $reason ,
                        'payment_method' => $payment_method ,
                        'paypal_details' => $paypal_details ,
                        'bank_details'   => $bank_details ,
                            ) ;

                    HRWP_Withdrawal_Handler::process_wallet_withdrawal ( $args ) ;

                    HRW_Form_Handler::add_message ( esc_html__ ( 'Amount withdrawn successfully' , HRW_LOCALE ) ) ;

                    //reset wallet
                    HRW_Wallet_User::reset () ;

                    $hrw_otp_enabled = false ;
                    unset ( $_POST[ 'hrw-withdrawal-nonce' ] ) ;
                    unset ( $_POST[ 'hrw_withdrawal' ] ) ;
                } else {

                    //Send OTP
                    $this->send_otp ( HRW_Wallet_User::get_wallet_id () , $amount ) ;

                    $hrw_otp_enabled = true ;

                    HRW_Form_Handler::add_message ( esc_html__ ( 'OTP is sent to your Email ID/Phone Number' , HRW_LOCALE ) ) ;
                }
            } catch ( Exception $ex ) {

                HRW_Form_Handler::add_error ( $ex->getMessage () ) ;
            }
        }

        /**
         * Cancel Withdrawal
         */
        public function cancel_withdrawal() {

            check_ajax_referer ( 'hrw-withdrawal_nonce' , 'hrw_security' ) ;

            try {

                if ( ! isset ( $_POST ) || ! isset ( $_POST[ 'post_id' ] ) )
                    throw new exception ( __ ( 'Invalid Request' , HRW_LOCALE ) ) ;

                $post_args = array (
                    'post_status' => 'hrw_cancelled' ,
                        ) ;

                hrw_update_wallet_withdrawal ( absint ( $_POST[ 'post_id' ] ) , array () , $post_args ) ;

                HRWP_Withdrawal_Handler::handle_wallet_credit ( absint ( $_POST[ 'post_id' ] ) ) ;

                do_action ( 'hrw_withdrawal_rejected_notification' , absint ( $_POST[ 'post_id' ] ) ) ;

                wp_send_json_success ( 'success' ) ;
            } catch ( Exception $e ) {
                wp_send_json_error ( array ( 'error' => $e->getMessage () ) ) ;
            }
        }

        /*
         * Send OTP
         */

        public function send_otp( $wallet_id , $amount ) {
            $wallet = hrw_get_wallet ( $wallet_id ) ;

            if ( ! is_object ( $wallet ) )
                return ;

            $validity      = time () + (( int ) $this->otp_validity * 60) ;
            $generated_otp = hrw_generate_random_codes ( array ( 'length' => $this->otp_character_count , 'character_type' => 1 ) ) ;

            //Update user meta OTP Details
            update_post_meta ( $wallet_id , 'hrw_withdrawal_otp_validity' , $validity ) ;
            update_post_meta ( $wallet_id , 'hrw_withdrawal_otp' , $generated_otp ) ;

            //Send OTP to user via SMS
            $this->send_sms_otp ( $wallet , $generated_otp , $amount ) ;

            //Send OTP to user via Email
            $this->send_email_otp ( $wallet , $generated_otp , $amount ) ;
        }

        /*
         * Send SMS OTP
         */

        public function send_sms_otp( $wallet , $generated_otp , $amount ) {
            if ( ! HRW_Module_Instances::get_module_by_id ( 'sms' )->is_enabled () )
                return ;

            if ( $this->enable_sms_otp != 'yes' )
                return ;

            if ( ! $wallet->get_phone () )
                return ;

            $site_name = wp_specialchars_decode ( get_option ( 'blogname' ) , ENT_QUOTES ) ;

            $shortcode_array = array ( '{site_name}' , '{transfer_amount}' , '{otp}' ) ;
            $replace_array   = array ( $site_name , $amount , $generated_otp ) ;

            $message = str_replace ( $shortcode_array , $replace_array , $this->otp_sms_message ) ;

            HRW_SMS_Handler::send_sms ( $wallet->get_phone () , $message ) ;
        }

        /*
         * Send Email OTP
         */

        public function send_email_otp( $wallet , $generated_otp , $amount ) {
            if ( $this->enable_email_otp != 'yes' && HRW_Module_Instances::get_module_by_id ( 'sms' )->is_enabled () )
                return ;

            $site_name = wp_specialchars_decode ( get_option ( 'blogname' ) , ENT_QUOTES ) ;

            $shortcode_array = array ( '{site_name}' , '{transfer_amount}' , '{otp}' , '{otp_validity}' , '{user_name}' ) ;
            $replace_array   = array ( $site_name , $amount , $generated_otp , $this->otp_validity , $wallet->get_user ()->display_name ) ;

            $subject = str_replace ( $shortcode_array , $replace_array , $this->otp_email_subject ) ;
            $message = str_replace ( $shortcode_array , $replace_array , $this->otp_email_message ) ;

            $notifications_object = new HRW_Notifications() ;
            $notifications_object->send_email ( $wallet->get_user ()->user_email , $subject , $message ) ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {

            if ( isset ( $_GET[ 'action' ] ) && sanitize_title ( $_GET[ 'action' ] ) == 'edit' )
                return ;

            if ( ! class_exists ( 'HRW_Wallet_Withdrawal_Table' ) )
                require_once( HRW_PLUGIN_PATH . '/premium/inc/admin/wp-list-table/class-hrw-wallet-withdrawal-table.php' ) ;

            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . esc_html__ ( 'Wallet Withdrawal Logs' , HRW_LOCALE ) . '</h2>' ;

            $post_table = new HRW_Wallet_Withdrawal_Table() ;
            $post_table->prepare_items () ;

            if ( $post_table->has_items () ) {

                echo '<button type="submit" class="' . $this->plugin_slug . '_add_submit_btn" name="' . $this->plugin_slug . '_paypal_mass_payment_csv">' . esc_html__ ( 'Export CSV for Paypal Mass Payment' , HRW_LOCALE ) . '</button>' ;

                if ( isset ( $_REQUEST[ 'hrw_paypal_mass_payment_csv' ] ) )
                    HRWP_Export_CSV::export_paypal_mass_payment_csv () ;
            }

            $post_table->display () ;

            echo '</div>' ;
        }

        /*
         * Before save
         */

        public function before_save() {
            if ( ! empty ( $_POST[ 'hrw-wallet-withdrawal-action' ] ) )
                $this->update_withdrawal_status () ;

            $this->display_validation_error_messages () ;
        }

        /*
         * Display Validation Error Messages
         */

        public function display_validation_error_messages() {

            /* Validate payment when all the status were disabled */
            if ( isset ( $_POST[ 'hrw_sorted_payments_status' ] ) ) {

                $disable_count = 0 ;
                foreach ( $_POST[ 'hrw_sorted_payments_status' ] as $key => $values ) {

                    if ( $values == 'enable' )
                        continue ;

                    $disable_count ++ ;
                }

                $payment_status = hrw_payment_method_preference_status () ;

                if ( count ( $payment_status ) == $disable_count )
                    throw new Exception ( esc_html__ ( 'Enable atleast one payment method' , HRW_LOCALE ) ) ;
            }

            /* Validate fee when the field is left empty */
            if ( isset ( $_POST[ 'hrw_wallet_withdrawal_enable_withdrawal_fee' ] , $_POST[ 'hrw_wallet_withdrawal_withdrawal_fee_value' ] ) ) {

                $enable_fee = $_POST[ 'hrw_wallet_withdrawal_enable_withdrawal_fee' ] == '1' ? true : false ;
                $fee_value  = ! empty ( $_POST[ 'hrw_wallet_withdrawal_withdrawal_fee_value' ] ) ? true : false ;

                if ( $enable_fee && ! $fee_value )
                    throw new Exception ( esc_html__ ( 'Fee value is mandatory' , HRW_LOCALE ) ) ;
            }
        }

    }

}
