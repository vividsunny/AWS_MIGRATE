<?php

/**
 * Messages Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Messages_Tab' ) ) {
    return new HRW_Messages_Tab() ;
}

/**
 * HRW_Messages_Tab.
 */
class HRW_Messages_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'messages' ;
        $this->code  = 'fa-envelope' ;
        $this->label = esc_html__( 'Messages' , HRW_LOCALE ) ;

        parent::__construct() ;
    }

    /**
     * Get settings for messages section array.
     */
    public function messages_section_array() {
        $section_fields = array() ;

        //Error Message Section Start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Error Message Settings' , HRW_LOCALE ) ,
            'id'    => 'hrw_error_messages_options' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Minimum Amount for Wallet Top-up' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Please enter an amount greater than {topup-min-amount}' ,
            'id'      => $this->get_option_key( 'minimum_topup_amount_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Amount for Wallet Top-up' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Please do not enter more than {topup-max-amount}' ,
            'id'      => $this->get_option_key( 'maximum_topup_amount_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Wallet Balance' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'You cannot Top-up your Wallet because you have reached the Maximum Threshold. Try using the available funds before proceeding to Top-up.' ,
            'id'      => $this->get_option_key( 'topup_maximum_wallet_balance_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Minimum Cart Total for Wallet Usage' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Minimum cart total of {min-cart-total} is required to use your Wallet' ,
            'id'      => $this->get_option_key( 'wallet_usage_minimum_cart_total_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Maximum Cart Total Threshold for Wallet Usage' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Since your cart total reached {max-cart-total}, you cannot use your Wallet' ,
            'id'      => $this->get_option_key( 'wallet_usage_maximum_cart_total_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Usage Date Restriction' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Wallet Usage is Restricted from {from-duration} to {to-duration}' ,
            'id'      => $this->get_option_key( 'wallet_usage_date_restriction_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Purchase Restriction' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => '{product-name} can not be purchased using Wallet' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => 'hr_wallet_msg_usage_restriction_purchase' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Top-up User Restriction' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Sorry, you cannot add funds to your Wallet' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'topup_user_restriction_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Usage Restriction' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'You are restricted to use your wallet.' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => 'hrw_wallet_msg_usage_restriction_user' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Insufficient Funds for Partial Usage' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'You have entered more than your available Funds' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => 'hr_wallet_msg_insuff_funds' ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'hrw_error_messages_options' ,
                ) ;
        //Error Message Section End
        //Top-up Message Section Start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Top-up Messages' , HRW_LOCALE ) ,
            'id'    => 'hrw_topup_messages_options' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Top-up Message on Cart Page' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'id'      => $this->get_option_key( 'enable_topup_cart_msg' ) ,
            'options' => array(
                '1' => esc_html__( 'Show' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Hide' , HRW_LOCALE ) ,
            ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Message' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Purchasing the {wallet-topup-product} will add {topup-amount} to your Wallet' ,
            'id'      => $this->get_option_key( 'topup_cart_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Top-up Message on Checkout Page' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'id'      => $this->get_option_key( 'enable_topup_checkout_msg' ) ,
            'options' => array(
                '1' => esc_html__( 'Show' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Hide' , HRW_LOCALE ) ,
            ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Message' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Purchasing the {wallet-topup-product} will add {topup-amount} to your Wallet' ,
            'id'      => $this->get_option_key( 'topup_checkout_msg' ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Low Balance Notification Messages' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'id'      => $this->get_option_key( 'enable_low_wallet_balance_msg' ) ,
            'class'   => 'hrw_premium_info_settings' ,
            'options' => array(
                '1' => esc_html__( 'Show' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Hide' , HRW_LOCALE ) ,
            ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Message' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Your Wallet Balance is running low, Please add funds to your Wallet' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => $this->get_option_key( 'low_wallet_balance_msg' ) ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'hrw_topup_messages_options' ,
                ) ;
        //Top-up Message Section End
        //wallet Usage Message Section Start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Usage Messages' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_usage_messages_options' ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Wallet Balance after Partial Usage' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'id'      => 'hr_wallet_balance_partial_usage_enable' ,
            'class'   => 'hrw_premium_info_settings' ,
            'options' => array(
                '1' => esc_html__( 'Show' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Hide' , HRW_LOCALE ) ,
            ) ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Message' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Purchasing the {wallet-topup-product} will add {topup-amount} to your Wallet' ,
            'class'   => 'hrw_premium_info_settings' ,
            'id'      => 'hr_wallet_msg_balance_partial_usage' ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'hrw_wallet_usage_messages_options' ,
                ) ;
        //wallet Usage Message Section End

        return $section_fields ;
    }

}

return new HRW_Messages_Tab() ;
