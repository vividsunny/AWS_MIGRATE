<?php
/**
 * This template displays Withdrawal form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/wallet-withdrawal.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw-wallet-withdrawal">
    <?php
    echo HRW_Form_Handler::show_messages () ;
    do_action ( 'hrw_validate_withdrawal_form_display' ) ;
    ?>
    <form class="hrw_frontend_form hrw_wallet_withdrawal" action="" method="post" enctype="multipart/form-data">
        <p>
            <label class="hrw_labels hrw-total-wallet-balance"><?php echo esc_html ( get_option ( 'hrw_wallet_withdrawal_current_balance_label' ) ) ; ?></label>
            <?php echo hrw_price ( HRW_Wallet_User::get_available_balance () ) ; ?>
        <p>
        <p>
            <label class="hrw_labels hrw-withdrawal-amount"><?php echo esc_html ( get_option ( 'hrw_wallet_withdrawal_amount_label' ) ) ; ?><span class="required">*</span></label>
            <input class="hrw_withdrawal_amount" type="number" min="0" name="hrw_withdrawal[amount]" value="<?php echo esc_attr ( $amount ) ; ?>">
        </p>
        <?php if ( get_option ( 'hrw_wallet_withdrawal_enable_withdrawal_fee' , 'no' ) == 'yes' ) { ?>
            <p>
                <label class="hrw_labels hrw-withdrawal-fee"><?php echo esc_html ( get_option ( 'hrw_wallet_withdrawal_fee_label' ) ) ; ?></label>
                <input type ="text" class="hrw_withdrawal_fee" name="hrw_withdrawal[fee]" readonly="readonly" value="<?php echo esc_attr ( $fee ) ; ?>">
            </p>
        <?php } ?> 
        <p>
            <label class="hrw_labels hrw-withdrawal-reason"><?php echo esc_html ( get_option ( 'hrw_wallet_withdrawal_reason_label' ) ) ; ?></label>
            <textarea name="hrw_withdrawal[reason]" class="hrw_withdrawal_reason"><?php echo esc_html ( $reason ) ; ?></textarea>
        </p>
        <p>
            <label class="hrw_labels hrw-withdrawal-payment-method"><?php echo esc_html ( get_option ( 'hrw_wallet_withdrawal_payment_method_label' ) ) ; ?><span class="required">*</span></label>
            <select class="hrw_withdrawal_payment_method" name="hrw_withdrawal[payment_method]">
                <?php
                foreach ( get_option ( 'hrw_sorted_payments_status' ) as $key => $value ) {

                    if ( $value == 'disable' )
                        continue ;

                    $payment_label = hrw_payment_method_preference () ;
                    ?>
                    <option value="<?php echo esc_html ( $key ) ; ?>"<?php selected ( $key , $value ) ; ?>><?php echo esc_html ( $payment_label[ $key ] ) ; ?></option>
                    <?php
                }
                ?>
            </select> 
        </p>
        <p>
            <label class="hrw_labels hrw-payment_details"><?php esc_html_e ( 'Enter the Bank Transfer Details' , HRW_LOCALE ) ; ?><span class="required">*</span></label>
            <textarea class="hrw_withdrawal_bank_details" name="hrw_withdrawal[bank_details]"><?php echo esc_html ( $bank_details ) ; ?></textarea>
        </p>
        <p>
            <label class="hrw_labels hrw-payment_details"><?php esc_html_e ( 'Enter the paypal Details' , HRW_LOCALE ) ; ?><span class="required">*</span></label>
            <input type="email" class="hrw_withdrawal_paypal_details" name="hrw_withdrawal[paypal_details]" value="<?php echo esc_attr ( $paypal_details ) ; ?>">
        </p>
        <p>
            <input type="hidden" name="hrw-action" value="withdrawal" />
            <input type="hidden" name="hrw-withdrawal-nonce" value="<?php echo wp_create_nonce ( 'hrw-withdrawal' ) ; ?>" />
            <input type="submit" class="hrw_withdrawal_button hrw_form_button" name="hrw_withdrawal_button" value="<?php esc_attr_e ( 'Withdrawal' , HRW_LOCALE ) ; ?>"/>
        </p>
    </form>
</div>
<?php
