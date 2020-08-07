<?php
/**
 * Refund Request Form
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

do_action( 'hrr_before_frontend_refund_request_table' , $order ) ;

$currency_code  = $order->get_currency() ;
$tax_enabled    = ( 'yes' == get_option( 'hrr_refund_refund_tax' ) ) ;
$header_columns = array( esc_html__('Image', 'refund') , esc_html__('Product Name', 'refund') , esc_html__('Item Value', 'refund') , esc_html__('Qty', 'refund') , esc_html__('Total', 'refund') , esc_html__('Refund Qty', 'refund') , esc_html__('Refund total', 'refund') ) ;
$refund_method  = get_option( 'hrr_refund_refund_method' );
?>
<div class='hrr-refund-form'>
	<form id='hrr-refund-form' method='post'>
		<div class='hrr-refund-form-field'>
			<table class='shop_table hrr-request-table' cellspacing="0" cellpadding="6">
				<thead>
					<tr>
						<?php
						do_action( 'hrr_frontend_item_column_header_start' , $order ) ;
						foreach ( $header_columns as $column_name ) :
							?>
							<th><?php echo esc_html( $column_name ) ; ?></th>
							<?php
						endforeach ;
						do_action( 'hrr_forntend_item_column_header_end' , $order ) ;
						?>
					</tr>
				</thead>
				<tbody>
					<?php
					$tax_total_value     = 0 ;
					$total_refund_amount = 0 ;
					$tax_value           = 0 ;
					$partial_enabled     = apply_filters( 'hrr_partial_enabled' , false ) ;
					$readonly            = ( ! $partial_enabled ) ? 'readonly="readonly"' : '' ;
					foreach ( $order->get_items() as $item_id => $item ) :
						$original_quantity  = isset( $item[ 'quantity' ] ) ? $item[ 'quantity' ] : $item[ 'qty' ] ;
						$item_value         = $item[ 'line_total' ] / $original_quantity ;
						$already_refund_qty = ( int ) wc_get_order_item_meta( $item_id , 'hr_refund_request_item_qty' ) ;
						$check              = ( $already_refund_qty < $original_quantity ) ;
						$quantity           = $original_quantity - $already_refund_qty ;
						$total_refund       = $item_value * $quantity ;
						$line_tax_data      = maybe_unserialize( $item[ 'line_tax_data' ] ) ;
						if ( $tax_enabled ) {
							$item_and_tax_value = ( $item[ 'line_subtotal' ] + $item[ 'line_subtotal_tax' ] ) / $original_quantity ;
							$tax_value          = $item[ 'line_subtotal_tax' ] / $original_quantity ;
							$tax_total_value    += $item[ 'line_subtotal_tax' ] ;
							$total_refund       = $item_and_tax_value * $quantity ;
						}
						$total_refund_amount += $total_refund ;

						$quantity     = ( $check ) ? $quantity : '0' ;
						$total_refund = ( $check ) ? $total_refund : '0' ;
						?>
						<tr class='hrr_refund_items' data-order_item_id="<?php echo esc_attr( $item_id ) ; ?>">
							<?php do_action( 'hrr_frontend_item_column_start' , $check , $order , $item_id ) ; ?>
							<td class='hrr_refund_product_image' data-title="<?php esc_attr_e( 'Image' , 'refund' ) ; ?>">
								<?php echo hrr_get_product_image( $item ) ; ?>
							</td>
							<td class='hrr_refund_product_name' data-title="<?php esc_attr_e( 'Product Name' , 'refund' ) ; ?>">
								<?php echo hrr_get_product_name( $item ); ?>
							</td>
							<td class='hrr_refund_product_price' data-title="<?php esc_attr_e( 'Item Value' , 'refund' ) ; ?>">
								<?php echo wc_price( $item_value , $currency_code ) ; ?>
							</td>
							<td class='hrr_refund_product_qty' data-title="<?php esc_attr_e( 'Qty' , 'refund' ) ; ?>">
								<?php echo esc_html( $item[ 'quantity' ] ) ; ?>
							</td>
							<td class='hrr_refund_product_total' data-title="<?php esc_attr_e( 'Total' , 'refund' ) ; ?>">
								<?php echo wc_price( $item[ 'line_total' ] , $currency_code ) ; ?>
							</td>
							<td class='hrr_refund_product_refund_qty hrr_refund_item_data' data-title="<?php esc_attr_e( 'Refund Qty' , 'refund' ) ; ?>">
								<input type='number' id='hrr_refund_item_qty' class="hrr_refund_item_qty" min='0' 
									   max='<?php echo esc_attr( $quantity ) ; ?>' value='<?php echo esc_attr( $quantity ) ; ?>' <?php echo $readonly ; ?>/>

								<input type='hidden' id='hrr_refund_request_item_id' class='hrr_refund_request_item_id' value='<?php echo esc_attr( $item_id ) ; ?>'/>
								<input type='hidden' id='hrr_refund_request_price' class='hrr_refund_request_price' value='<?php echo esc_attr( $item_value ) ; ?>'/>
								<input type='hidden' id='hrr_refund_request_subtotal' class='hrr_refund_request_subtotal' value='<?php echo esc_attr( $total_refund ) ; ?>'/>
								<input type='hidden' id='hrr_refund_request_qty' class='hrr_refund_request_qty' value='<?php echo esc_attr( $quantity ) ; ?>'/>
								<?php
								if ( $tax_enabled ) :
									foreach ( $line_tax_data[ 'total' ] as $tax_id => $value ) :
										?>
										<input type="hidden" class="hrr_refund_request_tax" data-tax_id="<?php echo esc_attr( $tax_id ) ; ?>" value="<?php echo esc_attr( $value / $item[ 'qty' ] ) ; ?>">
										<?php
									endforeach ;
								endif ;
								?>
								<input type = 'hidden' id = 'hrr_refund_request_tax_value' class = 'hrr_refund_request_tax_value' value = '<?php echo esc_attr( $tax_value ) ; ?>'/>
							</td>
							<td class='hrr_refund_item_subtotal' data-title="<?php esc_attr_e( 'Refund total' , 'refund' ) ; ?>">
								<?php echo wc_price( $total_refund , $currency_code ) ; ?>
							</td>
							<?php do_action( 'hrr_forntend_item_column_end' , $check , $order , $item_id ) ; ?>
						</tr>
					<?php endforeach ; ?>
				</tbody>
				<tfoot>
					<?php
					$shipping_total = ( $tax_enabled ) ? ( $order->get_total_shipping() + $order->get_shipping_tax() ) : $order->get_total_shipping() ;
					$total_value    = $total_refund_amount + $shipping_total ;
					$colspan        = ( $partial_enabled ) ? 5 : 4 ;
					?>
					<tr align='center' class='hrr_refund_item_total'>
						<th scope="row" colspan="<?php echo esc_attr( $colspan ) ; ?>"><?php esc_html_e( 'Total' , 'refund' ) ; ?></th>
						<td data-title="<?php esc_attr_e( 'Total' , 'refund' ) ; ?>"><?php echo $order->get_formatted_order_total() ; ?></td>
						<th scope="row"><?php esc_html_e( 'Refund Total' , 'refund' ) ; ?></th>
						<td class='hrr_refund_item_total_value' data-title="<?php esc_attr_e( 'Refund Total' , 'refund' ) ; ?>">
							<?php echo wc_price( $total_value , $currency_code ) ; ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<hr>
		<div class='hrr-refund-form-field'>
			<table id='hrr_refund_form_table' class='shop_table hrr-request-form-table'>
				<tr>
					<th><?php echo esc_html( get_option( 'hrr_refund_request_reason' ) ) ; ?></th>
					<td>
						<select id='hrr_refund_general_reasons' name='hrr_refund_general_reasons'>
							<?php
							$reasons_array  = explode( ',' , get_option( 'hrr_refund_refund_reason' ) ) ;
							$reasons_array  = array_merge( $reasons_array , array( 'Others' ) ) ;
							$reasons_array  = array_filter( $reasons_array ) ;
							foreach ( $reasons_array as $value ) :
								?>
								<option value='<?php echo esc_attr( $value ) ; ?>'><?php echo esc_html( $value ) ; ?></option>
							<?php endforeach ; ?>
						</select>
					</td>
				</tr>
				<?php if ( 'yes' == $refund_method ) : ?>
					<tr>
						<th><?php echo esc_html( get_option( 'hrr_refund_refund_mode' ) ) ; ?></th>
						<td>
							<select id='hrr_refund_request_as' name='hrr_refund_request_as'>
								<?php
								$options = array( 'Amount' => 'Amount' ) ;
								$options = array_filter( apply_filters( 'hrr_request_mode_options' , $options ) ) ;
								foreach ( $options as $option ) :
									?>
									<option value="<?php echo esc_attr( $option ) ; ?>"><?php echo esc_html( $option ) ; ?></option>
								<?php endforeach ; ?>
							</select>
						</td>
					</tr>
					<?php 
				endif ;
				if ( apply_filters ( 'hrr_is_reason_field_enabled' , true ) ) : 
					?>
				<tr>
					<th><?php echo esc_html( get_option( 'hrr_refund_detail_request_reason' ) ) ; ?></th>
					<td><textarea id='hrr_refund_form_details' name='hrr_refund_form_details'></textarea></td>
				</tr>
								<?php 
								endif ; 
								do_action ( 'hrr_before_request_submit' ) ;
				?>
				<tr>
					<th></th>
					<td>
						<input type='hidden' id='hrr_refund_user_id' name='hrr_refund_user_id' value='<?php echo esc_attr( get_current_user_id() ) ; ?>'/>
						<input type='hidden' id='hrr_refund_order_id' name='hrr_refund_order_id' value='<?php echo esc_attr( $order_id ) ; ?>'/>
						<input type="hidden" id="hrr_refund_total" name='hrr_refund_total' value="<?php echo esc_attr( $total_value ) ; ?>">
						<input type='submit' id='hrr_refund_submit' value='<?php echo esc_attr( get_option( 'hrr_refund_submit_button' ) ) ; ?>'/>
						<p class="hrr-update-content">
							<img src="<?php echo esc_url( HRR_PLUGIN_URL . '/assets/images/update.gif' ); ?>" id='hrr_refund_img'>
						</p>
						<p id='hrr_refund_message' ><?php esc_html_e( 'Refund Request Send Successfully' , 'refund' ) ; ?></p>
					</td>
				</tr>
			</table>
		</div>
	</form>
</div>
<?php
do_action( 'hrr_after_frontend_refund_request_table' , $order ) ;
