<?php
/**
 * Fund Transfer
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Fund_Transfer_Module' ) ) {

    /**
     * Class HRW_Fund_Transfer_Module
     */
    class HRW_Fund_Transfer_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                              => 'no' ,
            'enable_transfer_fund'                 => '' ,
            'transfer_minimum_value'               => '' ,
            'transfer_maximum_value'               => '' ,
            'maximum_transaction_per_day'          => '' ,
            'user_maximum_transaction_per_day'     => '' ,
            'enable_transfer_fee'                  => '' ,
            'transfer_fee_type'                    => '' ,
            'transfer_fee_value'                   => '' ,
            'enable_request_fund'                  => '' ,
            'request_minimum_value'                => '' ,
            'request_maximum_value'                => '' ,
            'maximum_request_per_day'              => '' ,
            'user_maximum_request_per_day'         => '' ,
            'enable_non_approved_users'            => '' ,
            'enable_non_approved_users_list'       => '' ,
            'user_restriction_type'                => '' ,
            'user_restriction'                     => array() ,
            'user_role_restriction'                => array() ,
            'site_activity_type'                   => '' ,
            'registered_day_count'                 => '' ,
            'purchased_amount'                     => '' ,
            'order_placed_count'                   => '' ,
            'transfer_minimum_value_msg'           => '' ,
            'transfer_maximum_value_msg'           => '' ,
            'maximum_transaction_per_day_msg'      => '' ,
            'user_maximum_transaction_per_day_msg' => '' ,
            'request_minimum_value_msg'            => '' ,
            'request_maximum_value_msg'            => '' ,
            'maximum_request_per_day_msg'          => '' ,
            'user_maximum_request_per_day_msg'     => '' ,
            'fund_debit_localization'              => '' ,
            'fund_credit_localization'             => '' ,
            'fund_request_alert_msg'               => '' ,
            'fund_request_transfer_alert_msg'      => '' ,
            'fund_request_cancel_alert_msg'        => '' ,
            'fund_request_decline_alert_msg'       => '' ,
            'enable_email_otp'                     => '' ,
            'otp_email_subject'                    => '' ,
            'otp_email_message'                    => '' ,
            'enable_sms_otp'                       => '' ,
            'otp_sms_message'                      => '' ,
            'otp_character_count'                  => '' ,
            'otp_validity'                         => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'fund_transfer' ;
            $this->title = esc_html__( 'Fund Transfer' , HRW_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium() ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf( esc_html__( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            $section_fields = array() ;
            $user_roles     = hrw_get_user_roles() ;

            //Fund Transfer section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Fund Transfer Settings' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_options' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Enable Fund Transfer' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'enable_transfer_fund' ) ,
                'desc'    => esc_html__( 'When enabled, users will be able to directly transfer their Wallet balance to other users in the site' , HRW_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Minimum Amount for Wallet Transfer per Transaction' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'transfer_minimum_value' ) ,
                'desc'              => esc_html__( 'The minimum amount which can be transferred in a single transaction' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_transfer_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Maximum Amount for Wallet Transfer per Transaction' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'transfer_maximum_value' ) ,
                'desc'              => esc_html__( 'The maximum amount which can be transferred in a single transaction' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_transfer_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Max Number of Transactions per Day' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'maximum_transaction_per_day' ) ,
                'desc'              => esc_html__( 'The maximum number of wallet transfers which can be performed in a day' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_transfer_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Maximum Number of Unique Transfers(unique users) per Day' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'user_maximum_transaction_per_day' ) ,
                'desc'              => esc_html__( 'The maximum number of transfers that can be made to one user in a day' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_transfer_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Charge Wallet Transfer Fee' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'enable_transfer_fee' ) ,
                'desc'    => esc_html__( 'When enabled, users can be charged a fee for transferring wallet balance' , HRW_LOCALE ) ,
                'class'   => 'hrw_fund_transfer_options' ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Transfer Fee Type' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'transfer_fee_type' ) ,
                'class'   => 'hrw_fund_transfer_options hrw_transfer_fee_options' ,
                'type'    => 'select' ,
                'default' => '1' ,
                'options' => array(
                    '1' => esc_html__( 'Fixed' , HRW_LOCALE ) ,
                    '2' => esc_html__( 'Percentage' , HRW_LOCALE )
                )
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Transfer Fee Value' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'transfer_fee_value' ) ,
                'class'             => 'hrw_fund_transfer_options hrw_transfer_fee_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_transfer_options' ,
                    ) ;
            //Fund Transfer section end
            //Fund Request section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Fund Request Settings' , HRW_LOCALE ) ,
                'id'    => 'fund_request_options' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Enable Fund Request' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'enable_request_fund' ) ,
                'desc'    => esc_html__( 'When enabled, users will be able to request for funds from other users in the site' , HRW_LOCALE ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Minimum Amount for Wallet Fund Request per Transaction' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'request_minimum_value' ) ,
                'desc'              => esc_html__( 'The minimum amount which can be requested in a single transaction' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_request_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Maximum Amount for Wallet Fund Request per Transaction' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'request_maximum_value' ) ,
                'desc'              => esc_html__( 'The maximum amount which can be requested in a single transaction' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_request_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Maximum Number of Requests per Day' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'maximum_request_per_day' ) ,
                'desc'              => esc_html__( 'The maximum number of wallet requests which can be submitted in a day' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_request_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Maximum Number of Unique Requests(unique users) per Day' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'user_maximum_request_per_day' ) ,
                'desc'              => esc_html__( 'The maximum number of requests that can be submitted to one user in a day' , HRW_LOCALE ) ,
                'class'             => 'hrw_fund_request_options' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_request_options' ,
                    ) ;
            //Fund Request section end
            //restriction section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Restriction Settings' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_restriction_options' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'User Selection' , HRW_LOCALE ) ,
                'type'    => 'select' ,
                'default' => '1' ,
                'id'      => $this->get_field_key( 'user_restriction_type' ) ,
                'class'   => 'hrw_user_selection_type' ,
                'options' => array(
                    '1' => esc_html__( 'All Users' , HRW_LOCALE ) ,
                    '2' => esc_html__( 'Selected Users' , HRW_LOCALE ) ,
                    '3' => esc_html__( 'Selected User Roles' , HRW_LOCALE ) ,
                ) ,
                    ) ;
            $section_fields[] = array(
                'title'       => esc_html__( 'Select User(s)' , HRW_LOCALE ) ,
                'id'          => $this->get_field_key( 'user_restriction' ) ,
                'action'      => 'hrw_customers_search' ,
                'class'       => 'hrw_selected_users hrw_user_selection' ,
                'type'        => 'ajaxmultiselect' ,
                'list_type'   => 'customers' ,
                'placeholder' => esc_html__( 'Select a User' , HRW_LOCALE ) ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Select User Roles' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'user_role_restriction' ) ,
                'class'   => 'hrw_select2 hrw_selected_user_roles hrw_user_selection' ,
                'type'    => 'multiselect' ,
                'options' => $user_roles ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Site Activity' , HRW_LOCALE ) ,
                'type'    => 'select' ,
                'default' => '' ,
                'class'   => 'hrw_site_activity_type' ,
                'options' => array(
                    ''  => esc_html__( 'Choose an Option' , HRW_LOCALE ) ,
                    '1' => esc_html__( 'No of Days Registered' , HRW_LOCALE ) ,
                    '2' => esc_html__( 'Total Purchase Amount on the Site' , HRW_LOCALE ) ,
                    '3' => esc_html__( 'Total Number of Orders Placed' , HRW_LOCALE ) ,
                ) ,
                'id'      => $this->get_field_key( 'site_activity_type' ) ,
                'desc'    => esc_html__( 'This option controls the wallet transfer feature availability to the users based on their site activity' , HRW_LOCALE ) ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'No of Days Registered' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'registered_day_count' ) ,
                'class'             => 'hrw_registered_days_site_activity hrw_site_activity' ,
                'type'              => 'number' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Purchase Amount' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'purchased_amount' ) ,
                'class'             => 'hrw_purchase_amount_site_activity hrw_site_activity' ,
                'type'              => 'number' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'Order Placed' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'order_placed_count' ) ,
                'class'             => 'hrw_order_placed_site_activity hrw_site_activity' ,
                'type'              => 'number' ,
                'custom_attributes' => array( 'min' => 0 ) ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_transfer_restriction_options' ,
                    ) ;
            //restriction section end
            //Message section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Messages' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_message_options' ,
                    ) ;
            $section_fields[] = array(
                'type'  => 'subtitle' ,
                'title' => esc_html__( 'Fund Transfer Messages' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_message_subtitle' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Minimum Wallet Transfer Amount Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'transfer_minimum_value_msg' ) ,
                'desc'    => esc_html__( 'Error Message which has to be displayed when the user tries to transfer an amount which is less than the minimum transfer amount' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'Please enter an amount more than [minimum_amount]' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Maximum Wallet Transfer Amount Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'transfer_maximum_value_msg' ) ,
                'desc'    => esc_html__( 'Error Message which has to be displayed when the user tries to transfer an amount which is more than the maximum transfer amount' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'Please enter an amount less than [maximum_amount]' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Maximum Number of Transfers Per Day Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'maximum_transaction_per_day_msg' ) ,
                'desc'    => esc_html__( 'Error Message which has to be displayed when the user has exceeded the maximum number of transfers per day' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'You have reached the Maximum Number of Transfers for Today. Please try tomorrow' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Maximum Number of Unique Transfers(unique users) per Day  Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'user_maximum_transaction_per_day_msg' ) ,
                'desc'    => esc_html__( 'Error message which has to be displayed when the user has exceeded the maximum number of unique transfers per day' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'You have reached the Maximum User count for transfers for Today. Please try tomorrow' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Transfer Restriction Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_trnsfer_restriction_msg' ) ,
                'desc'    => esc_html__( 'The maximum number of requests that can be Fund Transfer Restriction Message' , HRW_LOCALE ) ,
                'class'   => 'hrw_fund_request_options' ,
                'type'    => 'text' ,
                'default' => 'Currently, you are being restricted to transfer the funds to others.' ,
                    ) ;
            $section_fields[] = array(
                'type'  => 'subtitle' ,
                'title' => esc_html__( 'Fund Request Messages' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_message_subtitle' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Minimum Wallet Transfer Amount Request Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'request_minimum_value_msg' ) ,
                'desc'    => esc_html__( 'Error Message which has to be displayed when the user tries to request an amount which is less than the minimum request amount' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'Please enter an amount more than [minimum_amount]' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Maximum Wallet Transfer Amount Request Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'request_maximum_value_msg' ) ,
                'desc'    => esc_html__( 'Error Message which has to be displayed when the user tries to request an amount which is more than the maximum request amount' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'Please enter an amount less than [maximum_amount]' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Maximum Number of Requests Per Day Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'maximum_request_per_day_msg' ) ,
                'desc'    => esc_html__( 'Error Message which has to be displayed when the user has exceeded the maximum number of requests per day' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'You have reached the Maximum Number of Requests for Today. Please try tomorrow' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Maximum Number of Unique Requests(unique users) per Day  Error Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'user_maximum_request_per_day_msg' ) ,
                'desc'    => esc_html__( 'Error message which has to be displayed when the user has exceeded the maximum number of unique requests per day' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => 'You have reached the Maximum User count for requests for Today. Please try tomorrow' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Request Restriction Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'hrw_req_restriction_msg' ) ,
                'desc'    => esc_html__( 'The maximum number of requests that can be Fund Request Restriction Message' , HRW_LOCALE ) ,
                'class'   => 'hrw_fund_request_options' ,
                'type'    => 'text' ,
                'default' => 'Currently, you are being restricted to give fund request to others.' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_transfer_message_options' ,
                    ) ;
            //Message section end
            //Localization section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Localization' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_localization_options' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Transferred' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_debit_localization' ) ,
                'type'    => 'text' ,
                'default' => 'You have successfully transferred [amount] to [user_name]' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Received' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_credit_localization' ) ,
                'type'    => 'text' ,
                'default' => 'You have successfully received [amount] from [user_name]' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_transfer_localization_options' ,
                    ) ;
            //Localization section end
            //Alert message section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Alert Messages for Fund Request' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_localization_options' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Request' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_request_alert_msg' ) ,
                'type'    => 'text' ,
                'default' => 'Are you sure you want to give a request?' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Transfered' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_request_transfer_alert_msg' ) ,
                'type'    => 'text' ,
                'default' => 'Are you sure you want to pay this request?' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Cancel' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_request_cancel_alert_msg' ) ,
                'type'    => 'text' ,
                'default' => 'Are you sure you want to cancel this request?' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Decline' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'fund_request_decline_alert_msg' ) ,
                'type'    => 'text' ,
                'default' => 'Are you sure you want to decline this request?' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_transfer_localization_options' ,
                    ) ;
            //Alert message section end
            //OTP section start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'OTP Settings' , HRW_LOCALE ) ,
                'id'    => 'fund_transfer_otp_options' ,
                    ) ;
            if ( HRW_Module_Instances::get_module_by_id( 'sms' )->is_enabled() ) {
                $section_fields[] = array(
                    'title'   => esc_html__( 'Send OTP via Email' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'enable_email_otp' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
            }
            $section_fields[] = array(
                'title'   => esc_html__( 'Email Subject' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'otp_email_subject' ) ,
                'type'    => 'text' ,
                'class'   => 'hrw_fund_transfer_otp_email' ,
                'default' => '{site_name} One Time Password for Wallet Fund Transfer' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Email Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'otp_email_message' ) ,
                'type'    => 'textarea' ,
                'class'   => 'hrw_fund_transfer_otp_email' ,
                'default' => 'Hi {user_name},

{otp} is the One Time Password for Wallet Fund Transfer of {transfer_amount} to {receiver_email} on {site_name}.

Note:

This One Time Password is valid only for {otp_validity} minutes.' ,
                    ) ;
            if ( HRW_Module_Instances::get_module_by_id( 'sms' )->is_enabled() ) {
                $section_fields[] = array(
                    'title'   => esc_html__( 'Send OTP via SMS' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'enable_sms_otp' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
                $section_fields[] = array(
                    'title'   => esc_html__( 'SMS Message' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'otp_sms_message' ) ,
                    'type'    => 'textarea' ,
                    'class'   => 'hrw_fund_transfer_otp_sms' ,
                    'default' => 'OTP to approve wallet transfer of {transfer_amount} to {receiver_email} on {site_name} is {otp}.' ,
                        ) ;
            }
            $section_fields[] = array(
                'title'             => esc_html__( 'OTP Length' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'otp_character_count' ) ,
                'type'              => 'number' ,
                'custom_attributes' => array( 'min' => 4 ) ,
                'default'           => '4' ,
                    ) ;
            $section_fields[] = array(
                'title'             => esc_html__( 'OTP Validity in Minutes' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key( 'otp_validity' ) ,
                'custom_attributes' => array( 'min' => 5 , 'max' => 60 ) ,
                'type'              => 'number' ,
                'default'           => '5' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'fund_transfer_security_options' ,
                    ) ;
            //OTP section end

            return $section_fields ;
        }

        /*
         * Frontend action
         */

        public function admin_action() {
            add_action( 'wp_ajax_hrw_response_fund_request' , array( $this , 'response_fund_request' ) ) ;
        }

        /*
         * Frontend action
         */

        public function frontend_action() {
            //Add fund transfer dashboard menu
            add_filter( 'hrw_frontend_dashboard_menu' , array( $this , 'add_dashboard_menu' ) , 10 , 1 ) ;
            //Add fund transfer dashboard submenu
            add_filter( 'hrw_frontend_dashboard_fund_transfer_submenus' , array( $this , 'add_dashboard_submenu' ) , 10 , 1 ) ;
            //Remove query args
            add_filter( 'hrw_dashboard_remove_query_args' , array( $this , 'remove_query_args' ) , 10 , 1 ) ;
            //Display fund transafer transactions submenu content
            add_action( 'hrw_frontend_dashboard_menu_content_fund_tranfer_transactions' , array( $this , 'render_transactions_submenu_content' ) ) ;
            //Display fund transfer submenu content
            add_action( 'hrw_frontend_dashboard_menu_content_fund_transfer_form' , array( $this , 'render_fund_transfer_submenu_content' ) ) ;
            //Display fund request submenu content
            add_action( 'hrw_frontend_dashboard_menu_content_fund_request_form' , array( $this , 'render_fund_request_submenu_content' ) ) ;
            //Process Fund Transfer
            add_action( 'wp_loaded' , array( $this , 'process_fund_transfer' ) ) ;
            //Process Fund request
            add_action( 'wp_loaded' , array( $this , 'process_fund_request' ) ) ;
        }

        /*
         * Frontend external js files
         */

        public function frontend_external_js_files() {
            //Enqueue Fund transfer script
            wp_enqueue_script( 'hrw_fund_transfer' , HRW_PLUGIN_URL . '/premium/assets/js/frontend/fund-transfer.js' , array( 'jquery' , 'blockUI' ) , HRW_VERSION ) ;

            wp_localize_script(
                    'hrw_fund_transfer' , 'hrw_fund_transfer_params' , array(
                'ajax_url'                        => HRW_ADMIN_AJAX_URL ,
                'fund_transfer_nonce'             => wp_create_nonce( 'hrw-fund-transfer-nonce' ) ,
                'fund_request_alert_msg'          => $this->fund_request_alert_msg ,
                'fund_request_transfer_alert_msg' => $this->fund_request_transfer_alert_msg ,
                'fund_request_cancel_alert_msg'   => $this->fund_request_cancel_alert_msg ,
                'fund_request_decline_alert_msg'  => $this->fund_request_decline_alert_msg ,
                'enable_transfer_fee'             => $this->enable_transfer_fee ,
                'transfer_fee_type'               => $this->transfer_fee_type ,
                'transfer_fee_value'              => $this->transfer_fee_value ,
                    )
            ) ;
        }

        /*
         * Add Fund Transfer Dashboard Menu
         */

        public function add_dashboard_menu( $menus ) {

            $menus[ 'fund_transfer' ] = array(
                'label' => get_option( 'hrw_dashboard_customization_appointments_label' , 'Fund Transfer/Request' ) ,
                'code'  => 'fa fa-credit-card-alt' ,
                    ) ;

            return $menus ;
        }

        /*
         * Add Fund Transfer Dashboard Submenu
         */

        public function add_dashboard_submenu( $submenus ) {
            $submenus[ 'fund_tranfer_transactions' ] = get_option( 'hrw_dashboard_customization_appointments_label' , 'Transactions' ) ;

            if ( $this->enable_transfer_fund == 'yes' )
                $submenus[ 'fund_transfer_form' ] = get_option( 'hrw_dashboard_customization_appointments_label' , 'Fund Transfer' ) ;
            if ( $this->enable_request_fund == 'yes' )
                $submenus[ 'fund_request_form' ]  = get_option( 'hrw_dashboard_customization_appointments_label' , 'Fund Request' ) ;

            return $submenus ;
        }

        /*
         * Validate Render Menu
         */

        public function validate_render_menu() {
            if ( $this->user_roles_restriction() )
                return true ;

            if ( $this->site_activity_restriction() )
                return true ;

            return false ;
        }

        /*
         * User Roles Restriction
         */

        public function user_roles_restriction() {
            $return = true ;

            switch ( $this->user_restriction_type ) {

                case '2':
                    if ( ! in_array( HRW_Wallet_User::get_user_id() , $this->user_restriction ) )
                        return false ;

                    break ;
                case '3':
                    $user_roles = HRW_Wallet_User::get_user()->roles ;
                    $return     = false ;

                    if ( hrw_check_is_array( $user_roles ) ) {
                        foreach ( $user_roles as $user_role ) {
                            if ( in_array( $user_role , $this->user_role_restriction ) )
                                $return = true ;
                        }
                    }
                    break ;
            }

            return $return ;
        }

        /*
         * Site Activity Restriction
         */

        public function site_activity_restriction() {

            switch ( $this->site_activity_type ) {
                case '1':
                    if ( $this->registered_day_count ) {
                        $current_dateobject    = HRW_Date_Time::get_date_time_object( 'now' , true ) ;
                        $registered_dateobject = HRW_Date_Time::get_date_time_object( HRW_Wallet_User::get_user()->user_registered , true ) ;
                        $registered_dateobject->modify( '+' . absint( $this->registered_day_count ) . ' days' ) ;

                        if ( $registered_dateobject > $current_dateobject )
                            return true ;
                    }

                    break ;
                case '2':
                    $purchased_amount = wc_get_customer_total_spent( HRW_Wallet_User::get_user_id() ) ;

                    if ( $this->purchased_amount && ($this->purchased_amount > $purchased_amount) )
                        return true ;

                    break ;
                case '3':
                    $order_count = wc_get_customer_order_count( HRW_Wallet_User::get_user_id() ) ;

                    if ( $this->order_placed_count && ($this->order_placed_count > $order_count) )
                        return true ;

                    break ;
            }

            return false ;
        }

        /*
         * Remove Query arguments
         */

        public function remove_query_args( $args ) {
            $args[] = 'fund_transfer_view' ;
            $args[] = 'hrw_user_id' ;

            return $args ;
        }

        /*
         * Display the transactions submenu content
         */

        public function render_transactions_submenu_content() {
            if ( isset( $_GET[ 'fund_transfer_view' ] ) && ! empty( $_GET[ 'fund_transfer_view' ] ) ) {
                $id               = absint( $_GET[ 'fund_transfer_view' ] ) ;
                $transaction_logs = hrw_get_fund_transfer_logs( $id ) ;
                $fund_transfer    = hrw_get_fund_transfer( $id ) ;

                hrw_get_template( 'dashboard/fund-transfer-view-transaction.php' , true , array( 'fund_transfer' => $fund_transfer , 'transaction_logs' => $transaction_logs ) ) ;
            } else {

                $per_page     = 5 ;
                $current_page = HRW_Dashboard::get_current_page_number() ;

                $default_args = array(
                    'post_type'      => HRWP_Register_Post_Types::FUND_TRANSFER_POSTTYPE ,
                    'post_status'    => hrw_get_fund_transfer_log_statuses() ,
                    'author'         => HRW_Wallet_User::get_user_id() ,
                    'fields'         => 'ids' ,
                    'posts_per_page' => '-1'
                        ) ;

                /* Calculate Page Count */
                $overall_count = get_posts( $default_args ) ;
                $page_count    = ceil( count( $overall_count ) / $per_page ) ;

                $default_args[ 'offset' ]         = ($current_page - 1) * $per_page ;
                $default_args[ 'posts_per_page' ] = $per_page ;

                $data_args = array(
                    'transactions'  => get_posts( $default_args ) ,
                    'serial_number' => ( $current_page * $per_page ) - $per_page + 1 ,
                    'pagination'    => array(
                        'page_count'      => $page_count ,
                        'current_page'    => $current_page ,
                        'next_page_count' => (($current_page + 1) > ($page_count - 1)) ? ($current_page) : ($current_page + 1) ,
                    ) ) ;

                hrw_get_template( 'dashboard/fund-transfer-transactions.php' , true , $data_args ) ;
            }
        }

        /*
         * Display the fund trnasfer submenu content
         */

        public function render_fund_transfer_submenu_content() {

            global $hrw_otp_enabled ;
            if ( $hrw_otp_enabled ) {
                $user_id = isset( $_POST[ 'hrw_fund_transfer' ][ 'user_selection' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw_fund_transfer' ][ 'user_selection' ] ) : array() ;
                $amount  = isset( $_POST[ 'hrw_fund_transfer' ][ 'amount' ] ) ? floatval( $_POST[ 'hrw_fund_transfer' ][ 'amount' ] ) : 0 ;
                $reason  = isset( $_POST[ 'hrw_fund_transfer' ][ 'reason' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw_fund_transfer' ][ 'reason' ] ) : '' ;
                $fee     = isset( $_POST[ 'hrw_fund_transfer' ][ 'fee' ] ) ? floatval( $_POST[ 'hrw_fund_transfer' ][ 'fee' ] ) : 0 ;

                $fund_transfer_fields_data = array(
                    'user_id' => reset( $user_id ) ,
                    'amount'  => $amount ,
                    'reason'  => $reason ,
                    'fee'     => $fee ,
                        ) ;

                hrw_get_template( 'dashboard/fund-transfer-confirmation.php' , true , $fund_transfer_fields_data ) ;
            } else {
                $user_id = isset( $_GET[ 'hrw_user_id' ] ) ? hrw_sanitize_text_field( $_GET[ 'hrw_user_id' ] ) : array() ;
                $user_id = isset( $_POST[ 'hrw_fund_transfer' ][ 'user_selection' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw_fund_transfer' ][ 'user_selection' ] ) : $user_id ;
                $amount  = isset( $_POST[ 'hrw_fund_transfer' ][ 'amount' ] ) ? floatval( $_POST[ 'hrw_fund_transfer' ][ 'amount' ] ) : 0 ;
                $reason  = isset( $_POST[ 'hrw_fund_transfer' ][ 'reason' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw_fund_transfer' ][ 'reason' ] ) : '' ;
                $fee     = isset( $_POST[ 'hrw_fund_transfer' ][ 'fee' ] ) ? floatval( $_POST[ 'hrw_fund_transfer' ][ 'fee' ] ) : 0 ;


                $include_users      = array() ;
                $include_user_roles = array() ;

                $exclude_users      = array( HRW_Wallet_User::get_user_id() ) ;
                $exclude_user_roles = array() ;

                if ( '2' == $this->user_restriction_type ) {
                    $include_users = array_merge( $include_users , $this->user_restriction ) ;
                } elseif ( '3' == $this->user_restriction_type ) {
                    $include_user_roles = $this->user_role_restriction ;
                }

                $fund_transfer_fields_data = array(
                    'user_id'               => $user_id ,
                    'amount'                => $amount ,
                    'reason'                => $reason ,
                    'fee'                   => $fee ,
                    'display_user_selector' => ! isset( $_GET[ 'hrw_user_id' ] ) ,
                    'include_users'         => $include_users ,
                    'include_user_roles'    => $include_user_roles ,
                    'exclude_users'         => $exclude_users ,
                    'exclude_user_roles'    => $exclude_user_roles
                        ) ;
                if ( $this->validate_render_menu() ) {
                    hrw_get_template( 'dashboard/fund-transfer-form.php' , true , $fund_transfer_fields_data ) ;
                } else {
                    ?>
                    <label><?php esc_html_e( get_option( 'hrw_fund_transfer_fund_trnsfer_restriction_msg' ) , HRW_LOCALE ) ; ?></label>
                    <?php
                }
            }
        }

        /*
         * Display the fund request submenu content
         */

        public function render_fund_request_submenu_content() {

            $user_id = isset( $_GET[ 'hrw_user_id' ] ) ? hrw_sanitize_text_field( $_GET[ 'hrw_user_id' ] ) : array() ;
            $user_id = isset( $_POST[ 'hrw_fund_request' ][ 'user_selection' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw_fund_request' ][ 'user_selection' ] ) : $user_id ;
            $amount  = isset( $_POST[ 'hrw_fund_request' ][ 'amount' ] ) ? floatval( $_POST[ 'hrw_fund_request' ][ 'amount' ] ) : 0 ;
            $reason  = isset( $_POST[ 'hrw_fund_request' ][ 'reason' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw_fund_request' ][ 'reason' ] ) : '' ;

            $include_users      = array() ;
            $include_user_roles = array() ;

            $exclude_users      = array( HRW_Wallet_User::get_user_id() ) ;
            $exclude_user_roles = array() ;

            if ( '2' == $this->user_restriction_type ) {
                $include_users = array_merge( $include_users , $this->user_restriction ) ;
            } elseif ( '3' == $this->user_restriction_type ) {
                $include_user_roles = $this->user_role_restriction ;
            }

            $fund_request_fields_data = array(
                'user_id'               => $user_id ,
                'amount'                => $amount ,
                'reason'                => $reason ,
                'display_user_selector' => ! isset( $_GET[ 'hrw_user_id' ] ) ,
                'include_users'         => $include_users ,
                'include_user_roles'    => $include_user_roles ,
                'exclude_users'         => $exclude_users ,
                'exclude_user_roles'    => $exclude_user_roles
                    ) ;

            if ( $this->validate_render_menu() ) {
                hrw_get_template( 'dashboard/fund-request-form.php' , true , $fund_request_fields_data ) ;
            } else {
                ?>
                <label><?php esc_html_e( get_option( 'hrw_fund_transfer_hrw_req_restriction_msg' ) , HRW_LOCALE ) ; ?></label>
                <?php
            }
        }

        /*
         * Process fund transfer
         */

        public function process_fund_transfer() {
            global $hrw_otp_enabled ;
            $nonce_value = isset( $_POST[ 'hrw-fund-transfer-nonce' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw-fund-transfer-nonce' ] ) : null ;
            if ( ! isset( $_POST[ 'hrw-action' ] ) || empty( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce( $nonce_value , 'hrw-fund-transfer' ) )
                return ;

            try {
                if ( ! isset( $_POST[ 'hrw_fund_transfer' ] ) ) {
                    throw new Exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;
                }

                $fund_transfer = $_POST[ 'hrw_fund_transfer' ] ;
                //validate if user is selected
                if ( empty( $fund_transfer[ 'user_selection' ] ) ) {
                    throw new Exception( esc_html__( 'Please select a Receiver' , HRW_LOCALE ) ) ;
                }
                //validate if amount is entered
                if ( empty( $fund_transfer[ 'amount' ] ) ) {
                    throw new Exception( esc_html__( 'Please enter funds to transfer' , HRW_LOCALE ) ) ;
                }

                $amount = floatval( $fund_transfer[ 'amount' ] ) ;
                //validate if user wallet amount is sufficient
                if ( HRW_Wallet_User::get_available_balance() <= 0 ) {
                    throw new Exception( esc_html__( 'Insufficient balance to transfer funds' , HRW_LOCALE ) ) ;
                }

                $fee = 0 ;
                //Prepare argument to transfer amount
                if ( $this->enable_transfer_fee == 'yes' ) {
                    $fee_value = ( float ) $this->transfer_fee_value ;
                    if ( $this->transfer_fee_type == '2' ) {
                        $fee = ($fee_value) ? ($fee_value / 100) * $amount : $fee_value ;
                    } else {
                        $fee = $fee_value ;
                    }
                }

                //validate if user wallet amount is less than entered amount
                if ( HRW_Wallet_User::get_available_balance() < ($amount + $fee) ) {
                    throw new Exception( esc_html__( 'Insufficient balance to transfer funds' , HRW_LOCALE ) ) ;
                }

                if ( $this->transfer_minimum_value && $this->transfer_minimum_value > $amount ) {
                    throw new Exception( str_replace( '[minimum_amount]' , $this->transfer_minimum_value , $this->transfer_minimum_value_msg ) ) ;
                }

                if ( $this->transfer_maximum_value && $this->transfer_maximum_value < $amount ) {
                    throw new Exception( str_replace( '[maximum_amount]' , $this->transfer_maximum_value , $this->maximum_transaction_per_day_msg ) ) ;
                }

                if ( $this->maximum_transaction_per_day ) {
                    if ( hrw_get_user_fund_transfered_count( HRW_Wallet_User::get_user_id() ) >= $this->maximum_transaction_per_day )
                        throw new Exception( str_replace( '[transfer_count]' , $this->maximum_transaction_per_day , $this->maximum_transaction_per_day_msg ) ) ;
                }

                $receiver_id      = hrw_sanitize_text_field( $fund_transfer[ 'user_selection' ] ) ;
                $receiver_user_id = reset( $receiver_id ) ;

                if ( $this->user_maximum_transaction_per_day ) {
                    if ( hrw_get_per_user_fund_transfered_count( HRW_Wallet_User::get_user_id() , $receiver_user_id ) >= $this->user_maximum_transaction_per_day )
                        throw new Exception( str_replace( '[user_transfer_count]' , $this->user_maximum_transaction_per_day , $this->user_maximum_transaction_per_day_msg ) ) ;
                }

                if ( isset( $fund_transfer[ 'verify_otp' ] ) ) {
                    $hrw_otp_enabled = true ;
                    $verify_otp      = absint( $fund_transfer[ 'verify_otp' ] ) ;
                    $saved_otp       = get_post_meta( HRW_Wallet_User::get_user_id() , 'hrw_fund_transfer_otp' , true ) ;

                    if ( $this->otp_validity ) {
                        $validity = get_post_meta( HRW_Wallet_User::get_user_id() , 'hrw_fund_transfer_otp_validity' , true ) ;

                        if ( time() >= $validity )
                            throw new Exception( esc_html__( 'OTP Expired' , HRW_LOCALE ) ) ;
                    }

                    if ( $verify_otp != $saved_otp )
                        throw new Exception( esc_html__( 'The OTP which you have given is incorrect. Please enter correct OTP' , HRW_LOCALE ) ) ;

                    $reason = hrw_sanitize_text_field( $fund_transfer[ 'reason' ] ) ;

                    $args = array(
                        'sender_id'   => HRW_Wallet_User::get_user_id() ,
                        'receiver_id' => $receiver_user_id ,
                        'amount'      => $amount ,
                        'fee'         => $fee ,
                        'reason'      => $reason
                            ) ;

                    //Transfer amount to receiver
                    HRWP_Fund_Transfer_Handler::process_fund_transaction( $args ) ;

                    HRW_Form_Handler::add_message( esc_html__( 'Funds Transferred successfully' , HRW_LOCALE ) ) ;

                    $hrw_otp_enabled = false ;
                    unset( $_POST[ 'hrw-fund-transfer-nonce' ] ) ;
                    unset( $_POST[ 'hrw_fund_transfer' ] ) ;
                    unset( $_GET[ 'hrw_user_id' ] ) ;

                    //reset wallet
                    HRW_Wallet_User::reset() ;
                } else {

                    //Send OTP
                    $this->send_otp( HRW_Wallet_User::get_user_id() , $receiver_user_id , $amount ) ;

                    $hrw_otp_enabled = true ;

                    HRW_Form_Handler::add_message( esc_html__( 'OTP is sent to your Email ID/Phone Number' , HRW_LOCALE ) ) ;
                }
            } catch ( Exception $ex ) {

                HRW_Form_Handler::add_error( $ex->getMessage() ) ;
            }
        }

        /*
         * Process fund request
         */

        public function process_fund_request() {
            $nonce_value = isset( $_POST[ 'hrw-fund-request-nonce' ] ) ? hrw_sanitize_text_field( $_POST[ 'hrw-fund-request-nonce' ] ) : null ;
            if ( ! isset( $_POST[ 'hrw-action' ] ) || empty( $_POST[ 'hrw-action' ] ) || ! wp_verify_nonce( $nonce_value , 'hrw-fund-request' ) )
                return ;

            try {
                if ( ! isset( $_POST[ 'hrw_fund_request' ] ) ) {
                    throw new Exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;
                }

                $fund_request = $_POST[ 'hrw_fund_request' ] ;

                if ( empty( $fund_request[ 'user_selection' ] ) ) {
                    throw new Exception( esc_html__( 'Please select a Receiver' , HRW_LOCALE ) ) ;
                }

                if ( empty( $fund_request[ 'amount' ] ) ) {
                    throw new Exception( esc_html__( 'Please enter funds to request' , HRW_LOCALE ) ) ;
                }

                $amount = floatval( $fund_request[ 'amount' ] ) ;

                if ( $this->request_minimum_value && $this->request_minimum_value > $amount ) {
                    throw new Exception( str_replace( '[minimum_amount]' , $this->request_minimum_value , $this->request_minimum_value_msg ) ) ;
                }

                if ( $this->request_maximum_value && $this->request_maximum_value < $amount ) {
                    throw new Exception( str_replace( '[maximum_amount]' , $this->request_maximum_value , $this->request_maximum_value_msg ) ) ;
                }

                if ( $this->maximum_request_per_day ) {
                    if ( hrw_get_user_fund_requested_count( HRW_Wallet_User::get_user_id() ) >= $this->maximum_request_per_day )
                        throw new Exception( str_replace( '[request_count]' , $this->maximum_request_per_day , $this->maximum_request_per_day_msg ) ) ;
                }

                $receiver_id      = hrw_sanitize_text_field( $fund_request[ 'user_selection' ] ) ;
                $receiver_user_id = reset( $receiver_id ) ;

                if ( $this->user_maximum_request_per_day ) {
                    if ( hrw_get_per_user_fund_requested_count( HRW_Wallet_User::get_user_id() , $receiver_user_id ) >= $this->user_maximum_request_per_day )
                        throw new Exception( str_replace( '[user_request_count]' , $this->user_maximum_request_per_day , $this->user_maximum_request_per_day_msg ) ) ;
                }

                $fee    = 0 ;
                $reason = hrw_sanitize_text_field( $fund_request[ 'reason' ] ) ;

                $args = array(
                    'sender_id'   => HRW_Wallet_User::get_user_id() ,
                    'receiver_id' => $receiver_user_id ,
                    'amount'      => $amount ,
                    'reason'      => $reason ,
                    'type'        => 'request'
                        ) ;

                //Request amount from receiver
                HRWP_Fund_Transfer_Handler::process_fund_transaction( $args ) ;

                HRW_Form_Handler::add_message( esc_html__( 'Funds requested successfully' , HRW_LOCALE ) ) ;

                unset( $_POST[ 'hrw-fund-request-nonce' ] ) ;
                unset( $_POST[ 'hrw_fund_request' ] ) ;
                unset( $_GET[ 'hrw_user_id' ] ) ;
            } catch ( Exception $ex ) {

                HRW_Form_Handler::add_error( $ex->getMessage() ) ;
            }
        }

        /*
         * Response fund request
         */

        public function response_fund_request() {
            check_ajax_referer( 'hrw-fund-transfer-nonce' , 'hrw_security' ) ;

            try {
                if ( ! isset( $_REQUEST ) || ! isset( $_REQUEST[ 'transaction_id' ] ) )
                    throw new exception( esc_html__( 'Invalid Request' , HRW_LOCALE ) ) ;

                $transaction_id         = absint( $_REQUEST[ 'transaction_id' ] ) ;
                $transaction_log_object = hrw_get_fund_transfer_log( $transaction_id ) ;

                if ( ! is_object( $transaction_log_object ) )
                    throw new exception( esc_html__( 'Invalid Request ID' , HRW_LOCALE ) ) ;

                $type = hrw_sanitize_text_field( $_REQUEST[ 'type' ] ) ;
                if ( ! $type )
                    throw new exception( esc_html__( 'Invalid Request Type' , HRW_LOCALE ) ) ;

                $fee_value = 0 ;
                //Prepare argument to transfer amount
                if ( $this->enable_transfer_fee == 'yes' ) {
                    $fee_value = ( float ) $this->transfer_fee_value ;
                    if ( $this->transfer_fee_type == '2' ) {
                        $fee = ($fee_value) ? ($fee_value / 100) * $transaction_log_object->get_amount() : $fee_value ;
                    } else {
                        $fee = $fee_value ;
                    }
                }

                $args = array(
                    'sender_id'       => $transaction_log_object->get_sender_id() ,
                    'receiver_id'     => absint( $transaction_log_object->get_receiver_id() ) ,
                    'amount'          => $transaction_log_object->get_amount() ,
                    'fee'             => $fee_value ,
                    'sender_log_id'   => absint( $transaction_log_object->get_id() ) ,
                    'receiver_log_id' => absint( $transaction_log_object->get_receiver_log_id() ) ,
                    'type'            => $type ,
                        ) ;

                //Response Fund Request
                HRWP_Fund_Transfer_Handler::process_response_fund_request( $args ) ;

                $transaction_log_object = hrw_get_fund_transfer_log( $transaction_id ) ;
                $html                   = hrw_get_template_html( 'dashboard/fund-transfer-transaction-log.php' , true , array( 'transaction_log_object' => $transaction_log_object ) ) ;

                wp_send_json_success( array( 'html' => $html ) ) ;
            } catch ( Exception $ex ) {
                wp_send_json_error( array( 'error' => $ex->getMessage() ) ) ;
            }
        }

        /*
         * Send OTP
         */

        public function send_otp( $user_id , $receiver_user_id , $amount ) {

            $validity      = time() + (( int ) $this->otp_validity * 60) ;
            $generated_otp = hrw_generate_random_codes( array( 'length' => $this->otp_character_count , 'character_type' => 1 ) ) ;
            $user_data     = get_userdata( $user_id ) ;
            $receiver_data = get_userdata( $receiver_user_id ) ;

            if ( ! is_object( $user_data ) || ! is_object( $receiver_data ) )
                return ;

            //Update user meta OTP Details
            update_post_meta( $user_id , 'hrw_fund_transfer_otp_validity' , $validity ) ;
            update_post_meta( $user_id , 'hrw_fund_transfer_otp' , $generated_otp ) ;

            //Send OTP to user via SMS
            $this->send_sms_otp( $user_id , $generated_otp , $receiver_data , $amount ) ;

            //Send OTP to user via Email
            $this->send_email_otp( $user_data , $generated_otp , $receiver_data , $amount ) ;
        }

        /*
         * Send SMS OTP
         */

        public function send_sms_otp( $user_id , $generated_otp , $receiver_data , $amount ) {
            if ( ! HRW_Module_Instances::get_module_by_id( 'sms' )->is_enabled() )
                return ;

            if ( $this->enable_sms_otp != 'yes' )
                return ;

            $to = get_user_meta( $user_id , 'hrw_phone_number' , true ) ;

            if ( ! $to )
                return ;

            $site_name = wp_specialchars_decode( get_option( 'blogname' ) , ENT_QUOTES ) ;

            $shortcode_array = array( '{site_name}' , '{receiver_email}' , '{transfer_amount}' , '{otp}' ) ;
            $replace_array   = array( $site_name , $receiver_data->user_email , hrw_price( $amount ) , $generated_otp ) ;

            $message = str_replace( $shortcode_array , $replace_array , $this->otp_sms_message ) ;

            HRW_SMS_Handler::send_sms( $to , $message ) ;
        }

        /*
         * Send Email OTP
         */

        public function send_email_otp( $user_data , $generated_otp , $receiver_data , $amount ) {
            if ( $this->enable_email_otp != 'yes' && HRW_Module_Instances::get_module_by_id( 'sms' )->is_enabled() )
                return ;

            $site_name = wp_specialchars_decode( get_option( 'blogname' ) , ENT_QUOTES ) ;

            $shortcode_array = array( '{site_name}' , '{receiver_email}' , '{transfer_amount}' , '{otp}' , '{otp_validity}' , '{user_name}' ) ;
            $replace_array   = array( $site_name , $receiver_data->user_email , hrw_price( $amount ) , $generated_otp , $this->otp_validity , $user_data->display_name ) ;

            $subject = str_replace( $shortcode_array , $replace_array , $this->otp_email_subject ) ;
            $message = str_replace( $shortcode_array , $replace_array , $this->otp_email_message ) ;

            $notifications_object = new HRW_Notifications() ;
            $notifications_object->send_email( $user_data->user_email , $subject , $message ) ;
        }

    }

}
