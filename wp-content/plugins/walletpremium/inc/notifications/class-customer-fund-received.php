<?php

/**
 * Customer- Fund Received
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Customer_Fund_Received_Notification' ) ) {

    /**
     * Class HRW_Customer_Fund_Received_Notification
     */
    class HRW_Customer_Fund_Received_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'customer_fund_received' ;
            $this->section = 'module' ;
            $this->title   = esc_html__( 'Customer - Fund Received' , HRW_LOCALE ) ;

            add_action( sanitize_key( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action( sanitize_key( $this->plugin_slug . '_after_fund_transfer' ) , array( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return '{site_name} – Funds Received Via Fund Transfer' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return "Hi {user_name},

Your wallet has been credited with {transfer_amount} for  Fund Transfer from {sender} on {transfer_date}.

Your current wallet balance is {wallet_balance}." ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return 'Your wallet has been credited with {transfer_amount} for  Fund Transfer from {sender}.' ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf( esc_html__( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium() ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $args ) {

            $wallet_id     = hrw_get_wallet_id_by_user_id( $args[ 'receiver_id' ] ) ;
            $wallet_object = hrw_get_wallet( $wallet_id ) ;

            if ( is_object( $wallet_object ) ) {
                $this->recipient                           = $wallet_object->get_user()->user_email ;
                $this->sms_recipient                       = $wallet_object->get_phone() ;
                $this->placeholders[ '{transfer_amount}' ] = hrw_price( $args[ 'amount' ] ) ;
                $this->placeholders[ '{sender}' ]          = get_userdata( $args[ 'sender_id' ] )->display_name ;
                $this->placeholders[ '{user_name}' ]       = $wallet_object->get_user()->display_name ;
            }

            if ( $this->is_email_enabled() && $this->is_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_formatted_message() , $this->get_headers() , $this->get_attachments() ) ;
            }

            if ( $this->is_sms_enabled() && $this->get_sms_recipient() ) {
                $this->send_sms( $this->get_sms_recipient() , $this->get_sms_message() ) ;
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
                'id'    => 'customer_fund_received_shortcodes' ,
                    ) ;
            $settings[] = array(
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'customer_fund_received_shortcodes' ,
                    ) ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'customer_fund_received_notifications_options' ,
                    ) ;

            if ( $this->sms_module_enabled() ) {

                $settings[] = array(
                    'type'    => 'checkbox' ,
                    'title'   => esc_html__( 'Send Mail' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'mail_enabled' ) ,
                    'default' => 'yes' ,
                        ) ;
            }

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
                'id'   => 'customer_fund_received_notifications_options' ,
                    ) ;
            if ( $this->sms_module_enabled() ) {

                $settings[] = array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => 'customer_fund_received_sms_options' ,
                        ) ;
                $settings[] = array(
                    'title'   => esc_html__( 'Send SMS' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'sms_enabled' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
                $settings[] = array(
                    'title'   => esc_html__( 'Message' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key( 'sms_message' ) ,
                    'type'    => 'wpeditor' ,
                    'default' => $this->get_sms_default_message() ,
                        ) ;
                $settings[] = array(
                    'type' => 'sectionend' ,
                    'id'   => 'customer_fund_received_sms_options' ,
                        ) ;
            }
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array(
                '{site_name}'       => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Sitename' , HRW_LOCALE )
                ) ,
                '{transfer_amount}' => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Transfered amount' , HRW_LOCALE )
                ) ,
                '{user_name}'       => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Username' , HRW_LOCALE )
                ) ,
                '{sender}'          => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Sender Name' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
