<?php
/**
 *
 * Plugin Name: Video Games Import
 * Plugin URI:
 * Description: Video Games Import
 * Version: 1.0
 * Author: Team Vivid
 * Author URI: http://vividwebsolutions.in
 * Text Domain:
 *
 * @package Wp_Video_Games
 */

define( 'VIDEO_GAMES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VIDEO_GAMES_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Define Class Wp_Video_Games
 */
class Wp_Video_Games {

	/**
	 * Class construct function
	 */
	public function __construct() {
		// code.
		$blog_id = get_current_blog_id();
		if( $blog_id === 1 ){
			add_action( 'admin_menu', array( $this, 'video_games_import_admin_menu' ) );	
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'video_games_import_admin_style' ) );
		
		add_action( 'init', array( &$this, 'video_games_import_include_template_functions' ), 20 );
		add_action( 'upload_mimes', array( $this, 'video_games_import_custom_upload_mimes' ) );

		add_action('init',array( $this , 'setup_videolog_dir' ) );

		// 1. Add custom field input @ Product Data > Variations > Single Variation
		add_action( 'woocommerce_variation_options_pricing', array( $this, 'add_custom_field_to_variations' ), 10, 3 );

		// 2. Save custom field on product variation save
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_custom_field_variations' ), 10, 2 );
 
	}

	/**
	 * Add jQuery & CSS
	 */
	public function video_games_import_admin_style() {

		wp_register_style( 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'admin/css/bootstrap.min.css', '', '1.0', '' );
		wp_register_style( 'invoice_style', plugin_dir_url( __FILE__ ) . 'admin/css/video_list_style.css', '', '1.0', '' );
		wp_register_script( 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'admin/js/bootstrap.min.js', '', '1.0', false );

	}

	/**
	 * Add admin menu page
	 */
	public function video_games_import_admin_menu() {
		// adding as main menu.
		add_menu_page( 'Video Games Import', 'Video Games Import', 'manage_options', 'video_games_import', array( $this, 'video_games_import_html' ), 'dashicons-upload', 6 );

	}

	/**
	 * Add admin page setting
	 */
	public function video_games_import_html() {
		require_once 'admin/html/video-list-html.php';
	}
	

	/**
	 * Override any of the template functions
	 * with our own template functions file
	 */
	public function video_games_import_include_template_functions() {
		include VIDEO_GAMES_PLUGIN_DIR . 'include/template-ajax.php';
	}

	/**
	 * Display total percentage and return result.
	 *
	 * @since  1.0
	 * @param  bool $total_row Display total row.
	 * @param  bool $end_pos Display last position.
	 * @return bool result
	 */
	public function video_games_get_percent_complete( $total_row, $end_pos ) {
		return min( round( ( $end_pos / $total_row ) * 100, 2 ), 100 );
	}

	/**
	 * Display total record and return result.
	 *
	 * @since  1.0
	 * @param  bool $filename Display total record.
	 * @return bool result
	 */
	public function video_games_count_total_file_row( $filename ) {
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
	public function video_games_import_custom_upload_mimes( $mimes = array() ) {

		// Add a key and value for the CSV file type.
		$mimes['csv'] = 'text/csv';
		return $mimes;
	}

	public static function vg_plugin_dir() {
        return plugin_dir_path(__FILE__);
    }

    public static function vg_plugin_url() {
        return plugin_dir_url(__FILE__);
    }

	public function setup_videolog_dir(){
        $dir = $this->vg_plugin_dir();
        $plugin_url = $this->vg_plugin_url();
        $log_dir = $dir."log/" ;
        if ( ! is_dir( $log_dir ) ) {
            wp_mkdir_p( $log_dir, 0777 );
            if ( $file_handle = @fopen( trailingslashit( $log_dir ) .'video_log.log', 'w' ) ) {
                fwrite( $file_handle, 'testing' );
                fclose( $file_handle );
            }
        }

    }
 	
 	public function vg_import_log($str) {

        $d = date("j-M-Y H:i:s");
        $dir = $this->vg_plugin_dir();
        $plugin_url = $this->vg_plugin_url();
        $log_dir = $dir."log/" ;
        error_log('['.$d.']'. $str.PHP_EOL, 3, $log_dir."/video_log.log");
    }

 	// 1. Add custom field input @ Product Data > Variations > Single Variation
	public function add_custom_field_to_variations( $loop, $variation_data, $variation ) {
		
		/* Gamestop */
		woocommerce_wp_text_input( array(
			'id' => 'gamestop_price_' . $loop,
			'class' => 'short',
			'label' => __( 'Gamestop price' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'gamestop_price', true )
			)
		);

		woocommerce_wp_text_input( array(
			'id' => 'gamestop_trade_price_' . $loop,
			'class' => 'short',
			'label' => __( 'Gamestop trade price', 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'gamestop_trade_price', true )
			)
		);

		/* Loose */
		woocommerce_wp_text_input( array(
			'id' => 'retail_loose_buy_' . $loop,
			'class' => 'short',
			'label' => __( 'Retail loose buy' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'retail_loose_buy', true )
			)
		);

		woocommerce_wp_text_input( array(
			'id' => 'retail_loose_sell_' . $loop,
			'class' => 'short',
			'label' => __( 'Retail loose sell', 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'retail_loose_sell', true )
			)
		);

		/* CIB */
		woocommerce_wp_text_input( array(
			'id' => 'retail_cib_buy_' . $loop,
			'class' => 'short',
			'label' => __( 'Retail cib buy' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'retail_cib_buy', true )
			)
		);

		woocommerce_wp_text_input( array(
			'id' => 'retail_cib_sell_' . $loop,
			'class' => 'short',
			'label' => __( 'Retail cib sell', 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'retail_cib_sell', true )
			)
		);

		/* New */
		woocommerce_wp_text_input( array(
			'id' => 'retail_new_buy_' . $loop,
			'class' => 'short',
			'label' => __( 'Retail new buy' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'retail_new_buy', true )
			)
		);

		woocommerce_wp_text_input( array(
			'id' => 'retail_new_sell_' . $loop,
			'class' => 'short',
			'label' => __( 'Retail new sell', 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'retail_new_sell', true )
			)
		);


		/* UPC */
		woocommerce_wp_text_input( array(
			'id' => 'upc_' . $loop,
			'class' => 'short',
			'label' => __( 'UPC' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'upc', true )
			)
		);

		/* sales-volume */
		woocommerce_wp_text_input( array(
			'id' => 'sales_volume_' . $loop,
			'class' => 'short',
			'label' => __( 'Sales volume', 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'sales_volume', true )
			)
		);

		/* Genre */
		woocommerce_wp_text_input( array(
			'id' => 'genre_' . $loop,
			'class' => 'short',
			'label' => __( 'Genre' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'genre', true )
			)
		);

		/* asin */
		woocommerce_wp_text_input( array(
			'id' => 'asin_' . $loop,
			'class' => 'short',
			'label' => __( 'asin' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'asin', true )
			)
		);

		/* epid */
		woocommerce_wp_text_input( array(
			'id' => 'epid_' . $loop,
			'class' => 'short',
			'label' => __( 'epid' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-first',
			'value' => get_post_meta( $variation->ID, 'epid', true )
			)
		);

		/* epid */
		woocommerce_wp_text_input( array(
			'id' => 'release_date_' . $loop,
			'class' => 'short',
			'label' => __( 'Release date' , 'woocommerce' ),
			'wrapper_class' => 'form-row form-row-last',
			'value' => get_post_meta( $variation->ID, 'release_date', true )
			)
		);

	}

	public function save_custom_field_variations( $variation_id, $i ) {

		$gamestop_price 		= $_POST['gamestop_price_0'];
		$gamestop_trade_price	= $_POST['gamestop_trade_price_0'];
		$retail_loose_buy		= $_POST['retail_loose_buy_0'];
		$retail_loose_sell		= $_POST['retail_loose_sell_0'];
		$retail_cib_buy		    = $_POST['retail_cib_buy_0'];
		$retail_cib_sell		= $_POST['retail_cib_sell_0'];
		$retail_new_buy		 	= $_POST['retail_new_buy_0'];
		$retail_new_sell		= $_POST['retail_new_sell_0'];
		$upc 					= $_POST['upc_0'];
		$sales_volume		 	= $_POST['sales_volume_0'];
		$genre		 			= $_POST['genre_0'];
		$asin 					= $_POST['asin_0'];
		$epid		 			= $_POST['epid_0'];
		$release_date 			= $_POST['release_date_0'];

		if ( isset( $gamestop_price ) ) 
			update_post_meta( $variation_id, 'gamestop_price', esc_attr( $gamestop_price ) );

		if ( isset( $gamestop_trade_price ) ) 
			update_post_meta( $variation_id, 'gamestop_trade_price', esc_attr( $gamestop_trade_price ) );

		if ( isset( $retail_loose_buy ) ) 
			update_post_meta( $variation_id, 'retail_loose_buy', esc_attr( $retail_loose_buy ) );

		if ( isset( $retail_loose_sell ) ) 
			update_post_meta( $variation_id, 'retail_loose_sell', esc_attr( $retail_loose_sell ) );

		if ( isset( $retail_cib_buy ) ) 
			update_post_meta( $variation_id, 'retail_cib_buy', esc_attr( $retail_cib_buy ) );

		if ( isset( $retail_cib_sell ) ) 
			update_post_meta( $variation_id, 'retail_cib_sell', esc_attr( $retail_cib_sell ) );

		if ( isset( $retail_new_buy ) ) 
			update_post_meta( $variation_id, 'retail_new_buy', esc_attr( $retail_new_buy ) );

		if ( isset( $retail_new_sell ) ) 
			update_post_meta( $variation_id, 'retail_new_sell', esc_attr( $retail_new_sell ) );

		if ( isset( $upc ) ) 
			update_post_meta( $variation_id, 'upc', esc_attr( $upc ) );

		if ( isset( $sales_volume ) ) 
			update_post_meta( $variation_id, 'sales_volume', esc_attr( $sales_volume ) );

		if ( isset( $genre ) ) 
			update_post_meta( $variation_id, 'genre', esc_attr( $genre ) );

		if ( isset( $asin ) ) 
			update_post_meta( $variation_id, 'asin', esc_attr( $asin ) );

		if ( isset( $epid ) ) 
			update_post_meta( $variation_id, 'epid', esc_attr( $epid ) );

		if ( isset( $release_date ) ) 
			update_post_meta( $variation_id, 'release_date', esc_attr( $release_date ) );

	}

	public function import_video_games( $args ) {

		// vivid( $args ); 
		$product_name = $args['product-name'];


		$fount_post = post_exists($product_name,'','','product');

		$loose_price 	= $args['loose-price'];
		$cib_price 		= $args['cib-price'];
		$new_price 		= $args['new-price'];
		$box_only_price = $args['box-only-price'];
		$manual_price 	= $args['manual-only-price'];

		$console_name 	= $args['console-name'];
		$id 			= $args['id'];


		$loose_sku 		= 'VG-'.$console_name.'-'.$id.'-L';
		$cib_sku 		= 'VG-'.$console_name.'-'.$id.'-C';
		$new_sku 		= 'VG-'.$console_name.'-'.$id.'-N';
		$box_only_sku 	= 'VG-'.$console_name.'-'.$id.'-B';
		$menual_sku 	= 'VG-'.$console_name.'-'.$id.'-H';

		$meta_arr = array(
			'loose' => array( 
				'_price' => $loose_price,
				'_regular_price' => $loose_price,
				'_sku' => $loose_sku
			),

			'complete-in-box' => array( 
				'_price' => $cib_price,
				'_regular_price' => $cib_price,
				'_sku' => $cib_sku
			),

			'new' => array( 
				'_price' => $new_price,
				'_regular_price' => $new_price,
				'_sku' => $new_sku
			),

			'box-only' => array( 
				'_price' => $box_only_price,
				'_regular_price' => $box_only_price,
				'_sku' => $box_only_sku
			),

			'manual-only' => array( 
				'_price' => $manual_price,
				'_regular_price' => $manual_price,
				'_sku' => $menual_sku
			),
		);

		$product_data = array(
	        'author'        => '', // optional
	        'title'         => $product_name,
	        'content'       => '<p>This is the product content <br>A very nice product, soft and clear…<p>',
	        'excerpt'       => 'The product short description…',
	        // 'regular_price' => '16', // product regular price
	        // 'sale_price'    => '', // product sale price (optional)
	        // 'stock'         => '10', // Set a minimal stock quantity
	        // 'image_id'      => '', // optional
	        // 'gallery_ids'   => array(), // optional
	        'sku'           => 'VG-'.$console_name.'-'.$id, // optional
	        // 'tax_class'     => '', // optional
	        // 'weight'        => '', // optional
	        // For NEW attributes/values use NAMES (not slugs)
	        'attributes'    => array(
	            'Video Game'   =>  array( 'Loose', 'Complete-In-Box', 'New', 'Box Only','Manual Only' ),
	        ),

	        'variations' => array( 
	            'video-game' => array(
	                'attributes' => array( 'Loose', 'Complete-In-Box', 'New', 'Box Only','Manual Only' ), 
	                'price' => array( $loose_price, $cib_price, $new_price, $box_only_price, $manual_price ), 
	                'sku' => array( $loose_sku, $cib_sku, $new_sku, $box_only_sku, $menual_sku ), 
	            ),
	        ),
			'child_cat' 			=> $args['console-name'],
			'gamestop_price' 		=> $args['gamestop-price'],
			'gamestop_trade_price' 	=> $args['gamestop-trade-price'],
			'retail_loose_buy' 		=> $args['retail-loose-buy'],
			'retail_loose_sell'		=> $args['retail-loose-sell'],
			'retail_cib_buy' 		=> $args['retail-cib-buy'],
			'retail_cib_sell' 		=> $args['retail-cib-sell'],
			'retail_new_buy' 		=> $args['retail-new-buy'],
			'retail_new_sell' 		=> $args['retail-new-sell'],
			'upc' 					=> $args['upc'],
			'sales_volume' 			=> $args['sales-volume'],
			'genre' 				=> $args['genre'],
			'asin' 					=> $args['asin'],
			'epid' 					=> $args['epid'],
			'release_date' 			=> $args['release-date'],
	    );
		
		$all_post = $this->search_post_exist( $product_data );

		// vivid( $product_data );die();
		if( empty( $all_post ) ){
			$this->create_product_variation( $product_data );
			$this->import_subsite_product_variation( $args , $product_data, $meta_arr );
	      	$result = $product_name.' - #Inserted';
	    }else{
	    	$product = wc_get_product( $all_post[0]->ID );
	    	$available_variations = $product->get_available_variations();

	    	// vivid( $available_variations );
	    	foreach ($available_variations as $key => $value){ 
	    		$variation_post_id = $value['variation_id'];
	    		$this->add_variation_meta( $variation_post_id, $product_data, $meta_arr, $value);
			}

			$product->save();
			update_post_meta( $all_post[0]->ID, '_stock_status', 'instock' );
			update_post_meta($all_post[0]->ID, '_sku', $product_data['sku']);
			$this->import_subsite_product_variation( $args , $product_data, $meta_arr );
	    	$result = $all_post[0]->ID.' - '.$all_post[0]->post_title.' - #Update';
	    }
	   
		return $result;
	}

	public function add_variation_meta( $variation_post_id, $product_data, $meta_array, $value ){

		if( $value['attributes']['attribute_pa_video-game'] == 'loose'){
			foreach ($meta_array as $key => $value) {
					if( $key == 'loose'){
						update_post_meta($variation_post_id, '_price', $value['_price']);
	    				update_post_meta($variation_post_id, '_regular_price', $value['_regular_price']);
	    				update_post_meta($variation_post_id, '_sku', $value['_sku']);
	    				$this->update_variation_data( $variation_post_id, $product_data );
					}
					
			}

		}

		if( $value['attributes']['attribute_pa_video-game'] == 'complete-in-box'){
			foreach ($meta_array as $key => $value) {
					if( $key == 'complete-in-box'){
						update_post_meta($variation_post_id, '_price', $value['_price']);
	    				update_post_meta($variation_post_id, '_regular_price', $value['_regular_price']);
	    				update_post_meta($variation_post_id, '_sku', $value['_sku']);
	    				$this->update_variation_data( $variation_post_id, $product_data );
					}
					
			}
		}

		if( $value['attributes']['attribute_pa_video-game'] == 'new'){
			foreach ($meta_array as $key => $value) {
					if( $key == 'new'){
						update_post_meta($variation_post_id, '_price', $value['_price']);
	    				update_post_meta($variation_post_id, '_regular_price', $value['_regular_price']);
	    				update_post_meta($variation_post_id, '_sku', $value['_sku']);
	    				$this->update_variation_data( $variation_post_id, $product_data );
					}
					
			}
		}

		if( $value['attributes']['attribute_pa_video-game'] == 'box-only'){
			foreach ($meta_array as $key => $value) {
					if( $key == 'box-only'){
						update_post_meta($variation_post_id, '_price', $value['_price']);
	    				update_post_meta($variation_post_id, '_regular_price', $value['_regular_price']);
	    				update_post_meta($variation_post_id, '_sku', $value['_sku']);
	    				$this->update_variation_data( $variation_post_id, $product_data );
					}
					
			}
		}

		if( $value['attributes']['attribute_pa_video-game'] == 'manual-only'){
			foreach ($meta_array as $key => $value) {
					if( $key == 'manual-only'){
						update_post_meta($variation_post_id, '_price', $value['_price']);
	    				update_post_meta($variation_post_id, '_regular_price', $value['_regular_price']);
	    				update_post_meta($variation_post_id, '_sku', $value['_sku']);
	    				$this->update_variation_data( $variation_post_id, $product_data );
					}
					
			}
		}

	}

	public function import_subsite_product_variation( $args, $product_data, $meta_arr ){

		$subsites = get_sites();
		

		foreach( $subsites as $subsite ) {
			$subsite_id = get_object_vars( $subsite )["blog_id"];
            $subsite_name = get_blog_details( $subsite_id )->blogname;

            switch_to_blog( $subsite_id );
            
            $all_post = $this->search_post_exist( $product_data );

            if( empty( $all_post ) ){
            	// $this->insert_taxonomy_subsite( $product_data );
				$this->create_product_variation( $product_data );
		      	$result = $product_name.' - #Inserted';
		    }else{
		    	// $this->insert_taxonomy_subsite( $product_data );
		    	$product = wc_get_product( $all_post[0]->ID );
	    		$available_variations = $product->get_available_variations();

	    		foreach ($available_variations as $key => $value){ 
	    			$variation_post_id = $value['variation_id'];

	    			$this->add_variation_meta( $variation_post_id, $product_data, $meta_arr, $value);
	    		}
	    		$product->save();
				update_post_meta( $all_post[0]->ID, '_stock_status', 'instock' );
				update_post_meta($all_post[0]->ID, '_sku', $product_data['sku']);
		    }
            restore_current_blog();
        }

		
	}

	public function insert_taxonomy_subsite( $data ){
		$product_attributes = array();

	    foreach( $data['attributes'] as $key => $terms ){
	    	
			//Save variation, returns variation id
			//$variation->save();
	        $taxonomy = wc_attribute_taxonomy_name($key); // The taxonomy slug
	        $attr_label = ucfirst($key); // attribute label name
	        $attr_name = ( wc_sanitize_taxonomy_name($key)); // attribute slug

	        // NEW Attributes: Register and save them
	        if( ! taxonomy_exists( $attr_name ) ){
	            $this->save_product_attribute_from_name( $attr_name, $attr_label );
	            flush_rewrite_rules();
	            //wp_reset_query();
	        	// If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
			        
	        }
	        

	        $product_attributes[$taxonomy] = array (
	            'name'         => $taxonomy,
	            'value'        => '',
	            'position'     => '',
	            'is_visible'   => 1,
	            'is_variation' => 1,
	            'is_taxonomy'  => 1
	        );

	        foreach( $terms as $value ){
	            
	            $term_name = ucfirst($value);
	            $term_slug = sanitize_title($value);

		        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug;
	     		       
	            // Check if the Term name exist and if not we create it.
	            if( ! term_exists( $term_name, $taxonomy ) ){
	            	// echo "taxonomy = $taxonomy";
	            	// echo " --iff-- $term_name = $taxonomy <br/>";
	             	$inserted_term = wp_insert_term( $term_name, $taxonomy, array('slug' => $term_slug ) ); // Create the term
	            }

	            // Set attribute values
	            wp_set_object_terms( $product_id, $term_name, $taxonomy, true );
	            
	        }

	    }
	    
	    update_post_meta( $product_id, '_product_attributes', $product_attributes );
	}

	public function search_post_exist( $product_data ){

		$args = array(
			    'post_type' => 'product',
			    'posts_per_page' => -1,
			    's' 				=> $product_data['title'],
			    'meta_key' 			=> '_sku', // (string) - Custom field key.
    			'meta_value' 		=> $product_data['sku'],
			    'tax_query' => array(
			        array(
			            'taxonomy' => 'product_cat',
			            'field'    => 'slug',
			            'terms'    => $product_data['child_cat'],
			        ),
			    ),
			);
		$query = new WP_Query( $args );
		$all_post = $query->posts;

		return $all_post;
	}

	public function add_child_term( $parent_term_name, $child_term_name ){

			$parent_term = term_exists( $parent_term_name ); // array is returned if taxonomy is given
			// $parent_term_id = $parent_term['term_id'];


			$check_child_term = term_exists( $child_term_name ); 

			if ( $check_child_term ) 
        		return $check_child_term;

			// get numeric term id
			$child_term = wp_insert_term(
			    $child_term_name,   // the term 
			    'product_cat', // the taxonomy
			    array(
			        // 'description' => 'A yummy apple.',
			        'slug'        => $child_term_name,
			        'parent'      => $parent_term,
			    )
			);

			return $child_term;
	}

	public function update_variation_data( $variation_post_id, $data ){
        update_post_meta( $variation_post_id, 'gamestop_price', esc_attr( $data['gamestop_price'] ) );
		update_post_meta( $variation_post_id, 'gamestop_trade_price', esc_attr( $data['gamestop_trade_price'] ) );
		update_post_meta( $variation_post_id, 'retail_loose_buy', esc_attr( $data['retail_loose_buy'] ) );
		update_post_meta( $variation_post_id, 'retail_loose_sell', esc_attr( $data['retail_loose_sell'] ) );
		update_post_meta( $variation_post_id, 'retail_cib_buy', esc_attr( $data['retail_cib_buy'] ) );
		update_post_meta( $variation_post_id, 'retail_cib_sell', esc_attr( $data['retail_cib_sell'] ) );
		update_post_meta( $variation_post_id, 'retail_new_buy', esc_attr( $data['retail_new_buy'] ) );
		update_post_meta( $variation_post_id, 'retail_new_sell', esc_attr( $data['retail_new_sell'] ) );
		update_post_meta( $variation_post_id, 'upc', esc_attr( $data['upc'] ) );
		update_post_meta( $variation_post_id, 'sales_volume', esc_attr( $data['sales_volume'] ) );
		update_post_meta( $variation_post_id, 'genre', esc_attr( $data['genre'] ) );
		update_post_meta( $variation_post_id, 'asin', esc_attr( $data['asin'] ) );
		update_post_meta( $variation_post_id, 'epid', esc_attr( $data['epid'] ) );
		update_post_meta( $variation_post_id, 'release_date', esc_attr( $data['release_date'] ) );

	}
	/**
	 * Save a new product attribute from his name (slug).
	 *
	 * @since 3.0.0
	 * @param string $name  | The product attribute name (slug).
	 * @param string $label | The product attribute label (name).
	 */
	public function save_product_attribute_from_name( $name, $label='', $set=true ){

	    // if( ! function_exists ($this, 'get_attribute_id_from_name') ) return;
	    
	    global $wpdb;

	    $label = $label == '' ? ucfirst($name) : $label;
	    $attribute_id = $this->get_attribute_id_from_name( $name );

	    if( empty($attribute_id) ){
	        $attribute_id = NULL;
	    } else {
	        $set = false;
	    }
	    $args = array(
	        'attribute_id'      => $attribute_id,
	        'attribute_name'    => $name,
	        'attribute_label'   => $label,
	        'attribute_type'    => 'select',
	        'attribute_orderby' => 'menu_order',
	        'attribute_public'  => 0,
	    );


	    if( empty($attribute_id) ) {
	        $wpdb->insert(  "{$wpdb->prefix}woocommerce_attribute_taxonomies", $args );
	        do_action( 'woocommerce_attribute_added', $wpdb->insert_id, $args );
	        set_transient( 'wc_attribute_taxonomies', false );
	        delete_transient( 'wc_attribute_taxonomies' );
	        flush_rewrite_rules();

	    }

	    if( $set ){
	        $attributes = wc_get_attribute_taxonomies();
	        $args['attribute_id'] = $this->get_attribute_id_from_name( $name );
	        $attributes[] = (object) $args;
	        return $attributes;
	        //print_r($attributes);
	        set_transient( 'wc_attribute_taxonomies', $attributes );
	    } else {
	        return;
	    }
	}

	/**
	 * Get the product attribute ID from the name.
	 *
	 * @since 3.0.0
	 * @param string $name | The name (slug).
	 */
	public function get_attribute_id_from_name( $name ){
	    global $wpdb;
	    $attribute_id = $wpdb->get_col("SELECT attribute_id
	    FROM {$wpdb->prefix}woocommerce_attribute_taxonomies
	    WHERE attribute_name LIKE '$name'");
	    return reset($attribute_id);
	}

	public function create_product_variation( $data ){
		
	    //if( ! function_exists ($this,'save_product_attribute_from_name') ) return;

	    $postname = sanitize_title( $data['title'] );
	    $author = empty( $data['author'] ) ? '1' : $data['author'];

	    $post_data = array(
	        'post_author'   => $author,
	        'post_name'     => $postname,
	        'post_title'    => $data['title'],
	        'post_content'  => $data['content'],
	        'post_excerpt'  => $data['excerpt'],
	        'post_status'   => 'publish',
	        'ping_status'   => 'closed',
	        'post_type'     => 'product',
	        'guid'          => home_url( '/product/'.$postname.'/' ),
	    );

	    // Creating the product (post data)
	    $product_id = wp_insert_post( $post_data );

	    wp_set_object_terms( $product_id, "Video Games",'product_cat');

	    $parent_term_name = 'video-games';
		$child_term_name = $data['child_cat'];
		$this->add_child_term( $parent_term_name, $child_term_name );

		wp_set_object_terms( $product_id, $child_term_name,'product_cat');
	    // Get an instance of the WC_Product_Variable object and save it
	    $product = new WC_Product_Variable( $product_id );
	    
	    $product->save();

	    ## ---------------------- Other optional data  ---------------------- ##
	    ##     (see WC_Product and WC_Product_Variable setters methods)

	    // THE PRICES (No prices yet as we need to create product variations)

	    // IMAGES GALLERY
	    // if( ! empty( $data['gallery_ids'] ) && count( $data['gallery_ids'] ) > 0 )
	    //     $product->set_gallery_image_ids( $data['gallery_ids'] );

	    // SKU
	    // $product->set_sku( $data['sku'] );
	    // STOCK (stock will be managed in variations)
	    // $product->set_stock_quantity( $data['stock'] ); // Set a minimal stock quantity
	    $product->set_manage_stock('no');
	    // $product->set_stock_status('instock');

	    // Tax class
	    if( empty( $data['tax_class'] ) )
	        $product->set_tax_class( $data['tax_class'] );

	    // WEIGHT
	    if( ! empty($data['weight']) )
	        $product->set_weight(''); // weight (reseting)
	    else
	        $product->set_weight($data['weight']);

	    $product->validate_props(); // Check validation

	    ## ---------------------- VARIATION ATTRIBUTES ---------------------- ##

	    $product_attributes = array();

	    foreach( $data['attributes'] as $key => $terms ){
	    	
			//Save variation, returns variation id
	        $taxonomy = wc_attribute_taxonomy_name($key); // The taxonomy slug
	        $attr_label = ucfirst($key); // attribute label name
	        $attr_name = ( wc_sanitize_taxonomy_name($key)); // attribute slug

	        // NEW Attributes: Register and save them
	        if( ! taxonomy_exists( $attr_name ) ){
	            $this->save_product_attribute_from_name( $attr_name, $attr_label );
	            flush_rewrite_rules();
	            //wp_reset_query();
	        	// If taxonomy doesn't exists we create it (Thanks to Carl F. Corneil)
			        
	        }
	        

	        $product_attributes[$taxonomy] = array (
	            'name'         => $taxonomy,
	            'value'        => '',
	            'position'     => '',
	            'is_visible'   => 1,
	            'is_variation' => 1,
	            'is_taxonomy'  => 1
	        );

	        foreach( $terms as $value ){
	            $term_name = ucfirst($value);
	            $term_slug = sanitize_title($value);

		        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug;
	     		       
	            // Check if the Term name exist and if not we create it.
	            if( ! term_exists( $term_name, $taxonomy ) ){
	            	
	             	$inserted_term = wp_insert_term( $term_name, $taxonomy, array('slug' => $term_slug ) ); // Create the term
	            }

	            // Set attribute values
	            wp_set_object_terms( $product_id, $term_name, $taxonomy, true );
	            
	        }

	    }
	    update_post_meta( $product_id, '_stock_status', 'instock' );
	    update_post_meta( $product_id, '_product_attributes', $product_attributes );
	    update_post_meta( $product_id, '_sku', $data['sku'] );
	    $this->insert_product_variations($product_id, $data['variations'], $data);
	    $product->save(); // Save the data
	}

	public function insert_product_variations ($post_id, $variations, $data){
		
        foreach ($variations as $index => $variation)
        {
            // Insert the variation

            foreach ($variation['attributes'] as $attribute => $value) // Loop through the variations attributes
            {

                $variation_post = array( // Setup the post data for the variation

                'post_title'  => 'Variation #'.$attribute.' of '.count($variations).' for product#'. $post_id,
                'post_name'   => 'product-'.$post_id.'-variation-'.$attribute,
                'post_status' => 'publish',
                'post_parent' => $post_id,
                'post_type'   => 'product_variation',
                'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $attribute
            );

            
            $variation_post_id = wp_insert_post($variation_post); 
                $attribute_term = get_term_by('name', $value, 'pa_'.$index); // We need to insert the slug not the name into the variation post meta

                update_post_meta($variation_post_id, 'attribute_pa_'.$index, $attribute_term->slug);
                update_post_meta($variation_post_id, '_price', $variation['price'][$attribute]);
                update_post_meta($variation_post_id, '_regular_price', $variation['price'][$attribute]);
                
                update_post_meta($variation_post_id, '_sku', $variation['sku'][$attribute]);
                // update_post_meta($variation_post_id, '_sku', $variation['sku'][$attribute]);

                update_post_meta( $variation_post_id, 'gamestop_price', esc_attr( $data['gamestop_price'] ) );
				update_post_meta( $variation_post_id, 'gamestop_trade_price', esc_attr( $data['gamestop_trade_price'] ) );
				update_post_meta( $variation_post_id, 'retail_loose_buy', esc_attr( $data['retail_loose_buy'] ) );
				update_post_meta( $variation_post_id, 'retail_loose_sell', esc_attr( $data['retail_loose_sell'] ) );
				update_post_meta( $variation_post_id, 'retail_cib_buy', esc_attr( $data['retail_cib_buy'] ) );
				update_post_meta( $variation_post_id, 'retail_cib_sell', esc_attr( $data['retail_cib_sell'] ) );
				update_post_meta( $variation_post_id, 'retail_new_buy', esc_attr( $data['retail_new_buy'] ) );
				update_post_meta( $variation_post_id, 'retail_new_sell', esc_attr( $data['retail_new_sell'] ) );
				update_post_meta( $variation_post_id, 'upc', esc_attr( $data['upc'] ) );
				update_post_meta( $variation_post_id, 'sales_volume', esc_attr( $data['sales_volume'] ) );
				update_post_meta( $variation_post_id, 'genre', esc_attr( $data['genre'] ) );
				update_post_meta( $variation_post_id, 'asin', esc_attr( $data['asin'] ) );
				update_post_meta( $variation_post_id, 'epid', esc_attr( $data['epid'] ) );
				update_post_meta( $variation_post_id, 'release_date', esc_attr( $data['release_date'] ) );

            }

            
        }
    }

} /* End Class */

$Wp_Video_Games = new Wp_Video_Games();
