<?php
/**
 * This template displays fund transfer transaction log
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/fund-transfer-transaction-log.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_fund_transfer_transaction_content">
    <p class="hrw_transfer_amount"><?php echo hrw_price( $transaction_log_object->get_amount() ) ; ?></p>
    <p class="hrw_transfer_description"><?php echo esc_html( $transaction_log_object->get_reason() ) ; ?></p>
    <p class="hrw_transfer_status"><?php echo hrw_format_fund_transfer_log_status( $transaction_log_object ) ; ?></p>

    <?php if ( in_array( $transaction_log_object->get_status() , array( 'hrw_requested' , 'hrw_new_requested' ) ) ): ?>
        <p>
            <input type="hidden" class="hrw_fund_transfer_transaction_id" value="<?php echo esc_attr( $transaction_log_object->get_id() ) ; ?>"/>
            <?php if ( $transaction_log_object->get_sent_from() != 'yes' ): ?>
                <input type="button" class="hrw_accept_fund_request hrw_fund_request_buttons" value="<?php esc_html_e( 'Transfer' , HRW_LOCALE ) ; ?>"/>
                <input type="button" class="hrw_decline_fund_request hrw_fund_request_buttons" value="<?php esc_html_e( 'Decline' , HRW_LOCALE ) ; ?>"/>
            <?php else: ?>
                <input type="button" class="hrw_cancel_fund_request hrw_fund_request_buttons" value="<?php esc_html_e( 'Cancel' , HRW_LOCALE ) ; ?>"/>
            <?php endif ; ?>
        </p>
    <?php endif ; ?>
</div>
<?php
