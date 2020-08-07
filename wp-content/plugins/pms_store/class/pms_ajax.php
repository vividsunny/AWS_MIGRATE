<?php
/**
 * 
 */
class PMS_ajax
{
	
	function __construct()
	{
		# Safe Trip
		add_action('wp_ajax_sync_product', array( &$this,'PMS_sync_product_handler'));
		add_action('wp_ajax_nopriv_sync_product',array( &$this, 'PMS_sync_product_handler'));

	}

	public function PMS_sync_product_handler(){

		$blog_ids = $_POST['blog_ids'];
		$current_post = $_POST['current_post'];

		$blog_arr = explode(",", $blog_ids);
		$final_blog_ids = array_filter( $blog_arr );

		$original_post   = get_post( $current_post );
		$post_data = array(
			'post_author' 	=> $original_post->post_author,
			'post_date' 	=> $original_post->post_date,
			'post_modified' => $original_post->post_modified,
			'post_content' 	=> $original_post->post_content,
			'post_title' 	=> $original_post->post_title,
			'post_excerpt' 	=> $original_post->post_excerpt,
			'post_status' 	=> 'publish',
			'post_name' 	=> $original_post->post_name,
			'post_type' 	=> $original_post->post_type,
		);
		
		$dimond = get_post_meta($current_post,'diamond_number',true);
		if( !empty( $dimond ) ){
			$dimond = $dimond;
		}else{
			$dimond = get_post_meta($current_post,'Diamond Number',true);
		}

		$meta_array = $this->PMS_get_product_meta_arr( $current_post );
		$this->PMS_in_subsite( $post_data, $final_blog_ids, $meta_array, $dimond );

		wp_die();
	}

	public function PMS_in_subsite( $post_data, $blog_ids, $meta_array, $dimond ){

		// $the_slug = $post_data['post_name'];
		$the_slug = $dimond;

		foreach ($blog_ids as $key => $value) {

			switch_to_blog( $value );

			$all_product = $this->PMS_check_product_exist( $the_slug );
			// $all_product = array();

			if( !empty( $all_product )){
				// vivid( '---- IF ----' );
				// vivid( $value );
				foreach ($all_product as $blog_value) {

					$blog_post_id = $blog_value->ID;
					
					// vivid( $blog_post_id );
					$this->PMS_subsite_update_product( $blog_post_id, $post_data );
					$this->PMS_update_product_meta_arr( $blog_post_id, $meta_array );
				}

			}else{
				vivid( '---- ELSE ----' );
				vivid( $value );
				// $blog_post_id = $this->PMS_subsite_insert_product( $post_data );
				// $this->PMS_update_product_meta_arr( $blog_post_id, $meta_array );
			}

			restore_current_blog();

		}

	}

	public function PMS_check_product_exist( $the_slug ){

		$args=array(
		    // 'post_title'     => $the_slug,
		    // 'name'     => $the_slug,
		    'post_type'      => 'product',
		    'post_status'    => 'publish',
		    'posts_per_page' => -1,
		    'meta_key'		=> 'diamond_number',
    		'meta_value'	=> $the_slug,
		);

		$query = new WP_Query( $args );
	
		$exist_posts = array();
		if( $query->have_posts() ) {
			$exist_posts = $query->posts;
		}

		return $exist_posts;
	}

