<?php 
/* Product Shortcode */
add_shortcode('import_date_filter','display_my_product_callback');
function display_my_product_callback($atts,$content,$tag){

    ob_start();
    wp_enqueue_script("shortcode-script");
    wp_enqueue_style("shortcode-style");

    $prod_args = array(
        'post_type'         => 'product',
        'posts_per_page'    => -1,
            'post_status'   => 'publish',//array( 'wc-on-hold', 'wc-active' ),
            'meta_query'    => array (
                'relation'  => 'OR',
                'import_date'   => array (
                    'key'       => 'import_date',
                    'compare'   => 'EXISTS',
                ),
            ),
        );

    $prod_query_arr = new WP_Query($prod_args);
    $all_product_id = $prod_query_arr->posts;
    $total_ids = count($all_product_id);

        
    $all_data_arr = array();
    $latest_import_date = '';
    foreach ($all_product_id as $prod_value) {
        $prod_id = $prod_value->ID;
        $import_date = get_post_meta($prod_id,'import_date',true);
        $all_data_arr[] = $import_date;
    }


    $final = array_count_values($all_data_arr);

    krsort($final);

    ?>
    <!-- title -->
    <h3>Shortcode import_date meta_key</h3>
    <form name="prod_filter" id="prod_filter" method="get">
        <section>
           <h5>Filter by Date :</h5>
            <select name="sel_import_date" class="sel_import_date">
                <option value="" disabled selected="selected">Please Select Date</option>
                <?php
                $i = 1;
                foreach ($final as $key => $key_value) {
                    if($i == 1){
                        $latest_import_date = $key;
                    }
                    ?>
                    <option value="<?php echo $key; ?>" <?php if($i == 1 ){ echo 'selected="selected"';} ?> ><?php echo $key.' ('.$key_value.')'; ?></option>
                    <?php  
                    $i++; 
                }
                ?>
            </select>
            <input type="button" name="" class="button _submit_search_form" value="Filter"><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="filter_loader" style="display:none;">
            <input type="hidden" name="action" class="" value="func_import_date_filter">
        </section>
    </form>

    <?php
    //collect values, combining passed in values and defaults
    $values = shortcode_atts(array(
        'per_page' => ''
    ),$atts);  

    if(!empty($atts['per_page'])){
        $per_page = $atts['per_page'];
    }else{
        $per_page = 12;
    }

    if(!empty($atts['order'])){
        $popup_order = $atts['order'];
    }else{
        $popup_order = 'DESC';
    }

    if(!empty($atts['orderby'])){
        $popup_orderby = $atts['orderby'];
    }else{
        $popup_orderby = 'date';
    }

    $args = array(
            'posts_per_page'    =>  $per_page,
            'post_type'         => array('product'/* ,'product_variation' */),
            'post_status'       => 'publish',
            'orderby'           => $popup_orderby,
            'order'             => $popup_order,
            'meta_query'        => array (
                'relation'      => 'OR',
                'import_date'   => array (
                    'key'       => 'import_date',
                    'value'     => $latest_import_date,
                    'compare'   => 'EXISTS',
                ),
            ),
            
        );

    $prod_query = new WP_Query($args);

    

    if ( ! $prod_query->have_posts() ) {
        echo "No Product found!";
        //exit();
    }else{


    ?>

	    <section id="cards" class="cards">
	        <?php
	        while ( $prod_query->have_posts() ): $prod_query->the_post();
	            $product = new WC_Product(get_the_ID());

	            $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

	            if(!empty($feat_image)){
	                $prod_img =  $feat_image[0];
	            }else{
	                $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
	            }
	            ?>


	            <article>
	                <div class="product_column product-block">
	                    <div class="product-img">
	                        <a href="<?php  echo get_permalink(get_the_ID()); ?>">
	                            <div class="vyte-overlay"></div>
	                            <img src="<?php echo $prod_img; ?>" alt = "">
	                        </a>
	                    </div>
	                    <div class="product-info">
	                        <div class="prod_links">
	                            <a href="<?php  echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title();  ?> </a>
	                        </div>

	                        <div class="prod_price">
	                            <?php echo $product->get_price_html(); ?>
	                        </div>

	                        <div class="add_to_cart_link">
	                            <?php
	                            $add_to_cart = do_shortcode('[add_to_cart_url id="'.get_the_ID().'"]');
	                            ?>

	                            <a href="<?php echo $add_to_cart; ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku="" aria-label="" rel="nofollow">Add to cart</a><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="img_loader" style="display:none;">

	                        </div>


	                    </div>
	                </div>
	            </article>

	            <?php
	        endwhile; 
	        ?>
	    </section>
	    
	    <?php
     }
    return ob_get_clean();
}

