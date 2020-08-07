<?php

/**
 * Customer- Purchase Total Cashback
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Customer_Purchase_Total_Cashback_Notification' ) ) {

    /**
     * Class HRW_Customer_Purchase_Total_Cashback_Notification
     */
    class HRW_Customer_Purchase_Total_Cashback_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'customer_purchase_total_cashback' ;
            $this->section = 'module' ;
            $this->title   = esc_html__( 'Customer - Purchase Total Cashback' , HRW_LOCALE ) ;

            add_action( sanitize_key( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action( sanitize_key( $this->plugin_slug . '_cashback_credit_notification' ) , array( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct() ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return '{site_name} – Cashback received for the order {order_id}' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return "Hi {user_name},

You have received {cashback_value} as a cashback for placing the order {order_id} on {site_link}. 

For your reference, please check your wallet and make use of the funds to get a discount on your future purchases.

Thanks." ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return 'A cashback of {cashback_value} added  to our wallet for placing the order {order_id}' ;
        }

        /**
         * Get Enabled.
         */
        public function get_enabled() {
            return $this->get_option( 'enabled' , 'no' ) ;
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
        public function trigger( $order , $wallet_object = false ) {
            $cashback_rules = get_post_meta( $order->get_id() , 'hrw_matched_rule' , true ) ;
            if ( ! isset( $cashback_rules[ 'order_rule' ] ) )
                return ;

            $cashback_amnt = get_post_meta( $order->get_id() , 'hrw_cashback_value' , true ) ;

            $wallet_object = hrw_get_wallet( $order->get_user_id() ) ;

            if ( is_object( $wallet_object ) ) {
                $this->recipient                          = $wallet_object->get_user()->user_email ;
                $this->sms_recipient                      = $wallet_object->get_phone() ;
                $this->placeholders[ '{order_id}' ]       = $order->get_id() ;
                $this->placeholders[ '{cashback_value}' ] = hrw_price( $cashback_amnt ) ;
                $this->placeholders[ '{user_name}' ]      = hrw_get_page_id( 'topup' , true , wc_get_page_id( 'myaccount' ) ) ;
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
                'id'    => 'customer_purchase_total_shortcodes' ,
                    ) ;
            $settings[] = array(
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array(
                'type' => 'sectionend' ,
                'id'   => 'customer_purchase_total_shortcodes' ,
                    ) ;

            $settings[] = array(
                'type'  => 'title' ,
                'title' => esc_html__( 'Email Settings - Purchase Total' , HRW_LOCALE ) ,
                'id'    => 'customer_purchase_total_notifications_options' ,
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
                'id'   => 'customer_purchase_total_notifications_options' ,
                    ) ;
            
            if ( $this->sms_module_enabled() ) {

                $settings[] = array(
                    'type'  => 'title' ,
                    'title' => esc_html__( 'SMS Settings' , HRW_LOCALE ) ,
                    'id'    => 'customer_purchase_total_sms_options' ,
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
                    'id'   => 'customer_purchase_total_sms_options' ,
                        ) ;
            }
            
            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array(
                '{user_name}'      => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Username' , HRW_LOCALE )
                ) ,
                '{site_name}'      => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Sitename' , HRW_LOCALE )
                ) ,
                '{site_link}'      => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Site Link' , HRW_LOCALE )
                ) ,
                '{order_id}'       => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Order ID' , HRW_LOCALE )
                ) ,
                '{cashback_value}' => array( 'where' => esc_html__( 'Email, SMS' , HRW_LOCALE ) ,
                    'usage' => esc_html__( 'Displays the Cashback Amount Credited' , HRW_LOCALE )
                ) ,
                    ) ;
        }

    }

}
