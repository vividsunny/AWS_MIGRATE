<?php
add_action( 'wp_ajax_weekly_invoice_import_script', 'va_weekly_invoice_import_script' );
add_action( 'wp_ajax_nopriv_weekly_invoice_import_script', 'va_weekly_invoice_import_script' );
function va_weekly_invoice_import_script(){
	$obj_class = new wp_weekly_invoice();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];
	$invoice_date = $_POST['invoice_date'];

	$delimiter = ! empty( $_POST['delimiter'] ) ? wc_clean( wp_unslash( $_POST['delimiter'] ) ) : ',';

	$d = date("j-M-Y H:i:s");
	$total_data = $obj_class->count_total_file_row($file_url);
	
	$row = 1;
	if (($handle = fopen($file_url, "r")) !== FALSE) {
		$parse_data = array();
		//$header = fgetcsv( $handle, 0);

        $header = Array(
                '0' => 'item_code',
                '1' => 'diamond_no',
                '2' => 'item_title',
                '3' => 'item_1',
                '4' => 'item_2',
                '5' => 'item_3',
                '6' => 'item_4',
                '7' => 'item_5',
                '8' => 'item_6',
            );

		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$num = count($data);
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach($header as $i => $key){
				$key = strtolower($key);
				$key = str_replace(' ', '_', $key);
                $parse_data[$key] = $data[$i]; 

            }
            $end_pos = $startpos+1;
			$total_percentage = $obj_class->get_percent_complete($total_data,$end_pos);


			$row++;
			//echo $message = '['.$d.'] - row-->'.$row.'end_pos--->'.$end_pos;
			if($total_data <= $startpos){
				$message = '['.$d.'] - Done ';

				
				wp_send_json_success(
			    	array(
			    		'pos' => 'done',
			    		'file_path' => $file_url,
			    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
			    		'message' => $message,
			    		'invoice_date' => $invoice_date,
			    		
			    	)
			    );
            }else if( $row == $end_pos){
            	
            	if(isset( $parse_data['item_code'] ) && !empty( $parse_data['item_code'] ) ){

            		$message = '['.$d.'] - '.$obj_class->va_weekly_import_product( $parse_data, $startpos, $file_url, $total_data, $message, $invoice_date);
            		//$message = '['.$d.'] - '.print_r($parse_data,true);

            		//$message = '['.$d.'] - '.$parse_data['old_title'];
			    	wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    		'invoice_date' => $invoice_date,
				    	)
				    );

            	}else{
            		$message = '['.$d.'] - NO Data Found!';

            		wp_send_json_success(
				    	array(
				    		'pos' => $end_pos,
				    		'file_path' => $file_url,
				    		'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				    		'message' => $message,
				    		'invoice_date' => $invoice_date,
				    	)
				    );

            	}
            	
            }
			/*for ($c=0; $c < $num; $c++) {
				 echo $data[$c] . "<br />\n";
			}*/
		}
		fclose($handle);
	}
	wp_die();
}

add_action( 'wp_ajax_weekly_invoice_import_update_qty', 'va_weekly_invoice_import_update_qty' );
add_action( 'wp_ajax_nopriv_weekly_invoice_import_update_qty', 'va_weekly_invoice_import_update_qty' );
function va_weekly_invoice_import_update_qty(){
	//vivid( $_POST);
	$obj_class = new wp_weekly_invoice();

	$end_pos = $_POST['startpos'];
	$file_url = $_POST['file_url'];
	$prod_id = $_POST['prod_id'];
	$title = $_POST['popup_prod_title'];
	$qty_value = $_POST['qty_value'];
	$invoice_date = $_POST['invoice_date'];

	$total_data = $obj_class->count_total_file_row($file_url);

	if(!empty( $prod_id )){
		$d = date("j-M-Y H:i:s");
		//$message = '['.$d.'] - '.$title.' - #Popup Update';

		$message = '['.$d.'] - '.$obj_class->va_weekly_import_preorder_update_qty( $prod_id, $title, $invoice_date, $qty_value );

		wp_send_json_success(
			array(
				'pos' => $end_pos,
				'file_path' => $file_url,
				'percentage' => $obj_class->get_percent_complete($total_data,$end_pos),
				'message' => $message,
			)
		);

	}

	wp_die();
}

