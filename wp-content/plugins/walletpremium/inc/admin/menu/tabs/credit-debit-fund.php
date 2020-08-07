<?php

/**
 * Credit Debit Fund Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Credit_Debit_Fund_Tab' ) ) {
    return new HRW_Credit_Debit_Fund_Tab() ;
}

/**
 * HRW_Credit_Debit_Fund_Tab.
 */
class HRW_Credit_Debit_Fund_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'credit_debit_fund' ;
        $this->code  = 'fa-credit-card' ;
        $this->label = esc_html__( 'Credit Debit Fund' , HRW_LOCALE ) ;

        parent::__construct() ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Get settings Credit debit fund section array.
     */
    public function credit_debit_fund_section_array() {

        $section_fields   = array() ;
        //credit debit section start
        $section_fields[] = array(
            'type'  => 'title' ,
            'title' => esc_html__( 'Credit/Debit Funds' , HRW_LOCALE ) ,
            'id'    => 'esf_credit_debit_options' ,
                ) ;
        $section_fields[] = array(
            'title'       => esc_html__( 'Select a user' , HRW_LOCALE ) ,
            'id'          => 'hr_select_customers' ,
            'action'      => 'hrw_customers_search' ,
            'type'        => 'ajaxmultiselect' ,
            'list_type'   => 'customers' ,
            'placeholder' => esc_html__( 'Select a user' , HRW_LOCALE ) ,
            'multiple'    => false ,
            'allow_clear' => false ,
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Credit/Debit' , HRW_LOCALE ) ,
            'type'    => 'select' ,
            'default' => '1' ,
            'id'      => 'hr_wallet_send_funds_type' ,
            'options' => array(
                '1' => esc_html__( 'Credit' , HRW_LOCALE ) ,
                '2' => esc_html__( 'Debit' , HRW_LOCALE ) ,
            ) ,
                ) ;
        $section_fields[] = array(
            'title'             => esc_html__( 'Enter Funds' , HRW_LOCALE ) ,
            'type'              => 'number' ,
            'default'           => '' ,
            'id'                => 'hr_wallet_send_funds_value' ,
            'custom_attributes'  => array('min' => '1'),
                ) ;
        $section_fields[] = array(
            'title'   => esc_html__( 'Reason' , HRW_LOCALE ) ,
            'type'    => 'textarea' ,
            'default' => '' ,
            'id'      => 'hr_wallet_send_reason' ,
                ) ;
        $section_fields[] = array(
            'type'          => 'button' ,
            'without_label' => true ,
            'default'       => esc_html__( 'Credit / Debit' , HRW_LOCALE ) ,
            'id'            => 'hrw_credit_debit_btn' ,
                ) ;
        $section_fields[] = array(
            'type' => 'sectionend' ,
            'id'   => 'esf_display_options' ,
                ) ;
        //credit debit section end
        return $section_fields ;
    }

}

return new HRW_Credit_Debit_Fund_Tab() ;
