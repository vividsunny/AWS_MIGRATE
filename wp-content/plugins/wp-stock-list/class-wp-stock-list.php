<?php
/**
 *
 * Plugin Name: Stock list Import
 * Plugin URI:
 * Description: Stock list Import
 * Version: 1.0
 * Author: Team Vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain:
 *
 * @package Wp_Stock_List
 */

define( 'STOCK_LIST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'STOCK_LIST_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Define Class Wp_Stock_List
 */
class Wp_Stock_List {

	/**
	 * Class construct function
	 */
	public function __construct() {
		// code.
		add_action( 'admin_enqueue_scripts', array( $this, 'stock_list_import_admin_style' ) );
		add_action( 'admin_menu', array( $this, 'stock_list_import_admin_menu' ) );
		add_action( 'init', array( &$this, 'stock_list_import_include_template_functions' ), 20 );
		add_action( 'upload_mimes', array( $this, 'stock_list_import_custom_upload_mimes' ) );

	}

	/**
	 * Add jQuery & CSS
	 */
	public function stock_list_import_admin_style() {

		wp_register_style( 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'admin/css/bootstrap.min.css', '', '1.0', '' );
		wp_register_style( 'invoice_style', plugin_dir_url( __FILE__ ) . 'admin/css/stock_list_style.css', '', '1.0', '' );
		wp_register_script( 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'admin/js/bootstrap.min.js', '', '1.0', false );

	}

	/**
	 * Add admin menu page
	 */
	public function stock_list_import_admin_menu() {
		// adding as main menu.
		add_menu_page( 'Stock list Import', 'Stock list Import', 'manage_options', 'stock_list_import', array( $this, 'stock_list_import_html' ), 'dashicons-upload', 6 );

		add_submenu_page('stock_list_import', __('Not In list'), __('Not In list'), 'manage_options', 'not_in_list', array( $this,'not_in_list_html') );

		// add_menu_page( 'Stock list Import', 'Stock list Import', 'manage_options', 'stock_list_import', array( $this, 'stock_list_import_html' ), 'dashicons-upload', 6 );
	}

	/**
	 * Add admin page setting
	 */
	public function stock_list_import_html() {
		require_once 'admin/html/stock-list-html.php';
	}

	public function not_in_list_html() {
		require_once 'admin/html/not-in-list-html.php';
	}

	/**
	 * Override any of the template functions
	 * with our own template functions file
	 */
	public function stock_list_import_include_template_functions() {
		include STOCK_LIST_PLUGIN_DIR . 'include/template-ajax.php';
	}

	/**
	 * Display total percentage and return result.
	 *
	 * @since  1.0
	 * @param  bool $total_row Display total row.
	 * @param  bool $end_pos Display last position.
	 * @return bool result
	 */
	public function stock_list_get_percent_complete( $total_row, $end_pos ) {
		return min( round( ( $end_pos / $total_row ) * 100, 2 ), 100 );
	}

	/**
	 * Display total record and return result.
	 *
	 * @since  1.0
	 * @param  bool $filename Display total record.
	 * @return bool result
	 */
	public function stock_list_count_total_file_row( $filename ) {
		$fp = file( $filename, FILE_SKIP_EMPTY_LINES );
		return count( $fp );
	}

	/**
	 * Add CSV file support
	 *
	 * @since  1.0
	 * @param  bool $mimes allow csv file.
	 * @return bool result
	 */
	public function stock_list_import_custom_upload_mimes( $mimes = array() ) {

		// Add a key and value for the CSV file type.
		$mimes['csv'] = 'text/csv';
		return $mimes;
	}

	/**
	 * Not in list items function
	 *
	 * @since  1.0
	 * @param  array $args CSV argumeny.
	 * @return bool result
	 */
	public function not_list_import_product_update_status( $args ){

		// vivid( $args );exit;

		if ( ! empty( $args ) ) {
			foreach ($args as $key => $value) {
				
				$stock = get_post_meta( $value->ID, '_stock', true );

				// $stock = 1;
				if( $stock <= 0 ){
					/* Set Unpublish */

					$available = get_post_meta( $value->ID, 'available', true );

					$today_date = date("Y-m-d");
					$after_2_week = date('Y-m-d', strtotime(' + 14 days'));

					// vivid( $available );
					// vivid( $today_date );
					// vivid( $after_2_week );exit;
					$newDateTime 	= date("Y-m-d H:i:s", strtotime($available));
					$today 			= date("Y-m-d H:i:s", strtotime($today_date));
					$to_date 		= date("Y-m-d H:i:s", strtotime($after_2_week));

					if(strtotime($newDateTime) >= strtotime($today) && strtotime($newDateTime) <= strtotime($to_date)){

						$update_result = $this->stock_list_published_product( $args );
						$result = $update_result;

					}else{

						$update_result = $this->stock_list_unpublished_product( $args );
						$result = $update_result;

					}
					
				}else{

					/* Set Publish */

					$update_result = $this->stock_list_published_product( $args );
					$result = $update_result;
				}

			}
		}

		return $result;
	}

	/**
	 * Change Product Status
	 *
	 * @since  1.0
	 * @param  array $args CSV argumeny.
	 * @return bool result
	 */
	public function stock_list_import_product_update_status( $args ) {

		$diamond_no = $args['order_id'];
		$status     = $args['status'];

		$diamond_no = substr($diamond_no, 0, -1);
		// $diamond_no = SEP192242;
		if ( ! empty( $diamond_no ) ) {
			$found_product = $this->stock_list_check_diamond_number_exists( $diamond_no );
			// vivid( $found_product );
			// $found_product = 36309;
			if ( ! empty( $found_product ) ) {
				
				foreach ($found_product as $key => $value) {
					# code...

					$stock = get_post_meta( $value->ID, '_stock', true );
					// $stock = 1;
					if( $stock < 0 ){

						/* Set Unpublish */
						$this->set_meta_for_items( $value->ID );
						$update_result = $this->stock_list_unpublished_product( $found_product );
						$result = $update_result;
					}else{

						/* Set Publish */
						$this->set_meta_for_items( $value->ID );
						$update_result = $this->stock_list_published_product( $found_product );
						$result = $update_result;
					}
					
				}
				
				/* Old Code */
				/*if ( 0 === $status ) {
					$result = $args['order_id'] . ' - Update diamond_no -> ' . $diamond_no . ' - #Unpublished Exists';
					// $update_result = $this->stock_list_unpublished_product( $found_product );
					// $this->stock_list_import_in_subsite( $args );
					
					$result = $update_result;
				} else {
					$result = $args['order_id'] . ' - Update diamond_no -> ' . $diamond_no . ' - #Published Exists';
					// $update_result = $this->stock_list_published_product( $found_product );
					// $this->stock_list_import_in_subsite( $args );
					$result = $update_result;
				}*/


			} else {
				$result = $args['order_id'] . ' - Update diamond_no -> ' . $diamond_no . ' - #Skip Exists';
			}
		} else {
			$result = $args['order_id'] . ' - Update diamond_no -> ' . $diamond_no . ' - #Diamond number is empty!';
		}

		return $result;
	}


	public function set_meta_for_items($product_id){

		$list_meta = 'item_in_list';
		$meta_value = 'in_list_file';
		if ( metadata_exists( 'post', $product_id, $list_meta ) ) {
			update_post_meta($product_id, $list_meta, $meta_value );  
		}else{
			update_post_meta($product_id, $list_meta, $meta_value );
		}
	}

	/**
	 * Update product status in subsite
	 *
	 * @since  1.0
	 * @param  array $args product array.
	 * @return array  result
	 */
	public function stock_list_import_in_subsite( $args ){

		$diamond_no = $args['order_id'];
		$status     = $args['status'];
		
		$diamond_no = substr($diamond_no, 0, -1);

		$subsites = get_sites();
      	unset($subsites[0]);

      	foreach( $subsites as $subsite ) {
      		$subsite_id = get_object_vars( $subsite )["blog_id"];
        	$subsite_name = get_blog_details( $subsite_id )->blogname;

          	switch_to_blog( $subsite_id );

			if ( ! empty( $diamond_no ) ) {
				$found_product = $this->stock_list_check_diamond_number_exists( $diamond_no );

				if ( ! empty( $found_product ) ) {

					if ( 0 === $status ) {

						$update_result = $this->stock_list_unpublished_product( $found_product );
						$result = $update_result;
					} else {

						$update_result = $this->stock_list_published_product( $found_product );
						$result = $update_result;
					}
				} else {

					$result = $args['order_id'] . ' - Number -> ' . $args['number'] . ' - #Skip Exists';

				}
			} else {

				$result = $args['order_id'] . ' - Number -> ' . $args['number'] . ' - #Diamond number is empty!';

			}

			restore_current_blog();
      	}


		return $result;
	}

	/**
	 * Check Product with diamond number
	 *
	 * @since  1.0
	 * @param  array $diamond_no Get product array.
	 * @return array  result
	 */
	public function stock_list_check_diamond_number_exists( $diamond_no ) {

		$args = array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'   => 'diamond_number',
					'value' => $diamond_no,
				),
				array(
					'key'   => 'Diamond Number',
					'value' => $diamond_no,
				),
			),

		);

		$query = new WP_Query( $args );

		$exist_posts = array();
		if ( $query->have_posts() ) {
			$exist_posts = $query->posts;
		}

		return $exist_posts;
	}

	/**
	 * Change product status to 'draft'
	 *
	 * @since  1.0
	 * @param  array $found_product product array.
	 * @return array  result
	 */
	public function stock_list_unpublished_product( $found_product ) {

		$status = array( 'draft' );
		foreach ( $found_product as $blog_value ) {

			$post_id    = $blog_value->ID;
			$post_title = $blog_value->post_title;

			$post = array(
				'ID'          => $post_id,
				'post_status' => 'draft',
			);
			wp_update_post( $post );
			$result = $post_title . ' - #Unpublished Product';

		}

		return $result;
	}

	/**
	 * Change product status to 'publish'
	 *
	 * @since  1.0
	 * @param  array $found_product product array.
	 * @return array  result
	 */
	public function stock_list_published_product( $found_product ) {

		$status = array( 'publish' );
		foreach ( $found_product as $blog_value ) {

			$post_id    = $blog_value->ID;
			$post_title = $blog_value->post_title;

			$post = array(
				'ID'          => $post_id,
				'post_status' => 'publish',
			);
			wp_update_post( $post );
			$result = $post_title . ' - #Published Product';

		}
		
		return $result;
	}

} /* End Class */

$wp_upload = new Wp_Stock_List();
