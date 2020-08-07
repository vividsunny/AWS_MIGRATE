<?php

/**
 * Customer- Gift Card Received
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Customer_Gift_Card_Received_Notification' ) ) {

    /**
     * Class HRW_Customer_Gift_Card_Received_Notification
     */
    class HRW_Customer_Gift_Card_Received_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'hrw_customer_gift_card_received' ;
            $this->section = 'module' ;
            $this->title   = esc_html__ ( 'Customer - Gift Card Received' , HRW_LOCALE ) ;

            add_action ( sanitize_key ( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array ( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action ( sanitize_key ( $this->plugin_slug . '_after_gift_card_created' ) , array ( $this , 'trigger' ) , 10 , 1 ) ;

            parent::__construct () ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return '{site_name} - New Gift Card Received' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return 'Hi {user_name},

You have received the Gift Card worth of {gift_card_amount} from {sender} on {sent_date}. Redeem the code on {site_link} before it gets expire.  For more information, please check the PDF attachment.

Thanks.' ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return 'A new gift card worth{gift_card_code} of {gift_card_amount} has been received from {sender}. It will expire on {expire_date}. Make use of the code on {site_link} before it gets expire and the corresponding amount will be added to your wallet after you redeem the code.' ;
        }

        /**
         * Get Enabled.
         */
        public function get_enabled() {

            if ( $this->get_option ( 'enabled' ) )
                return $this->get_option ( 'enabled' , 'no' ) ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return hrw_is_premium () ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            $message = sprintf ( esc_html__ ( 'This feature is available in %s' , HRW_LOCALE ) , '<a href="https://hoicker.com/product/wallet" target="_blank">' . esc_html__ ( "Wallet Premium Version" , HRW_LOCALE ) . '</a>' ) ;

            return '<i class="fa fa-info-circle"></i> ' . $message ;
        }

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $gift_id ) {

            $gift_object = hrw_get_gift ( $gift_id ) ;

            if ( is_object ( $gift_object ) ) {
                $this->recipient                            = $gift_object->get_receiver_id () ;
                $this->placeholders[ '{user_name}' ]        = $gift_object->get_user ()->display_name ;
                $this->placeholders[ '{gift_card_code}' ]   = $gift_object->get_gift_code () ;
                $this->placeholders[ '{gift_card_amount}' ] = hrw_price ( $gift_object->get_amount () ) ;
                $this->placeholders[ '{sender}' ]           = $gift_object->get_user_display () ;
                $this->placeholders[ '{sent_date}' ]        = $gift_object->get_formatted_created_date () ;
                $this->placeholders[ '{expire_date}' ]      = $gift_object->get_formatted_expired_date () ;
            }

            if ( $this->is_email_enabled () && $this->is_enabled () && $this->get_recipient () ) {
                $this->send_email ( $this->get_recipient () , $this->get_subject () , $this->get_formatted_message () , $this->get_headers () , array ( $gift_object->get_gift_attachment () ) ) ;
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
                'id'    => $this->id . '_shortcodes' ,
                    ) ;
            $settings[] = array (
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => $this->id . '_shortcodes' ,
                    ) ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => $this->id . '_email_options' ,
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
                'id'   => $this->id . '_email_options' ,
                    ) ;

            if ( $this->sms_module_enabled () ) {

                $settings[] = array (
                    'type'  => 'title' ,
                    'title' => esc_html__ ( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => $this->id . '_sms_options' ,
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
                    'id'   => $this->id . '_sms_options' ,
                        ) ;
            }
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array (
                '{site_name}'        => array ( 'where' => esc_html__ ( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Sitename' , HRW_LOCALE )
                ) ,
                '{user_name}'        => array ( 'where' => esc_html__ ( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Username' , HRW_LOCALE )
                ) ,
                '{gift_card_code}'   => array ( 'where' => esc_html__ ( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Gift Card' , HRW_LOCALE )
                ) ,
                '{gift_card_amount}' => array ( 'where' => esc_html__ ( 'Email,SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Gift Card Amount' , HRW_LOCALE )
                ) ,
                '{sender}'           => array ( 'where' => esc_html__ ( 'Email,SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Sender Name' , HRW_LOCALE )
                ) ,
                '{expire_date}'      => array ( 'where' => esc_html__ ( 'SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Expiry Date' , HRW_LOCALE )
                ) ,
                '{sent_date}'        => array ( 'where' => esc_html__ ( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Gift Card Sent Date' , HRW_LOCALE )
                ) ,
                '{site_link}'        => array ( 'where' => esc_html__ ( 'Email,SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Site Link' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
