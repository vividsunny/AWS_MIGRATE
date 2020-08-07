<?php
/* Layout */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

global $current_section ;
$modules = HRW()->modules() ;

foreach ( $modules as $module ) :

    if ( ! $module->get_id() )
        continue ;

    $module_grid_class = ($module->is_enabled()) ? $this->plugin_slug . '_module_active' : $this->plugin_slug . '_module_inactive' ;
    ?>
    <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_modules_grid">
        <input class="<?php echo esc_html( $this->plugin_slug ) ; ?>_module_name" type="hidden" value="<?php echo esc_attr( $module->get_id() ) ; ?>" />
        <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_modules_grid_inner <?php echo esc_attr( $module_grid_class ) ; ?>">
            <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_modules_grid_inner_top">
                <h3><?php echo esc_html( $module->get_title() ) ; ?></h3>
            </div>
            <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_modules_grid_inner_bottom">
                <label class="<?php echo esc_html( $this->plugin_slug ) ; ?>_switch">
                    <input class="<?php echo esc_html( $this->plugin_slug ) ; ?>_modules_enabled" type="checkbox" value="true" <?php checked( $module->is_enabled() , true ) ?>>
                    <span class="<?php echo esc_html( $this->plugin_slug ) ; ?>_slider <?php echo esc_html( $this->plugin_slug ) ; ?>_round"></span>
                </label>
                <?php
                if ( $module->settings_link() ) :
                    $display_style = ( ! $module->is_enabled()) ? 'hrw_hide' : '' ;
                    ?>
                    <a class="<?php echo esc_html( $this->plugin_slug ) ; ?>_settings_link <?php echo esc_attr( $display_style ) ; ?>" href="<?php echo esc_url( $module->settings_link() ) ; ?>"><?php esc_html_e( 'Settings' , HRW_LOCALE ) ; ?></a>
                <?php endif ; ?>
            </div>
            <?php if ( ! $module->is_plugin_enabled() ) : ?>
                <div class="mask"><p><?php echo $module->get_warning_message() ; ?></p></div>
                    <?php endif ; ?>
        </div>
    </div>
    <?php
endforeach ;
