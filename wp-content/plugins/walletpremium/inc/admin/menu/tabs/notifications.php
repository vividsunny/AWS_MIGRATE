<?php

/**
 * Notifications Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Notifications_Tab' ) ) {
    return new HRW_Notifications_Tab() ;
}

/**
 * HRW_Notifications_Tab.
 */
class HRW_Notifications_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'notifications' ;
        $this->code  = 'fa-bell' ;
        $this->label = esc_html__( 'Notifications' , HRW_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_notifications' ) , array( $this , 'output_notifications' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get sections.
     */
    public function get_sections() {
        $sections = array(
            'general' => array(
                'label' => esc_html__( 'General Notifications' , HRW_LOCALE ) ,
                'code'  => 'fa-bell-o'
            ) ,
            'module'  => array(
                'label' => esc_html__( 'Module Notifications' , HRW_LOCALE ) ,
                'code'  => 'fa-bell-o'
            ) ,
                ) ;

        return apply_filters( $this->plugin_slug . '_get_sections_' . $this->id , $sections ) ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array(
            array( 'type' => 'output_notifications' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        global $current_sub_section ;

        if ( $current_sub_section ) {
            HRW_Settings::output_buttons() ;
        }
    }

    /**
     * Output the notifications
     */
    public function output_notifications() {
        global $current_sub_section ;

        if ( $current_sub_section ) {

            $notification_object = HRW_Notification_Instances::get_notification_by_id( $current_sub_section ) ;
            if ( is_object( $notification_object ) ) {
                HRW_Settings::output_fields( $notification_object->settings_options_array() ) ;
            }
        } else {
            include_once( HRW_PLUGIN_PATH . '/inc/notifications/views/layout.php' ) ;
        }
    }

    /**
     * Output the notifications
     */
    public function save() {
        if ( ! isset( $_POST[ 'save' ] ) || empty( $_POST[ 'save' ] ) )
            return ;

        global $current_sub_section ;

        if ( ! $current_sub_section )
            return ;

        $notification_object = HRW_Notification_Instances::get_notification_by_id( $current_sub_section ) ;
        if ( is_object( $notification_object ) ) {
            HRW_Settings::save_fields( $notification_object->settings_options_array() ) ;
        }

        HRW_Settings::add_message( esc_html__( 'Your settings have been saved.' , HRW_LOCALE ) ) ;
    }

    /**
     * Reset settings.
     */
    public function reset() {
        if ( ! isset( $_POST[ 'reset' ] ) || empty( $_POST[ 'reset' ] ) )
            return ;

        global $current_sub_section ;

        if ( ! $current_sub_section )
            return ;

        $notification_object = HRW_Notification_Instances::get_notification_by_id( $current_sub_section ) ;
        if ( is_object( $notification_object ) ) {
            HRW_Settings::reset_fields( $notification_object->settings_options_array() ) ;
        }

        HRW_Settings::add_message( esc_html__( 'Your settings have been reset.' , HRW_LOCALE ) ) ;
    }

}

return new HRW_Notifications_Tab() ;
