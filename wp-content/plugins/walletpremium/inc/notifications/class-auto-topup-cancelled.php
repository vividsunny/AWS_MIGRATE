<?php

/**
 * Auto Topup Cancelled Manually
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Auto_Topup_Cancelled_Notification' ) ) {

    /**
     * Class HRW_Auto_Topup_Cancelled_Notification
     */
    class HRW_Auto_Topup_Cancelled_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'auto_topup_cancelled' ;
            $this->section = 'module' ;
            $this->title   = esc_html__( 'Auto Top-up Cancelled' , HRW_LOCALE ) ;

            add_action( sanitize_key( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action( sanitize_key( $this->plugin_slug . '_auto_topup_is_cancelled' ) , array( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return '{site_name} – Wallet Auto Top-up Funds addition Cancelled on{sitename}' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return "Hi {user_name},

Wallet Auto Top-up on {sitename} is Cancelled successfully. Click here to go to Wallet dashboard and re-authenticate.

Your current Wallet balance is {wallet_balance}.

Note: 

You can re-authenticate your Auto Top-up authentication any time from your Wallet Dashboard.

Thanks." ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return '' ;
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

        /*
         * sms module enabled
         */

        public function sms_module_enabled() {

            return false ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $args ) {
            if ( is_object( $args[ 'auto_topup' ] ) && is_object( $args[ 'wallet' ] ) ) {
                $this->recipient                          = $args[ 'wallet' ]->get_user()->user_email ;
                $this->sms_recipient                      = $args[ 'wallet' ]->get_phone() ;
                $this->placeholders[ '{wallet_balance}' ] = hrw_price( $args[ 'wallet' ]->get_available_balance() , array( 'currency' , $args[ 'wallet' ]->get_currency() ) ) ;
                $this->placeholders[ '{user_name}' ]      = $args[ 'wallet' ]->get_user()->display_name ;
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
                'id'    => 'customer_request_approved_shortcodes' ,
                    ) ;
            $settings[] = array(
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'customer_request_approved_shortcodes' ,
                    ) ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'customer_request_approved_notifications_options' ,
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
                'id'   => 'customer_request_approved_notifications_options' ,
                    ) ;
            if ( $this->sms_module_enabled() ) {

                $settings[] = array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => 'customer_request_approved_sms_options' ,
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
                    'id'   => 'customer_request_approved_sms_options' ,
                        ) ;
            }
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array(
                '{site_name}'      => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Sitename' , HRW_LOCALE )
                ) ,
                '{user_name}'      => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Username' , HRW_LOCALE )
                ) ,
                '{wallet_balance}' => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet Balance' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
