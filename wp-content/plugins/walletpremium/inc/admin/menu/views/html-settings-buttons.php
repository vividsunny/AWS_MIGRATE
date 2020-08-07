<?php
/* Admin HTML Settings Buttons */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<p class = 'submit'>
    <?php if ( ! isset( $GLOBALS[ 'hide_save_button' ] ) ) : ?>
        <input name='<?php echo esc_attr( self::$plugin_slug ) ; ?>_save' class='button-primary <?php echo esc_attr( self::$plugin_slug ) ; ?>_save_btn' type='submit' value="<?php esc_attr_e( 'Save changes' , HRW_LOCALE ) ; ?>" />
        <input type="hidden" name="save" value="save"/>
        <?php
        wp_nonce_field( self::$plugin_slug . '_save_settings' , '_' . self::$plugin_slug . '_nonce' , false , true ) ;
    endif ;
    ?>
</p>
<?php if ( $reset ) : ?>
    </form>
    <form method='post' action='' enctype='multipart/form-data' class="hrw_reset_form">
        <input id='reset' name='<?php echo esc_attr( self::$plugin_slug ) ; ?>_reset' class='button-secondary <?php echo esc_attr( self::$plugin_slug ) ; ?>_reset_btn' type='submit' value="<?php esc_attr_e( 'Reset' , HRW_LOCALE ) ; ?>"/>
        <input type="hidden" name="reset" value="reset"/>
        <?php
        wp_nonce_field( self::$plugin_slug . '_reset_settings' , '_' . self::$plugin_slug . '_nonce' , false , true ) ;
    endif;
