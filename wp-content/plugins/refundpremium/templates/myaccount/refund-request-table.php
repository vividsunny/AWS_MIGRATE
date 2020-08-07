<?php
/**
 * Refund Request Table
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}

$columns_array = apply_filters( 'hrr_request_table_cloumns' , array(
	'hrr_refund_request_id' => 'ID' ,
	'hrr_refund_order_id'   => 'Order id' ,
	'hrr_refund_status'     => 'Refund request status' ,
	'hrr_refund_type'       => 'Refund Type' ,
	'hrr_refund_mode'       => 'Refund request as' ,
	'hrr_refund_amount'     => 'Refund Total' ,
	'hrr_refund_view'       => 'View'
		)
		) ;
?>
<h3><?php echo esc_html( get_option( 'hrr_table_request_title_label' , 'Refund Requests' ) ) ; ?></h3>
<div>
	<span>
		<select id='hrr_pagination'>
			<?php
			for ( $k = 1 ; $k <= 20 ; $k ++ ) {
				if ( 10 == $k ) {
					echo '<option value="' . esc_attr( $k ) . '" selected="selected">' . esc_html( $k ) . '</option>' ;
				} else {
					echo '<option value="' . esc_attr( $k ) . '">' . esc_html( $k ) . '</option>' ;
				}
			}
			?>
		</select>
	</span>
	<label><?php esc_html_e( 'Search' , 'refund' ) ; ?></label>
	<input type='text' name='hrr_search' id='hrr_search'>
</div>
<br>
<?php do_action( 'hrr_before_request_table' , $request_data ) ; ?>

<table class='hrr-request-table table' data-page-size='10' data-filter='#hrr_search' data-filter-minimum='1'>
	<thead>
		<tr>
			<?php foreach ( $columns_array as $key => $label ) : ?>
				<th><?php echo get_option( $key , $label ) ; ?> </th>
			<?php endforeach ; ?>
		</tr>
	</thead>
	<?php
	if ( hrr_check_is_array( $request_data ) ) :
		foreach ( $request_data as $postid ) :
			$request = hrr_get_request( $postid ) ;
			?>
			<tbody>
				<tr>
					<td data-title="<?php esc_attr_e( 'Id' , 'refund' ) ; ?>">
						<?php echo esc_html( $request->get_id() ) ; ?>
					</td>
					<td data-title="<?php esc_attr_e( 'Order Number' , 'refund' ) ; ?>">
						<?php echo '<a href=' . esc_url( admin_url( 'post.php?post=' . $request->get_order_id() . '&action=edit' ) ) . '>#' . esc_html( $request->get_order_id() ) . '</a>' ; ?>
					</td>
					<td data-title="<?php esc_attr_e( 'Status' , 'refund' ) ; ?>">
						<?php echo hrr_display_status( $request->get_status() ) ; ?>
					</td>
					<td data-title="<?php esc_attr_e( 'Type' , 'refund' ) ; ?>">
						<?php echo esc_html( $request->get_type() ) ; ?>
					</td>
					<td data-title="<?php esc_attr_e( 'Mode' , 'refund' ) ; ?>">
						<?php echo esc_html( $request->get_mode() ) ; ?>
					</td>
					<td data-title="<?php esc_attr_e( 'Amount' , 'refund' ) ; ?>">
						<?php echo wc_price( $request->get_total() , $request->get_currency() ) ; ?>
					</td>
					<td data-title="<?php esc_attr_e( 'View' , 'refund' ) ; ?>">
						<?php HRR_Myaccount_Handler::button_in_request_table( $request->get_id() ) ; ?>
					</td>
				</tr>
			</tbody>
			<?php
		endforeach ;
	else :
		?>
		<tr>
			<td colspan="8">
				<?php esc_html_e( 'No Refund Requests Send' , 'refund' ) ; ?>
			</td>
		</tr>
	<?php endif ; ?>
</table>

<?php
do_action( 'hrr_after_request_table' , $request_data ) ;
