<?php
/**
 * Help Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRR_Help_Tab' ) ) {
	return new HRR_Help_Tab() ;
}

/**
 * HRR_Help_Tab.
 */
class HRR_Help_Tab extends HRR_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'help' ;
		$this->label = esc_html__( 'Help' , 'refund' ) ;
				$this->code  = 'fa-life-ring' ;

		parent::__construct() ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the help content.
	 */
	public function output_extra_fields() {
		$support_site_url = '<a href="https://hoicker.com/support" target="_blank">' . esc_html__( 'Here' , 'refund' ) . '</a>' ;
		?>
		<div class="hrr-help-content">
			<h3><?php esc_html_e( 'Documentation' , 'refund' ) ; ?></h3>
			<p> <?php esc_html_e( 'Please check the documentation as we have lots of information there. The documentation file can be found inside the documentation folder which you will find when you unzip the downloaded zip file.' , 'refund' ) ; ?></a></p>
			<h3><?php esc_html_e( 'Contact Support' , 'refund' ) ; ?></h3>
			<p> 
							<?php 
							/* translators: %s: Site URL */
							echo sprintf( esc_html__( 'You can report bugs %s' , 'refund' ) , $support_site_url ) ; 
							?>
						</p>
		</div>
		<?php
	}

}

return new HRR_Help_Tab() ;
