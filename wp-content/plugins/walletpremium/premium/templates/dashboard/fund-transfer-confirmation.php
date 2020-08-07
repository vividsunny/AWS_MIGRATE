<?php
/**
 * This template displays fund transfer confirmation
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/fund-transfer-confirmation.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_fund_transfer_wrapper">
    <?php echo HRW_Form_Handler::show_messages() ; ?>
    <form method="POST" action="" class="hrw_frontend_form hrw_fund_transfer hrw_fund_transfer_information">
        <div class='hrw_fund_transfer_content'>
            <p class="form-row">
                <label><?php esc_html_e( 'User Selection' , HRW_LOCALE ) ; ?></label>
                <span><?php echo esc_html( get_userdata( $user_id )->display_name ) ; ?></span>
                <input type="hidden" name="hrw_fund_transfer[user_selection][]" value="<?php echo esc_attr( $user_id ) ; ?>" />
            </p>
            <p class="form-row">
                <label><?php esc_html_e( 'Available Amount' , HRW_LOCALE ) ; ?></label>
                <span><?php echo hrw_formatted_price( HRW_Wallet_User::get_available_balance() ) ; ?></span>
            </p>
            <p class="form-row">
                <label><?php esc_html_e( 'Amount to Transfer' , HRW_LOCALE ) ; ?></label>
                <span><?php echo hrw_formatted_price( $amount ) ; ?></span>
                <input type="hidden" name="hrw_fund_transfer[amount]" value="<?php echo esc_attr( $amount ) ; ?>" />
            </p>
            <?php if ( HRW_Module_Instances::get_module_by_id( 'fund_transfer' )->enable_transfer_fee == 'yes' ): ?>
                <p class="form-row">
                    <label><?php esc_html_e( 'Transfer Fee' , HRW_LOCALE ) ; ?></label>
                    <span><?php echo hrw_formatted_price( $fee ) ; ?></span>
                    <input type="hidden" name="hrw_fund_transfer[fee]" value="<?php echo esc_attr( $fee ) ; ?>" />
                </p>
            <?php endif ; ?>
            <p class="form-row">
                <label><?php esc_html_e( 'Reason' , HRW_LOCALE ) ; ?></label>
                <span><?php echo esc_html( $reason ) ; ?></span>
                <input type="hidden" name="hrw_fund_transfer[reason]" value="<?php echo esc_attr( $reason ) ; ?>" />
            </p>
            <p class="form-row">
                <label><?php esc_html_e( 'Verify OTP' , HRW_LOCALE ) ; ?></label>
                <input type="text" name="hrw_fund_transfer[verify_otp]" class="hrw_fund_transfer_verify_otp" />
            </p>
            <p class="form-row">
                <input type="hidden" name="hrw-action" value="fund_transfer" />
                <input type="hidden" name="hrw-fund-transfer-nonce" value="<?php echo wp_create_nonce( 'hrw-fund-transfer' ) ; ?>" />
                <input type='submit' class="hrw_fund_transfer_button hrw_form_button" value="<?php echo esc_html_e( 'Verify OTP' , HRW_LOCALE ) ; ?>" >
            </p>
        </div>
    </form>
</div>
<?php