add_action('wp_ajax_func_weekly_import_date_filter', 'vvd_func_weekly_import_date_filter');
add_action('wp_ajax_nopriv_func_weekly_import_date_filter', 'vvd_func_weekly_import_date_filter');
function vvd_func_weekly_import_date_filter() {

    global $wp;
    $current_url = home_url();
    $is_home = $_POST['is_home'];
    $latest_import_date = $_POST['weekley_sel_import_date'];
    $args = array(
        'posts_per_page' => 12,
        'post_type' => array('product'/* ,'product_variation' */),
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        'meta_query' => array (
            'relation' => 'OR',
             'import_invoice_date'   => array (
                'key'       => 'import_invoice_date',
                'value'     => $latest_import_date,
                'compare'   => 'EXISTS',
            ),
        ),
        
    );

    $prod_query = new WP_Query($args);

    if ( ! $prod_query->have_posts() ) {
        $json['error'] = true;
        $json['message'] =  __("No Product found!.", "tamberra") ;
        echo json_encode($json);
        die();
    } 

    
    $html = '';
    while ( $prod_query->have_posts() ): $prod_query->the_post();
        $product = new WC_Product(get_the_ID());

        $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
        $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

        $aws_img = fetch_image_from_AWS( get_the_ID() );
        if(!empty($aws_img)){
            $prod_img = $aws_img;
            $image_info = getimagesize( $prod_img ); 

        }else{
            $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
        }

        $add_to_cart = '?add-to-cart='.get_the_ID();
        $price = $product->get_price_html();
        $loader = "/wp-admin/images/spinner.gif";


        $currency = get_woocommerce_currency_symbol();
        $price = get_post_meta( get_the_ID(), '_regular_price', true);
        $sale = get_post_meta( get_the_ID(), '_sale_price', true);

        if($sale) : 
            $price = '<del>'.$currency.$price.'</del>'.$currency.$sale;    
        elseif($price) :
            $price = $currency.$price;   
        endif; 

        $html .='<article>
            <div class="product_column product-block">
            <a href="'.get_permalink(get_the_ID()).'">
                <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('.$prod_img.')">
                    
                        <div class="vyte-overlay"></div>
                        
                    
                </div></a>
                <div class="product-info">
                    <div class="prod_links">
                        <a href="'.get_permalink(get_the_ID()).'">'.get_the_title().' </a>
                    </div>

                    <div class="prod_price">
                        '.$price.'
                    </div>

                </div>
            </div>
        </article>';

        
    endwhile; 
       if ( $prod_query->max_num_pages > 1 ) {

            if( 1 == $is_home ){
                $loadmore .= '<a href="'.site_url().'/shop/#newreleases" id="more_posts" class="button product_type_simple newreleases" style="display: block;text-align: center;">More New Releases</a>';
                $json['success'] = true;
                $json['html'] = $html;
                $json['morepost'] = $loadmore;
            }else{
                $loadmore .= '<div id="misha_loadmore" class="misha_loadmore" data-args="' . esc_attr( json_encode( $args ) ) . '" data-max-page="' . $prod_query->max_num_pages . '" data-current-page="1">Shop More</div>';
                $json['success'] = true;
                $json['html'] = $html;
                $json['loadmore'] = $loadmore;
            }
          
        }

        // $json['success'] = true;
        // $json['html'] = $html;
        
        echo json_encode($json);

    wp_die();
}

add_action('wp_ajax_multisiteloadmore', 'misha_multisite_loadmore'); 
add_action('wp_ajax_nopriv_multisiteloadmore', 'misha_multisite_loadmore'); 
 
function misha_multisite_loadmore(){
 
    $args = $_POST['query'];
    $args['paged'] = $_POST['page'] + 1; // next page of posts

    $prod_query = new WP_Query($args);

    if ( ! $prod_query->have_posts() ) {
        $json['error'] = true;
        $json['message'] =  __("No Product found!.", "tamberra") ;
        echo json_encode($json);
        die();
    } 

    
    $html = '';
    while ( $prod_query->have_posts() ): $prod_query->the_post();
        $product = new WC_Product(get_the_ID());

        $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
        $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

        $aws_img = fetch_image_from_AWS( get_the_ID() );
        if(!empty($aws_img)){
            $prod_img = $aws_img;
            $image_info = getimagesize( $prod_img ); 

        }else{
            $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
        }

        $add_to_cart = '?add-to-cart='.get_the_ID();
        $price = $product->get_price_html();
        $loader = "/wp-admin/images/spinner.gif";


        $currency = get_woocommerce_currency_symbol();
        $price = get_post_meta( get_the_ID(), '_regular_price', true);
        $sale = get_post_meta( get_the_ID(), '_sale_price', true);

        if($sale) : 
            $price = '<del>'.$currency.$price.'</del>'.$currency.$sale;    
        elseif($price) :
            $price = $currency.$price;   
        endif; 

        $html .='<article>
            <div class="product_column product-block">
            <a href="'.get_permalink(get_the_ID()).'">
                <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('.$prod_img.')">
                    
                        <div class="vyte-overlay"></div>
                        
                    
                </div></a>
                <div class="product-info">
                    <div class="prod_links">
                        <a href="'.get_permalink(get_the_ID()).'">'.get_the_title().' </a>
                    </div>

                    <div class="prod_price">
                        '.$price.'
                    </div>

                </div>
            </div>
        </article>';

        
    endwhile; 
    
    /*<div class="add_to_cart_link">

    <a href="'.$add_to_cart.'" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="'.get_the_ID().'" data-product_sku="" aria-label="" rel="nofollow">Add to cart</a><img src='.$loader.' class="img_loader" style="display:none;">

    </div>*/

    $json['success'] = true;
    $json['html'] = $html;
    echo json_encode($json);

    wp_die();
}