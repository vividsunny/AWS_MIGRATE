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
<?php if ( get_option( 'hrw_fund_transfer_user_restriction_type' ) != 'true' ) { ?>
    <div class="hrw_fund_transfer_wrapper">
        <?php echo HRW_Form_Handler::show_messages() ; ?>
        <form method="POST" action="" class="hrw_frontend_form hrw_fund_transfer">
            <div class='hrw_fund_transfer_content'>
                <p class="form-row">

                    <?php
                    if ( $display_user_selector ) :
                        ?><label><?php esc_html_e( 'User Selection' , HRW_LOCALE ) ; ?><span class="required">*</span></label><?php
                        $args = array(
                            'action'            => 'hrw_customers_search' ,
                            'type'              => 'ajaxmultiselect' ,
                            'list_type'         => 'customers' ,
                            'multiple'          => false ,
                            'placeholder'       => esc_html__( 'Select a User' , HRW_LOCALE ) ,
                            'name'              => 'hrw_fund_request[user_selection]' ,
                            'class'             => 'hrw_fund_request_user_selection' ,
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
                        <input type="hidden" name="hrw_fund_request[user_selection][]" value="<?php echo esc_attr( $user_id ) ; ?>" />
                    <?php endif ; ?>
                </p>

                <p class="form-row">
                    <label><?php esc_html_e( 'Amount to Request' , HRW_LOCALE ) ; ?><span class="required">*</span></label>
                    <input type='number' min='0' name='hrw_fund_request[amount]' class='hrw_fund_request_amount' value='<?php echo esc_attr( $amount ) ; ?>'/>
                </p>

                <p class="form-row">
                    <label><?php esc_html_e( 'Reason' , HRW_LOCALE ) ; ?></label>
                    <textarea name='hrw_fund_request[reason]' class='hrw_fund_request_reason'><?php echo esc_html( $reason ) ; ?></textarea>
                </p>

                <p class="form-row">
                    <input type="hidden" name="hrw-action" value="fund_request" />
                    <input type="hidden" name="hrw-fund-request-nonce" value="<?php echo wp_create_nonce( 'hrw-fund-request' ) ; ?>" />
                    <input type='submit' class="hrw_fund_request_button hrw_form_button" value="<?php echo esc_html_e( 'Request' , HRW_LOCALE ) ; ?>" >
                </p>
            </div>
        </form>
    <?php } else { ?>
        <label><?php esc_html_e( 'Fund Request Restrict' , HRW_LOCALE ) ; ?></label>
    <?php } ?>
</div>
<?php
