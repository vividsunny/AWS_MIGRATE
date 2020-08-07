<?php
/**
 * Display Shortcodes.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}
?>
<div class="hrr-shortcodes-wrapper">
	<table class="hrr-shortcodes-info-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Shortcode' , 'refund' ) ; ?></th>
				<th><?php esc_html_e( 'Context where shortcode is valid' , 'refund' ) ; ?></th>
				<th><?php esc_html_e( 'Purpose' , 'refund' ) ; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if ( hrr_check_is_array( $shortcodes_info ) ) :
				foreach ( $shortcodes_info as $shortcode => $s_info ) :
					?>
					<tr>
						<td><?php echo esc_html( $shortcode ) ; ?></td>
						<td><?php echo esc_html( $s_info[ 'where' ] ) ; ?></td>
						<td><?php echo esc_html( $s_info[ 'usage' ] ) ; ?></td>
					</tr>
					<?php
				endforeach ;
			endif ;
			?>
		</tbody>
	</table>
	<?php echo do_action( 'hrr_after_shortcodes_content' ) ; ?>
</div>
<?php
