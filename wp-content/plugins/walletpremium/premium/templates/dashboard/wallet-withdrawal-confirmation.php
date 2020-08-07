<?php
/**
 * This template displays Withdrawal confirmation
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/wallet-withdrawal-confirmation.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw-wallet-withdrawal">
    <?php echo HRW_Form_Handler::show_messages() ; ?>
    <form class="hrw_frontend_form hrw_wallet_withdrawal_info" action="" method="post" enctype="multipart/form-data">
        <p>
            <label class="hrw_labels hrw-withdrawal-amount"><?php esc_html_e( 'Withdrawal Amount' , HRW_LOCALE ) ; ?></label>
            <span><?php echo hrw_price( $amount ) ; ?></span>
            <input type="hidden" name="hrw_withdrawal[amount]" value="<?php echo esc_attr( $amount ) ; ?>">
        </p>
        <?php if ( get_option( 'hrw_wallet_withdrawal_enable_withdrawal_fee' , 'no' ) == 'yes' ) { ?>
            <p>
                <label class="hrw_labels hrw-withdrawal-fee"><?php echo esc_html( get_option( 'hrw_wallet_withdrawal_fee_label' ) ) ; ?></label>
                <span> <?php echo hrw_price( $fee ) ; ?></span>
                <input type ="hidden" name="hrw_withdrawal[fee]" value="<?php echo esc_attr( $fee ) ; ?>">
            </p>
        <?php } ?> 
        <p>
            <label class="hrw_labels hrw-withdrawal-reason"><?php esc_html_e( 'Reason' , HRW_LOCALE ) ; ?></label>
            <span><?php echo esc_html( $reason ) ; ?></span>
            <input type ="hidden" name="hrw_withdrawal[reason]" value="<?php echo esc_attr( $reason ) ; ?>">
        </p>
        <p>
            <label class="hrw_labels hrw-withdrawal-payment-method"><?php echo esc_html( get_option( 'hrw_wallet_withdrawal_payment_method_label' ) ) ; ?></label>
            <?php $payment_label = hrw_payment_method_preference( $payment_method ) ; ?>
            <span><?php echo esc_html( $payment_label ) ; ?></span>
            <input type ="hidden" name="hrw_withdrawal[payment_method]" value="<?php echo esc_html( $payment_method ) ; ?>">
        </p>
        <?php if ( $payment_method == 'paypal' ): ?>
            <p>
                <label class="hrw_labels hrw-payment_details"><?php esc_html_e( 'Paypal Email ID' , HRW_LOCALE ) ; ?></label>
                <span> <?php echo esc_html( $paypal_details ) ; ?></span>
                <input type ="hidden" name="hrw_withdrawal[paypal_details]" value="<?php echo esc_attr( $paypal_details ) ; ?>">
            </p>
        <?php else: ?>
            <p>
                <label class="hrw_labels hrw-payment_details"><?php esc_html_e( 'Bank Transfer Details' , HRW_LOCALE ) ; ?></label>
                <span><?php echo esc_html( $bank_details ) ; ?></span>
                <input type ="hidden" name="hrw_withdrawal[bank_details]" value="<?php echo esc_attr( $bank_details ) ; ?>">
            </p>
        <?php endif ; ?>
        <p>
            <label class="hrw_labels hrw-verify-otp"><?php esc_html_e( 'Verify OTP' , HRW_LOCALE ) ; ?></label>
            <input type="text" class="hrw_withdrawal_verify_otp" name="hrw_withdrawal[verify_otp]">
        </p>
        <p>
            <input type="hidden" name="hrw-action" value="withdrawal" />
            <input type="hidden" name="hrw-withdrawal-nonce" value="<?php echo wp_create_nonce( 'hrw-withdrawal' ) ; ?>" />
            <input type="submit" class="hrw_withdrawal_button hrw_form_button" name="hrw_withdrawal_button" value="<?php esc_attr_e( 'Verify OTP' , HRW_LOCALE ) ; ?>"/>
        </p>

    </form>
</div>
<?php
