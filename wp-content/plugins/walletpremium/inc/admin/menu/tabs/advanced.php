<?php

/**
 * Advanced Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Advanced_Tab' ) ) {
    return new HRW_Advanced_Tab() ;
}

/**
 * HRW_Advanced_Tab.
 */
class HRW_Advanced_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'advanced' ;
        $this->code  = 'fa-sliders' ;
        $this->label = esc_html__( 'Advanced' , HRW_LOCALE ) ;

        parent::__construct() ;
    }

    /**
     * Get advanced setting section array.
     */
    public function advanced_section_array() {
        $section_fields = array () ;
        $user_roles     = hrw_get_user_roles() ;
        $wc_categories  = hrw_get_wc_categories() ;

        //Email section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Email Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_cron_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Email Type' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '2' ,
            'options' => array (
                '1' => esc_html__( 'HTML' , HRW_LOCALE ) ,
                '2' => esc_html__( 'WooCommerce Template' , HRW_LOCALE ) ,
            ) ,
            'desc'    => esc_html__( 'If "HTML Template" is selected, plain text emails will be sent. If "WooCommerce Template" is selected, email will be sent with customization made in WooCommerce Email' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'email_template_type' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'From Name' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => get_option( 'woocommerce_email_from_name' ) ,
            'desc'    => esc_html__( 'Sender name for wallet emails' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'email_from_name' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'From Email' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => get_option( 'woocommerce_email_from_address' ) ,
            'desc'    => esc_html__( 'Sender email for wallet emails' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'email_from_email' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_cron_options' ,
                ) ;
        //Email section end
        //Cron section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Cron Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_cron_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Cron Job Running Time Type' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array (
                '1' => esc_html__( 'Minutes' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Hours' , HRW_LOCALE ) ,
                '3' => esc_html__( 'Days' , HRW_LOCALE ) ,
            ) ,
            'id'      => $this->get_option_key( 'cron_time_type' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Cron Job Running Time' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '30' ,
            'id'      => $this->get_option_key( 'cron_time_value' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_cron_options' ,
                ) ;
        //Cron section end
        //Wallet fund threshold start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Low Wallet Funds Threshold Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_fund_threshold_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Threshold for Low Wallet Funds' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '' ,
            'class'   => 'hrw_premium_info_settings' ,
            'desc'    => esc_html__( 'If the wallet balance goes less than or equal to the threshold, a warning message will be displayed for the user to Top-up their wallet' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'low_wallet_amount_limit' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Display Low Wallet Threshold Message as Pop-Up' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'alert_low_balance' ) ,
            'type'    => 'checkbox' ,
            'class'   => 'hrw_premium_info_settings' ,
            'default' => 'no' ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_cron_options' ,
                ) ;
        //Wallet fund threshold end
        //Wallet restriction section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Restriction Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_restriction_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Enable Wallet Top-up for' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'class'   => 'hrw_premium_info_settings' ,
            'options' => array (
                '1' => esc_html__( 'All Users' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Selected Users' , HRW_LOCALE ) ,
                '3' => esc_html__( 'Selected User Roles' , HRW_LOCALE ) ,
            ) ,
            'id'      => $this->get_option_key( 'topup_user_restriction_type' ) ,
                ) ;
        $section_fields[] = array (
            'title'       => esc_html__( 'Select User(s)' , HRW_LOCALE ) ,
            'id'          => $this->get_option_key( 'topup_user_restriction' ) ,
            'class'       => 'hrw_topup_allowed_users_restriction hrw_premium_info_settings' ,
            'action'      => 'hrw_customers_search' ,
            'type'        => 'ajaxmultiselect' ,
            'list_type'   => 'customers' ,
            'placeholder' => esc_html__( 'Select a User' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Select User Roles' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'topup_user_role_restriction' ) ,
            'class'   => 'hrw_select2 hrw_topup_allowed_users_restriction hrw_premium_info_settings' ,
            'type'    => 'multiselect' ,
            'options' => $user_roles ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Balance can be Used for Purchasing' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array (
                '1' => esc_html__( 'All Products' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Selected Products' , HRW_LOCALE ) ,
                '3' => esc_html__( 'Products Of Any Category' , HRW_LOCALE ) ,
                '4' => esc_html__( 'Products Of Selected Category' , HRW_LOCALE ) ,
            ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'wallet_usage_product_restriction_type' ) ,
                ) ;
        $section_fields[] = array (
            'title'       => esc_html__( 'Select Product(s)' , HRW_LOCALE ) ,
            'id'          => $this->get_option_key( 'wallet_usage_product_restriction' ) ,
            'class'       => 'hrw_wallet_bal_pruchase_restriction hrw_premium_info_settings' ,
            'action'      => 'hrw_product_search' ,
            'type'        => 'ajaxmultiselect' ,
            'list_type'   => 'products' ,
            'placeholder' => esc_html__( 'Select a Product' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Select Categories' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'wallet_usage_category_restriction' ) ,
            'class'   => 'hrw_select2 hrw_wallet_bal_pruchase_restriction hrw_premium_info_settings' ,
            'type'    => 'multiselect' ,
            'options' => $wc_categories ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Enable Wallet Usage for' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array (
                '1' => esc_html__( 'All Users' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Selected Users' , HRW_LOCALE ) ,
                '3' => esc_html__( 'Selected User Roles' , HRW_LOCALE ) ,
            ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'wallet_usage_user_restriction_type' ) ,
                ) ;
        $section_fields[] = array (
            'title'       => esc_html__( 'Select User(s)' , HRW_LOCALE ) ,
            'id'          => $this->get_option_key( 'wallet_usage_user_restriction' ) ,
            'class'       => 'hrw_usage_allowed_restriction hrw_premium_info_settings' ,
            'action'      => 'hrw_customers_search' ,
            'type'        => 'ajaxmultiselect' ,
            'list_type'   => 'customers' ,
            'placeholder' => esc_html__( 'Select a User' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Select User Roles' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'wallet_usage_user_role_restriction' ) ,
            'class'   => 'hrw_select2 hrw_usage_allowed_restriction hrw_premium_info_settings' ,
            'type'    => 'multiselect' ,
            'options' => $user_roles ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Hide Wallet Menu in My Account Page' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'hide_wallet_menu' ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'type'    => 'checkbox' ,
            'default' => 'no' ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_cron_options' ,
                ) ;
        //Wallet restriction section end
        //Wallet balance round off section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Balance Round Off Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_balance_round_off_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Round Off Type' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array (
                '1' => esc_html__( 'Two Decimal Places' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Whole Number' , HRW_LOCALE ) ,
                '3' => esc_html__('WooCommerce', HRW_LOCALE)
            ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'round_off_type' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Rounding Method' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array (
                '1' => esc_html__( 'Floor' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Ceil' , HRW_LOCALE ) ,
            ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'round_off_method' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_wallet_balance_round_off_options' ,
                ) ;
        //Wallet balance round off section end
        //Wallet balance display section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Balance Display Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_balance_display_options' ,
                ) ;
        $section_fields[] = array (
            'title'         => esc_html__( 'Display Wallet Balance' , HRW_LOCALE ) ,
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => 'start' ,
            'class'         => 'hrw_premium_info_settings' ,
            'id'            => $this->get_option_key( 'enable_cart_wallet_balance' ) ,
            'desc'          => esc_html__( 'Below Cart Table' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array (
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => 'end' ,
            'class'         => 'hrw_premium_info_settings' ,
            'id'            => $this->get_option_key( 'enable_checkout_wallet_balance' ) ,
            'desc'          => esc_html__( 'Checkout Page' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_wallet_balance_display_options' ,
                ) ;
        //Wallet balance display section end
        //Dashboard Display section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Table Display Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_dashboard_display_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Column Type' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array (
                '1' => esc_html__( 'S.No' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Post ID' , HRW_LOCALE ) ,
            ) ,
            'class'   => 'hrw_sno_type_settings' ,
            'id'      => $this->get_option_key( 'sno_type' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_dashboard_display_options' ,
                ) ;
        //Dashboard Display section end
        //Wallet custom css section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Custom CSS' , HRW_LOCALE ) ,
            'id'    => 'hrw_custom_css_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Custom CSS' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => '' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'custom_css' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_custom_css_options' ,
                ) ;
        //Wallet custom css section end

        return $section_fields ;
    }

}

return new HRW_Advanced_Tab() ;
