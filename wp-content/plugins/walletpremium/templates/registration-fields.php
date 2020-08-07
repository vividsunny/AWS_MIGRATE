<?php
/**
 * This template displays Registration fields
 * 
 * This template can be overridden by copying it to yourtheme/wallet/registration-fields.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>

<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide hrw_registration_field">

    <label class = "hrw_account_phone">
        <?php esc_html_e( 'Phone Number' , HRW_LOCALE ) ; ?>
        <?php if ( get_option( 'hrw_general_select_field_option' , '1' ) == '2' ) { ?>
            <span class="required hrw_required_field"> * </span>
        <?php } ?>
    </label>

    <input type="text" class="input-text hrw_account_phone" name="hrw_account_phone" id="hrw_account_phone"/>
</p>

<?php 