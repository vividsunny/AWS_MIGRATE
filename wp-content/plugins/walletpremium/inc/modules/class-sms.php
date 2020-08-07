<?php

/**
 * SMS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_SMS_Module' ) ) {

    /**
     * Class HRW_SMS_Module
     */
    class HRW_SMS_Module extends HRW_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                   => 'no' ,
            'api_method'                => '1' ,
            'twilio_account_sid'        => '' ,
            'twilio_account_auth_token' => '' ,
            'from_number'               => '' ,
            'admin_number'              => '' ,
            'nexmo_key'                 => '' ,
            'nexmo_secret'              => ''
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sms' ;
            $this->title = esc_html__( 'SMS' , HRW_LOCALE ) ;

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

            //SMS Settings Section Start
            $section_fields[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'SMS Settings' , HRW_LOCALE ) ,
                'id'    => 'sms_options' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'From Number' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'from_number' ) ,
                'desc'    => esc_html__( 'The Number from which the SMS should be sent.' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Admin Mobile Number' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'admin_number' ) ,
                'desc'    => esc_html__( 'The Mobile Number should be entered with Country Code.' , HRW_LOCALE ) ,
                'type'    => 'text' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'SMS API' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'api_method' ) ,
                'desc'    => esc_html__( 'Choose the API through which the SMS should be sent.' , HRW_LOCALE ) ,
                'type'    => 'select' ,
                'class'   => 'hrw_sms_module_api_method' ,
                'default' => '1' ,
                'options' => array(
                    '1' => esc_html__( 'Twilio' , HRW_LOCALE ) ,
                    '2' => esc_html__( 'Nexmo' , HRW_LOCALE ) ,
                ) ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Twilio Account SID' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'twilio_account_sid' ) ,
                'type'    => 'text' ,
                'class'   => 'hrw_twilio_account_method' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Twilio Account Auth Token' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'twilio_account_auth_token' ) ,
                'type'    => 'text' ,
                'class'   => 'hrw_twilio_account_method' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Nexmo Key' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'nexmo_key' ) ,
                'type'    => 'text' ,
                'class'   => 'hrw_nexmo_account_method' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array(
                'title'   => esc_html__( 'Nexmo Secret' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'nexmo_secret' ) ,
                'type'    => 'text' ,
                'class'   => 'hrw_nexmo_account_method' ,
                'default' => '' ,
                    ) ;
            $section_fields[] = array(
                'type' => 'sectionend' ,
                'id'   => 'sms_options' ,
                    ) ;
            //SMS Settings Section end

            return $section_fields ;
        }

        /*
         * Action
         */

        public function actions() {
            add_filter( 'hrw_sms_module_enable' , array( $this , 'sms_module_enable' ) , 10 , 1 ) ;
        }

        /*
         * Check SMS Module enabled
         */

        public function sms_module_enable( $bool ) {
            return true ;
        }

    }

}
