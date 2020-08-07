<?php
/**
 * This template displays fund transfer view transaction
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/fund-transfer-view-transaction.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_fund_transfer_view_transaction_wrapper">
    <div class="hrw_fund_transfer_info">
        <h3><?php echo esc_html( $fund_transfer->get_receiver()->display_name ) ; ?>
            <i><?php echo esc_html( $fund_transfer->get_receiver()->user_email ) ; ?><i/>
        </h3>
    </div>
    <div class="fund_transfer_transactions">
        <?php
        foreach ( $transaction_logs as $transaction_log ):
            $transaction_log_object = hrw_get_fund_transfer_log( $transaction_log ) ;

            $class = ($transaction_log_object->get_sent_from() == 'yes') ? ' hrw_fund_transfer_sender' : ' hrw_fund_transfer_receiver' ;
            ?><div class="hrw_fund_transfer_transaction<?php echo esc_attr( $class ) ; ?>"><?php
            /*
             * Display fund transfer transaction
             */
            hrw_get_template( 'dashboard/fund-transfer-transaction-log.php' , true , array( 'transaction_log_object' => $transaction_log_object ) ) ;
            ?></div><?php
        endforeach ;
        ?>
    </div>
    <div class="hrw_fund_transfer_footer">
        <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( 'fund_transfer_form' , array( 'hrw_user_id' => $fund_transfer->get_receiver_id() ) ) ) ; ?>"><?php esc_html_e( 'Transfer' , HRW_LOCALE ) ; ?></a>
        <a href="<?php echo esc_url( HRW_Dashboard::prepare_menu_url( 'fund_request_form' , array( 'hrw_user_id' => $fund_transfer->get_receiver_id() ) ) ) ; ?>"><?php esc_html_e( 'Request' , HRW_LOCALE ) ; ?></a>
    </div>
</div>
<?php
