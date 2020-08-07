<?php
/* Local Rule List for Discount */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<tr>
    <td>
        <input type="number" name="hrw_discount_rules[<?php echo esc_attr( $postid ) ; ?>][local_rule][<?php echo esc_attr( $uniqueid ) ; ?>][min]"/>
    </td>
    <td>
        <input type="number" name="hrw_discount_rules[<?php echo esc_attr( $postid ) ; ?>][local_rule][<?php echo esc_attr( $uniqueid ) ; ?>][max]"/>
    </td>
    <td>
        <select name="hrw_discount_rules[<?php echo esc_attr( $postid ) ; ?>][local_rule][<?php echo esc_attr( $uniqueid ) ; ?>][type]" >
            <option value="1"><?php esc_html_e( 'Fixed' , HRW_LOCALE ) ; ?></option>
            <option value="2"><?php esc_html_e( 'Percentage' , HRW_LOCALE ) ; ?></option>
        </select>
    </td>
    <td>
        <input type="number" name="hrw_discount_rules[<?php echo esc_attr( $postid ) ; ?>][local_rule][<?php echo esc_attr( $uniqueid ) ; ?>][value]"/>
    </td>
    <td>
        <button class="hrw_remove_discount_rule"><i class="fa fa-trash"></i></button>
    </td>
</tr>
<?php
