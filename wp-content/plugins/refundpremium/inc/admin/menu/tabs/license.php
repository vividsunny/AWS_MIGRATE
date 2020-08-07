<?php
/**
 * License Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRR_License_Tab' ) ) {
	return new HRR_License_Tab() ;
}

/**
 * HRR_License_Tab.
 */
class HRR_License_Tab extends HRR_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'license' ;
		$this->label = esc_html__( 'License' , 'refund' ) ;
				$this->code  = 'fa-lock' ;

		parent::__construct() ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the license content.
	 */
	public function output_extra_fields() {
		?>
		<div class="hrr-license-verification-wrapper">
			<div class="hrr-license-verification-content" >
				<h2><?php esc_html_e( 'License Information' , 'refund' ) ; ?></h2>     
				<?php
				$license_handler_obj = new HRR_License_Handler( HRR_VERSION , HRR_PLUGIN_SLUG ) ;

				echo $license_handler_obj->show_activation_panel() ;
				?>
			</div>
		</div>    
		<?php
	}

}

return new HRR_License_Tab() ;
