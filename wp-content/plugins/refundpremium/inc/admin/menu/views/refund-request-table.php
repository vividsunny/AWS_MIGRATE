<?php
/**
 * Refund Request Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

do_action( 'hrr_before_admin_refund_request_table' , $order , $request ) ;
?>
<div class='hrr-refund-request'>
	<form id='hrr-refund-form' method='post'>
		<div class='hrr-refund-form-field'>
			<div>
				<h2> 
									<?php 
									/* translators: %d: Refund Id */
									echo sprintf( esc_html__( 'Refund Request #%d' ) , $request->get_id() ) ; 
									?>
								</h2>
				<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $request->get_order_id() . '&action=edit' ) ) ; ?>" class="button-primary" title="<?php echo esc_attr( $title_attr ) ; ?>"><?php esc_html_e( 'View Order' , 'refund' ) ; ?></a>
			</div>
			<div class='hrr-refund-items-table'>
				<table class='shop_table' cellspacing="0" cellpadding="6" border='1'>
					<thead>
						<tr>
							<?php
							do_action( 'hrr_admin_item_column_header_start' , $order ) ;
							foreach ( $columns as $column ) :
								?>
								<th><?php echo esc_html( $column ) ; ?></th>
								<?php
							endforeach ;
							do_action( 'hrr_admin_item_column_header_end' , $order ) ;
							?>
						</tr>
					</thead>
					<tbody>
						<?php
												$tax_value     = 0 ;
												$total_refund_amount = 0 ;
						foreach ( $order->get_items() as $item_id => $item ) :
							if ( ! array_key_exists( $item_id , $line_items ) ) {
								continue ;
							}

							$quantity      = isset( $item[ 'quantity' ] ) ? $item[ 'quantity' ] : $item[ 'qty' ] ;
														$refunded_qty  = ( int ) wc_get_order_item_meta( $item_id , 'hr_refund_request_item_qty' ) ;
														$check         = ( $refunded_qty <= $quantity ) ;
							$item_value    = $item[ 'line_total' ] / $quantity ;
							$total_refund  = $item_value * $line_items[ $item_id ] ;
							$line_tax_data = maybe_unserialize( $item[ 'line_tax_data' ] ) ;
							if ( $tax_enabled ) {
								$item_and_tax_value = ( $item[ 'line_subtotal' ] + $item[ 'line_subtotal_tax' ] ) / $item[ 'qty' ] ;
								$tax_value          = $item[ 'line_subtotal_tax' ] / $item[ 'qty' ] ;
								$total_refund       = $item_and_tax_value * $line_items[ $item_id ] ;
							}
														$total_refund_amount += $total_refund ;
							?>
							<tr class='hrr_refund_items' data-order_item_id="<?php echo esc_attr( $item_id ) ; ?>">
								<?php do_action( 'hrr_admin_item_column_start' , $check , $order , $item_id ) ; ?>
								<td align='center'>
									<?php echo hrr_get_product_image( $item ) ; ?>
								</td>
								<td align='center'>
									<?php echo hrr_get_product_name( $item ) ; ?>
								</td>
								<td align='center'>
									<?php echo wc_price( $item_value , $request->get_currency() ) ; ?>
								</td>
								<td align='center'>
									<?php echo esc_html( $quantity ) ; ?>
								</td>
								<td align='center'>
									<?php echo wc_price( $item[ 'line_total' ] , $request->get_currency() ) ; ?>
								</td>
								<td align='center' class='hrr_refund_item_data'>
									<input type='number' id='hrr_refund_item_qty' class="hrr_refund_item_qty" min='1' 
										   max='<?php echo esc_attr( $line_items[ $item_id ] ) ; ?>' value='<?php echo esc_attr( $line_items[ $item_id ] ) ; ?>' <?php echo esc_html( $readonly ) ; ?>/>

									<input type='hidden' id='hrr_refund_request_item_id' class='hrr_refund_request_item_id' value='<?php echo esc_attr( $item_id ) ; ?>'/>
									<input type='hidden' id='hrr_refund_request_price' class='hrr_refund_request_price' value='<?php echo esc_attr( $item_value ) ; ?>'/>
									<input type='hidden' id='hrr_refund_request_subtotal' class='hrr_refund_request_subtotal' value='<?php echo esc_attr( $total_refund ) ; ?>'/>
									<input type='hidden' id='hrr_refund_request_qty' class='hrr_refund_request_qty' value='<?php echo esc_attr( $line_items[ $item_id ] ) ; ?>'/>
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
								<td align='center' class='hrr_refund_item_subtotal'>
									<?php echo wc_price( $total_refund , $request->get_currency() ) ; ?>
								</td>
								<?php do_action( 'hrr_admin_item_column_end' , $order , $item_id ) ; ?>
							</tr>
						<?php endforeach ; ?>
					</tbody>
					<tfoot>
											<?php
											$shipping_total = ( $tax_enabled ) ? ( $order->get_total_shipping() + $order->get_shipping_tax() ) : $order->get_total_shipping() ;
											$total_value    = $total_refund_amount + $shipping_total ;
											$refund_amount  = wc_price( $total_value , $request->get_currency() ) ;
											?>
						<tr align='center' class='hrr_refund_item_total'>
							<th scope="row" colspan="<?php echo esc_attr( $colspan ) ; ?>" ><?php echo esc_html__( 'Total' , 'refund' ) ; ?></th>
							<td><?php echo $order->get_formatted_order_total() ; ?></td>
							<th scope="row"><?php esc_html_e( 'Refund Total' , 'refund' ) ; ?></th>
							<td class='hrr_refund_item_total_value'>
								<?php echo wc_price( $total_value , $request->get_currency() ) ; ?>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class='hrr_refund_after_table'>
				<div class='hrr_refund_restock'>
					<p>
						<span>
							<input id="hrr_restock_products" type="checkbox">
							<label for="hrr_restock_products"><?php esc_html_e( 'Restock selected items?' , 'refund' ); ?></label>
						</span>
					</p>
				</div>
				<div class="hrr_refund_button">
					<input type="hidden" id="hrr_order_id" value="<?php echo esc_attr( $request->get_order_id() ) ; ?>">
					<input type="hidden" id="hrr_post_id" value="<?php echo esc_attr( $request->get_id() ) ; ?>">
					<input type="hidden" id="hrr_refund_total" value="<?php echo esc_attr( $total_value ) ; ?>">
					<div class="refund-actions">
												<button  id="hrr_refund_manual_refund_button" data-paytype="manual" class="hrr_refund_request_button button button-primary tips" 
												<?php 
												echo $manual_tip ;
												echo esc_attr($disable_button) ; 
												?>
												>
							<?php 
														/* translators: %s:Refund amount */
														echo sprintf( esc_html__( 'Refund %s manually' , 'refund' ) , $refund_amount ) ; 
							?>
						</button>
						<button id="hrr_refund_api_refund_button" data-paytype="gateway" class="hrr_refund_request_button tips button button-primary" 
						<?php 
						echo $automatic_tip ;
						echo esc_attr($disable_button) ; 
						?>
						>
							<?php 
														/* translators: %s:Refund amount */
														echo sprintf( esc_html__( 'Refund %1$s via %2$s' , 'refund' ) , $refund_amount , $gateway_name ) ; 
							?>
						</button>
						<?php do_action( 'hrr_refund_available_refund_via' , $refund_amount , $request->get_id() , $request->get_order_id() , $disable_button ) ; ?>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<?php
do_action( 'hrr_after_admin_refund_request_table' , $order , $request ) ;
