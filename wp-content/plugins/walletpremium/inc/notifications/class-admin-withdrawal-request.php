<?php
/**
 * Admin- Withdrawal Request Submitted
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'HRW_Admin_Withdrawal_Notification' ) ) {

    /**
     * Class HRW_Admin_Withdrawal_Notification
     */
    class HRW_Admin_Withdrawal_Notification extends HRW_Notifications {

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id      = 'admin_withdrawal_request' ;
            $this->section = 'module' ;
            $this->title   = esc_html__ ( 'Admin - Withdrawal Request' , HRW_LOCALE ) ;

            add_action ( sanitize_key ( $this->plugin_slug . '_admin_field_' . $this->id . '_shortcodes_table' ) , array ( $this , 'output_shortcodes_table' ) ) ;

            // Triggers for this email.
            add_action ( sanitize_key ( $this->plugin_slug . '_withdrawal_request_notification' ) , array ( $this , 'trigger' ) , 10 , 2 ) ;

            parent::__construct () ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return '{site_name} New Wallet Balance Withdrawal Request' ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return "Hi,	

A new Withdrawal request of wallet funds has been submitted by {user}. The details are as follows,

{withdrawal_details}" ;
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

        /**
         * Trigger the sending of this email.
         */
        public function trigger( $withdrawal_id , $withdrawal_object = false ) {

            if ( $withdrawal_id && ! is_a ( $withdrawal_object , 'HRW_Wallet_Withdrawal' ) ) {
                $withdrawal_object = hrw_get_wallet_withdrawal ( $withdrawal_id ) ;
            }
        
            if ( is_object ( $withdrawal_object ) ) {
                $this->recipient                              = $this->get_from_address() ;
                $this->placeholders[ '{user}' ]               = $withdrawal_object->get_user ()->display_name ;
                $this->placeholders[ '{withdrawal_details}' ] = self::get_withdrawal_details ( $withdrawal_object ) ;
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
                'id'    => 'admin_withdrawal_request_shortcodes' ,
                    ) ;
            $settings[] = array (
                'type' => $this->id . '_shortcodes_table'
                    ) ;

            $settings[] = array (
                'type' => 'sectionend' ,
                'id'   => 'admin_withdrawal_request_shortcodes' ,
                    ) ;

            $settings[] = array (
                'type'  => 'title' ,
                'title' => esc_html__ ( 'Email Settings' , HRW_LOCALE ) ,
                'id'    => 'admin_withdrawal_request_notifications_options' ,
                    ) ;

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
                'id'   => 'admin_withdrawal_request_notifications_options' ,
                    ) ;

            return $settings ;
        }

        /**
         * Get Shortcodes
         */
        public function get_shortcodes() {

            return array (
                '{withdrawal_details}' => array ( 'where' => esc_html__ ( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Withdrawal Details' , HRW_LOCALE )
                ) ,
                '{user}'               => array ( 'where' => esc_html__ ( 'Email' , HRW_LOCALE ) ,
                    'usage' => esc_html__ ( 'Displays the Username' , HRW_LOCALE )
                ) ,
                    ) ;
        }

        /**
         * Get Withdrawal Details
         */
        public function get_withdrawal_details( $withdrawal_object ) {
            ob_start () ;
            ?>
            <table>
                <thead>
                <th><?php echo esc_html__ ( 'Amount' , HRW_LOCALE ) ; ?></th>
                <th><?php echo esc_html__ ( 'Reason' , HRW_LOCALE ) ; ?></th>
            </thead>
            <tbody>
            <td><?php echo hrw_price($withdrawal_object->get_amount ()) ; ?></td>
            <td><?php echo esc_html__ ( $withdrawal_object->get_reason () ) ; ?></td>
            </tbody>
            </table>
            <?php
            $contents = ob_get_contents () ;
            ob_end_clean () ;
            
            return $contents;
        } 

    }

}
