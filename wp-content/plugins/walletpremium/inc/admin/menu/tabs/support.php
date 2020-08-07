<?php
/**
 * Help Tab
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRW_Help_Tab' ) ) {
    return new HRW_Help_Tab() ;
}

/**
 * HRW_Help_Tab.
 */
class HRW_Help_Tab extends HRW_Settings_Page {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->id    = 'help' ;
        $this->code  = 'fa-life-ring' ;
        $this->label = esc_html__( 'Help' , HRW_LOCALE ) ;

        add_action( sanitize_key( $this->plugin_slug . '_admin_field_output_help' ) , array( $this , 'output_help' ) ) ;

        parent::__construct() ;
    }

    /**
     * Get settings array.
     */
    public function get_settings( $current_section = '' ) {
        return array(
            array( 'type' => 'output_help' )
                ) ;
    }

    /**
     * Output the settings buttons.
     */
    public function output_buttons() {
        
    }

    /**
     * Output the help content
     */
    public function output_help() {
        $support_site_url = '<a href="https://hoicker.com/support" target="_blank">' . esc_html__( 'Here' , HRW_LOCALE ) . '</a>' ;
        ?>
        <div class="esf_help_content">
            <h3><?php esc_html_e( 'Documentation' , HRW_LOCALE ) ; ?></h3>
            <p> <?php echo esc_html__( 'Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.' , HRW_LOCALE ) ; ?></a></p>
            <h3><?php esc_html_e( 'Contact Support' , HRW_LOCALE ) ; ?></h3>
            <p> <?php echo sprintf( esc_html__( 'You can report bugs %s' , HRW_LOCALE ) , $support_site_url ) ; ?></a></p>
        </div>
        <?php
    }

}

return new HRW_Help_Tab() ;