	public function PMS_get_product_meta_arr( $post_id ){
		$meta_arr = array();

		// wp_set_object_terms( $post_id, 'simple', 'product_type' );
	    $_visibility 			= get_post_meta( $post_id, '_visibility', true);
	    $_stock_status 			= get_post_meta( $post_id, '_stock_status', true);
	    $total_sales 			= get_post_meta( $post_id, 'total_sales', true );
	    $_downloadable 			= get_post_meta( $post_id, '_downloadable', true );
	    $_virtual 				= get_post_meta( $post_id, '_virtual', true );
	    $_regular_price 		= get_post_meta( $post_id, '_regular_price', true );
	    $_sale_price 			= get_post_meta( $post_id, '_sale_price', true );
	    $_purchase_note 		= get_post_meta( $post_id, '_purchase_note', true );
	    $_featured 				= get_post_meta( $post_id, '_featured', true );
	    $_sku 					= get_post_meta( $post_id, '_sku',  true );
	    $_product_attributes 	= get_post_meta( $post_id, '_product_attributes', true );
	    $_sale_price_dates_from = get_post_meta( $post_id, '_sale_price_dates_from', true );
	    $_sale_price_dates_to 	= get_post_meta( $post_id, '_sale_price_dates_to', true );
	    $_price 				= get_post_meta( $post_id, '_price', true );
	    $_sold_individually 	= get_post_meta( $post_id, '_sold_individually', true );
	    $_manage_stock 			= get_post_meta( $post_id, '_manage_stock', true );
	    $_backorders 			= get_post_meta( $post_id, '_backorders', true );
	    $_stock 				= get_post_meta( $post_id, '_stock', true );

	    /* diamond_number */
		$diamond_number 		= get_post_meta( $post_id, 'diamond_number', true);
		if( !empty( $diamond_number ) ){
			$diamond_number = $diamond_number;
		}else{
			$diamond_number = get_post_meta( $post_id, 'Diamond Number', true);
		}

		/* stock_number */
		$stock_number 			= get_post_meta( $post_id, 'stock_number', true);
		if( !empty( $stock_number ) ){
			$stock_number = $stock_number;
		}else{
			$stock_number = get_post_meta( $post_id, 'Stock Number', true);
		}

		/* series_code */
		$series_code 			= get_post_meta( $post_id, 'series_code', true);
		if( !empty( $series_code ) ){
			$series_code = $series_code;
		}else{
			$series_code = get_post_meta( $post_id, 'Series Code', true);
		}

		/* issue_number */
		$issue_number 			= get_post_meta( $post_id, 'issue_number', true);
		if( !empty( $issue_number ) ){
			$issue_number = $issue_number;
		}else{
			$issue_number = get_post_meta( $post_id, 'Issue Number', true);
		}

		/* issue_sequence_number */
		$issue_sequence_number 	= get_post_meta( $post_id, 'issue_sequence_number', true);
		if( !empty( $issue_sequence_number ) ){
			$issue_sequence_number = $issue_sequence_number;
		}else{
			$issue_sequence_number = get_post_meta( $post_id, 'Issue Sequence Number', true);
		}

		/* price */
		$price 					= get_post_meta( $post_id, 'price', true);
		if( !empty( $price ) ){
			$price = $price;
		}else{
			$price = get_post_meta( $post_id, 'Price', true);
		}

		/* price */
		$publisher 				= get_post_meta( $post_id, 'publisher', true);
		if( !empty( $publisher ) ){
			$publisher = $publisher;
		}else{
			$publisher = get_post_meta( $post_id, 'Publisher', true);
		}

		/* upc_number */
		$upc_number 			= get_post_meta( $post_id, 'upc_number', true);
		if( !empty( $upc_number ) ){
			$upc_number = $upc_number;
		}else{
			$upc_number = get_post_meta( $post_id, 'UPC Number', true);
		}

		/* cards_per_pack */
		$cards_per_pack 		= get_post_meta( $post_id, 'cards_per_pack', true);
		if( !empty( $cards_per_pack ) ){
			$cards_per_pack = $cards_per_pack;
		}else{
			$cards_per_pack = get_post_meta( $post_id, 'Cards Per Pack', true);
		}

		/* pack_per_box */
		$pack_per_box 			= get_post_meta( $post_id, 'pack_per_box', true);
		if( !empty( $pack_per_box ) ){
			$pack_per_box = $pack_per_box;
		}else{
			$pack_per_box = get_post_meta( $post_id, 'Pack Per Box', true);
		}

		/* box_per_case */
		$box_per_case 			= get_post_meta( $post_id, 'box_per_case', true);
		if( !empty( $box_per_case ) ){
			$box_per_case = $box_per_case;
		}else{
			$box_per_case = get_post_meta( $post_id, 'Box Per Case', true);
		}

		/* discount_code */
		$discount_code 			= get_post_meta( $post_id, 'discount_code', true);
		if( !empty( $discount_code ) ){
			$discount_code = $discount_code;
		}else{
			$discount_code = get_post_meta( $post_id, 'Discount Code', true);
		}

		/* increment */
		$increment 				= get_post_meta( $post_id, 'increment', true);
		if( !empty( $increment ) ){
			$increment = $increment;
		}else{
			$increment = get_post_meta( $post_id, 'Increment', true);
		}

		/* print_date */
		$print_date 			= get_post_meta( $post_id, 'print_date', true);
		if( !empty( $print_date ) ){
			$print_date = $print_date;
		}else{
			$print_date = get_post_meta( $post_id, 'Print Date', true);
		}

		/* foc_vendor */
		$foc_vendor 			= get_post_meta( $post_id, 'foc_vendor', true);
		if( !empty( $foc_vendor ) ){
			$foc_vendor = $foc_vendor;
		}else{
			$foc_vendor = get_post_meta( $post_id, 'FOC Vendor', true);
		}

		/* available */
		$available 				= get_post_meta( $post_id, 'available', true);
		if( !empty( $available ) ){
			$available = $available;
		}else{
			$available = get_post_meta( $post_id, 'Available', true);
		}

		/* srp */
		$srp 					= get_post_meta( $post_id, 'srp', true);
		if( !empty( $srp ) ){
			$srp = $srp;
		}else{
			$srp = get_post_meta( $post_id, 'SRP', true);
		}

		/* category */
		$category 				= get_post_meta( $post_id, 'category', true);
		if( !empty( $category ) ){
			$category = $category;
		}else{
			$category = get_post_meta( $post_id, 'Category', true);
		}

		/* mature */
		$mature 				= get_post_meta( $post_id, 'mature', true);
		if( !empty( $mature ) ){
			$mature = $mature;
		}else{
			$mature = get_post_meta( $post_id, 'Mature', true);
		}

		/* adult */
		$adult 					= get_post_meta( $post_id, 'adult', true);
		if( !empty( $adult ) ){
			$adult = $adult;
		}else{
			$adult = get_post_meta( $post_id, 'Adult', true);
		}

		/* oa */
		$oa 					= get_post_meta( $post_id, 'oa', true);
		if( !empty( $oa ) ){
			$oa = $oa;
		}else{
			$oa = get_post_meta( $post_id, 'OA', true);
		}

		/* caut1 */
		$caut1 					= get_post_meta( $post_id, 'caut1', true);
		if( !empty( $caut1 ) ){
			$caut1 = $caut1;
		}else{
			$caut1 = get_post_meta( $post_id, 'CAUT1', true);
		}

		/* caut2 */
		$caut2 					= get_post_meta( $post_id, 'caut2', true);
		if( !empty( $caut2 ) ){
			$caut2 = $caut2;
		}else{
			$caut2 = get_post_meta( $post_id, 'CAUT2', true);
		}

		/* caut3 */
		$caut3 					= get_post_meta( $post_id, 'caut3', true);
		if( !empty( $caut3 ) ){
			$caut3 = $caut3;
		}else{
			$caut3 = get_post_meta( $post_id, 'CAUT3', true);
		}

		/* resol */
		$resol 					= get_post_meta( $post_id, 'resol', true);
		if( !empty( $resol ) ){
			$resol = $resol;
		}else{
			$resol = get_post_meta( $post_id, 'RESOL', true);
		}

		/* note_price */
		$note_price 			= get_post_meta( $post_id, 'note_price', true);
		if( !empty( $note_price ) ){
			$note_price = $note_price;
		}else{
			$note_price = get_post_meta( $post_id, 'Note Price', true);
		}

		/* order_form_notes */
		$order_form_notes 		= get_post_meta( $post_id, 'order_form_notes', true);
		if( !empty( $order_form_notes ) ){
			$order_form_notes = $order_form_notes;
		}else{
			$order_form_notes = get_post_meta( $post_id, 'Order Form Notes', true);
		}

		/* page */
		$page 					= get_post_meta( $post_id, 'page', true);
		if( !empty( $page ) ){
			$page = $page;
		}else{
			$page = get_post_meta( $post_id, 'Page', true);
		}

		/* foc_date */
		$foc_date 				= get_post_meta( $post_id, 'foc_date', true);
		if( !empty( $foc_date ) ){
			$foc_date = $foc_date;
		}else{
			$foc_date = get_post_meta( $post_id, 'FOC Date', true);
		}

		/* preview_html */
		$preview_html 			= get_post_meta( $post_id, 'preview_html', true);
		if( !empty( $preview_html ) ){
			$preview_html = $preview_html;
		}else{
			$preview_html = get_post_meta( $post_id, 'Preview HTML', true);
		}

		/* image_path */
		$pro = get_post_meta($post_id ,"Image Path", true);
		$image_path = get_post_meta($post_id ,"image_path", true);
		if(!empty($pro)){
			$image_path = $pro;
		}else if( !empty($image_path) ){
	      $image_path = $image_path;
	    }else{
			$image_path = get_post_meta($post_id ,"Image-Path", true);
		}


		/* genre */
		$genre 					= get_post_meta( $post_id, 'genre', true);
		if( !empty( $genre ) ){
			$genre = $genre;
		}else{
			$genre = get_post_meta( $post_id, 'Genre', true);
		}

		/* brand_code */
		$brand_code 			= get_post_meta( $post_id, 'brand_code', true);
		if( !empty( $brand_code ) ){
			$brand_code = $brand_code;
		}else{
			$brand_code = get_post_meta( $post_id, 'Brand Code', true);
		}

		/* writer */
		$writer 				= get_post_meta( $post_id, 'writer', true);
		if( !empty( $writer ) ){
			$writer = $writer;
		}else{
			$writer = get_post_meta( $post_id, 'Writer', true);
		}

		/* artist */
		$artist 				= get_post_meta( $post_id, 'artist', true);
		if( !empty( $artist ) ){
			$artist = $artist;
		}else{
			$artist = get_post_meta( $post_id, 'Artist', true);
		}

		/* covert_artist */
		$covert_artist 			= get_post_meta( $post_id, 'covert_artist', true);
		if( !empty( $covert_artist ) ){
			$covert_artist = $covert_artist;
		}else{
			$covert_artist = get_post_meta( $post_id, 'Covert Artist', true);
		}

		/* variant_desc */
		$variant_desc  			= get_post_meta( $post_id, 'variant_desc', true);
		if( !empty( $variant_desc ) ){
			$variant_desc = $variant_desc;
		}else{
			$variant_desc = get_post_meta( $post_id, 'Variant Desc', true);
		}

		/* short_isbn_no */
		$short_isbn_no 			= get_post_meta( $post_id, 'short_isbn_no', true);
		if( !empty( $short_isbn_no ) ){
			$short_isbn_no = $short_isbn_no;
		}else{
			$short_isbn_no = get_post_meta( $post_id, 'Short ISBN No', true);
		}

		/* ean_no */
		$ean_no 				= get_post_meta( $post_id, 'ean_no', true);
		if( !empty( $ean_no ) ){
			$ean_no = $ean_no;
		}else{
			$ean_no = get_post_meta( $post_id, 'EAN No', true);
		}

		/* colorist */
		$colorist 				= get_post_meta( $post_id, 'colorist', true);
		if( !empty( $colorist ) ){
			$colorist = $colorist;
		}else{
			$colorist = get_post_meta( $post_id, 'Colorist', true);
		}

		/* alliance_sku */
		$alliance_sku 			= get_post_meta( $post_id, 'alliance_sku', true);
		if( !empty( $alliance_sku ) ){
			$alliance_sku = $alliance_sku;
		}else{
			$alliance_sku = get_post_meta( $post_id, 'Alliance SKU', true);
		}

		/* volume_tag */
		$volume_tag 			= get_post_meta( $post_id, 'volume_tag', true);
		if( !empty( $volume_tag ) ){
			$volume_tag = $volume_tag;
		}else{
			$volume_tag = get_post_meta( $post_id, 'Volume Tag', true);
		}

		/* parent_item_no_alt */
		$parent_item_no_alt 	= get_post_meta( $post_id, 'parent_item_no_alt', true);
		if( !empty( $parent_item_no_alt ) ){
			$parent_item_no_alt = $parent_item_no_alt;
		}else{
			$parent_item_no_alt = get_post_meta( $post_id, 'Parent Item No Alt', true);
		}

		/* offered_day */
		$offered_day 			= get_post_meta( $post_id, 'offered_day', true);
		if( !empty( $offered_day ) ){
			$offered_day = $offered_day;
		}else{
			$offered_day = get_post_meta( $post_id, 'Offered Day', true);
		}

		/* max_issue */
		$max_issue 				= get_post_meta( $post_id, 'max_issue', true);
		if( !empty( $max_issue ) ){
			$max_issue = $max_issue;
		}else{
			$max_issue = get_post_meta( $post_id, 'Max Issue', true);
		}

		/* cost */
		$cost 					= get_post_meta( $post_id, 'cost', true);
		if( !empty( $cost ) ){
			$cost = $cost;
		}else{
			$cost = get_post_meta( $post_id, 'Cost', true);
		}

		/* cost */
		$stockid 				= get_post_meta( $post_id, 'stockid', true);
		if( !empty( $stockid ) ){
			$stockid = $stockid;
		}else{
			$stockid = get_post_meta( $post_id, 'StockID', true);
		}


		$_wc_pre_orders_availability = get_post_meta( $post_id, '_wc_pre_orders_availability_datetime', true );
		$_wc_pre_orders_enabled = get_post_meta( $post_id, '_wc_pre_orders_enabled', true );

	    $meta_arr = array(
			'_visibility'			=> $_visibility,
			'_stock_status'			=> $_stock_status,
			'total_sales'			=> $total_sales,
			'_downloadable'			=> $_downloadable,
			'_virtual'				=> $_virtual,
			'_regular_price'		=> $_regular_price,
			'_sale_price'			=> $_sale_price,
			'_purchase_note'		=> $_purchase_note,
			'_featured'				=> $_featured,
			'_sku'					=> $_sku,
			'_product_attributes'	=> $_product_attributes,
			'_sale_price_dates_from'=> $_sale_price_dates_from,
			'_sale_price_dates_to'	=> $_sale_price_dates_to,
			'_price'				=> $_price,
			'_sold_individually'	=> $_sold_individually,
			'_manage_stock'			=> $_manage_stock,
			'_backorders'			=> $_backorders,
			'_stock'				=> $_stock,

			'diamond_number'		=> $diamond_number,
			'stock_number'			=> $stock_number,
			'series_code'			=> $series_code,
			'issue_number'			=> $issue_number,
			'issue_sequence_number'	=> $issue_sequence_number,
			'price'					=> $price,
			'publisher'				=> $publisher,
			'upc_number'			=> $upc_number,
			'cards_per_pack'		=> $cards_per_pack,
			'pack_per_box'			=> $pack_per_box,
			'box_per_case'			=> $box_per_case,
			'discount_code'			=> $discount_code,
			'increment'				=> $increment,
			'print_date'			=> $print_date,
			'foc_vendor'			=> $foc_vendor,
			'available'				=> $available,
			'srp'					=> $srp,
			'category'				=> $category,
			'mature'				=> $mature,
			'adult'					=> $adult,
			'oa'					=> $oa,
			'caut1'					=> $caut1,
			'caut2'					=> $caut2,
			'caut3'					=> $caut3,
			'resol'					=> $resol,
			'note_price'			=> $note_price,
			'order_form_notes'		=> $order_form_notes,
			'page'					=> $page,
			'foc_date'				=> $foc_date,
			'preview_html'			=> $preview_html,
			'image_path'			=> $image_path,
			'genre'					=> $genre,
			'brand_code'			=> $brand_code,
			'writer'				=> $writer,
			'artist'				=> $artist,
			'covert_artist'			=> $covert_artist,
			'variant_desc'			=> $variant_desc,
			'short_isbn_no'			=> $short_isbn_no,
			'ean_no'				=> $ean_no,
			'colorist'				=> $colorist,
			'alliance_sku'			=> $alliance_sku,
			'volume_tag'			=> $volume_tag,
			'parent_item_no_alt'	=> $parent_item_no_alt,
			'offered_day'			=> $offered_day,
			'max_issue'				=> $max_issue,
			'cost'					=> $cost,
			'stockid'				=> $stockid,
			'_wc_pre_orders_availability_datetime' => $_wc_pre_orders_availability,
			'_wc_pre_orders_enabled' => $_wc_pre_orders_enabled,
	    );

		return $meta_arr;
	}

