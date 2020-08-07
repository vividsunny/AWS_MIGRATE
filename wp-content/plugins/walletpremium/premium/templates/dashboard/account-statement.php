<?php
/**
 * This template displays account statement
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/dashboard/account-statement.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_frontend_form hrw_statement_form">
    <p>
        <label class="hrw_radio_btn_wrapper">
            <input type='radio' class='hrw_wallet_statement_duration' name='hrw_wallet_statement_predefined_duration' value="1" />
            <span class="hrw_radio_btn"><i class="fa fa-check"></i></span>

            <strong><?php esc_html_e( 'Last 1 Month' , HRW_LOCALE ) ; ?></strong>
            </lebel>
    </p>
    <p>
        <label class="hrw_radio_btn_wrapper">
            <input type='radio' class='hrw_wallet_statement_duration' name='hrw_wallet_statement_predefined_duration' value="3" />
            <span class="hrw_radio_btn"><i class="fa fa-check"></i></span>
            <strong><?php esc_html_e( 'Last 3 Months' , HRW_LOCALE ) ; ?></strong>
            </lebel>
    </p>
    <p><label class="hrw_radio_btn_wrapper">
            <input type='radio' class='hrw_wallet_statement_duration' name='hrw_wallet_statement_predefined_duration' value="6" />
            <span class="hrw_radio_btn"><i class="fa fa-check"></i></span>
            <strong><?php esc_html_e( 'Last 6 Months' , HRW_LOCALE ) ; ?></strong>
            </lebel>
    </p>
    <p><label class="hrw_radio_btn_wrapper">
            <input type='radio' class='hrw_wallet_statement_duration' name='hrw_wallet_statement_predefined_duration' value="12" />
            <span class="hrw_radio_btn"><i class="fa fa-check"></i></span>
            <strong><?php esc_html_e( 'Last 12 Months' , HRW_LOCALE ) ; ?></strong>
            </lebel>
    </p>
    <p><label class="hrw_radio_btn_wrapper">
            <input type='radio' class='hrw_wallet_statement_duration' name='hrw_wallet_statement_predefined_duration' value="custom" />
            <span class="hrw_radio_btn"><i class="fa fa-check"></i></span>
            <strong><?php esc_html_e( 'Custom Duration' , HRW_LOCALE ) ; ?></strong>
            </lebel>
    </p>
    <p>
        <?php esc_html_e( 'From' , HRW_LOCALE ) ; ?><input type='text' id='hrw_statement_from_date' class='hrw_datepicker hrw_statement_custom_date_field' />
        <?php esc_html_e( 'To' , HRW_LOCALE ) ; ?><input type='text' id='hrw_statement_to_date' class='hrw_datepicker hrw_statement_custom_date_field' />
    </p>
    <p>
        <strong>
            <?php esc_html_e( 'Email ID : ' , HRW_LOCALE ) ; ?>
        </strong>
        <?php echo HRW_Wallet_User::get_user_emai_id() ; ?>
    </p>
    <p>
        <input type='button' class='hrw_form_button hrw_wallet_statement_sent_email_btn' value='<?php esc_html_e( "Send Email" , HRW_LOCALE ) ?>' />
    </p>
</div>
<?php 