<?php

/**
 * Localizations Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Localizations_Tab' ) ) {
    return new HRW_Localizations_Tab() ;
}

/**
 * HRW_Localizations_Tab.
 */
class HRW_Localizations_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'localizations' ;
        $this->code  = 'fa-pencil-square-o' ;
        $this->label = esc_html__( 'Localizations' , HRW_LOCALE ) ;

        parent::__construct() ;
    }

    /**
     * Get settings for localizations section array.
     */
    public function localizations_section_array() {
        $section_fields = array () ;

        //Wallet Menu section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Menu Label' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_menu_label_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Menu Name' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Wallet' ,
            'id'      => $this->get_option_key( 'wallet_menu_label' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_wallet_menu_label_options' ,
                ) ;
        //Wallet Menu section End
        //Topup form labels section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Top-up Form Labels' , HRW_LOCALE ) ,
            'id'    => 'hrw_topup_form_lables_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Top-up Form Title' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Add Funds to Wallet' ,
            'id'      => $this->get_option_key( 'topup_form_title_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Top-up Amount' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Top-up Amount' ,
            'id'      => $this->get_option_key( 'topup_form_amount_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Top-up Amount Placeholder' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Enter Amount' ,
            'id'      => $this->get_option_key( 'topup_form_amount_placeholder' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Add to Wallet Button' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Add to Wallet' ,
            'id'      => $this->get_option_key( 'topup_form_button_label' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_topup_form_lables_options' ,
                ) ;
        //Topup form labels section end
        //Transaction log labels section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Transaction Log Table Labels' , HRW_LOCALE ) ,
            'id'    => 'hrw_transaction_log_labels_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Current Status' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Wallet Status' ,
            'id'      => $this->get_option_key( 'wallet_balance_status_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Balance' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Wallet balance' ,
            'id'      => $this->get_option_key( 'wallet_balance_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Total Amount Spent on Purchase' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Total Amount Spent on Purchase' ,
            'id'      => $this->get_option_key( 'total_amount_spent_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Expiry Date' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Expiry date' ,
            'id'      => $this->get_option_key( 'wallet_balance_expiry_date_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'S.No' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'S.No' ,
            'id'      => $this->get_option_key( 'transaction_log_sno_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Event' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Event' ,
            'id'      => $this->get_option_key( 'transaction_log_event_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Amount' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Amount' ,
            'id'      => $this->get_option_key( 'transaction_log_amount_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Status' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Status' ,
            'id'      => $this->get_option_key( 'transaction_log_status_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Total' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Total' ,
            'id'      => $this->get_option_key( 'transaction_log_total_label' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Date' , HRW_LOCALE ) ,
            'type'    => 'text' ,
            'default' => 'Date' ,
            'id'      => $this->get_option_key( 'transaction_log_date_label' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_transaction_log_labels_options' ,
                ) ;
        //Transaction log labels section end
        //Log messages section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Log Messages' , HRW_LOCALE ) ,
            'id'    => 'hrw_log_messages_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Top-up Success' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Wallet Top-up Successfull' ,
            'id'      => $this->get_option_key( 'wallet_topup_success_log' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Usage through Gateway' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Wallet Gateway used on Order {orderid}' ,
            'id'      => $this->get_option_key( 'wallet_usage_through_gateway_log' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Partial Wallet Usage' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'class'   => 'hrw_premium_info_settings' ,
            'default' => 'Wallet Balance used on Order {orderid}' ,
            'id'      => $this->get_option_key( 'partial_wallet_usage_log' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Funds Credited' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Funds has been credited to Wallet by Site Admin' ,
            'id'      => 'hr_wallet_log_funds_credited' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Funds Debited' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Funds has been debited from Wallet by Site Admin' ,
            'id'      => 'hr_wallet_log_funds_debited' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Wallet Expired' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Debited due to wallet expiry' ,
            'id'      => $this->get_option_key( 'wallet_expired_log' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_log_messages_options' ,
                ) ;
        //Log messages section end
        //Wallet actions section start
        $section_fields[] = array (
            'type'  => 'title' ,
            'title' => esc_html__( 'Wallet Actions' , HRW_LOCALE ) ,
            'id'    => 'hrw_wallet_actions_options' ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Order Cancel' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Funds has been Credited on your Wallet for Cancelling on Order {orderid}' ,
            'id'      => $this->get_option_key( 'order_cancel_debit_amount_log' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Order Refund' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Funds has been Credited on your Wallet for Refunding on Order {orderid}' ,
            'id'      => $this->get_option_key( 'order_refund_debit_amount_log' ) ,
                ) ;
        $section_fields[] = array (
            'title'   => esc_html__( 'Order Failed' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => 'Funds has been Credited on your Wallet due to Failed Order {orderid}' ,
            'id'      => $this->get_option_key( 'order_failed_debit_amount_log' ) ,
                ) ;
        $section_fields[] = array (
            'type' => 'sectionend' ,
            'id'   => 'hrw_wallet_actions_options' ,
                ) ;
        //Wallet actions section end

        return $section_fields ;
    }

}

return new HRW_Localizations_Tab() ;
