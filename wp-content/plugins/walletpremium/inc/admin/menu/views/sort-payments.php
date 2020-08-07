<?php
/* Sort Payments */

if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>
<tbody id="hrw_payment_settings_table">

    <?php
    foreach( $available_payments as $pay_key => $status ) {

        $payment_label = hrw_payment_method_preference( $pay_key ) ;

        if( $payment_label == '' )
            continue ;
        ?>

        <tr>
            <td>
                <label><?php echo esc_html( $payment_label ) ; ?></label>
                <input type="hidden" name="sorted_payments_label[<?php echo esc_html( $pay_key ) ; ?>]" value="<?php echo esc_html( $payment_label ) ; ?>" >
            </td>
            <td>
                <select name="hrw_sorted_payments_status[<?php echo esc_html( $pay_key ) ; ?>]" >
                    <option <?php if( $status == 'enable' ) { ?> selected="" <?php } ?> value="<?php echo 'enable' ; ?>"><?php esc_html_e( 'Enable' , HRW_LOCALE ) ; ?></option>
                    <option <?php if( $status == 'disable' ) { ?> selected="" <?php } ?> value="<?php echo 'disable' ; ?>"><?php esc_html_e( 'Disable' , HRW_LOCALE ) ; ?></option>
                </select>
                <input type="hidden" name="sorted_payments_demo[]" value ="<?php echo esc_html( $pay_key ) ; ?>" >
            </td>
            <td class="sort hrw_payments_sort_handle" style="cursor: move;" >
                <img src=<?php echo HRW_PLUGIN_URL . '/assets/images/others/drag-icon.png' ; ?> ></img>
            </td>
        <tr>

        <?php } ?>
</tbody>