add_shortcode('recent_import_product','display_recent_import_product_callback');
function display_recent_import_product_callback($atts,$content,$tag){

    ob_start();
    wp_enqueue_style("shortcode-style");

    //collect values, combining passed in values and defaults
    $values = shortcode_atts(array(
        'per_page' => ''
    ),$atts);  

    if(!empty($atts['per_page'])){
        $per_page = $atts['per_page'];
    }else{
        $per_page = 12;
    }

    if(!empty($atts['order'])){
        $popup_order = $atts['order'];
    }else{
        $popup_order = 'DESC';
    }

    if(!empty($atts['orderby'])){
        $popup_orderby = $atts['orderby'];
    }else{
        $popup_orderby = 'date';
    }

    $args = array(
            'posts_per_page' =>  $per_page,
            'post_type' => array('product'/* ,'product_variation' */),
            'post_status' => 'publish',
            'orderby' => $popup_orderby,
            'order' => $popup_order,
            'meta_query' => array (
                'relation' => 'OR',
                'import_date' => array (
                    'key' => 'import_date',
                    'compare' => 'EXISTS',
                ),
            ),
            
        );

    $prod_query = new WP_Query($args);

    
    //echo '<h3>Recent Import Product</h3>';
    if ( ! $prod_query->have_posts() ) {
        echo "No Product found!";
        //exit();
    }else{
    ?>

        <section id="cards" class="cards">
            <?php
            while ( $prod_query->have_posts() ): $prod_query->the_post();
                $product = new WC_Product(get_the_ID());
                $product_diam_id = get_post_meta(get_the_ID(),'Diamond Number',true);
                $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );
                $feat_image_dn = "https://s3.us-east-2.amazonaws.com/darksidecomics/previews/$product_diam_id.jpg";
                $file_headers = get_headers($feat_image_dn);
                $file_header_string = $file_headers[0];
                //if(!empty($feat_image)){
                    //$prod_img =  $feat_image[0];
                $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

                 if(!empty($aws_key)){
                    $prod_img = "https://s3.us-east-2.amazonaws.com/darksidecomics/".$aws_key;
                  }else{
                    $prod_img ='https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg'; 
                  }

                /*if (strpos($file_header_string, '403')) {             
                    $prod_img ='https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';                    
                }else{
                    $prod_img = $feat_image_dn;
                }*/
                ?>
                <article>
                    <div class="product_column product-block">
                        <div class="product-img image_wrapper">
                            <a href="<?php  echo get_permalink(get_the_ID()); ?>">
                                <div class="vyte-overlay"></div>
                            <img src="<?php echo $prod_img; ?>" alt = "<?php echo $aws_key; ?>">
                            </a>
                            <div class="image_links double" style="">
                                <?php
                                    $add_to_cart = do_shortcode('[add_to_cart_url id="'.get_the_ID().'"]');
                                ?>
                                <a rel="nofollow" href="<?php echo $add_to_cart; ?>" data-quantity="1" data-product_id="<?php echo get_the_ID(); ?>" class="add_to_cart_button ajax_add_to_cart product_type_simple">
                                    <i class="icon-basket"></i>
                                </a>
                                <a class="link" href="<?php  echo get_permalink(get_the_ID()); ?>">
                                    <i class="icon-link"></i>
                                </a>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="prod_links">
                                <a href="<?php  echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title();  ?> </a>
                            </div>

                            <div class="prod_price">
                                <?php echo $product->get_price_html(); ?>
                            </div>

                           <!-- <div class="add_to_cart_link">
                                <?php
                                   // $add_to_cart = do_shortcode('[add_to_cart_url id="'.get_the_ID().'"]');
                                ?>

                                <a href="<?php //echo $add_to_cart; ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php //echo get_the_ID(); ?>" data-product_sku="" aria-label="" rel="nofollow">Add to cart</a><img src="<?php //echo site_url();?>/wp-admin/images/spinner.gif" class="img_loader" style="display:none;">

                            </div>-->


                        </div>
                    </div>
                </article>

                <?php
            endwhile; 
            ?>
        </section>
        
        <?php
     }
    return ob_get_clean();
}

