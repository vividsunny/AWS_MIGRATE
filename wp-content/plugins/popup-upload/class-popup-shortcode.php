<?php
/**
 * 
 */
class popupShortCode{
	
	function __construct(){
		//master product
		add_shortcode( 'master_product_list' , array( $this, 'sc_master_product' ) );
	}

	public function sc_master_product(){
		$path = ketoGetLocalPath( '/shortcodes/master-product-list.php' );
		ob_start();
		if ( file_exists( $path ) ) include $path;
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

}
$popupshortcode = new popupShortCode();
?>