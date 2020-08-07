<?php
/**
 * This template displays MyAccount Wallet
 * 
 * This template can be overridden by copying it to yourtheme/wallet/myaccount-wallet.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_myaccount_wallet_wrapper">
    <?php
    /*
     * Hook: hrw_before_myaccount_wallet_content  
     */

    do_action( 'hrw_before_myaccount_wallet_content' ) ;
    ?>

    <?php hrw_get_template( 'dashboard/wallet-balance.php' , false ) ; ?>

    <p class="hrw_dashboard_view_link">
        <?php echo sprintf( esc_html__( 'To see more details, %s' , HRW_LOCALE ) , '<a href="' . hrw_get_page_id( 'dashboard' , true ) . '">' . esc_html__( 'View Dashboard' , HRW_LOCALE ) . '</a>' ) ; ?>
    </p>

    <?php
    /*
     * Hook: hrw_before_myaccount_wallet_content  
     */

    do_action( 'hrw_after_myaccount_wallet_content' ) ;
    ?>

</div>
<?php
