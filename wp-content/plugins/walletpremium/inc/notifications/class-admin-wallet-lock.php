<?php

/**
 * Admin- Scheduled Wallet Lock
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( ! class_exists( 'HRW_Admin_Wallet_Lock_Notification' ) ) {

    /**
     * Class HRW_Admin_Wallet_Lock_Notification
     */
    class HRW_Admin_Wallet_Lock_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id    = 'admin_wallet_lock' ;
            $this->title = esc_html__( 'Admin - Wallet Lock' , HRW_LOCALE ) ;

            add_action( sanitize_key( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action( sanitize_key( $this->plugin_slug . '_wallet_lock_notification' ) , array( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return 'Wallet locked on {site_name}' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return "Hi,

The Wallet of your customer {username} has
 been locked on {date}. The Customer currently has {wallet-balance} in their account

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
            if( $this->get_option( 'enabled' ) )
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
        public function trigger( $wallet_id , $wallet_object = false ) {

            $wallet_object = hrw_get_wallet( $wallet_id ) ;

            if( is_object( $wallet_object ) ) {
                $this->recipient                          = $this->get_from_address() ;
                $this->sms_recipient                      = $wallet_object->get_phone() ;
                $this->placeholders[ '{username}' ]       = $wallet_object->get_user()->display_name ;
                $this->placeholders[ '{date}' ]           = $wallet_object->get_date() ;
                $this->placeholders[ '{wallet-balance}' ] = hrw_price( $wallet_object->get_available_balance() ) ;
            }

            if( $this->is_email_enabled() && $this->is_enabled() && $this->get_recipient() ) {
                $this->send_email( $this->get_recipient() , $this->get_subject() , $this->get_formatted_message() , $this->get_headers() , $this->get_attachments() ) ;
            }

            if( $this->is_sms_enabled() && $this->get_sms_recipient() ) {
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
                'id'    => 'admin_wallet_lock_shortcodes' ,
                    ) ;
            $settings[] = array(
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'admin_wallet_lock_shortcodes' ,
                    ) ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'admin_wallet_lock_notifications_options' ,
                    ) ;

            if( $this->sms_module_enabled() ) {

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
                'id'   => 'admin_wallet_lock_notifications_options' ,
                    ) ;

            if( $this->sms_module_enabled() ) {

                $settings[] = array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => 'admin_wallet_lock_sms_options' ,
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
                    'id'   => 'admin_wallet_lock_sms_options' ,
                        ) ;
            }
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array(
                '{username}'       => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Username' , HRW_LOCALE )
                ) ,
                '{date}'           => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet transaction date' , HRW_LOCALE )
                ) ,
                '{wallet-balance}' => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet Balance' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
