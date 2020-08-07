<?php
/**
 * widget weekly Shortcode Class
 */
class ClassWidgetShortcode
{
	
	function __construct()
	{
		# code...
		add_shortcode('widget_weekly_product', array( &$this, 'widget_weekly_product_shortcode'));
	}

	public function widget_weekly_product_shortcode($atts){
	    ob_start();
	     
	      #Style load
	      // wp_enqueue_style("invoice_grid_style");
	      wp_enqueue_script( "widget_grid" );
	      wp_enqueue_script( "widget_loadmore" );

	      $status = array('publish', 'draft');
	      $args = array(
	        'post_type' => 'product',
	        'posts_per_page' => -1,
	        'post_status' => $status,
	        'meta_query' => array(
	          array(
	            'key' => 'weekly_invoice',
	            'value' =>'yes',
	            'compare' => 'EXISTS',
	          )
	        ),
	      );

	      $query = new WP_Query( $args );
	      $all_post = $query->posts;

	      $save_week_invoice_date = get_option( 'weekly_invoice_import_date');
	      $week_invoice_date_arr = array();
	      foreach ($save_week_invoice_date as $key => $value) {
	        # code...

	        $week_invoice_date_arr[] =  strtotime($value);
	      }

	      $result = array_unique( $week_invoice_date_arr );
	      arsort($result);
	      ?>

	      <form name="widget_prod_filter" id="widget_prod_filter" method="get">
	        <section>
	           <h5>Filter by Date :</h5>
	            <select name="widget_sel_import_date" class="widget_sel_import_date">
	                <option value="" disabled selected="selected">Please Select Date</option>
	                <?php
	                $i = 1;
	                foreach ($result as $key_value) {
                    if($i == 1){
                        $latest_import_date = date("m/d/Y", $key_value);
                    }
                    ?>
                    <option value="<?php echo date("m/d/Y", $key_value); ?>" <?php if($i == 1 ){ echo 'selected="selected"';} ?> ><?php echo date("m/d/Y", $key_value); ?></option>
                    <?php  
                    $i++; 
	                }
	                ?>
	            </select>
	            <input type="button" name="" class="button widget_submit_search_form" value="Filter"><img src="<?php echo site_url();?>/wp-admin/images/spinner.gif" class="widget_loader" style="display:none;">
	            <input type="hidden" name="action" class="" value="func_widget_import_date_filter">
	        </section>
	      </form>
	      <?php

	      	$settings = get_option( "widget_theme_settings" );
        	// $grid_val = $settings['widget_grid_setting'];

        	if(!empty( $settings['widget_grid_setting'] )){
	            $grid_val = $settings['widget_grid_setting'];
	        }else{
	            $grid_val = 4;
	        }

	        if(!empty( $settings['widget_per_page'] )){
	            $per_page = $settings['widget_per_page'];
	        }else{
	            $per_page = 12;
	        }

	        if(!empty( $settings['widget_text_color'] )){
	            $text_color = $settings['widget_text_color'];
	        }else{
	            $text_color = '#f47630';
	        }

	        if(!empty( $settings['widget_text_size'] )){
	            $text_size = $settings['widget_text_size'];
	        }else{
	            $text_size = '15';
	        }


	        

	      	$atts = shortcode_atts(array(
		        'order' => '',
		        'orderby' => '',
	        ),$atts, 'widget_weekly_product'); 

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

	        


	        if(!empty($atts['show_add_to_cart'])){
	            $show_add_to_cart = $atts['show_add_to_cart'];
	        }else{
	            $show_add_to_cart = 'No';
	        }

	        $args = array(
	                'posts_per_page'    =>  $per_page,
	                'post_type'         => array('product'/* ,'product_variation' */),
	                'post_status'       => 'publish',
	                'orderby'           => $popup_orderby,
	                'order'             => $popup_order,
	                'meta_query'        => array (
	                    'relation'      => 'OR',
	                    'import_invoice_date'   => array (
	                        'key'       => 'import_invoice_date',
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
	            <style type="text/css">
	            	.widget_prod_links a{
	            		color:<?php echo $text_color; ?>;
	            	}
	            	.widget_prod_links{
	            		font-size:<?php echo $text_size.'px'; ?>;
	            	}
	            </style>
	            <section id="widget_section" class="widget_section">
	              <?php

	              	

	              $i = 0;
	              while ( $prod_query->have_posts() ): $prod_query->the_post();
	                  $product = new WC_Product(get_the_ID());

	                  $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
	                  $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

	                  $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

	                  $aws_img = fetch_image_from_AWS( get_the_ID() );
	                  if(!empty($aws_img)){
	                    $image_info = getimagesize( $prod_img ); 
	                    $prod_img = $aws_img;

	                  }else{
	                    $prod_img = 'https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg';
	                  }
	                  


	                  	$grid = $grid_val;
		     			if($i == $grid || $i == 0){
		     				$class = "first";
		     			}else{
		     				$class = "";
		     			}
		                echo '<div class="widget_col grid_1_of_'.$grid.' '.$class.'">';
			                ?>
			                <div class="widget_product_column product-block" data-dimond="<?php echo $dimond_no; ?>">
		                        <a target="_blank" href="<?php  echo get_permalink(get_the_ID()); ?>">
		                          	<div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('<?php echo $prod_img; ?>')">
		                                <div class="vyte-overlay"></div>
		                          	</div>
		                      	</a>
		                        <div class="product-info">
		                            <div class="widget_prod_links">
		                                <a target="_blank" href="<?php  echo get_permalink(get_the_ID()); ?>"><?php echo get_the_title();  ?> </a>
		                            </div>
		                        </div>
		                    </div>
			                <?php
		                echo '</div>';

		                

		                if($i == $grid){
		                	$i = 0;
		                }
		                
		                // echo 'i --> '.$i.' - '.$grid;

		                $i++;

	              endwhile;

	            ?>
	          </section>


	        <?php

	        if ( $prod_query->max_num_pages > 1 ) {
	            echo '<div id="widget_loadmore" class="widget_loadmore" data-args="' . esc_attr( json_encode( $args ) ) . '" data-max-page="' . $prod_query->max_num_pages . '" data-current-page="1">More posts</div>';
	          } 

	    }
	    return ob_get_clean();
	  }

}/* End Class */

$WidgetShortcode = new ClassWidgetShortcode();
