<?php

/**
 * Modules Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Modules_Tab' ) ) {
    return new HRW_Modules_Tab() ;
}

/**
 * HRW_Modules_Tab.
 */
class HRW_Modules_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'modules' ;
        $this->code  = 'fa-th' ;
        $this->label = esc_html__( 'Modules' , HRW_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_modules' ) , array( $this , 'output_modules' ) ) ;
        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_frontend_form' ) , array( $this , 'output_frontend_form' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {

        return array(
            array( 'type' => 'output_modules' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        global $current_sub_section ;

        if ( $current_sub_section ) {
            $module_object = HRW_Module_Instances::get_module_by_id( $current_sub_section ) ;
            if ( is_object( $module_object ) ) {
                $module_object->output_buttons() ;
            }
        }
    }

    /**
     * Output the modules
     */
    public function output_modules() {
        global $current_sub_section ;

        if ( $current_sub_section ) {
            $module_object = HRW_Module_Instances::get_module_by_id( $current_sub_section ) ;
            if ( is_object( $module_object ) ) {
                HRW_Settings::output_fields( $module_object->settings_options_array() ) ;
            }
        } else {
            include_once( HRW_PLUGIN_PATH . '/inc/modules/views/layout.php' ) ;
        }
    }

    /**
     * Output the modules
     */
    public function save() {

        try {
            global $current_sub_section ;
            if( ! $current_sub_section )
                return ;

            $module_object = HRW_Module_Instances::get_module_by_id( $current_sub_section ) ;

            if( ! is_object( $module_object ) )
                return ;

            $module_object->before_save() ;

            if( ! isset( $_POST[ 'save' ] ) || empty( $_POST[ 'save' ] ) )
                return ;

            $module_object->save() ;

            HRW_Settings::save_fields( $module_object->settings_options_array() ) ;

            $module_object->after_save() ;

            HRW_Settings::add_message( esc_html__( 'Your settings have been saved.' , HRW_LOCALE ) ) ;
        } catch( Exception $ex ) {
            HRW_Settings::add_error( $ex->getMessage() ) ;
        }
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

        $module_object = HRW_Module_Instances::get_module_by_id( $current_sub_section ) ;
        if ( is_object( $module_object ) ) {
            HRW_Settings::reset_fields( $module_object->settings_options_array() ) ;
        }

        HRW_Settings::add_message( esc_html__( 'Your settings have been reset.' , HRW_LOCALE ) ) ;
    }

    /**
     * Output the extra fields
     */
    public function output_extra_fields() {
        global $current_sub_section ;

        if ( ! $current_sub_section )
            return ;

        $module_object = HRW_Module_Instances::get_module_by_id( $current_sub_section ) ;

        $module_object->extra_fields() ;
    }

}

return new HRW_Modules_Tab() ;
