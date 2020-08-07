<?php

/**
 * Customer- Scheduled Wallet Lock
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Customer_Wallet_Lock_Notification' ) ) {

    /**
     * Class HRW_Customer_Wallet_Lock_Notification
     */
    class HRW_Customer_Wallet_Lock_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'customer_wallet_lock' ;
            $this->section = 'general' ;
            $this->title   = esc_html__ ( 'Customer - Wallet Lock' , HRW_LOCALE ) ;

            add_action ( sanitize_key ( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array ( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action ( sanitize_key ( $this->plugin_slug . '_wallet_lock_notification' ) , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct () ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return 'Wallet Locked on {site_name}' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return "Hi,

Your Wallet has been locked by Site Admin.
Please contact Site Admin for More Info

Thanks." ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return '' ;
        }

        /**
         * Get Enabled.
         */
        public function get_enabled() {
            if ( $this->get_option ( 'enabled' ) )
                return $this->get_option ( 'enabled' , 'no' ) ;

            return get_option ( 'hr_wallet_email_block_user_enable' ) ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf ( esc_html__ ( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__ ( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium () ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $wallet_id , $wallet_object = false ) {

            $wallet_object = hrw_get_wallet ( $wallet_id ) ;

            if ( is_object ( $wallet_object ) ) {
                $this->recipient     = $wallet_object->get_user ()->user_email ;
                $this->sms_recipient = $wallet_object->get_phone () ;
            }

            if ( $this->is_email_enabled () && $this->is_enabled () && $this->get_recipient () ) {
                $this->send_email ( $this->get_recipient () , $this->get_subject () , $this->get_formatted_message () , $this->get_headers () , $this->get_attachments () ) ;
            }
            if ( $this->is_sms_enabled () && $this->get_sms_recipient () ) {
                $this->send_sms ( $this->get_sms_recipient () , $this->get_sms_message () ) ;
            }
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            $settings = array () ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Shortcodes' , HRW_LOCALE ) ,
                'id'    => 'customer_wallet_lock_shortcodes' ,
                    ) ;
            $settings[] = array (
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'customer_wallet_lock_shortcodes' ,
                    ) ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'customer_wallet_lock_notifications_options' ,
                    ) ;

            if ( $this->sms_module_enabled () ) {

                $settings[] = array (
                    'type'    => 'checkbox' ,
                    'title'   => esc_html__ ( 'Send Mail' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'mail_enabled' ) ,
                    'default' => 'yes' ,
                        ) ;
            }

            $settings[] = array (
                'title'   => esc_html__ ( 'Subject' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'subject' ) ,
                'type'    => 'text' ,
                'default' => $this->get_default_subject () ,
                    ) ;
            $settings[] = array (
                'title'   => esc_html__ ( 'Message' , HRW_LOCALE ) ,
                'id'      => $this->get_field_key ( 'message' ) ,
                'type'    => 'wpeditor' ,
                'default' => $this->get_default_message () ,
                    ) ;
            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'customer_wallet_lock_notifications_options' ,
                    ) ;

            if ( $this->sms_module_enabled () ) {

                $settings[] = array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => 'customer_wallet_lock_sms_options' ,
                        ) ;
                $settings[] = array (
                    'title'   => esc_html__ ( 'Send SMS' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'sms_enabled' ) ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                        ) ;
                $settings[] = array (
                    'title'   => esc_html__ ( 'Message' , HRW_LOCALE ) ,
                    'id'      => $this->get_field_key ( 'sms_message' ) ,
                    'type'    => 'wpeditor' ,
                    'default' => $this->get_sms_default_message () ,
                        ) ;
                $settings[] = array (
                    'type' => 'sectionend' ,
                    'id'   => 'customer_wallet_lock_sms_options' ,
                        ) ;
            }
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array (
                '{site_name}' => array ( 'where' => esc_html__ ( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Sitename' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
