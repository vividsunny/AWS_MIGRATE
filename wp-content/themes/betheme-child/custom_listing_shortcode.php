<?php

function preorderable_products_function($attr) {
    global $post;
    global $woocommerce; 
    
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    extract(shortcode_atts(array(
            'columns'   => '4',
			'title'     => '',
			'show'		=> '',
			'category'	=> '',
			'orderby' 	=> 'date',
			'order'		=> 'DESC',
		), $attr));

		// query args

		$args =  array(
			'post_type'			=> 'product',
			'posts_per_page' 	=> 12,
			'orderby'			=> $orderby,
			'order'				=> $order,
			'ignore_sticky_posts'	=> 1,
            'paged'             => $paged
		);
		
		$args['post__in'] =  array_merge(array( 0 ), wc_get_product_ids_on_preorder());
		
		$query_shop = new WP_Query();
		$query_shop->query($args);
    
        $output = '<div class="special-products woocommerce" data-count="'. esc_attr($query_shop->post_count) .'">'; 
        $output .= '<div class="products_wrapper">';
        $output .= '<ul class="products grid col-4">'; 
        
            while ($query_shop->have_posts()) {                    
                $query_shop->the_post();
                global $product;
            
            $diamond = get_post_meta($product->get_id() ,"diamond_number", true);
            if (empty($diamond)) {
                $diamond = 'comingsooncolor';
            }
            $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/$diamond.jpg";
            $thumbnail_p = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
            
            if (file_get_contents($imgurl) === false) {  
                if (file_get_contents($thumbnail_p) === false){
                    $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/comingsooncolor.jpg";
                }else{
                    $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                }                        
            }
        
            $output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';
                $output .= '<div class="item_wrapper">';
                    $output .= '<div class="image_frame scale-with-grid product-loop-thumb">';
                        $output .= '<div class="image_wrapper">';
                            $output .= '<a href="'. esc_url(get_the_permalink()) .'">';
                                $output .= '<div class="mask"></div>';
                                $output .= '<img src="'. esc_url($imgurl) .'" class="woocommerce-placeholder wp-post-image">';
                            $output .= '</a>';
                            $output .= '<div class="image_links">';
                                $output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'"><i class="icon-link"></i></a>';
                            $output .= '</div>';
                        $output .= '</div>';
                         if ($product->is_on_sale()) { 
                             $output .= '<span class="onsale"><i class="icon-star"></i></span>';
                         }
                    $output .= '</div>';
                    $output .= '<div class="desc">';
                        $output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';
                    $output .= '</div>';
                    if ($price_html = $product->get_price_html()) {
                        $output .= '<span class="price">'. $price_html .'</span>';
                    }
                    
                $output .= '</div>';
            $output .= '</li>';
        
            }
            
        $output .= '</ul>';
    
    $output .= '<nav id="nav-below" class="navigation">';
    $output .= '<div class="alignleft">'. previous_posts_link('« Previews', $query_shop->max_num_pages) .'</div>';
    $output .= '<div class="alignright">'. next_posts_link('Next »', $query_shop->max_num_pages) .'</div>';
    $output .= '</nav>';
    
    return $output;
      
    wp_reset_postdata();

}
add_shortcode('preorderables', 'preorderable_products_function');



function onsale_products_function($attr) {
    global $post;
    global $woocommerce;
    
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    extract(shortcode_atts(array(
            'columns'   => '4',
			'title'     => '',
			'show'		=> '',
			'category'	=> '',
			'orderby' 	=> 'date',
			'order'		=> 'DESC',
		), $attr));

		// query args

		$args = array(
			'post_type'			=> 'product',
			'posts_per_page' 	=> 12,
			'orderby'			=> $orderby,
			'order'				=> $order,
			'ignore_sticky_posts'	=> 1,
			'paged' 			=> $paged
		);
		
		$args['post__in'] =  array_merge(array( 0 ), wc_get_product_ids_on_sale());
		
		$query_shop = new WP_Query();
		$query_shop->query($args);
    
        $output = '<div class="special-products woocommerce" data-count="'. esc_attr($query_shop->post_count) .'">'; 
        $output .= '<div class="products_wrapper">';
        $output .= '<ul class="products grid col-4">'; 
        
            while ($query_shop->have_posts()) {                    
                $query_shop->the_post();
                global $product;
            
            $diamond = get_post_meta($product->get_id() ,"diamond_number", true);
            if (empty($diamond)) {
                $diamond = 'comingsooncolor';
            }
            $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/$diamond.jpg";
            $thumbnail_p = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
            
            if (file_get_contents($imgurl) === false) {  
                if (file_get_contents($thumbnail_p) === false){
                    $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/comingsooncolor.jpg";
                }else{
                    $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                }                        
            }
        
            $output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';
                $output .= '<div class="item_wrapper">';
                    $output .= '<div class="image_frame scale-with-grid product-loop-thumb">';
                        $output .= '<div class="image_wrapper">';
                            $output .= '<a href="'. esc_url(get_the_permalink()) .'">';
                                $output .= '<div class="mask"></div>';
                                $output .= '<img src="'. esc_url($imgurl) .'" class="woocommerce-placeholder wp-post-image">';
                            $output .= '</a>';
                            $output .= '<div class="image_links">';
                                $output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'"><i class="icon-link"></i></a>';
                            $output .= '</div>';
                        $output .= '</div>';
                         if ($product->is_on_sale()) { 
                             $output .= '<span class="onsale"><i class="icon-star"></i></span>';
                         }
                    $output .= '</div>';
                    $output .= '<div class="desc">';
                        $output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';
                    $output .= '</div>';
                    if ($price_html = $product->get_price_html()) {
                        $output .= '<span class="price">'. $price_html .'</span>';
                    }
                    
                $output .= '</div>';
            $output .= '</li>';
        
            }
        $output .= '</ul>';
    
    $output .= '<nav id="nav-below" class="navigation">';
    $output .= '<div class="alignleft">'. previous_posts_link('« Previews') .'</div>';
    $output .= '<div class="alignright">'. next_posts_link('Next »', $query_shop->max_num_pages) .'</div>';
    $output .= '</nav>';
        
    return $output;
        
    wp_reset_postdata();
 
}
add_shortcode('onsaleproducts', 'onsale_products_function');



