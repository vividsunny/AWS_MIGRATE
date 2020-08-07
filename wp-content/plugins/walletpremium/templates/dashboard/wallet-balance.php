<?php
/**
 * This template displays Wallet Balance
 * 
 * This template can be overridden by copying it to yourtheme/wallet/dashboard/wallet-balance.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_wallet_balance">
    <h2>
        <?php echo esc_html( get_option( 'hrw_localizations_wallet_balance_label' , 'Wallet balance' ) ) ; ?>
        <span class="hrw_wallet_price"><?php echo hrw_price( HRW_Wallet_User::get_available_balance() ) ; ?></span>
    </h2>
    <table class="hrw_wallet_balance_table">
        <tbody>
            <tr>
                <td><span class="hrw_labels hrw_status"><?php echo esc_html( get_option( 'hrw_localizations_transaction_log_table_status_label' , 'Wallet Status' ) ) ; ?></span></td>
                <td><?php echo hrw_display_status( HRW_Wallet_User::get_wallet_status() ) ; ?></td>
            </tr>
            <tr>
                <td><span class="hrw_labels hrw_total_balance"><?php echo esc_html( get_option( 'hrw_localizations_total_amount_spent_label' , 'Total Amount Spent on Purchase' ) ) ; ?></span></td>
                <td><?php echo hrw_price( HRW_Wallet_User::get_total_balance() ) ; ?></td>
            </tr>
            <?php
            if(apply_filters('hrw_visible_expiry_date' , true   )) { ?>
                <tr>
                    <td><span class="hrw_labels hrw_exp_date"><?php echo esc_html( get_option( 'hrw_localizations_wallet_balance_expiry_date_label' , 'Expiry date' ) ) ; ?></span></td>
                    <td><?php echo HRW_Wallet_User::get_formatted_expiry_date() ; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
