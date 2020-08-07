<?php

/**
 * General Tab
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( class_exists( 'HRW_General_Tab' ) ) {
    return new HRW_General_Tab() ;
}

/**
 * HRW_General_Tab.
 */
class HRW_General_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'general' ;
        $this->code  = 'fa-cogs' ;
        $this->label = esc_html__( 'General' , HRW_LOCALE ) ;

        if( empty( get_option( 'hrw_general_topup_product_id' , array() ) ) ) {
            HRW_Settings::add_error( esc_html__( 'Please create a new product under "Wallet - Funds Top-Up Settings" to allow your users to top up funds.' , HRW_LOCALE ) ) ;
        }

        parent::__construct() ;
    }

    /**
     * Get settings general section array.
     */
    public function general_section_array() {
        $section_fields        = array() ;
        $wc_order_statuses     = hrw_get_wc_order_statuses() ;
        $wc_available_gateways = hrw_get_wc_available_gateways() ;
        unset( $wc_available_gateways[ 'hrw_stripe' ] ) ;

        //General Section Start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'General Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_general_options' ,
                ) ;
        $section_fields[] = array(
            'title'         => esc_html__( 'Wallet Balance Expires after' , HRW_LOCALE ) ,
            'type'          => 'number' ,
            'default'       => '365' ,
            'custom_fields' => array( 'min' => 1 ) ,
            'class'         => 'hrw_premium_info_settings' ,
            'desc'          => esc_html__( 'day(s) from the date of credit purchase' , HRW_LOCALE ) ,
            'id'            => $this->get_option_key( 'wallet_expiry_limit' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Hide Expiry Date' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'hide_expiry_date' ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'type'    => 'checkbox' ,
            'default' => 'no' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Top-up Page URL' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => hrw_get_page_id( 'dashboard' ) ,
            'id'      => 'hrw_topup_page_id' ,
            'options' => hrw_get_page_ids() ,
            'desc'    => esc_html__( 'If you have set topup shortcode,please select that page' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Dashborad URL' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => hrw_get_page_id( 'dashboard' ) ,
            'id'      => 'hrw_dashboard_page_id' ,
            'options' => hrw_get_page_ids() ,
            'desc'    => esc_html__( 'If you have set topup shortcode,please select that page' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Enable User Phone Number Field in Registration Page' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'enable_phone_number_field' ) ,
            'type'    => 'checkbox' ,
            'default' => 'yes' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Select Field Option' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'select_field_option' ) ,
            'type'    => 'select' ,
            'class'   => 'hrw_select_field_option' ,
            'default' => '1' ,
            'options' => array(
                '1' => esc_html__( 'Optional' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Mandatory' , HRW_LOCALE ) ,
            ) ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'hrw_general_options' ,
                ) ;
        //General Section End
        //Top-up Section Start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet - Funds Top-up Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_fund_topup_options' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Allow Users to Deposit Funds' , HRW_LOCALE ) ,
            'type'    => 'checkbox' ,
            'default' => 'yes' ,
            'desc'    => esc_html__( 'If enabled, your customers can Top-up their wallet' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'enable_topup' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Top-up Product' , HRW_LOCALE ) ,
            'type'    => 'select' ,
//            'default' => '1' ,
            'id'      => $this->get_option_key( 'topup_product_type' ) ,
            'options' => array(
                '1' => esc_html__( 'New Product' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Existing Product' , HRW_LOCALE ) ,
            ) ,
            'desc'    => esc_html__( 'Wallet Top-up requires a product through which the Top-up has to be done. If "New Product" is selected, a new product will be created. If "Existing Product" is selected, an existing product has to be selected.' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'       => esc_html__( 'Select a Product' , HRW_LOCALE ) ,
            'id'          => $this->get_option_key( 'topup_product_id' ) ,
            'class'       => 'hrw_product_selection' ,
            'action'      => 'hrw_product_search' ,
            'type'        => 'ajaxmultiselect' ,
            'list_type'   => 'products' ,
            'placeholder' => esc_html__( 'Select a product' , HRW_LOCALE ) ,
            'multiple'    => false ,
            'allow_clear' => false ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Product Name' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Wallet' ,
            'id'      => $this->get_option_key( 'topup_product_name' ) ,
            'class'   => 'hrw_product_selection' ,
                ) ;
        $section_fields[] = array(
            'type'    => 'button' ,
            'default' => esc_html__( 'Create New product' , HRW_LOCALE ) ,
            'id'      => 'hrw_create_product_btn' ,
            'class'   => 'hrw_product_selection' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Fund Type' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'class'   => 'hrw_premium_info_settings' ,
            'options' => array(
                '1' => esc_html__( 'Default' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Prefilled Amount(Editable)' , HRW_LOCALE ) ,
                '3' => esc_html__( 'Prefilled Amount(Non-Editable)' , HRW_LOCALE ) ,
                '4' => esc_html__( 'Prefilled Buttons' , HRW_LOCALE ) ,
            ) ,
            'id'      => $this->get_option_key( 'topup_amount_type' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Fund Number Type' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'class'   => 'hrw_fund_number_type' ,
            'options' => array(
                '1' => esc_html__( 'Whole Number' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Decimals' , HRW_LOCALE ) ,
            ) ,
            'id'      => $this->get_option_key( 'fund_number_type' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Prefilled Amount' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => '' ,
            'class'   => 'hrw_premium_info_settings hrw_prefilled_amount' ,
            'id'      => $this->get_option_key( 'topup_prefilled_amount' ) ,
            'desc'    => esc_html__( 'For "Prefilled Buttons" value should be seperated by commas' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Minimum Amount for Top-up' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '' ,
            'class'   => 'hrw_prefilled_amount_min_max' ,
            'desc'    => esc_html__( 'The minimum amount needed for wallet Top-up' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'topup_minimum_amount' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Amount for Top-up' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '' ,
            'class'   => 'hrw_prefilled_amount_min_max' ,
            'desc'    => esc_html__( 'The maximum amount needed for wallet Top-up' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'topup_maximum_amount' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Wallet Balance Per User' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '1000' ,
            'desc'    => esc_html__( 'The maximum wallet balance which can be accumulated by a customer' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'topup_maximum_wallet_balance' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Hide Selected Payment Gateways for Wallet Top-up' , HRW_LOCALE ) ,
            'type'    => 'multiselect' ,
            'default' => array() ,
            'class'   => 'hrw_select2' ,
            'options' => $wc_available_gateways ,
            'desc'    => esc_html__( 'The selected payment gateways will be hidden during a wallet Top-up' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'topup_hide_wc_gateways' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Funds will be Added to Wallet when Order Status becomes' , HRW_LOCALE ) ,
            'type'    => 'multiselect' ,
            'class'   => 'hrw_select2' ,
            'default' => array( 'completed' ) ,
            'options' => $wc_order_statuses ,
            'id'      => $this->get_option_key( 'topup_order_status' ) ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'hrw_fund_topup_options' ,
                ) ;
        //Top-up Section End
        //Fund Usage Section Start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet - Funds Usage Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_fund_usage_options' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Funds will be Debited from Wallet when Order Status becomes' , HRW_LOCALE ) ,
            'type'    => 'multiselect' ,
            'default' => array( 'completed' ) ,
            'class'   => 'hrw_select2 hrw_premium_info_settings' ,
            'options' => $wc_order_statuses ,
            'id'      => $this->get_option_key( 'wallet_usage_order_status' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Minimum Cart Total for Wallet Usage' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '' ,
            'id'      => $this->get_option_key( 'wallet_usage_minimum_amount' ) ,
            'desc'    => esc_html__( 'The minimum cart total needed in order to use the wallet' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Cart Total for Wallet Usage' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '' ,
            'id'      => $this->get_option_key( 'wallet_usage_maximum_amount' ) ,
            'desc'    => esc_html__( 'The wallet cannot be used for orders which exceed the maximum cart total amount' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'           => esc_html__( 'Restrict Wallet Usage' , HRW_LOCALE ) ,
            'type'            => 'datepicker' ,
            'default'         => '' ,
            'datepickergroup' => 'start' ,
            'label'           => esc_html__( 'From' , HRW_LOCALE ) ,
            'id'              => $this->get_option_key( 'wallet_usage_from_date_restriction' ) ,
                ) ;
        $section_fields[] = array(
            'type'            => 'datepicker' ,
            'default'         => '' ,
            'datepickergroup' => 'end' ,
            'label'           => esc_html__( 'To' , HRW_LOCALE ) ,
            'id'              => $this->get_option_key( 'wallet_usage_to_date_restriction' ) ,
                ) ;
        $section_fields[] = array(
            'title'         => esc_html__( 'Restrict Wallet Usage on Following Day(s)' , HRW_LOCALE ) ,
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => 'start' ,
            'id'            => $this->get_option_key( 'wallet_usage_sunday_restriction' ) ,
            'desc'          => esc_html__( 'Sunday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => '' ,
            'id'            => $this->get_option_key( 'wallet_usage_monday_restriction' ) ,
            'desc'          => esc_html__( 'Monday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => '' ,
            'id'            => $this->get_option_key( 'wallet_usage_tuesday_restriction' ) ,
            'desc'          => esc_html__( 'Tuesday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => '' ,
            'id'            => $this->get_option_key( 'wallet_usage_wednesday_restriction' ) ,
            'desc'          => esc_html__( 'Wednesday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => '' ,
            'id'            => $this->get_option_key( 'wallet_usage_thursday_restriction' ) ,
            'desc'          => esc_html__( 'Thursday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => '' ,
            'id'            => $this->get_option_key( 'wallet_usage_friday_restriction' ) ,
            'desc'          => esc_html__( 'Friday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => 'end' ,
            'id'            => $this->get_option_key( 'wallet_usage_saturday_restriction' ) ,
            'desc'          => esc_html__( 'Saturday' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Allow Partial Payments Using Wallet' , HRW_LOCALE ) ,
            'type'    => 'checkbox' ,
            'default' => '' ,
            'class'   => 'hrw_premium_info_settings' ,
            'desc'    => esc_html__( 'If enabled, your customers will be able to pay partially using their wallet and the remaining amount using other payment gateways' , HRW_LOCALE ) ,
            'id'      => $this->get_option_key( 'enable_partial_payment' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Allow Partial Payments' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'options' => array(
                '1' => esc_html__( 'Any Time' , HRW_LOCALE ) ,
                '2' => esc_html__( 'When Wallet Balance is Less than Order Total' , HRW_LOCALE ) ,
            ) ,
            'id'      => $this->get_option_key( 'partial_payment_restriction_type' ) ,
            'class'   => 'hrw_partial_payment hrw_premium_info_settings' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Funds that can be Used in an Order' , HRW_LOCALE ) ,
            'type'    => 'number' ,
            'default' => '500' ,
            'id'      => $this->get_option_key( 'partial_payment_maximum_amount_limit' ) ,
            'class'   => 'hrw_partial_payment hrw_premium_info_settings' ,
                ) ;
        $section_fields[] = array(
            'title'         => esc_html__( 'Allow Partial Payments' , HRW_LOCALE ) ,
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => 'start' ,
            'id'            => $this->get_option_key( 'enable_partial_payment_in_cart' ) ,
            'class'         => 'hrw_partial_payment hrw_premium_info_settings' ,
            'desc'          => esc_html__( 'Cart Page' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'type'          => 'checkbox' ,
            'default'       => 'no' ,
            'checkboxgroup' => 'end' ,
            'id'            => $this->get_option_key( 'enable_partial_payment_in_checkout' ) ,
            'class'         => 'hrw_partial_payment hrw_premium_info_settings' ,
            'desc'          => esc_html__( 'Checkout Page' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Hide other Payment Gateways when Wallet Payment Gateway is Visible' , HRW_LOCALE ) ,
            'type'    => 'checkbox' ,
            'default' => 'no' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'hide_other_wc_gateways' ) ,
            'desc'    => esc_html__( 'Not applicable when wallet Top-up product is added in cart' , HRW_LOCALE ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Error message to display at checkout when using Wallet Payment Gateway with insufficient balance' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'You can\'t place this order using Wallet Payment Gateway due to insufficient wallet balance on your account. Complete this order using some other payment gateway.' ,
            'id'      => $this->get_option_key( 'insufficient_product_purchase_restriction_msg' ) ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'hrw_fund_usage_options' ,
                ) ;
        //Fund Usage Section End

        return $section_fields ;
    }

}

return new HRW_General_Tab() ;
