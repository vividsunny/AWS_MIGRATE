<?php
/* Layout */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

global $current_section ;
$notifications = HRW()->notifications() ;

foreach ( $notifications as $notification ) :

    if ( ! $notification->get_id() || ($notification->get_section() && $current_section != $notification->get_section()) )
        continue ;

    $notification_grid_class = ($notification->is_enabled()) ? $this->plugin_slug . '_notification_active' : $this->plugin_slug . '_notification_inactive' ;
    ?>
    <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_notifications_grid">
        <input class="<?php echo esc_html( $this->plugin_slug ) ; ?>_notification_name" type="hidden" value="<?php echo esc_attr( $notification->get_id() ) ; ?>" />
        <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_notifications_grid_inner <?php echo esc_attr( $notification_grid_class ) ; ?>">
            <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_notifications_grid_inner_top">
                <h3><?php echo esc_html( $notification->get_title() ) ; ?></h3>
            </div>
            <div class="<?php echo esc_html( $this->plugin_slug ) ; ?>_notifications_grid_inner_bottom">
                <label class="<?php echo esc_html( $this->plugin_slug ) ; ?>_switch">
                    <input class="<?php echo esc_html( $this->plugin_slug ) ; ?>_notifications_enabled" type="checkbox" value="true" <?php checked( $notification->is_enabled() , true ) ?>>
                    <span class="<?php echo esc_html( $this->plugin_slug ) ; ?>_slider <?php echo esc_html( $this->plugin_slug ) ; ?>_round"></span>
                </label>
                <?php
                if ( $notification->settings_link() ) :
                    $display_style = ( ! $notification->is_enabled()) ? 'hrw_hide' : '' ;
                    ?>
                    <a class="<?php echo esc_html( $this->plugin_slug ) ; ?>_settings_link <?php echo esc_attr( $display_style ) ; ?>" href="<?php echo esc_url( $notification->settings_link() ) ; ?>"><?php esc_html_e( 'Settings' , HRW_LOCALE ) ; ?></a>
                <?php endif ; ?>
            </div>
            <?php if ( ! $notification->is_plugin_enabled() ) : ?>
                <div class="mask"><p><?php echo $notification->get_warning_message() ; ?></p></div>
                    <?php endif ; ?>
        </div>
    </div>
    <?php
endforeach ;
