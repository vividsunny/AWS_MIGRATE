<?php

/**
 * Admin- Wallet Fund Debited
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Admin_Funds_Debited_Notification' ) ) {

    /**
     * Class HRW_Admin_Funds_Debited_Notification
     */
    class HRW_Admin_Funds_Debited_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'admin_funds_debited' ;
            $this->section = 'general' ;
            $this->title   = esc_html__( 'Admin - Wallet Funds Debited' , HRW_LOCALE ) ;

            add_action( sanitize_key( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action( sanitize_key( $this->plugin_slug . '_after_wallet_amount_debited' ) , array( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return 'Wallet Funds used on {site_name}' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return 'Hi,

Your Customer {username} has used their Wallet Funds {wallet-transaction-amount} for {event}. Their current available Wallet Balance is {wallet-balance}. It will expire on {expiry-date}

Thanks.' ;
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

            if ( $this->get_option( 'enabled' ) )
                return $this->get_option( 'enabled' , 'no' ) ;

            return get_option( 'hr_wallet_email_usage_funds_admin_enable' , 'no' ) ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $wallet_id , $transaction_id , $transaction_object = false ) {

            if ( $transaction_id && ! is_a( $transaction_object , 'HRW_Transaction_Log' ) ) {
                $transaction_object = hrw_get_transaction_log( $transaction_id ) ;
            }

            $wallet_object = $transaction_object->get_wallet() ;

            if ( is_object( $transaction_object ) && is_object( $wallet_object ) ) {
                $this->recipient                                     = $this->get_from_address() ;
                $this->sms_recipient                                 = $transaction_object->get_phone() ;
                $this->placeholders[ '{username}' ]                  = $transaction_object->get_user()->display_name ;
                $this->placeholders[ '{wallet-transaction-amount}' ] = hrw_price( $transaction_object->get_amount() ) ;
                $this->placeholders[ '{event}' ]                     = $transaction_object->get_event() ;
                $this->placeholders[ '{expiry-date}' ]               = $wallet_object->get_formatted_expired_date() ;
                $this->placeholders[ '{wallet-balance}' ]            = hrw_price( $transaction_object->get_total() ) ;
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
                'id'    => 'admin_funds_debited_shortcodes' ,
                    ) ;
            $settings[] = array(
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'admin_funds_debited_shortcodes' ,
                    ) ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'admin_funds_debited_notifications_options' ,
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
                'id'   => 'admin_funds_debited_notifications_options' ,
                    ) ;

            if ( $this->sms_module_enabled() ) {

                $settings[] = array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => 'admin_funds_debited_sms_options' ,
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
                    'id'   => 'admin_funds_debited_sms_options' ,
                        ) ;
            }
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array(
                '{username}'                  => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Username' , HRW_LOCALE )
                ) ,
                '{wallet-transaction-amount}' => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet transaction amount' , HRW_LOCALE )
                ) ,
                '{event}'                     => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet Event' , HRW_LOCALE )
                ) ,
                '{wallet-balance}'            => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet Balance' , HRW_LOCALE )
                ) ,
                '{expiry-date}'               => array( 'where' => esc_html__( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Wallet Expiry date' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