function bestselling_products_function($attr) {
    global $post;
    global $woocommerce;
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    extract(shortcode_atts(array(
            'columns'   => '4',
			'title'     => '',
			'show'		=> '',
			'category'	=> '',
			'orderby' 	=> 'date',
			'order'		=> 'DESC',
		), $attr));

		// query args

		$args = array(
			'post_type'			=> 'product',
			'posts_per_page' 	=> 12,
			'orderby'			=> 'meta_value_num',
			'order'				=> $order,
			'ignore_sticky_posts'	=> 1,
			'paged' 			=> $paged,
			'meta_key'          =>'total_sales',
			
		);
				
		$query_shop = new WP_Query();
		$query_shop->query($args);
    
        $output = '<div class="special-products woocommerce" data-count="'. esc_attr($query_shop->post_count) .'">'; 
        $output .= '<div class="products_wrapper">';
        $output .= '<ul class="products grid col-4">'; 
        
            while ($query_shop->have_posts()) {                    
                $query_shop->the_post();
                global $product;
            
            $diamond = get_post_meta($product->get_id() ,"diamond_number", true);
            if (empty($diamond)) {
                $diamond = 'comingsooncolor';
            }
            $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/$diamond.jpg";
            $thumbnail_p = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
            
            if (file_get_contents($imgurl) === false) {  
                if (file_get_contents($thumbnail_p) === false){
                    $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/zip-image/comingsooncolor.jpg";
                }else{
                    $imgurl = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$diamond.jpg";
                }                        
            }
        
            $output .= '<li class="'. esc_attr(implode(' ', get_post_class())) .'">';
                $output .= '<div class="item_wrapper">';
                    $output .= '<div class="image_frame scale-with-grid product-loop-thumb">';
                        $output .= '<div class="image_wrapper">';
                            $output .= '<a href="'. esc_url(get_the_permalink()) .'">';
                                $output .= '<div class="mask"></div>';
                                $output .= '<img src="'. esc_url($imgurl) .'" class="woocommerce-placeholder wp-post-image">';
                            $output .= '</a>';
                            $output .= '<div class="image_links">';
                                $output .= '<a class="link" href="'. esc_url(get_the_permalink()) .'"><i class="icon-link"></i></a>';
                            $output .= '</div>';
                        $output .= '</div>';
                         if ($product->is_on_sale()) { 
                             $output .= '<span class="onsale"><i class="icon-star"></i></span>';
                         }
                    $output .= '</div>';
                    $output .= '<div class="desc">';
                        $output .= '<h4><a href="'. esc_url(get_the_permalink()) .'">'. wp_kses(get_the_title(), mfn_allowed_html()) .'</a></h4>';
                    $output .= '</div>';
                    if ($price_html = $product->get_price_html()) {
                        $output .= '<span class="price">'. $price_html .'</span>';
                    }
                    
                $output .= '</div>';
            $output .= '</li>';
        
            }
        $output .= '</ul>';
    
    $output .= '<nav id="nav-below" class="navigation">';
    $output .= '<div class="alignleft">'. previous_posts_link('« Previews') .'</div>';
    $output .= '<div class="alignright">'. next_posts_link('Next »', $query_shop->max_num_pages) .'</div>';
    $output .= '</nav>';
        
    return $output;
        
    wp_reset_postdata();
}
add_shortcode('bestsellingproducts', 'bestselling_products_function');


add_filter('next_posts_link_attributes', 'posts_link_attributes_previews');
add_filter('previous_posts_link_attributes', 'posts_link_attributes_next');
function posts_link_attributes_previews() {
  return 'class="styled-button-previews"';
}
function posts_link_attributes_next() {
  return 'class="styled-button-next"';
}


?>
