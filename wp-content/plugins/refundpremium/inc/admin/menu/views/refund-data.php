<?php
/**
 * Refund Request View
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly
}
?>
<div class = 'hrr-refund-form-field'>
	<div class='hrr-data-type'>
		<span>
			<?php echo esc_html__( 'Type' , 'refund' ) . ':' ; ?>
		</span>
		<span>
			<b><?php echo esc_html( $request->get_type() ) ; ?> </b>
		</span>
	</div>
	<div class='hrr-data-request-as'>
		<span>
			<?php echo esc_html__( 'Request as' , 'refund' ) . ':' ; ?>
		</span>
		<span>
			<b><?php echo esc_html( $request->get_mode() ) ; ?> </b>
		</span>
	</div>
	<div class='hrr-data-customer-name'>
		<span>
			<?php echo esc_html__( 'Customer Name' , 'refund' ) . ':' ; ?>
		</span>
		<span>
			<b><?php echo is_object( $request->get_user() ) ? esc_html( $request->get_user()->display_name ) : esc_html_e( 'User details not available' ) ; ?> </b>
		</span>
	</div>
	<div class='hrr-data-customer-email'>
		<span>
			<?php echo esc_html__( 'Customer Email' , 'refund' ) . ':' ; ?>
		</span>
		<span>
			<b><?php echo is_object( $request->get_user() ) ? esc_html( $request->get_user()->user_email ) : esc_html_e( 'User details not available' ) ; ?> </b>
		</span>
	</div>
</div>
	<?php
 
