<?php

/**
 * License Verification Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_License_Verification_Tab' ) ) {
    return new HRW_License_Verification_Tab() ;
}

/**
 * HRW_License_Verification_Tab.
 */
class HRW_License_Verification_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'license_verification' ;
        $this->code  = 'fa-lock' ;
        $this->label = esc_html__( 'License Verification' , HRW_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_license_verification' ) , array( $this , 'output_license_verification' ) ) ;

        parent::__construct() ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Get license verification setting section array.
     */
    public function license_verification_section_array() {
        $section_fields = array() ;

        $section_fields[] = array(
            'type' => 'output_license_verification' ,
                ) ;

        return $section_fields ;
    }

    /**
     * Output the License Verification
     */
    public function output_license_verification() {
        HRW()->license()->show_panel() ;
    }

}

return new HRW_License_Verification_Tab() ;
