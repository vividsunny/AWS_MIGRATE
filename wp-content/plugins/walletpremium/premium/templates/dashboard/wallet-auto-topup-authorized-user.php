<?php
/**
 * This template displays Auto Top-up authorized user form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/wallet-auto-topup-authorized-user.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw-authorized-user-auto-topup">
    <form class="hrw_frontend_form" id="hrw_authorized_user_auto_topup" action="" method="post" enctype="multipart/form-data">
        <p>
            <label><span class="hrw_labels hrw-auto-topup-amount"><?php esc_html_e( 'Auto Top-up Amount' , HRW_LOCALE ) ; ?></span></label>
            <span><?php echo wc_price( $auto_topup->get_topup_amount() , array( 'currency' => $auto_topup->get_currency() ) ) ; ?></span>
        </p>
        <p>
            <label><span class="hrw_labels hrw-auto-topup-threshold-amount"><?php esc_html_e( 'Threshold Amount' , HRW_LOCALE ) ; ?></span></label>
            <span><?php echo wc_price( $auto_topup->get_threshold_amount() , array( 'currency' => $auto_topup->get_currency() ) ) ; ?></span>
        </p>
        <p>
            <input type="hidden" name="hrw-action" value="cancel-auto-topup" />
            <input type="hidden" name="hrw_cancel_auto_topup_id" value="<?php echo esc_attr( $auto_topup->get_id() ) ; ?>"/>
            <input type="hidden" name="hrw-auto-topup-nonce" value="<?php echo wp_create_nonce( 'hrw-auto-topup' ) ; ?>" />
            <input type="submit" class="hrw_cancel_auto_topup_button hrw_form_button" name="hrw_cancel_auto_topup_button" value="<?php esc_attr_e( 'Cancel Auto Top-up' , HRW_LOCALE ) ; ?>"/>
        </p>        
    </form>
</div>
<?php
