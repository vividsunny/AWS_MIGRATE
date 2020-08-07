<?php

/**
 * Abstract Notifications Class
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRW_Notifications' ) ) {

    /**
     * HRW_Notifications Class
     */
    class HRW_Notifications {
        /*
         * ID
         */

        protected $id ;

        /*
         * Title
         */
        protected $title ;

        /*
         * Section
         */
        protected $section = 'general' ;

        /*
         * Show settings
         */
        protected $show_settings = true ;

        /*
         * Message
         */
        protected $message = '' ;

        /*
         * SMS Message
         */
        protected $sms_message = '' ;


        /*
         * Template HTML
         */
        protected $template_html ;

        /*
         * Data
         */
        protected $data = array() ;

        /*
         * Subject
         */
        protected $subject = '' ;

        /*
         * Placeholders
         */
        protected $placeholders = array() ;

        /*
         * Plugin slug
         */
        protected $plugin_slug = 'hrw' ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->enabled      = $this->get_enabled() ;
            $this->sms_enabled  = $this->get_option( 'sms_enabled' , 'no' ) ;
            $this->mail_enabled = $this->get_option( 'mail_enabled' , 'yes' ) ;

            if ( empty( $this->placeholders ) ) {
                $this->placeholders = array(
                    '{site_name}' => $this->get_blogname() ,
                    '{sitename}'  => $this->get_blogname() ,
                    '{site_link}' => site_url() ,
                        ) ;
            }
        }

        /*
         * Get id
         */

        public function get_id() {
            return $this->id ;
        }

        /*
         * Get section
         */

        public function get_section() {
            return $this->section ;
        }

        /*
         * Get title
         */

        public function get_title() {
            return $this->title ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            if ( ! $this->show_settings )
                return false ;

            return hrw_get_settings_page_url( array( 'tab' => 'notifications' , 'section' => $this->get_section() , 'subsection' => $this->id ) ) ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {

            return $this->is_plugin_enabled() && 'yes' === $this->enabled ;
        }

        /*
         * is plugin enabled
         */

        public function is_plugin_enabled() {

            return true ;
        }

        /*
         * warning message
         */

        public function get_warning_message() {

            return '' ;
        }

        /*
         * Get Shortcodes
         */

        public function get_shortcodes() {

            return array() ;
        }

        /*
         * Output Shortcodes Table
         */

        public function output_shortcodes_table() {

            $shortcodes = $this->get_shortcodes() ;

            if ( ! hrw_check_is_array( $shortcodes ) )
                return ;

            include HRW_PLUGIN_PATH . '/inc/notifications/views/shortcodes-table.php' ;
        }

        /*
         * sms module enabled
         */

        public function sms_module_enabled() {

            return apply_filters( 'hrw_sms_module_enable' , false ) ;
        }

        /*
         * Get Content HTML
         */

        public function get_content_html() {

            if ( $this->template_html && ! empty( $this->data ) )
                return hrw_get_template_html( $this->template_html , $this->data ) ;

            return '' ;
        }

        /*
         * Is email enabled
         */

        public function is_email_enabled() {

            if ( $this->sms_module_enabled() ) {
                return $this->is_enabled() && 'yes' === $this->mail_enabled ;
            }

            return true ;
        }

        /*
         * is email enabled
         */

        public function is_sms_enabled() {

            return $this->is_enabled() && $this->sms_module_enabled() && 'yes' === $this->sms_enabled ;
        }

        /*
         * Default Subject
         */

        public function get_default_subject() {

            return $this->subject ;
        }

        /*
         * Default Message
         */

        public function get_default_message() {

            return $this->message ;
        }

        /*
         * Default SMS Message
         */

        public function get_sms_default_message() {

            return $this->sms_message ;
        }

        /**
         * Get Enabled.
         */
        public function get_enabled() {

            return $this->get_option( 'enabled' , 'no' ) ;
        }

        /**
         * Get subject.
         */
        public function get_subject() {

            return $this->format_string( $this->get_option( 'subject' , $this->get_default_subject() ) ) ;
        }

        /**
         * Get Message.
         */
        public function get_message() {
            $string = $this->format_string( $this->get_option( 'message' , $this->get_default_message() ) ) ;
            $string = wpautop( $string ) ;

            return $string ;
        }

        /**
         * Get SMS Message.
         */
        public function get_sms_message() {

            $string = $this->format_string( $this->get_option( 'sms_message' , $this->get_sms_default_message() ) ) ;

            return $string ;
        }

        /**
         * Get formatted Message
         */
        public function get_formatted_message() {

            if ( get_option( 'hrw_advanced_email_template_type' , '2' ) == '2' ) {

                ob_start() ;
                wc_get_template( 'emails/email-header.php' , array( 'email_heading' => $this->get_subject() ) ) ;
                echo $this->get_message() ;
                wc_get_template( 'emails/email-footer.php' ) ;
                $message = ob_get_clean() ;
            } else {
                $message = $this->get_message() ;
            }

            return $message ;
        }

        /**
         * Get email headers.
         */
        public function get_headers() {
            $header = 'Content-Type: ' . $this->get_content_type() . "\r\n" ;

            return $header ;
        }

        /**
         * Get attachments.
         */
        public function get_attachments() {

            $email_attachments = array() ;
            $upload_dir        = wp_upload_dir() ;
            $dir               = $upload_dir[ 'basedir' ] . '/hrw-files' ;
            $attachments       = $this->get_option( 'email_attachments' , array() ) ;

            foreach ( $attachments as $file_name => $attachment ) {
                $email_attachments[] = $dir . '/' . $file_name ;
            }

            $email_attachments = apply_filters( 'hrw_email_attachments' , $email_attachments , $this ) ;

            return $email_attachments ;
        }

        /**
         * Get WordPress blog name.
         */
        public function get_blogname() {
            return wp_specialchars_decode( get_option( 'blogname' ) , ENT_QUOTES ) ;
        }

        /**
         * Get valid recipients.
         */
        public function get_sms_recipient() {

            return $this->sms_recipient ;
        }

        /**
         * Get valid recipients.
         */
        public function get_recipient() {
            $recipients = array_map( 'trim' , explode( ',' , $this->recipient ) ) ;
            $recipients = array_filter( $recipients , 'is_email' ) ;

            return implode( ', ' , $recipients ) ;
        }

        /**
         * Format String
         */
        public function format_string( $string ) {
            $find    = array_keys( $this->placeholders ) ;
            $replace = array_values( $this->placeholders ) ;

            $string = str_replace( $find , $replace , $string ) ;

            return $string ;
        }

        /**
         * Send an email.
         */
        public function send_email( $to , $subject , $message , $headers = false , $attachments = array() ) {
            if ( ! $headers )
                $headers = $this->get_headers() ;

            add_filter( 'wp_mail_from' , array( $this , 'get_from_address' ) ) ;
            add_filter( 'wp_mail_from_name' , array( $this , 'get_from_name' ) ) ;
            add_filter( 'wp_mail_content_type' , array( $this , 'get_content_type' ) ) ;

            if ( get_option( 'hrw_advanced_email_template_type' , '2' ) == "2" ) {
                $mailer = WC()->mailer() ;
                $return = $mailer->send( $to , $subject , $message , $headers , $attachments ) ;
            } else {
                $return = wp_mail( $to , $subject , $message , $headers , $attachments ) ;
            }

            remove_filter( 'wp_mail_from' , array( $this , 'get_from_address' ) ) ;
            remove_filter( 'wp_mail_from_name' , array( $this , 'get_from_name' ) ) ;
            remove_filter( 'wp_mail_content_type' , array( $this , 'get_content_type' ) ) ;

            return $return ;
        }

        /**
         * Send an sms.
         */
        public function send_sms( $to , $message ) {

            return HRW_SMS_Handler::send_sms( $to , $message ) ;
        }

        /**
         * Get the from name
         */
        public function get_from_name() {

            $from_name = get_option( 'hrw_advanced_email_from_name' ) != '' ? get_option( 'hrw_advanced_email_from_name' ) : get_option( 'blogname' ) ;

            return wp_specialchars_decode( esc_html( $from_name ) , ENT_QUOTES ) ;
        }

        /**
         * Get the from address
         */
        public function get_from_address() {

            $from_address = get_option( 'hrw_advanced_email_from_email' ) != '' ? get_option( 'hrw_advanced_email_from_email' ) : get_option( 'new_admin_email' ) ;

            return sanitize_email( $from_address ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array() ;
        }

        /**
         * Get content type.
         */
        public function get_content_type() {

            return 'text/html' ;
        }

        /*
         * Update Option
         */

        public function update_option( $key , $value ) {
            $field_key = $this->get_field_key( $key ) ;

            return update_option( $field_key , $value ) ;
        }

        /*
         * Prepare Options
         */

        public function prepare_options() {
            $default_data = $this->data ;

            foreach ( $default_data as $key => $value ) {

                $this->$key = $this->get_option( $key , $value ) ;
            }
        }

        /*
         * Get Option
         */

        public function get_option( $key , $value = false ) {
            $field_key = $this->get_field_key( $key ) ;

            return get_option( $field_key , $value ) ;
        }

        /*
         * Get field key
         */

        public function get_field_key( $key ) {
            return sanitize_key( $this->plugin_slug . '_' . $this->id . '_' . $key ) ;
        }

        /*
         * Extra Fields
         */

        public function extra_fields() {
            
        }

    }

}
