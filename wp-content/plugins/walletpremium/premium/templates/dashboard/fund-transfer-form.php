<?php
/**
 * This template displays fund request form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/fund-request-form.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_fund_transfer_wrapper">
    <?php echo HRW_Form_Handler::show_messages() ; ?>
    <form method="POST" action="" class="hrw_frontend_form hrw_fund_transfer" enctype="multipart/form-data">
        <div class='hrw_fund_transfer_content'>

            <p class="form-row">
                <?php
                if ( $display_user_selector ) :
                    ?> <label><?php esc_html_e( 'User Selection' , HRW_LOCALE ) ; ?><span class="required">*</span></label><?php
                    $args = array(
                        'action'            => 'hrw_customers_search' ,
                        'type'              => 'ajaxmultiselect' ,
                        'list_type'         => 'customers' ,
                        'multiple'          => false ,
                        'placeholder'       => esc_html__( 'Select a User' , HRW_LOCALE ) ,
                        'name'              => 'hrw_fund_transfer[user_selection]' ,
                        'class'             => 'hrw_fund_transfer_user_selection' ,
                        'options'           => $user_id ,
                        'custom_attributes' => array(
                            'data-include'       => wp_json_encode( $include_users ) ,
                            'data-include_roles' => wp_json_encode( $include_user_roles ) ,
                            'data-exclude'       => wp_json_encode( $exclude_users ) ,
                            'data-exclude_roles' => wp_json_encode( $exclude_user_roles )
                        )
                            ) ;
                    hrw_select2_html( $args ) ;
                else:
                    ?>
                    <label><?php esc_html_e( 'User Name' , HRW_LOCALE ) ; ?></label>
                    <span><?php echo esc_html( get_userdata( $user_id )->display_name ) ; ?></span>
                    <input type="hidden" name="hrw_fund_transfer[user_selection][]" value="<?php echo esc_attr( $user_id ) ; ?>" />
                <?php endif ; ?>
            </p>

            <p class="form-row">
                <label><?php esc_html_e( 'Available Amount' , HRW_LOCALE ) ; ?></label>
                <input type='text' name='hrw_fund_transfer[available_amount]' class='hrw_fund_transfer_available_amount' readonly="readonly" value="<?php echo hrw_formatted_price( HRW_Wallet_User::get_available_balance() ) ; ?>"/>
            </p>

            <p class="form-row">
                <label><?php esc_html_e( 'Amount to Transfer' , HRW_LOCALE ) ; ?><span class="required">*</span></label>
                <input type='number' min='0' name='hrw_fund_transfer[amount]' class='hrw_fund_transfer_amount' value="<?php echo esc_attr( $amount ) ; ?>" />
            </p>

            <?php if ( HRW_Module_Instances::get_module_by_id( 'fund_transfer' )->enable_transfer_fee == 'yes' ): ?>
                <p class="form-row">
                    <label><?php esc_html_e( 'Transfer Fee' , HRW_LOCALE ) ; ?></label>
                    <input type='text' name='hrw_fund_transfer[fee]' class='hrw_fund_transfer_fee' value="<?php echo esc_attr( $fee ) ; ?>" readonly="readonly"/>
                </p>
            <?php endif ; ?>

            <p class="form-row">
                <label><?php esc_html_e( 'Reason' , HRW_LOCALE ) ; ?></label>
                <textarea name='hrw_fund_transfer[reason]' class='hrw_fund_transfer_reason'><?php echo esc_html( $reason ) ; ?></textarea>
            </p>

            <p class="form-row">
                <input type='submit' class="hrw_fund_transfer_button hrw_form_button" value="<?php echo esc_html_e( 'Transfer' , HRW_LOCALE ) ; ?>" >
                <input type="hidden" name="hrw-action" value="fund_transfer" />
                <input type="hidden" name="hrw-fund-transfer-nonce" value="<?php echo wp_create_nonce( 'hrw-fund-transfer' ) ; ?>" />
            </p>
        </div>
    </form>
</div>
<?php
