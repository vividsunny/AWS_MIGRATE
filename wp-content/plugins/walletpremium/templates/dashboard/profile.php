<?php
/**
 * This template displays User Profile
 * 
 * This template can be overridden by copying it to yourtheme/wallet/dashboard/profile.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<div class="hrw_dashboard_profile_wrapper">
    <h2><?php esc_html_e( 'Edit Profile' , HRW_LOCALE ) ; ?></h2>
    <?php echo HRW_Form_Handler::show_messages() ; ?>
    <form method="POST" action="" class="hrw_frontend_form hrw_fund_transfer" enctype="multipart/form-data">
        <div class='hrw_dashboard_profile_content'>
            <p class="form-row">
                <label><?php esc_html_e( 'Phone Number' , HRW_LOCALE ) ; ?></label>
                <input type ="text" 
                       class="hrw_user_phone_number" 
                       name="hrw_phone_number"
                       placeholder="<?php esc_attr_e( 'Enter Phone Number' , HRW_LOCALE ) ; ?>"
                       value="<?php echo esc_html( HRW_Wallet_User::get_user_phone() ) ; ?>">
            </p>
            <p class="form-row">
                <input type="hidden" name="hrw-action" value="dashboard_profile" />
                <input type="hidden" name="hrw-dashboard-profile-nonce" value="<?php echo wp_create_nonce( 'hrw-dashboard-profile' ) ; ?>" />
                <input type='submit' class="hrw_dashboard_profile_button hrw_form_button" value="<?php echo esc_html_e( 'Save Profile' , HRW_LOCALE ) ; ?>" >
            </p>
        </div>
    </form>
</div>
<?php
