<?php
/**
 * This template displays Top-up form
 * 
 * This template can be overridden by copying it to yourtheme/wallet/premium/topup-form.php
 * 
 * To maintain compatibility, Wallet will update the template files and you have to copy the updated files to your theme
 */
if( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
$topup_field_type = get_option( 'hrw_general_topup_amount_type' ) ;
$prefilled_values = get_option( 'hrw_general_topup_prefilled_amount' ) ;

$ready_only = ($topup_field_type == '3') ? ' readonly="readonly"' : '' ;
$value      = ($topup_field_type == '3' || $topup_field_type == '2') ? $prefilled_values : '' ;

$min_number = hrw_get_min_value_for_number_field() ;
$min_value  = sprintf( 'min=%s' , $min_number ) ;
$step       = sprintf( 'step=%s' , $min_number ) ;
?>
<label><?php echo esc_html( get_option( 'hrw_localizations_topup_form_amount_label' , 'Top-up Amount' ) ) ; ?></label>
<?php
switch( $topup_field_type ) {
    case '2':
    case '3':
        ?>
        <input type='number' name='hrw_topup_amount' 
               class='hrw_topup_amount' value="<?php echo esc_attr( $value ) ; ?>" 
               placeholder="<?php echo esc_attr( get_option( 'topup_form_amount_placeholder' , 'Enter Amount' ) ) ; ?>"
               <?php echo esc_attr( $min_value ) ; ?>
               <?php echo esc_attr( $step ) ; ?>
               <?php echo $ready_only ; ?> />
               <?php
               break ;
           case '4':
               $button_values = explode( ' , ' , $prefilled_values ) ;
               ?>
        <div class="hrw_topup_amount_buttons">
            <input type='hidden' name='hrw_topup_amount' class='hrw_topup_amount' />
            <?php
            foreach( $button_values as $button_value ):
                ?><input type="button" class="hrw_topup_prefilled_amount" data-amount="<?php echo esc_attr( $button_value ) ; ?>" value="<?php echo hrw_formatted_price( $button_value ) ; ?>" /><?php
            endforeach ;
            ?>
        </div>
        <?php
        break ;
    default :
        ?>
        <input type='number' 
               name='hrw_topup_amount'  
               class='hrw_topup_amount' 
               placeholder="<?php echo esc_attr( get_option( 'topup_form_amount_placeholder' , 'Enter Amount' ) ) ; ?>"
               <?php echo esc_attr( $min_value ) ; ?>
               <?php echo esc_attr( $step ) ; ?>>
        <?php
        break ;
}
