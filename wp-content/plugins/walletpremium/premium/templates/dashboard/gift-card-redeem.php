<?php
/**
 * This template displays gift card redeem form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/gift-card-redeem.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

do_action ( 'hrw_before_gift_card_redeem_form' ) ;
?>
<div class = "hrw_account_approval_form_wrapper">
    <h2><?php echo esc_html__ ( 'Gift Card Redeem Form' , HRW_LOCALE ) ; ?></h2>
    <?php echo HRW_Form_Handler::show_messages () ;
    ?>
    <form class="hrw_frontend_form form name"  method="post" enctype="multipart/form-data" >
        <p>
            <input type='text' name="hrw_gift_redeem" placeholder="<?php echo esc_html__ ( 'Enter the gift card' , HRW_LOCALE ) ; ?>"> 
        </p>
        <p>
            <input type="submit" class="hrw_redeem_button hrw_form_button" value="<?php esc_attr_e ( 'Redeem' , HRW_LOCALE ) ; ?>" >
            <?php wp_nonce_field ( 'hrw-gift-redeem' , 'hrw-gift-redeem-nonce' ) ; ?>
        </p>
    </form>
</div>

<?php
do_action ( 'hrw_after_gift_card_redeem_form' ) ;
