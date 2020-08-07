<?php

/**
 * Advanced Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Compatibility_Tab' ) ) {
    return new HRW_Compatibility_Tab() ;
}

/**
 * HRW_Compatibility_Tab.
 */
class HRW_Compatibility_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'compatibility' ;
        $this->code  = 'fa-plug' ;
        $this->label = esc_html__( 'Compatibility' , HRW_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_compatibility' ) , array( $this , 'output_compatibility' ) ) ;

        parent::__construct() ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Get compatibility setting section array.
     */
    public function compatibility_section_array() {
        $section_fields = array() ;

        $section_fields[] = array(
            'type' => 'output_compatibility' ,
                ) ;

        return $section_fields ;
    }

    /**
     * Output the Compatibility
     */
    public function output_compatibility() {
        $compatibility_plugins = array(
            array(
                'name'     => esc_html__( 'Refund' , HRW_LOCALE ) ,
                'img_url'  => HRW_PLUGIN_URL . '/assets/images/compatibility/refund.png' ,
                'site_url' => 'https://hoicker.com/product/refund'
            ) ,
            array(
                'name'     => esc_html__( 'SUMO Reward Points' , HRW_LOCALE ) ,
                'img_url'  => HRW_PLUGIN_URL . '/assets/images/compatibility/sumo-reward-points.png' ,
                'site_url' => 'https://codecanyon.net/item/sumo-reward-points-woocommerce-reward-system/7791451?ref=FantasticPlugins'
            ) ,
            array(
                'name'     => esc_html__( 'SUMO Subscription' , HRW_LOCALE ) ,
                'img_url'  => HRW_PLUGIN_URL . '/assets/images/compatibility/sumo-subscription.png' ,
                'site_url' => 'https://codecanyon.net/item/sumo-subscriptions-woocommerce-subscription-system/16486054?ref=FantasticPlugins'
            )
                ) ;

        //Compatibility layout
        include_once HRW_PLUGIN_PATH . '/inc/admin/menu/views/compatibility.php' ;
    }

}

return new HRW_Compatibility_Tab() ;
