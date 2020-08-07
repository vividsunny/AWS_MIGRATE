<?php
/* Wallet Rule List for Cashback */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<tr>
    <td>
        <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][min]"/>
    </td>
    <td>
        <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][max]"/>
    </td>
    <td>
        <select name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][type]" >
            <option value="1"><?php esc_html_e( 'Fixed' , HRW_LOCALE ) ; ?></option>
            <option value="2"><?php esc_html_e( 'Percentage' , HRW_LOCALE ) ; ?></option>
        </select>
    </td>
    <td>
        <input type="number" name="hrw_cashback_rules[<?php echo esc_attr( $postid ) ; ?>][wallet_rule][<?php echo esc_attr( $uniqueid ) ; ?>][value]"/>
    </td>
    <td>
        <button class="hrw_remove_cashback_rule"><i class="fa fa-trash"></i></button>
    </td>
</tr>
                <?php
