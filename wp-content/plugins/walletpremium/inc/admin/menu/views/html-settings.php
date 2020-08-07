<?php
/* Admin HTML Settings */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>

<div class = "wrap <?php echo esc_attr( self::$plugin_slug ) ; ?>_wrapper_cover">
    <h2></h2>
    <div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>_header">
        <div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>_header_title">
            <h2><?php esc_html_e( "Wallet Premium" , HRW_LOCALE ) ; ?></h2>
        </div>
        <div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>_header_logo">
            <img src="<?php echo HRW_PLUGIN_URL ; ?>/assets/images/header-logo.png">
        </div>
    </div>
    <form method = "post" enctype = "multipart/form-data">
        <div class = "<?php echo esc_attr( self::$plugin_slug ) ; ?>_wrapper">
            <ul class = "nav-tab-wrapper <?php echo esc_attr( self::$plugin_slug ) ; ?>_tab_ul">
                <?php foreach ( $tabs as $name => $tab ) { ?>
                    <li class="<?php echo esc_attr( self::$plugin_slug ) ; ?>_tab_li <?php echo esc_attr( $name ) . '_li' ; ?>">
                        <a href="<?php echo esc_url( hrw_get_settings_page_url( array( 'page' => 'hrw_settings' , 'tab' => $name ) ) ) ; ?>" class="nav-tab <?php echo esc_html( self::$plugin_slug ) ; ?>_tab_a <?php echo esc_attr( $name ) . '_a ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) ?>">
                            <i class="fa <?php echo esc_attr( $tab[ 'code' ] ) ; ?>"></i>
                            <span><?php echo esc_html( $tab[ 'label' ] ) ; ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>_tab_content hrw_<?php echo esc_attr( $current_tab ) ; ?>_tab_content_wrapper">
                <?php
                /* Display Sections */
                do_action( sanitize_key( self::$plugin_slug . '_sections_' . $current_tab ) ) ;
                ?>
                <div class="<?php echo esc_attr( self::$plugin_slug ) ; ?>_tab_inner_content hrw_<?php echo esc_attr( $current_tab ) ; ?>_tab_inner_content">
                    <?php do_action( sanitize_key( self::$plugin_slug . '_before_tab_sections' ) ) ; ?>

                    <h3><?php esc_html_e( "Wallet Premium - " , HRW_LOCALE ) ; ?> <span><?php echo esc_attr( $tabs[ $current_tab ][ 'label' ] ) ; ?> </span></h3>
                    <?php
                    /* Display Error or Warning Messages */
                    self::show_messages() ;

                    /* Display Tab Content */
                    do_action( sanitize_key( self::$plugin_slug . '_settings_' . $current_tab ) ) ;

                    /* Display Reset and Save Button */
                    do_action( sanitize_key( self::$plugin_slug . '_settings_buttons_' . $current_tab ) ) ;

                    /* Extra fields after setting button */
                    do_action( sanitize_key( self::$plugin_slug . '_after_setting_buttons_' . $current_tab ) ) ;
                    ?>
                </div>
            </div>
        </div>
    </form>
    <?php
    do_action( sanitize_key( self::$plugin_slug . '_' . $current_tab . '_' . $current_section . '_setting_end' ) ) ;
    do_action( sanitize_key( self::$plugin_slug . '_settings_end' ) ) ;
    ?>
</div><?php
