<?php

/**
 * Shortcodes Tab
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if( class_exists( 'HRW_Shortcodes_Tab' ) ) {
    return new HRW_Shortcodes_Tab() ;
}

/**
 * HRW_Shortcodes_Tab.
 */
class HRW_Shortcodes_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'shortcodes' ;
        $this->code  = 'fa-code' ;
        $this->label = esc_html__( 'Shortcodes' , HRW_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_shortcodes' ) , array( $this , 'output_shortcodes' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings for shortcodes section array.
     */
    public function shortcodes_section_array() {
        return array(
            array( 'type' => 'output_shortcodes' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the shortcodes table
     */
    public function output_shortcodes() {
        $shortcodes_info = array(
            '[hrw_dashboard]'       => array(
                'where' => esc_html__( 'Pages' , HRW_LOCALE ) ,
                'usage' => esc_html__( 'Displaying Dashboard Form' , HRW_LOCALE )
            ) ,
            '[hrw_topup_form]'      => array(
                'where' => esc_html__( 'Pages' , HRW_LOCALE ) ,
                'usage' => esc_html__( 'Displaying Top-up Form' , HRW_LOCALE )
            ) ,
            '[hrw_wallet_balance]'  => array(
                'where' => esc_html__( 'Pages' , HRW_LOCALE ) ,
                'usage' => esc_html__( 'Displaying Wallet Balance' , HRW_LOCALE )
            ) ,
            '[hrw_transaction_log]' => array(
                'where' => esc_html__( 'Pages' , HRW_LOCALE ) ,
                'usage' => esc_html__( 'Displaying Transaction Log table' , HRW_LOCALE )
            ) ,
            '[hrw_available_wallet_funds]'   => array(
                'where' => esc_html__( 'Pages' , HRW_LOCALE ) ,
                'usage' => esc_html__( 'Displaying Available Wallet Funds' , HRW_LOCALE )
            ) ,
                ) ;

        //Shortcodes layout
        include_once HRW_PLUGIN_PATH . '/inc/admin/menu/views/shortcodes-table.php' ;
    }

}

return new HRW_Shortcodes_Tab() ;