add_shortcode('import_date_cat','display_import_date_taxonomy_callback');
function display_import_date_taxonomy_callback($atts,$content,$tag){
    ob_start();

    $taxonomy     = 'import_date';
    $orderby      = 'name';  
    $show_count   = 0;    
    $pad_counts   = 0;    
    $hierarchical = 1;    
    $title        = '';  
    $empty        = 0;
    $order        = 'desc';

    $args = array(
       'taxonomy'     => $taxonomy,
       'orderby'      => $orderby,
       'order'        => $order,
       'show_count'   => $show_count,
       'pad_counts'   => $pad_counts,
       'hierarchical' => $hierarchical,
       'title_li'     => $title,
       'hide_empty'   => $empty
   );
    $all_categories = get_categories( $args );
    
    

    ?>
    <!-- title -->
    <h3>Shortcode Import date category</h3>
    <form name="import_date_cat" id="import_date_cat" method="get">
        <section>
           <h5>Filter by Date :</h5>
            <select name="sel_import_date" class="sel_import_date">
                <option value="" disabled selected="selected">Please Select Date</option>
                <?php
                $i = 1;

                foreach ($all_categories as $cat) {
                    if($cat->category_parent == 0) {
                        $category_id = $cat->term_id;   
                        //$all_data_arr[] = $cat->name.' ('.$cat->count.')';
                        if($i == 1){
                            $latest_import_date = $cat->name;
                        }

                        ?>
                        <option value="<?php echo $cat->name; ?>" <?php if($i == 1 ){ echo 'selected="selected"';} ?> ><?php echo $cat->name.' ('.$cat->count.')'; ?></option>
                        <?php
                       
                    }  
                     $i++;      
                }
               
                ?>
            </select>
            <input type="button" name="" class="button _submit_import_date_cat" value="Filter"><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="filter_loader" style="display:none;">
            <input type="hidden" name="action" class="" value="func_import_date_filter">
        </section>
    </form>

    <?php
    //collect values, combining passed in values and defaults
    $values = shortcode_atts(array(
        'per_page' => ''
    ),$atts);  

    if(!empty($atts['per_page'])){
        $per_page = $atts['per_page'];
    }else{
        $per_page = 12;
    }

    if(!empty($atts['order'])){
        $popup_order = $atts['order'];
    }else{
        $popup_order = 'DESC';
    }

    if(!empty($atts['orderby'])){
        $popup_orderby = $atts['orderby'];
    }else{
        $popup_orderby = 'date';
    }

    $args = array(
            'posts_per_page' =>  $per_page,
            'post_type' => array('product'/* ,'product_variation' */),
            'post_status' => 'publish',
            'orderby' => $popup_orderby,
            'order' => $popup_order,
            'meta_query' => array (
                'relation' => 'OR',
                'import_date' => array (
                    'key' => 'import_date',
                    'value' => $latest_import_date,
                    'compare' => 'EXISTS',
                ),
            ),
            
        );

    $prod_query = new WP_Query($args);

    

    if ( ! $prod_query->have_posts() ) {
        echo "No Product found!";
        //exit();
    }else{


    ?>

        <section id="import_date_category" class="cards">
            <?php
            while ( $prod_query->have_posts() ): $prod_query->the_post();
                $product = new WC_Product(get_the_ID());

                $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

                if(!empty($feat_image)){
                    $prod_img =  $feat_image[0];
                }else{
                    $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
                }
                ?>


                <article>
                    <div class="product_column product-block">
                        <div class="product-img">
                            <a href="<?php  echo get_permalink(get_the_ID()); ?>">
                                <div class="vyte-overlay"></div>
                                <img src="<?php echo $prod_img; ?>" alt = "">
                            </a>
                        </div>
                        <div class="product-info">
                            <div class="prod_links">
                                <a href="<?php  echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title();  ?> </a>
                            </div>

                            <div class="prod_price">
                                <?php echo $product->get_price_html(); ?>
                            </div>

                            <div class="add_to_cart_link">
                                <?php
                                $add_to_cart = do_shortcode('[add_to_cart_url id="'.get_the_ID().'"]');
                                ?>

                                <a href="<?php echo $add_to_cart; ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku="" aria-label="" rel="nofollow">Add to cart</a><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="img_loader" style="display:none;">

                            </div>


                        </div>
                    </div>
                </article>

                <?php
            endwhile; 
            ?>
        </section>
        
        <?php
     }

    return ob_get_clean();
}
