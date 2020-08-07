<?php
/**
 * This template displays gift card form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/gift-card-form.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

do_action ( 'hrw_before_gift_card_form' ) ;
?>
<div class="hrw-gift-form">
    <h2><?php echo esc_html__ ( 'Gift Card Form' , HRW_LOCALE ) ; ?></h2>
    <?php echo HRW_Form_Handler::show_messages () ; ?>
    <form class="hrw_frontend_form" id="hrw_gift_form" action="" method="post" enctype="multipart/form-data">
        <p>
            <label><?php esc_html_e ( 'Receiver Email ID' , HRW_LOCALE ) ?><span class="required" title="required">*</span> </label>
            <input type='email' name="hrw_gift[receiver]" placeholder="<?php echo esc_html__ ( 'Enter recipient email id' , HRW_LOCALE ) ; ?>" value="<?php echo esc_attr ( hrw_get_gift_data_by_key ( 'receiver' ) ) ; ?>" > 
        </p>
        <p>
            <label><?php esc_html_e ( 'Receiver Name' , HRW_LOCALE ) ?><span class="required" title="required">*</span> </label>
            <input type='text' name="hrw_gift[receiver_name]" placeholder="<?php echo esc_html__ ( 'Enter recipient name' , HRW_LOCALE ) ; ?>" value="<?php echo esc_attr ( hrw_get_gift_data_by_key ( 'receiver_name' ) ) ; ?>" > 
        </p>
        <p>
            <label><?php esc_html_e ( 'Amount' , HRW_LOCALE ) ; ?><span class="required" title="required">*</span></label>
            <?php if ( '1' === $gift_field_type ) { ?>
                <input type="number" name="hrw_gift[amount]" step="0.01" min="<?php echo esc_attr ( $min_gift ) ; ?>" max="<?php echo esc_attr ( $max_gift ) ; ?>" value="<?php echo esc_attr ( hrw_get_gift_data_by_key ( 'receiver' ) ) ; ?>"/>
            <?php } else if ( '2' === $gift_field_type ) { ?> 
                <select id="hrw_gift_card_select"  >
                    <?php foreach ( $prefilled_amount as $topup ) { ?>
                        <option <?php selected ( hrw_get_gift_data_by_key ( 'amount' ) , $topup ) ; ?> value="<?php echo esc_attr ( $topup ) ; ?>"><?php echo wc_price ( $topup ) ; ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" class="hrw_gift_card_amount"  name="hrw_gift[amount]" >
            <?php } else { ?>
                <select id="hrw_gift_card_select"  name="hrw_gift[gift_card_select]" >
                    <option value=""><?php esc_html_e ( 'Choose Amount' , HRW_LOCALE ) ; ?></option>
                    <?php foreach ( $prefilled_amount as $topup ) { ?>
                        <option <?php selected ( hrw_get_gift_data_by_key ( 'amount' ) , $topup ) ; ?> value="<?php echo esc_attr ( $topup ) ; ?>"><?php echo wc_price ( $topup ) ; ?></option>
                    <?php } ?>
                    <option value="user-defined"><?php esc_html_e ( 'Custom Amount' , HRW_LOCALE ) ; ?></option>
                </select>
                <input type="number" class="hrw_gift_card_amount"  name="hrw_gift[amount]" style="display: none;" min="0" step="0.01" value="<?php esc_attr ( hrw_get_gift_data_by_key ( 'amount' ) ) ; ?>" />
            <?php } ?>
        </p>
        <p>
            <label><?php esc_html_e ( 'Reason' , HRW_LOCALE ) ?></label>
            <textarea name="hrw_gift[reason]"><?php echo esc_textarea ( hrw_get_gift_data_by_key ( 'reason' ) ) ; ?></textarea> 
        </p>
        <p>
            <input type="submit" class="hrw_form_button" value="<?php esc_attr_e ( 'Send' , HRW_LOCALE ) ; ?>" >
            <?php wp_nonce_field ( 'hrw-gift-submit' , 'hrw-gift-submit-nonce' ) ; ?>
        </p>
    </form>
</div>
<?php
do_action ( 'hrw_after_gift_card_form' ) ;
