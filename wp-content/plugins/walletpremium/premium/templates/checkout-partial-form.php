<?php
/**
 * This template displays checkout partial form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/checkout-partial-form.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$min_number = hrw_get_min_value_for_number_field() ;
$min_value  = sprintf( 'min=%s' , $min_number ) ;
$step       = sprintf( 'step=%s' , $min_number ) ;
?>
<div class="hrw_partial_form_wrapper">
    <form method="post" action="">
        <div class="woocommerce-info"><?php esc_html_e( 'Have Funds ?' , HRW_LOCALE ) ; ?>
            <a class="hrw_partial_wallet" href="javascript:void(0)"><?php esc_html_e( 'Click Here To Use Wallet Funds' , HRW_LOCALE ) ; ?></a>
        </div>
        <div class="hrw_partial_usage_content hrw_hide">
            <fieldset>
                <div class="hrw_partial_usage_inner_content">
                    <label><?php esc_html_e( 'Apply Funds' , HRW_LOCALE ) ; ?></label>
                    <input type="number" name="hrw_partial_usage" class="hrw_partial_usage"
                    <?php echo esc_attr( $min_value ) ; ?>
                    <?php echo esc_attr( $step ) ; ?>>
                    <input type="hidden" name="hrw-action" value="partial_usage" />
                    <input type="hidden" name="hrw-partial-usage-nonce" value="<?php echo wp_create_nonce( 'hrw-partial-usage' ) ; ?>" />
                    <input type="submit" value="<?php esc_html_e( 'Apply Wallet Funds' , HRW_LOCALE ) ; ?>" class="button hrw_partial_usage_btn"/>
                </div>
            </fieldset>
        </div>
    </form>
</form>
</div>
<?php
