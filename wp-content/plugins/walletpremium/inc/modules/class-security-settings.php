<?php

/**
 * Security Settings
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Security_Settings_Module' ) ) {

    /**
     * Class HRW_Security_Settings_Module
     */
    class HRW_Security_Settings_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                           => 'no' ,
            'topup_restriction_enabled'         => 'no' ,
            'max_topup_restriction'             => '' ,
            'max_topup_amount_restriction'      => '' ,
            'usage_restriction_enabled'         => 'no' ,
            'max_usage_restriction'             => '' ,
            'max_usage_amount_restriction'      => '' ,
            'cashback_restriction_enabled'      => 'no' ,
            'max_cashback_restriction'          => '' ,
            'cashback_amount_restriction'       => '' ,
            'withdrawal_restriction_enabled'    => 'no' ,
            'max_withdrawal_req_restriction'    => '' ,
            'max_withdrawal_amount_restriction' => '' ,
            'user_registered_day_count'         => '' ,
            'topup_count_msg'                   => 'You are allowed to top-up a maximum of [top-up_count] time(s) per day. Since you have reached the count, you cannot top-up anymore today.' ,
            'topup_reached_msg'                 => 'You are allowed to top-up a maximum of [top-up_amount] per day. Since you have added the restricted amount to your wallet, you cannot top-up anymore today.' ,
            'topup_reaching_msg'                => 'By placing this order, you will be reaching the maximum wallet top-up amount [top-up_amount] per day. Your wallet top-up amount available today is [top-up_balance_amount].' ,
            'topup_max_entered_msg'             => 'Maximum top-up amount allowed per day is [top-up_amount]' ,
            'usage_count_msg'                   => 'You are allowed to use your wallet funds maximum of [wallet_usage_count] times per day.  Since you have reached the count, you cannot use your wallet funds anymore today.' ,
            'usage_reached_msg'                 => 'You are allowed to use your wallet funds maximum of [wallet_usage_amount] per day.  Since you have used up to the restricted amount, you cannot use your wallet funds anymore today.' ,
            'usage_max_entered_msg'             => 'Maximum wallet funds usage allowed per day is [wallet_usage_amount]' ,
            'usage_reaching_msg'                => 'By using the wallet funds in this order, you will be reaching the maximum wallet usage amount [wallet_usage_amount] per day. Hence, you cannot use your wallet funds anymore today.' ,
            'cashback_count_msg'                => 'You are allowed to get a cashback maximum of [wallet_cashback_count] times per day.  Since you have reached the count, you cannot get cashback anymore today.' ,
            'cashback_reached_msg'              => 'You are allowed to get a cashback amount maximum of [wallet_cashback_amount] per day.  Since you have already got the amount, you cannot get cashback anymore today.' ,
            'cashback_reaching_msg'             => 'By placing this order, you will be reaching to get the maximum cashback amount [wallet_cashback_amount] per day. <b>Wallet cashback available to get today is [cashback_balance].</b>' ,
            'withdrawal_count_msg'              => 'You are allowed to give the withdrawal request a maximum of [wallet_withdrawal_request_count] times per day.  Since you have reached the count, you cannot give a request anymore today.' ,
            'withdrawal_reached_msg'            => 'You are allowed to withdraw the amount maximum of [wallet_withdrawal_amount] per day.  Since you have already withdrawn the restricted amount, you cannot withdraw anymore today.' ,
            'withdrawal_reaching_msg'           => 'By giving the request this time, you will be reaching the maximum withdraw amount [wallet_withdrawal_amount] per day. Hence, you cannot withdraw anymore today.' ,
            'withdrawal_max_entered_msg'        => 'Maximum withdraw amount from your wallet allowed per day is [wallet_withdrawal_amount]'
                ) ;
        /*
         * Order IDs
         */
        protected static $hrw_order_ids ;

        /*
         * Usage Order IDs
         */
        protected static $hrw_usage_order_ids ;

        /*
         * Cashback IDs
         */
        protected static $hrw_cashback_ids ;

        /*
         * Withdrawal IDs
         */
        protected static $hrw_withdrawal_ids ;

        /*
         * Meta Key
         */
        protected static $meta_key ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'security_settings' ;
            $this->title = esc_html__ ( 'Security Settings' , HRW_LOCALE ) ;

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

            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Wallet Top-up Restriction' , HRW_LOCALE ) ,
                'id'    => 'wallet_topup_restriction_options' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Block Wallet Top-up for the day when a user tries to add funds to their wallet multiple times on a day' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'topup_restriction_enabled' ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Maximum No.of Top-up' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'max_topup_restriction' ) ,
                'class'             => $this->get_field_key ( 'topup_fileds' ) ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Maximum Top-up Amount' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'max_topup_amount_restriction' ) ,
                'class'             => $this->get_field_key ( 'topup_fileds' ) ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_topup_restriction_options' ,
                    ) ;
            $section_fields[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Wallet Usage Restriction' , HRW_LOCALE ) ,
                'id'    => 'wallet_usage_restriction_options' ,
                    ) ;
            $section_fields[] = array (
                'title'   => esc_html__ ( 'Block Wallet Usage for the day when a user tries to use wallet funds multiple times on a day(Applies to both Partial Usage and Wallet Payment Gateway)' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'usage_restriction_enabled' ) ,
                'type'    => 'checkbox' ,
                'default' => 'no' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Maximum No.of Usage' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'max_usage_restriction' ) ,
                'class'             => $this->get_field_key ( 'usage_fileds' ) ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array (
                'title'             => esc_html__ ( 'Maximum Usage Amount' , HRW_LOCALE ) ,
                'id'                => $this->get_field_key ( 'max_usage_amount_restriction' ) ,
                'class'             => $this->get_field_key ( 'usage_fileds' ) ,
                'custom_attributes' => array ( 'min' => 0 ) ,
                'type'              => 'number' ,
                'default'           => '' ,
                    ) ;
            $section_fields[] = array (
                'type' => 'sectionend' ,
                'id'   => 'wallet_usage_restriction_options' ,
                    ) ;
            if ( HRW_Module_Instances::get_module_by_id ( 'cashback' )->is_enabled () ) {
                $section_fields[] = array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Wallet Cashback Restriction' , HRW_LOCALE ) ,
                    'id'    => 'cashback_restriction_options' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Stop Issuing cashback to the user when getting multiple cashback in a day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'cashback_restriction_enabled' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
                $section_fields[] = array (
                    'title'             => esc_html__ ( 'Maximum No.of Cashback' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'max_cashback_restriction' ) ,
                    'class'             => $this->get_field_key ( 'cashback_fileds' ) ,
                    'custom_attributes' => array ( 'min' => 0 ) ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                        ) ;
                $section_fields[] = array (
                    'title'             => esc_html__ ( 'Cashback Amount' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'cashback_amount_restriction' ) ,
                    'class'             => $this->get_field_key ( 'cashback_fileds' ) ,
                    'custom_attributes' => array ( 'min' => 0 ) ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                        ) ;
                $section_fields[] = array (
                    'type' => 'sectionend' ,
                    'id'   => 'cashback_restriction_options' ,
                        ) ;
            }
            if ( HRW_Module_Instances::get_module_by_id ( 'wallet_withdrawal' )->is_enabled () ) {
                $section_fields[] = array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Wallet Withdrawal Restriction' , HRW_LOCALE ) ,
                    'id'    => 'withdrawal_restriction_option' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Block Wallet Withdrawal Request for the day when a user tries to perform multiple requests within a day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'withdrawal_restriction_enabled' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
                $section_fields[] = array (
                    'title'             => esc_html__ ( 'Maximum No.of Requests' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'max_withdrawal_req_restriction' ) ,
                    'class'             => $this->get_field_key ( 'withdrawal_fileds' ) ,
                    'custom_attributes' => array ( 'min' => 0 ) ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                        ) ;
                $section_fields[] = array (
                    'title'             => esc_html__ ( 'Maximum Withdrawal Amount' , HRW_LOCALE ) ,
                    'id'                => $this->get_field_key ( 'max_withdrawal_amount_restriction' ) ,
                    'class'             => $this->get_field_key ( 'withdrawal_fileds' ) ,
                    'custom_attributes' => array ( 'min' => 0 ) ,
                    'type'              => 'number' ,
                    'default'           => '' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Minimum Number of Days the Account has to be active before Submitting Wallet Withdrawal Request' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'user_registered_day_count' ) ,
                    'class'   => $this->get_field_key ( 'withdrawal_fileds' ) ,
                    'desc'    => '' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                        ) ;
                $section_fields[] = array (
                    'type' => 'sectionend' ,
                    'id'   => 'withdrawal_restriction_option' ,
                        ) ;

                $section_fields[] = array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'Messages' , HRW_LOCALE ) ,
                    'id'    => 'hrw_security_messages' ,
                        ) ;
                $section_fields[] = array (
                    'type'  => 'subtitle' ,
                    'title' => esc_html__ ( 'Wallet Top-up Restriction' , HRW_LOCALE ) ,
                    'id'    => 'hrw_security_topup_msgs' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user has reached the maximum number of top-up per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'topup_count_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You are allowed to top-up a maximum of [top-up_count] time(s) per day. Since you have reached the count, you cannot top-up anymore today.' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user has reached the maximum top-up amount per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'topup_reached_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You are allowed to top-up a maximum of [top-up_amount] per day. Since you have added the restricted amount to your wallet, you cannot top-up anymore today.' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user will be reaching the maximum top-up amount per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'topup_reaching_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'By placing this order, you will be reaching the maximum wallet top-up amount [top-up_amount] per day. <b>Your wallet top-up amount available today is [top-up_balance_amount]</b>.' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user tries to top-up more than the restricted amount per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'topup_max_entered_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Maximum top-up amount allowed per day is [top-up_amount]' ,
                        ) ;
                $section_fields[] = array (
                    'type'  => 'subtitle' ,
                    'title' => esc_html__ ( 'Wallet Usage Restriction' , HRW_LOCALE ) ,
                    'id'    => 'hrw_security_usage_msgs' ,
                        ) ;

                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user has reached the maximum number of wallet usage per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'usage_count_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You are allowed to use your wallet funds maximum of [wallet_usage_count] times per day.  Since you have reached the count, you cannot use your wallet funds anymore today.' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user has reached the maximum wallet usage per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'usage_reached_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'You are allowed to use your wallet funds maximum of [wallet_usage_amount] per day.  Since you have used up to the restricted amount, you cannot use your wallet funds anymore today.' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user will be reaching the maximum wallet funds usage per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'usage_reaching_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'By using the wallet funds in this order, you will be reaching the maximum wallet usage amount [wallet_usage_amount] per day. <b>Your wallet usage amount available today is [wallet_balance_amount].</b>' ,
                        ) ;
                $section_fields[] = array (
                    'title'   => esc_html__ ( 'Message to display when a user tries to use the wallet funds more than the restricted amount per day' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'usage_max_entered_msg' ) ,
                    'type'    => 'textarea' ,
                    'default' => 'Maximum wallet funds usage allowed per day is [wallet_usage_amount]' ,
                        ) ;
                if ( HRW_Module_Instances::get_module_by_id ( 'cashback' )->is_enabled () ) {
                    $section_fields[] = array (
                        'type'  => 'subtitle' ,
                        'title' => esc_html__ ( 'Wallet Cashback Restriction' , HRW_LOCALE ) ,
                        'id'    => 'hrw_security_cashback_msgs' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user has got the maximum number of cashback per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'cashback_count_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => 'You are allowed to get a cashback maximum of [wallet_cashback_count] times per day.  Since you have reached the count, you cannot get cashback anymore today.' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user has got the maximum cashback amount per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'cashback_reached_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => 'You are allowed to get a cashback amount maximum of [wallet_cashback_amount] per day.  Since you have already got the amount, you cannot get cashback anymore today.' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user will get the maximum cashback amount per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'cashback_reaching_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => 'By placing this order, you will be reaching to get the maximum cashback amount [wallet_cashback_amount] per day. <b>Wallet cashback available to get today is [cashback_balance].</b>' ,
                            ) ;
                }
                if ( HRW_Module_Instances::get_module_by_id ( 'wallet_withdrawal' )->is_enabled () ) {
                    $section_fields[] = array (
                        'type'  => 'subtitle' ,
                        'title' => esc_html__ ( 'Wallet Withdrawal Restriction' , HRW_LOCALE ) ,
                        'id'    => 'hrw_security_withdrawal_msgs' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user tries to give the maximum number of withdrawal request per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'withdrawal_count_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => 'You are allowed to give the withdrawal request a maximum of [wallet_withdrawal_request_count] times per day.  Since you have reached the count, you cannot give a request anymore today.' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user has withdrawn the maximum withdrawal amount per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'withdrawal_reached_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => 'You are allowed to withdraw the amount maximum of [wallet_withdrawal_amount] per day.  Since you have already withdrawn the restricted amount, you cannot withdraw anymore today.' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user will be reaching the maximum withdrawal amount per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'withdrawal_reaching_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => 'By giving the request this time, you will be reaching the maximum withdraw amount [wallet_withdrawal_amount] per day.  [withdrawal_balance_amount] is available to withdraw from your wallet today.' ,
                            ) ;
                    $section_fields[] = array (
                        'title'   => esc_html__ ( 'Message to display when a user tries to withdraw the amount more than the restricted amount per day' , HRW_LOCALE ) ,
                        'id'      => $this->get_field_key ( 'withdrawal_max_entered_msg' ) ,
                        'type'    => 'textarea' ,
                        'default' => ' Maximum withdraw amount from your wallet allowed per day is [wallet_withdrawal_amount]' ,
                            ) ;
                }
                $section_fields[] = array (
                    'type' => 'sectionend' ,
                    'id'   => 'hrw_security_messages' ,
                        ) ;
            }

            return $section_fields ;
        }

        /*
         * Security Settings JS Files
         */

        public function admin_external_js_files() {
            wp_enqueue_script ( 'hrw-security-settings' , HRW_PLUGIN_URL . '/premium/assets/js/admin/security-settings.js' , array ( 'jquery' ) , HRW_VERSION ) ;
        }

        /*
         * Frontend action
         */

        public function frontend_action() {

            if ( $this->topup_restriction_enabled == 'yes' ) {
                /* Notice */
                add_action ( 'hrw_validate_topup_form_display' , array ( $this , 'topup_restrictions' ) ) ;
                /* Topup Validation */
                add_action ( 'hrw_do_topup_validation' , array ( $this , 'topup_validation' ) ) ;
            }

            if ( $this->usage_restriction_enabled == 'yes' ) {
                /* Usage Validation - Partial */
                add_action ( 'hrw_do_partial_usage_validation' , array ( $this , 'usage_validation_partial' ) ) ;
                /* Usage Validation - Gateway */
                add_action ( 'hrw_do_gateway_usage_validation' , array ( $this , 'usage_validation_gateway' ) , 10 , 2 ) ;
            }

            if ( $this->cashback_restriction_enabled == 'yes' && HRW_Module_Instances::get_module_by_id ( 'cashback' )->is_enabled () ) {
                /* Notice */
                add_filter ( 'hrw_validate_cashback_form_display' , array ( $this , 'cashback_restrictions' ) , 10 , 2 ) ;
                /* Cashback validation */
                add_filter ( 'hrw_do_cashback_validation' , array ( $this , 'cashback_validations' ) , 10 , 2 ) ;
            }

            if ( $this->withdrawal_restriction_enabled == 'yes' && HRW_Module_Instances::get_module_by_id ( 'wallet_withdrawal' )->is_enabled () ) {
                /* Notice */
                add_action ( 'hrw_validate_withdrawal_form_display' , array ( $this , 'withdrawal_restrictions' ) ) ;
                /* Withdrawal validation */
                add_action ( 'hrw_do_withdrawal_validation' , array ( $this , 'withdrawal_validations' ) , 10 , 2 ) ;
            }
        }

        /*
         *  Topup Restrictions - Pre Notice
         */

        public function topup_restrictions() {

            if ( empty ( $this->max_topup_restriction ) && empty ( $this->max_topup_amount_restriction ) )
                return true ;

            if ( ! hrw_check_is_array ( self::get_order_ids ( 'hr_wallet_credited_once_flag' ) ) )
                return true ;

            if ( self::validate_maximum_top_up_request () )
                throw new Exception ( str_replace ( '[top-up_count]' , $this->max_topup_restriction , $this->topup_count_msg ) ) ;

            if ( self::validate_maximum_top_up_amount () )
                throw new Exception ( str_replace ( '[top-up_amount]' , hrw_price ( $this->max_topup_amount_restriction ) , $this->topup_reached_msg ) ) ;
        }

        /*
         * Validate Maximum Topup Amount
         */

        public function validate_maximum_top_up_amount() {

            if ( empty ( $this->max_topup_amount_restriction ) )
                return false ;

            return self::get_topup_from_order () >= $this->max_topup_amount_restriction ? true : false ;
        }

        /*
         * Validate Maximum Topup Request
         */

        public function validate_maximum_top_up_request() {

            if ( empty ( $this->max_topup_restriction ) )
                return false ;

            return count ( self::$hrw_order_ids ) >= $this->max_topup_restriction ? true : false ;
        }

        /*
         * Topup Validation - Post Notice
         */

        public function topup_validation() {

            if ( empty ( $this->max_topup_amount_restriction ) ) {
                return ;
            }

            $topup_from_order = self::get_topup_from_order () ;

            if ( $topup_from_order == 0 && ( $_POST[ 'hrw_topup_amount' ]) > $this->max_topup_amount_restriction ) {
                throw new Exception ( sprintf ( str_replace ( '[top-up_amount]' , hrw_price ( $this->max_topup_amount_restriction ) , $this->topup_max_entered_msg ) ) ) ;
            }

            if ( ($topup_from_order + $_POST[ 'hrw_topup_amount' ]) > $this->max_topup_amount_restriction ) {
                $topup_balance = $this->max_topup_amount_restriction - $topup_from_order ;
                throw new Exception ( sprintf ( str_replace ( array ( '[top-up_amount]' , '[top-up_balance_amount]' ) , array ( hrw_price ( $this->max_topup_amount_restriction ) , hrw_price ( $topup_balance ) ) , $this->topup_reaching_msg ) ) ) ;
            }
        }

        /*
         * Get Topup Amount From Order
         */

        public function get_topup_from_order() {
            if ( ! hrw_check_is_array ( self::get_order_ids ( 'hr_wallet_credited_once_flag' ) ) ) {
                return 0 ;
            }

            $top_up_amount = 0 ;
            foreach ( self::$hrw_order_ids as $order_id ) {
                $top_up_amount += get_post_meta ( $order_id , 'hr_wallet_topup_fund' , true ) ;
            }
            return $top_up_amount ;
        }

        /*
         * Validate Maximum Number of Cashback
         */

        public function validate_maximum_number_of_usage() {

            if ( ! $this->max_usage_restriction )
                return false ;

            return count ( self::$hrw_usage_order_ids ) >= $this->max_usage_restriction ? true : false ;
        }

        /*
         * Validate Maximum Cashback Amount
         */

        public function validate_maximum_usage_amount() {

            if ( ! $this->max_usage_amount_restriction )
                return false ;

            return self::get_usage_from_order () >= $this->max_usage_amount_restriction ? true : false ;
        }

        /*
         *  Partial Usage Validation - Post Notice
         */

        public function usage_validation_partial() {
            if ( empty ( $this->max_usage_amount_restriction ) ) {
                return ;
            }
            self::get_usage_order_ids () ;
            if ( self::validate_maximum_number_of_usage () ) {
                throw new Exception ( str_replace ( '[wallet_usage_count]' , $this->max_usage_restriction , $this->usage_count_msg ) ) ;
            }

            $usage_from_order = self::get_usage_from_order () ;
            if ( $usage_from_order == $this->max_usage_amount_restriction ) {
                throw new Exception ( str_replace ( '[wallet_usage_amount]' , hrw_price ( $this->max_usage_amount_restriction ) , $this->usage_reached_msg ) ) ;
            }

            if ( $usage_from_order == 0 && ( $_POST[ 'hrw_partial_usage' ] > $this->max_usage_amount_restriction ) ) {
                throw new Exception ( str_replace ( '[wallet_usage_amount]' , hrw_price ( $this->max_usage_amount_restriction ) , $this->usage_max_entered_msg ) ) ;
            }

            if ( ($usage_from_order + $_POST[ 'hrw_partial_usage' ]) > $this->max_usage_amount_restriction ) {
                $usage_balance = $this->max_usage_amount_restriction - $usage_from_order ;
                throw new Exception ( str_replace ( array ( '[wallet_usage_amount]' , '[wallet_balance_amount]' ) , array ( hrw_price ( $this->max_usage_amount_restriction ) , hrw_price ( $usage_balance ) ) , $this->usage_reaching_msg ) ) ;
            }
        }

        /*
         *  Gateway Usage Validation - Post Notice
         */

        public function usage_validation_gateway( $order , $orderid ) {
            if ( empty ( $this->max_usage_amount_restriction ) ) {
                return ;
            }
            self::get_usage_order_ids () ;
            if ( self::validate_maximum_number_of_usage () ) {
                throw new Exception ( str_replace ( '[wallet_usage_count]' , $this->max_usage_restriction , $this->usage_count_msg ) ) ;
            }
            $usage_from_order = self::get_usage_from_order () ;
            if ( $usage_from_order == $this->max_usage_amount_restriction ) {
                throw new Exception ( str_replace ( '[wallet_usage_amount]' , hrw_price ( $this->max_usage_amount_restriction ) , $this->usage_reached_msg ) ) ;
            }

            if ( $usage_from_order == 0 && ( $order->get_total () > $this->max_usage_amount_restriction ) ) {
                throw new Exception ( str_replace ( '[wallet_usage_amount]' , hrw_price ( $this->max_usage_amount_restriction ) , $this->usage_max_entered_msg ) ) ;
            }

            if ( ($usage_from_order + $order->get_total ()) > $this->max_usage_amount_restriction ) {
                $usage_balance = $this->max_usage_amount_restriction - $usage_from_order ;
                throw new Exception ( str_replace ( array ( '[wallet_usage_amount]' , '[wallet_balance_amount]' ) , array ( hrw_price ( $this->max_usage_amount_restriction ) , hrw_price ( $usage_balance ) ) , $this->usage_reaching_msg ) ) ;
            }
        }

        /*
         * Get Used fund from Order
         */

        public function get_usage_from_order() {
            if ( ! hrw_check_is_array ( self::get_usage_order_ids () ) ) {
                return 0 ;
            }
            $usage_amount = 0 ;
            foreach ( self::$hrw_usage_order_ids as $orderid ) {
                if ( get_post_meta ( $orderid , 'hr_wallet_partial_credit_flag' , true ) == 'yes' ) {
                    $usage_amount += get_post_meta ( $orderid , 'partial_applied_amount' , true ) ;
                }
                if ( get_post_meta ( $orderid , 'hr_wallet_full_debit_flag' , true ) == 'yes' ) {
                    $usage_amount += wc_get_order ( $orderid )->get_total () ;
                }
            }
            return $usage_amount ;
        }

        /*
         * Wallet Usage Restrictions 
         */

        public function cashback_restrictions( $msg , $cashback_value ) {

            if ( empty ( $this->max_cashback_restriction ) && empty ( $this->cashback_amount_restriction ) )
                return $msg ;

            if ( self::validate_maximum_number_of_cashback () )
                return str_replace ( '[wallet_cashback_count]' , $this->max_cashback_restriction , $this->cashback_count_msg ) ;

            if ( empty ( $this->cashback_amount_restriction ) )
                return $msg ;

            $cashback_from_post = self::get_cashback_amount () ;
            if ( $cashback_from_post >= $this->cashback_amount_restriction )
                return str_replace ( '[wallet_cashback_amount]' , hrw_price ( $this->cashback_amount_restriction ) , $this->cashback_reached_msg ) ;

            if ( ($cashback_from_post + $cashback_value) > $this->cashback_amount_restriction ) {
                $cashback_balance = $this->cashback_amount_restriction - $cashback_from_post ;
                return str_replace ( array ( '[wallet_cashback_amount]' , '[cashback_balance]' ) , array ( hrw_price ( $this->cashback_amount_restriction ) , hrw_price ( $cashback_balance ) ) , $this->cashback_reaching_msg ) ;
            }

            return $msg ;
        }

        /*
         * Validate Maximum Number of Cashback
         */

        public function validate_maximum_number_of_cashback() {

            if ( empty ( $this->max_cashback_restriction ) || ! hrw_check_is_array ( self::get_cashback_ids () ) )
                return false ;

            return count ( self::$hrw_cashback_ids ) >= $this->max_cashback_restriction ? true : false ;
        }

        /*
         * Validate Maximum Number of Cashback
         */

        public function validate_maximum_amount_of_cashback( $cashback_value ) {

            if ( empty ( $this->cashback_amount_restriction ) )
                return false ;

            return (self::get_cashback_amount () + $cashback_value) >= $this->cashback_amount_restriction ? true : false ;
        }

        /*
         *  Gateway Usage Validation - Post Notice
         */

        public function cashback_validations( $bool , $cashback_amount ) {
            if ( empty ( $this->cashback_amount_restriction ) ) {
                return ;
            }

            if ( (self::get_cashback_amount () + $cashback_amount) > $this->cashback_amount_restriction )
                return true ;
        }

        /*
         * Validate Maximum Cashback Amount
         */

        public function get_cashback_amount() {
            if ( ! hrw_check_is_array ( self::get_cashback_ids () ) ) {
                return 0 ;
            }

            $cashback_amount = 0 ;
            foreach ( self::$hrw_cashback_ids as $id ) {
                $cashback_object = hrw_get_cashback_log ( $id ) ;
                $cashback_amount += $cashback_object->get_credit_amount () ;
            }

            return $cashback_amount ;
        }

        /*
         * Withdrawal Restrictions
         */

        public function withdrawal_restrictions() {

            if ( empty ( $this->max_withdrawal_req_restriction ) && empty ( $this->max_withdrawal_amount_restriction ) )
                return true ;

            if ( ! hrw_check_is_array ( self::get_withdrawal_ids () ) )
                return true ;

            if ( self::validate_maximum_withdrawal_request () )
                throw new Exception ( str_replace ( '[wallet_withdrawal_request_count]' , $this->max_withdrawal_req_restriction , $this->withdrawal_count_msg ) ) ;

            if ( self::validate_maximum_withdrawal_amount () )
                throw new Exception ( str_replace ( '[wallet_withdrawal_amount]' , hrw_price ( $this->max_withdrawal_amount_restriction ) , $this->withdrawal_reached_msg ) ) ;
        }

        /*
         * Validate Maximum Number of Cashback
         */

        public function validate_maximum_withdrawal_request() {

            if ( empty ( $this->max_withdrawal_req_restriction ) )
                return false ;

            return count ( self::$hrw_withdrawal_ids ) >= $this->max_withdrawal_req_restriction ? true : false ;
        }

        /*
         * Validate Maximum Number of Cashback
         */

        public function validate_maximum_withdrawal_amount() {

            if ( empty ( $this->max_withdrawal_amount_restriction ) )
                return false ;

            return self::get_withdrawal_amount () >= $this->max_withdrawal_amount_restriction ? true : false ;
        }

        /*
         *  Gateway Usage Validation - Post Notice
         */

        public function withdrawal_validations() {
            if ( empty ( $this->max_withdrawal_amount_restriction ) || ! isset ( $_POST[ "hrw_withdrawal" ][ "amount" ] ) )
                return ;

            if ( $this->user_registered_day_count ) {
                $current_dateobject    = HRW_Date_Time::get_date_time_object ( 'now' , true ) ;
                $registered_dateobject = HRW_Date_Time::get_date_time_object ( HRW_Wallet_User::get_user ()->user_registered , true ) ;
                $registered_dateobject->modify ( '+' . absint ( $this->user_registered_day_count ) . ' days' ) ;

                if ( $registered_dateobject > $current_dateobject )
                    throw new Exception ( sprintf ( esc_html__ ( 'To withdraw, you must be a member on this site for %s days' , HRW_LOCALE ) , $this->user_registered_day_count ) ) ;
            }

            $revised_amount              = isset ( $_POST[ "hrw_withdrawal" ][ "fee" ] ) ? ($_POST[ "hrw_withdrawal" ][ "amount" ] + $_POST[ "hrw_withdrawal" ][ "fee" ]) : $_POST[ "hrw_withdrawal" ][ "amount" ] ;
            $withdrawal_amount_from_post = self::get_withdrawal_amount () ;
            if ( ( $withdrawal_amount_from_post == 0 && ( $revised_amount > $this->max_withdrawal_amount_restriction ) ) )
                throw new Exception ( str_replace ( array ( '[wallet_withdrawal_amount]' ) , array ( hrw_price ( $this->max_withdrawal_amount_restriction ) ) , $this->withdrawal_max_entered_msg ) ) ;

            if ( ( $withdrawal_amount_from_post + $revised_amount ) > $this->max_withdrawal_amount_restriction ) {
                $withdrawal_balance = $this->max_withdrawal_amount_restriction - $withdrawal_amount_from_post ;
                throw new Exception ( str_replace ( array ( '[wallet_withdrawal_amount]' , '[withdrawal_balance_amount]' ) , array ( hrw_price ( $this->max_withdrawal_amount_restriction ) , hrw_price ( $withdrawal_balance ) ) , $this->withdrawal_reaching_msg ) ) ;
            }
        }

        /*
         * Validate Maximum Cashback Amount
         */

        public function get_withdrawal_amount() {
            if ( ! hrw_check_is_array ( self::get_withdrawal_ids () ) ) {
                return 0 ;
            }

            $withdrawal_amount = 0 ;
            foreach ( self::$hrw_withdrawal_ids as $id ) {
                $withdrawal_obj    = hrw_get_wallet_withdrawal ( $id ) ;
                $withdrawal_amount += $withdrawal_obj->get_fee_included_amount () ;
            }

            return $withdrawal_amount ;
        }

        /*
         * Get Order IDs
         */

        public function get_order_ids( $meta_key ) {
            //Resetting Order Ids
            self::$hrw_order_ids = array () ;

            $default_args = self::get_default_args ( $meta_key ) ;

            self::$hrw_order_ids = get_posts ( $default_args ) ;

            return self::$hrw_order_ids ;
        }

        /*
         * Get Default Args
         */

        public function get_default_args( $meta_key ) {

            return array (
                'post_type'   => 'shop_order' ,
                'order'       => 'DESC' ,
                'post_status' => array ( 'wc-completed' , 'wc-processing' , 'wc-on-hold' ) ,
                'meta_key'    => '_customer_user' ,
                'meta_value'  => HRW_Wallet_User::get_user_id () ,
                'meta_query'  => array (
                    array (
                        'key'     => $meta_key ,
                        'compare' => 'yes'
                    )
                ) ,
                'numberposts' => '-1' ,
                'fields'      => 'ids' ,
                'date_query'  => array (
                    'column' => 'post_date_gmt' ,
                    'day'    => date ( 'd' ) ,
                    'year'   => date ( 'Y' ) ,
                    'month'  => date ( 'm' )
                )
                    ) ;
        }

        /*
         * Get Usage Order IDs
         */

        public function get_usage_order_ids() {

            if ( ! empty ( self::$hrw_usage_order_ids ) )
                return self::$hrw_usage_order_ids ;

            $partial_order_ids = self::get_order_ids ( 'hr_wallet_partial_credit_flag' ) ;
            $gateway_order_ids = self::get_order_ids ( 'hr_wallet_full_debit_flag' ) ;

            return self::$hrw_usage_order_ids = array_unique ( array_merge ( $partial_order_ids , $gateway_order_ids ) ) ;
        }

        /*
         * Get Cashback IDs
         */

        public function get_cashback_ids() {

            if ( ! empty ( self::$hrw_cashback_ids ) )
                return self::$hrw_cashback_ids ;

            self::$hrw_cashback_ids = get_posts ( array (
                'post_type'      => HRWP_Register_Post_Types::CASHBACK_LOG_POSTTYPE ,
                'post_status'    => array ( 'publish' ) ,
                'author'         => HRW_Wallet_User::get_user_id () ,
                'fields'         => 'ids' ,
                'posts_per_page' => '-1' ,
                'date_query'     => array (
                    'column' => 'post_date_gmt' ,
                    'day'    => date ( 'd' ) ,
                    'year'   => date ( 'Y' ) ,
                    'month'  => date ( 'm' )
                )
                    ) ) ;

            return self::$hrw_cashback_ids ;
        }

        /*
         * Withdrawal IDs
         */

        public function get_withdrawal_ids() {

            if ( ! empty ( self::$hrw_withdrawal_ids ) )
                return self::$hrw_withdrawal_ids ;

            self::$hrw_withdrawal_ids = get_posts ( array (
                'post_type'      => HRWP_Register_Post_Types::WALLET_WITHDRAWAL_POSTTYPE ,
                'post_status'    => hrw_get_withdrawal_log_statuses () ,
                'author'         => HRW_Wallet_User::get_user_id () ,
                'posts_per_page' => -1 ,
                'fields'         => 'ids' ,
                'date_query'     => array (
                    'column' => 'post_date_gmt' ,
                    'day'    => date ( 'd' ) ,
                    'year'   => date ( 'Y' ) ,
                    'month'  => date ( 'm' )
                )
                    ) ) ;

            return self::$hrw_withdrawal_ids ;
        }

    }

}
