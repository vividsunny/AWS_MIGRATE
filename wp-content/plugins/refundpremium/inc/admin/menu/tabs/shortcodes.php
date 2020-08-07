<?php

/**
 * Shortcodes Tab.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( class_exists( 'HRR_Shortcodes_Tab' ) ) {
	return new HRR_Shortcodes_Tab() ;
}

/**
 * HRR_Shortcodes_Tab.
 */
class HRR_Shortcodes_Tab extends HRR_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'shortcodes' ;
		$this->label = esc_html__( 'Shortcodes' , 'refund' ) ;
				$this->code  = 'fa-code' ;

		parent::__construct() ;
	}

	/**
	 * Output the settings buttons.
	 */
	public function output_buttons() {
		
	}

	/**
	 * Output the shortcodes table.
	 */
	public function output_extra_fields() {
		$shortcodes_info = array(
			'{hrr-refund.date}'         => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Date' , 'refund' )
			) ,
			'{hrr-refund.time}'         => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Time' , 'refund' )
			) ,
			'{hrr-refund.orderid}'      => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Refund Requested Order Id' , 'refund' )
			) ,
			'{hrr-refund.sitename}'     => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Site Name' , 'refund' )
			) ,
			'{hrr-refund.newstaus}'     => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Site Name' , 'refund' )
			) ,
			'{hrr-refund.oldstaus}'     => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Site Name' , 'refund' )
			) ,
			'{hrr-refund.requestid}'    => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Site Name' , 'refund' )
			) ,
			'{hrr-refund.customername}' => array(
				'where' => esc_html__( 'Email' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Site Name' , 'refund' )
			) ,
			'[hrr-refund-requests]'     => array(
				'where' => esc_html__( 'Pages' , 'refund' ) ,
				'usage' => esc_html__( 'Displaying Refund Request Table' , 'refund' )
			) ,
				) ;

		//Shortcodes layout.
		include_once HRR_ABSPATH . '/inc/admin/menu/views/shortcodes-table.php' ;
	}

}

return new HRR_Shortcodes_Tab() ;
