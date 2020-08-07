<?php
/**
 * Compatability Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRR_Compatability_Tab' ) ) {
	return new HRR_Compatability_Tab() ;
}

/**
 * HRR_Compatability_Tab.
 */
class HRR_Compatability_Tab extends HRR_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'compatability' ;
		$this->label = esc_html__( 'Compatability' , 'refund' ) ;
				$this->code  = 'fa-plug' ;

		parent::__construct() ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the Compatability content.
	 */
	public function output_extra_fields() {
		?>
		<div class='hrr-compatiblity-wrapper'>
			<div class="hrr-compatible" >
				<div class="hrr-compatible-img">
					<img src="<?php echo esc_url( HRR_PLUGIN_URL . '/assets/images/wallet.png' ) ; ?>">
				</div>
				<div class="hrr-compatible-title">
					<p><?php esc_html_e( 'Wallet' , 'refund' ) ; ?></p>
				</div>
				<div class="hrr-compatible-buynow">
					<a href="https://hoicker.com/product/wallet"><?php esc_html_e( 'Buy Now' , 'refund' ) ; ?></a>
				</div>
			</div>
			<?php echo do_action( 'hrr_compatiblity_premium_info' ); ?>  
		</div>
		<?php
	}

}

return new HRR_Compatability_Tab() ;