	public function PMS_update_product_meta_arr( $post_id, $meta_array ){

		if(!empty( $meta_array )){
			// wp_set_object_terms( $post_id, 'simple',  );
		    update_post_meta( $post_id, '_visibility', $meta_array['_visibility']);
		    update_post_meta( $post_id, '_stock_status',$meta_array['_stock_status']);
		    update_post_meta( $post_id, 'total_sales', $meta_array['total_sales']);
		    update_post_meta( $post_id, '_downloadable', $meta_array['_downloadable']);
		    update_post_meta( $post_id, '_virtual', $meta_array['_virtual']);
		    update_post_meta( $post_id, '_regular_price', $meta_array['_regular_price']);
		    update_post_meta( $post_id, '_sale_price', $meta_array['_sale_price']);
		    update_post_meta( $post_id, '_purchase_note', $meta_array['_purchase_note']);
		    update_post_meta( $post_id, '_featured', $meta_array['_featured']);
		    update_post_meta( $post_id, '_sku',  $meta_array['_sku']);
		    update_post_meta( $post_id, '_product_attributes', $meta_array['_product_attributes']);
		    update_post_meta( $post_id, '_sale_price_dates_from', $meta_array['_sale_price_dates_from']);
		    update_post_meta( $post_id, '_sale_price_dates_to', $meta_array['_sale_price_dates_to']);
		    update_post_meta( $post_id, '_price', $meta_array['_price']);
		    update_post_meta( $post_id, '_sold_individually', $meta_array['_sold_individually']);
		    update_post_meta( $post_id, '_manage_stock', $meta_array['_manage_stock']);
		    update_post_meta( $post_id, '_backorders', $meta_array['_backorders']);
		    update_post_meta( $post_id, '_stock', $meta_array['_stock']);

		    update_post_meta( $post_id, 'diamond_number',$meta_array['diamond_number']);
		    update_post_meta( $post_id, 'stock_number',$meta_array['stock_number']);
		    update_post_meta( $post_id, 'series_code',$meta_array['series_code']);
		    update_post_meta( $post_id, 'issue_number',$meta_array['issue_number']);
		    update_post_meta( $post_id, 'issue_sequence_number',$meta_array['issue_sequence_number']);
		    update_post_meta( $post_id, 'price',$meta_array['price']);
		    update_post_meta( $post_id, 'publisher',$meta_array['publisher']);
		    update_post_meta( $post_id, 'upc_number',$meta_array['upc_number']);
		    update_post_meta( $post_id, 'cards_per_pack',$meta_array['cards_per_pack']);
		    update_post_meta( $post_id, 'pack_per_box',$meta_array['pack_per_box']);
		    update_post_meta( $post_id, 'box_per_case',$meta_array['box_per_case']);
		    update_post_meta( $post_id, 'discount_code',$meta_array['discount_code']);
		    update_post_meta( $post_id, 'increment',$meta_array['increment']);
		    update_post_meta( $post_id, 'print_date',$meta_array['print_date']);
		    update_post_meta( $post_id, 'foc_vendor',$meta_array['foc_vendor']);
		    update_post_meta( $post_id, 'available',$meta_array['available']);
		    update_post_meta( $post_id, 'srp',$meta_array['srp']);
		    update_post_meta( $post_id, 'category',$meta_array['category']);
		    update_post_meta( $post_id, 'mature',$meta_array['mature']);
		    update_post_meta( $post_id, 'adult',$meta_array['adult']);
		    update_post_meta( $post_id, 'oa',$meta_array['oa']);
		    update_post_meta( $post_id, 'caut1',$meta_array['caut1']);
		    update_post_meta( $post_id, 'caut2',$meta_array['caut2']);
		    update_post_meta( $post_id, 'caut3',$meta_array['caut3']);
		    update_post_meta( $post_id, 'resol',$meta_array['resol']);
		    update_post_meta( $post_id, 'note_price',$meta_array['note_price']);
		    update_post_meta( $post_id, 'order_form_notes',$meta_array['order_form_notes']);
		    update_post_meta( $post_id, 'page',$meta_array['page']);
		    update_post_meta( $post_id, 'foc_date',$meta_array['foc_date']);
		    update_post_meta( $post_id, 'preview_html',$meta_array['preview_html']);
		    update_post_meta( $post_id, 'image_path',$meta_array['image_path']);
		    update_post_meta( $post_id, 'genre',$meta_array['genre']);
		    update_post_meta( $post_id, 'brand_code',$meta_array['brand_code']);
		    update_post_meta( $post_id, 'writer',$meta_array['writer']);
		    update_post_meta( $post_id, 'artist',$meta_array['artist']);
		    update_post_meta( $post_id, 'covert_artist',$meta_array['covert_artist']);
		    update_post_meta( $post_id, 'variant_desc',$meta_array['variant_desc']);
		    update_post_meta( $post_id, 'short_isbn_no',$meta_array['short_isbn_no']);
		    update_post_meta( $post_id, 'ean_no',$meta_array['ean_no']);
		    update_post_meta( $post_id, 'colorist',$meta_array['colorist']);
		    update_post_meta( $post_id, 'alliance_sku',$meta_array['alliance_sku']);
		    update_post_meta( $post_id, 'volume_tag',$meta_array['volume_tag']);
		    update_post_meta( $post_id, 'parent_item_no_alt',$meta_array['parent_item_no_alt']);
		    update_post_meta( $post_id, 'offered_day',$meta_array['offered_day']);
		    update_post_meta( $post_id, 'max_issue',$meta_array['max_issue']);
		    update_post_meta( $post_id, 'cost',$meta_array['cost']);
		    update_post_meta( $post_id, 'stockid',$meta_array['stockid']);
		    update_post_meta( $post_id, '_wc_pre_orders_availability_datetime',$meta_array['_wc_pre_orders_availability_datetime']);
		    update_post_meta( $post_id, '_wc_pre_orders_enabled',$meta_array['_wc_pre_orders_enabled']);
		}
	}

	public function PMS_subsite_insert_product( $original_post ){

		$product_data = array(
			'post_author' 	=> $original_post['post_author'],
			'post_date' 	=> $original_post['post_date'],
			'post_modified' => $original_post['post_modified'],
			'post_content' 	=> $original_post['post_content'],
			'post_title' 	=> $original_post['post_title'],
			'post_excerpt' 	=> $original_post['post_excerpt'],
			'post_status' 	=> 'publish',
			'post_name' 	=> $original_post['post_name'],
			'post_type' 	=> $original_post['post_type'],
		);

		$inserted_post_id = wp_insert_post( $product_data );

		return $inserted_post_id;
	}

	public function PMS_subsite_update_product( $blog_post_id, $original_post ){

		$product_data = array(
			'ID'           	=> $blog_post_id,
			'post_author' 	=> $original_post['post_author'],
			'post_content' 	=> $original_post['post_content'],
			'post_title' 	=> $original_post['post_title'],
			'post_excerpt' 	=> $original_post['post_excerpt'],
			'post_status' 	=> 'publish',
			'post_name' 	=> $original_post['post_name'],
			'post_type' 	=> $original_post['post_type'],
		);

		wp_update_post( $product_data );

	}

} /* End Class */

$ajax = new PMS_ajax();