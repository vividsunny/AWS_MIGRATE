<?php
/**
 * This template displays Top-up form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/topup-form.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_topup_form_wrapper">
    <?php echo HRW_Form_Handler::show_messages() ; ?>
    <form method="POST" action="" class="hrw_topup_form">
        <fieldset>
            <legend><?php echo esc_html( get_option( 'hrw_localizations_topup_form_title_label' , 'Add Funds to Wallet' ) ) ; ?></legend>
            <div class='hrw_topup_form_content'>
                <div class="hrw_topup_form_inner_content">
                    <?php if ( apply_filters( 'hrw_display_topup_field' , true ) ): ?>
                        <label><?php echo esc_html( get_option( 'hrw_localizations_topup_form_amount_label' , 'Top-up Amount' ) ) ; ?></label>
                        <input type='number' name='hrw_topup_amount' min="0" class='hrw_topup_amount' placeholder="<?php echo esc_html( get_option( 'topup_form_amount_placeholder' , 'Enter Amount' ) ) ; ?>">
                    <?php endif ; ?>

                    <?php
                    /*
                     * Hook : hrw_after_topup_form_field.
                     */
                    do_action( 'hrw_after_topup_form_field' ) ;
                    ?>   

                    <input type='submit' class="hrw_topup_button" value="<?php echo esc_html( get_option( 'hrw_localizations_topup_form_button_label' , 'Add to Wallet' ) ) ; ?>" >
                    <input type="hidden" name="hrw-action" value="topup" />
                    <input type="hidden" name="hrw-topup-nonce" value="<?php echo wp_create_nonce( 'hrw-topup' ) ; ?>" />
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
