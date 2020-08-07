<?php

/**
 * Year - Wallet Account Statement
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Customer_Year_Wallet_Account_Statement_Notification' ) ) {

    /**
     * Class HRW_Customer_Year_Wallet_Account_Statement_Notification
     */
    class HRW_Customer_Year_Wallet_Account_Statement_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'customer_year_wallet_account_statement' ;
            $this->section = 'module' ;
            $this->title   = esc_html__( 'Customer - Wallet Account Statement (Year)' , HRW_LOCALE ) ;

            add_action( sanitize_key( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action( sanitize_key( $this->plugin_slug . '_customer_year_wallet_account_statement_notification' ) , array( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct() ;
        }

        /*
         * default Subject
         */

        public function get_default_subject() {

            return 'Yearly Statement ({from_date} to {to_date}) of your Wallet Account' ;
        }

        /*
         * default Message
         */

        public function get_default_message() {

            return "Hi,

Please find the attachment  which shows the wallet statement from {from_date} to {to_date}.

Thanks." ;
        }

        /**
         * Get Enabled.
         */
        public function get_enabled() {
            if ( $this->get_option( 'enabled' ) )
                return $this->get_option( 'enabled' , 'no' ) ;

            return get_option( 'hr_wallet_email_block_admin_enable' ) ;
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

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $args ) {

            $wallet_object = hrw_get_wallet( $args[ 'wallet_id' ] ) ;

            if ( is_object( $wallet_object ) ) {
                $this->recipient                     = $wallet_object->get_user()->user_email ;
                $this->placeholders[ '{from_date}' ] = $args[ 'from_date' ] ;
                $this->placeholders[ '{to_date}' ]   = $args[ 'to_date' ] ;
            }

            if ( $this->is_email_enabled() && $this->is_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_formatted_message() , $this->get_headers() , array( $args[ 'file_path' ] ) ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            $settings = array() ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Shortcodes' , HRW_LOCALE ) ,
                'id'    => 'year_wallet_account_statement_shortcodes' ,
                    ) ;
            $settings[] = array(
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'year_wallet_account_statement_shortcodes' ,
                    ) ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'year_wallet_account_statement_notifications_options' ,
                    ) ;

            $settings[] = array(
                'title'   => esc_html__( 'Subject' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'subject' ) ,
                'type'    => 'text' ,
                'default' => $this->get_default_subject() ,
                    ) ;

            $settings[] = array(
                'title'   => esc_html__( 'Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key( 'message' ) ,
                'type'    => 'wpeditor' ,
                'default' => $this->get_default_message() ,
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'year_wallet_account_statement_notifications_options' ,
                    ) ;

            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array(
                '{from_date}' => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays From Date' , HRW_LOCALE )
                ) ,
                '{to_date}'   => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays To Date' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
