<?php
/* License Verification */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$purchase_code = $this->license_key() ;
$name          = ($purchase_code) ? 'Deactivate' : 'Activate' ;
$handler       = ($purchase_code) ? 'deactivate' : 'activate' ;
?>
<div class='hrw_license_verification_wrapper'>
    <div class="hrw_license_verification_content" >
        <h2><?php esc_html_e( 'License Information' , HRW_LOCALE ) ; ?></h2> 
        <div class='hrw_license_verification_label'>
            <label><?php esc_html_e( 'Purchase Code' , HRW_LOCALE ) ; ?></label>
        </div>
        <div class='hrw_license_activation_content'>
            <input type='text' id='hrw_license_key'/>
            <input type='hidden' id='hrw_license_verification_type' value='<?php echo $handler ; ?>'/>
            <input type="button" id='hrw_license_verification_btn' value="<?php echo $name ; ?>" class="button button-primary"/>
            <p class='hrw_license_verification_messages hrw_error'></p>
            <p class='hrw_license_verification_messages hrw_success'></p>
            <h4><?php esc_html_e( 'Where can I find my License Key?' , HRW_LOCALE ) ; ?></h4>
            <ul>
                <li><?php echo sprintf( esc_html__( '%s to Hoicker' , HRW_LOCALE ) , '<a href="https://hoicker.com/my-account/" target="blank">' . esc_html__( 'Login' , HRW_LOCALE ) . '</a>' ) ; ?></li>
                <li><?php esc_html_e( 'Go to My Account page' , HRW_LOCALE ) ; ?></li>
                <li><?php esc_html_e( 'In Orders section you will find your License Key' , HRW_LOCALE ) ; ?></li>
            </ul>
        </div>
    </div>
    <div class='clear'></div>
</div><?php
