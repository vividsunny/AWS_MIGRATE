<?php
/* Edit Withdrwl Page */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_edit_withdrawal_wrapper">
    <h2><?php esc_html_e( 'Edit Wallet Withdrawal' , HRW_LOCALE ) ; ?></h2>
    <table class="form-table hrw_withdrawal_info">
        <tbody>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Username' , HRW_LOCALE ) ; ?></th>
                <td><?php echo esc_html( $withdrawal_obj->get_user()->user_login ) ; ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Email' , HRW_LOCALE ) ; ?></th>
                <td><?php echo esc_html( $withdrawal_obj->get_user()->user_email ) ; ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Withdrawal Amount' , HRW_LOCALE ) ; ?></th>
                <td><?php echo hrw_price( $withdrawal_obj->get_amount() ) ; ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Withdrawal Fee' , HRW_LOCALE ) ; ?></th>
                <td><?php echo hrw_price( $withdrawal_obj->get_fee() ) ; ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Reason' , HRW_LOCALE ) ; ?></th>
                <td><?php echo esc_html( $withdrawal_obj->get_reason() ) ; ?></td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Payment Method' , HRW_LOCALE ) ; ?></th>
                <td><?php echo hrw_display_payment_method( $withdrawal_obj->get_payment_method() ) ; ?></td>
            </tr>
            <?php if( $withdrawal_obj->get_payment_method() == 'bank_transfer' ) : ?>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'Bank Details' , HRW_LOCALE ) ; ?></th>
                    <td><?php echo esc_html( $withdrawal_obj->get_bank_details() ) ; ?></td>
                </tr>
            <?php else : ?>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e( 'PayPal Email ID' , HRW_LOCALE ) ; ?></th>
                    <td><?php echo esc_html( $withdrawal_obj->get_paypal_details() ) ; ?></td>
                </tr>
            <?php endif ; ?>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Status' , HRW_LOCALE ) ; ?></th>
                <td>
                    <select class="hrw_edit_status" name ="hrw_edit_status" value="<?php echo esc_html( $withdrawal_obj->get_status() ) ; ?>">
                        <?php if ( $withdrawal_obj->get_status() != "hrw_cancelled" ) : ?>
                            <option value ="hrw_paid" <?php selected( $withdrawal_obj->get_status() , 'hrw_paid' , true ) ; ?>><?php esc_html_e( 'Paid' , HRW_LOCALE ) ; ?></option>
                        <?php endif ; ?>

                        <?php if ( $withdrawal_obj->get_status() != "hrw_paid" && $withdrawal_obj->get_status() != "hrw_cancelled" ) : ?>
                            <option value ="hrw_unpaid" <?php selected( $withdrawal_obj->get_status() , 'hrw_unpaid' , true ) ; ?>><?php esc_html_e( 'UnPaid' , HRW_LOCALE ) ; ?></option>
                            <option value ="hrw_in_progress" <?php selected( $withdrawal_obj->get_status() , 'hrw_in_progress' , true ) ; ?>><?php esc_html_e( 'Inprogress' , HRW_LOCALE ) ; ?></option>
                            <?php
                        endif ;
                        if ( $withdrawal_obj->get_status() != "hrw_paid" ) :
                            ?>
                            <option value ="hrw_cancelled" <?php selected( $withdrawal_obj->get_status() , 'hrw_cancelled' , true ) ; ?>><?php esc_html_e( 'Cancel' , HRW_LOCALE ) ; ?></option>
                        <?php endif ; ?>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <?php $url = add_query_arg( array( 'page' => 'hrw_settings' , 'tab' => 'modules' , 'section' => 'general' , 'subsection' => 'wallet_withdrawal' ) , HRW_ADMIN_URL ) ; ?>
                    <a href="<?php echo esc_url( $url ) ?>"><?php esc_html_e( 'Back' , HRW_LOCALE ) ; ?></a>
                </th>
                <td>
                    <input class ="button-primary hrw_update_btn" 
                           type="submit"
                           value="<?php esc_html_e( 'Update' , HRW_LOCALE ) ; ?>"
                           <?php if( $withdrawal_obj->get_status() == "hrw_cancelled" || $withdrawal_obj->get_status() == "hrw_paid" ) { ?> disabled <?php } ?>/>
                    <input type="hidden" name="hrw-wallet-withdrawal-action" value="wallet-withdrawal" />
                    <?php wp_nonce_field( $this->plugin_slug . '_edit_withdrawal' , '_' . $this->plugin_slug . '_withdrawal_nonce' , false , true ) ; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
  <?php       