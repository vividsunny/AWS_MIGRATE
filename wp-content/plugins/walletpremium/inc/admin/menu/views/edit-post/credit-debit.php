<?php
/* Wallet Credit Debit Display Settings */

if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_wallet_credit_debit_wrapper">
        <div class="hrw_wallet_credit_debit_row">
            <h2><?php esc_html_e ( 'Credit/Debit Funds' , HRW_LOCALE ) ; ?></h2>
        </div>

    <div class="hrw_wallet_credit_debit_row">
        <input type="hidden" id="hrw_wallet_user_id" value="<?php echo esc_attr ( $wallet->get_user ()->ID ) ; ?>">

        <label><?php esc_html_e ( 'Credit/Debit' , HRW_LOCALE ) ; ?></label>
        <?php
        $fund_type = array ( '1' => esc_html__ ( 'Credit' , HRW_LOCALE ) , '2' => esc_html__ ( 'Debit' , HRW_LOCALE ) ) ;
        ?>
        <select id="hrw_wallet_fund_type">
            <?php
            foreach ( $fund_type as $fund_id => $fund_name ) {
                ?>
                <option value="<?php echo esc_attr ( $fund_id ) ; ?>" ><?php echo esc_html ( $fund_name ) ; ?></option>
            <?php } ; ?>
        </select>
    </div>

    <div class="hrw_wallet_credit_debit_row">
        <label><?php esc_html_e ( 'Enter Funds' , HRW_LOCALE ) ; ?></label>
        <input type="text" id="hrw_wallet_fund_val" class="hrw_wallet_fund_fields">
    </div>

    <div class="hrw_wallet_credit_debit_row">
        <label><?php esc_html_e ( 'Enter Reason' , HRW_LOCALE ) ; ?></label>
        <input type="text" id="hrw_wallet_fund_reason" class="hrw_wallet_fund_fields">
    </div>

    <div class="hrw_wallet_credit_debit_row">
        <input type="button" id="hrw_wallet_credit_debit_btn" value="<?php esc_html_e ( 'credit/debit' , HRW_LOCALE ) ; ?>">
    </div>
</div>