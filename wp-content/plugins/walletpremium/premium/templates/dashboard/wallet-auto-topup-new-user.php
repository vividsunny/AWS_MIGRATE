<?php
/**
 * This template displays Auto Top-up New User form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/wallet-auto-topup-new-user.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw-new-user-auto-topup">
    <form class="hrw_frontend_form" id="hrw_new_user_auto_topup" action="" method="post" enctype="multipart/form-data">
        <p>
            <label><span class="hrw_labels hrw-auto-topup-amount"><?php esc_html_e( 'Amount for Auto Top-up' , HRW_LOCALE ) ; ?></span></label>
            <?php if( 'pre-defined' === $topup_amount_type ) { ?>
                <select name="hrw_auto_topup[amount]" >
                    <?php foreach( $topup_predefined_amount as $topup ) { ?>
                        <option value="<?php echo esc_attr( $topup ) ; ?>"><?php echo wc_price( $topup ) ; ?></option>
                    <?php } ?>
                </select>
            <?php } else if( 'user-defined' === $topup_amount_type ) { ?>
                <input type="number" name="hrw_auto_topup[amount]" step="0.01" min="<?php echo esc_attr( $topup_min_amount ) ; ?>" max="<?php echo esc_attr( $topup_max_amount ) ; ?>" value=""/>
            <?php } else { ?>
                <select id="hrw_auto_topup_amount" name="hrw_auto_topup[amount]" >
                    <?php foreach( $topup_predefined_amount as $topup ) { ?>
                        <option value="<?php echo esc_attr( $topup ) ; ?>"><?php echo wc_price( $topup ) ; ?></option>
                    <?php } ?>
                    <option value="user-defined"><?php esc_html_e( 'Select custom amount..' , HRW_LOCALE ) ; ?></option>
                </select>
                <input type="number" name="hrw_auto_topup[amount]" style="display: none;" step="0.01" min="<?php echo esc_attr( $topup_min_amount ) ; ?>" max="<?php echo esc_attr( $topup_max_amount ) ; ?>" data-min="<?php echo esc_attr( $topup_min_amount ) ; ?>" data-max="<?php echo esc_attr( $topup_max_amount ) ; ?>" value=""/>
            <?php } ?>
        </p>
        <p>
            <label><span class="hrw_labels hrw-auto-topup-threshold-amount"><?php esc_html_e( 'Threshold Value' , HRW_LOCALE ) ; ?></span></label>
            <?php if( 'pre-defined' === $threshold_amount_type ) { ?>
                <select name="hrw_auto_topup[threshold_amount]" >
                    <?php foreach( $threshold_predefined_amount as $topup ) { ?>
                        <option value="<?php echo esc_attr( $topup ) ; ?>"><?php echo wc_price( $topup ) ; ?></option>
                    <?php } ?>
                </select>
            <?php } else if( 'user-defined' === $threshold_amount_type ) { ?>
                <input type="number" name="hrw_auto_topup[threshold_amount]" step="0.01" min="<?php echo esc_attr( $threshold_min_amount ) ; ?>" max="<?php echo esc_attr( $threshold_max_amount ) ; ?>" value=""/>
            <?php } else { ?>
                <select id="hrw_auto_topup_threshold_amount" name="hrw_auto_topup[threshold_amount]" >
                    <?php foreach( $threshold_predefined_amount as $topup ) { ?>
                        <option value="<?php echo esc_attr( $topup ) ; ?>"><?php echo wc_price( $topup ) ; ?></option>
                    <?php } ?>
                    <option value="user-defined"><?php esc_html_e( 'Select custom amount..' , HRW_LOCALE ) ; ?></option>
                </select>
                <input type="number" name="hrw_auto_topup[threshold_amount]" style="display: none;" step="0.01" min="<?php echo esc_attr( $threshold_min_amount ) ; ?>" max="<?php echo esc_attr( $threshold_max_amount ) ; ?>" data-min="<?php echo esc_attr( $threshold_min_amount ) ; ?>" data-max="<?php echo esc_attr( $threshold_max_amount ) ; ?>" value=""/>
            <?php } ?>
        </p>    
        <?php if( 'yes' === get_option( 'hrw_auto_topup_display_privacy_policy_link' ) ) { ?>
                
        
        <div class="hrw_auto_iagree"><input type="checkbox" id="hrw_auto_topup_agree" name="hrw_auto_topup[agree]" value="yes"/><?php echo wp_kses_post( wpautop( str_replace( '[wallet_auto_topup_terms]' , get_option( 'hrw_auto_topup_privacy_policy_url' ) , get_option( 'hrw_auto_topup_privacy_policy_content' ) ) ) ) ; ?></div>
            
        <?php } ?>
        <p>
            <input type="hidden" name="hrw-action" value="auto-topup" />
            <input type="hidden" name="hrw-auto-topup-nonce" value="<?php echo wp_create_nonce( 'hrw-auto-topup' ) ; ?>" />
            <input type="submit" class="hrw_auto_topup_button hrw_form_button" name="hrw_auto_topup_button" value="<?php esc_attr_e( 'Auto Top-up' , HRW_LOCALE ) ; ?>"/>
        </p>        
    </form>
</div>
<?php
