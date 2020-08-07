<?php
/* User profile Extra Information */

if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

$phone_no = ! empty( $user->ID ) ? get_user_meta( $user->ID , 'hrw_phone_number' , true ) : '' ;
?>
<h2><?php esc_html_e( 'Wallet' , HRW_LOCALE ) ; ?></h2>

<table class="form-table hrw_wallet_user_info">

    <tr>
        <th><?php esc_html_e( 'Phone Number' , HRW_LOCALE ) ?></th>
        <td>
            <input type ="text"
                   class ="hrw_ph_no_field"
                   name = "hrw_ph_no_field" 
                   value="<?php echo esc_html( $phone_no ) ; ?>">
        </td>
    </tr>

</table>

<?php
