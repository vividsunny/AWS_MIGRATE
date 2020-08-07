<?php
/* Wallet Display Settings */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_wallet_details_status_wrapper">
    <div class="hrw_wallet_details_wrapper">

        <div class="hrw_wallet_user_details_row">
            <h2><?php esc_html_e( 'Customer Details' , HRW_LOCALE ) ; ?></h2>
        </div>
        <div class="hrw_wallet_user_details_row">
            <label><?php esc_html_e( 'Customer Name' , HRW_LOCALE ) ; ?></label>
            <label><?php echo esc_html( $wallet->get_user()->display_name ) ; ?></label>
        </div>
        <div class="hrw_wallet_user_details_row">
            <label><?php esc_html_e( 'Customer Email' , HRW_LOCALE ) ; ?></label>
            <label><?php echo esc_html( $wallet->get_user()->user_email ) ; ?></label>
        </div>
        <div class="hrw_wallet_user_details_row">
            <label><?php esc_html_e( 'Available Balance' , HRW_LOCALE ) ; ?></label>
            <label><?php echo hrw_price( $wallet->get_available_balance() ) ; ?></label>
        </div>
        <div class="hrw_wallet_user_details_row">
            <label><?php esc_html_e( 'Expires On' , HRW_LOCALE ) ; ?></label>
            <?php
            $args = array(
                'name'        => 'hrw_wallet_bal_expiry_date' ,
                'value'       => $wallet->get_expiry_date() ,
                'wp_zone'     => false ,
                'placeholder' => HRW_Date_Time::get_wp_date_format() ,
                    ) ;
            hrw_get_datepicker_html( $args ) ;
            ?>
        </div>
    </div>
</div>
<?php
