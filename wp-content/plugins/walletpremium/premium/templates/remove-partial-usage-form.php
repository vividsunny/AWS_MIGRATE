<?php
/**
 * This template displays partial remove form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/remove-partial-usage-form.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="woocommerce-info">
    <form method="post" action="">
        <?php esc_html_e( 'Remove Wallet Credit' , HRW_LOCALE ) ; ?>
        <input type="hidden" name="hrw-action" value="remove_partial_usage" />
        <input type="hidden" name="hrw-remove-partial-usage-nonce" value="<?php echo wp_create_nonce( 'hrw-remove-partial-usage' ) ; ?>" />
        <input type="submit" value="<?php esc_attr_e( 'Remove' , HRW_LOCALE ) ; ?>" class="hrw_remove_partial_usage" />
    </form>
</div>
<?php
