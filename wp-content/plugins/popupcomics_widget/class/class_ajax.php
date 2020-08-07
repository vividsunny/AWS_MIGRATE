<?php
/**
 * 
 */
class WidgetAjax
{
	
	function __construct()
	{
		# Filter
		add_action('wp_ajax_func_widget_import_date_filter', array( &$this,'vvd_func_widget_import_date_filter'));
		add_action('wp_ajax_nopriv_func_widget_import_date_filter',array( &$this, 'vvd_func_widget_import_date_filter'));

		# Load more
		add_action('wp_ajax_widget_loadmore', array( &$this, 'vvd_func_widget_loadmore')); 
		add_action('wp_ajax_nopriv_widget_loadmore', array( &$this, 'vvd_func_widget_loadmore'));

		# Generate File
		add_action('wp_ajax_widget_loadmore', array( &$this, 'vvd_func_widget_loadmore')); 
		add_action('wp_ajax_nopriv_widget_loadmore', array( &$this, 'vvd_func_widget_loadmore')); 

		add_action( 'wp_ajax_generate_file', array( &$this, 'generate_file_handler' ) );
		add_action( 'wp_ajax_nopriv_generate_file', array( &$this, 'generate_file_handler' ) );

	}

	
	public function vvd_func_widget_import_date_filter() {

	    global $wp;
	    $current_url = home_url();

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
        
	    $latest_import_date = $_POST['widget_sel_import_date'];
	    $args = array(
	        'posts_per_page' => $per_page,
	        'post_type' => array('product'/* ,'product_variation' */),
	        'post_status' => 'publish',
	        'orderby' => 'date',
	        'order' => 'desc',
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



	    $i = 0;
	    $html = '';
	    while ( $prod_query->have_posts() ): $prod_query->the_post();
	        $product = new WC_Product(get_the_ID());

	        $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
	        $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

	        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

	        if(!empty($aws_key)){
	            $prod_img = "https://s3.us-east-2.amazonaws.com/darksidecomics/".$aws_key;
	          }else{
	            $prod_img = 'https://popupcomicshop.staging.wpengine.com/wp-content/uploads/woocommerce-placeholder.png';
	          }

	        $loader = "/wp-admin/images/spinner.gif";

	        $grid = $grid_val;
			if($i == $grid || $i == 0){
				$class = "first";
			}else{
				$class = "";
			}

	        $html .='<div class="widget_col grid_1_of_'.$grid.' '.$class.'">
	            <div class="widget_product_column product-block">
	            <a target="_blank" href="'.get_permalink(get_the_ID()).'">
	                <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('.$prod_img.')">
	                        <div class="vyte-overlay"></div>
	                </div></a>
	                <div class="product-info">
	                    <div class="widget_prod_links">
	                        <a target="_blank" href="'.get_permalink(get_the_ID()).'">'.get_the_title().' </a>
	                    </div>
	                </div>
	            </div>
	        </div>';

	        if($i == $grid){
            	$i = 0;
            }
            
            $i++;
	        
	    endwhile; 
	       if ( $prod_query->max_num_pages > 1 ) {
	          $loadmore .= '<div id="widget_loadmore" class="widget_loadmore" data-args="' . esc_attr( json_encode( $args ) ) . '" data-max-page="' . $prod_query->max_num_pages . '" data-current-page="1">More posts</div>';
	        } 
	        $json['success'] = true;
	        $json['html'] = $html;
	        $json['loadmore'] = $loadmore;
	        echo json_encode($json);

	    wp_die();
	}

	public function vvd_func_widget_loadmore(){
	 
	    $args = $_POST['query'];
	    $args['paged'] = $_POST['page'] + 1; // next page of posts

	    $prod_query = new WP_Query($args);

	    if ( ! $prod_query->have_posts() ) {
	        $json['error'] = true;
	        $json['message'] =  __("No Product found!.", "tamberra") ;
	        echo json_encode($json);
	        die();
	    } 

	    $settings = get_option( "widget_theme_settings" );
	        	// $grid_val = $settings['widget_grid_setting'];

    	if(!empty( $settings['widget_grid_setting'] )){
            $grid_val = $settings['widget_grid_setting'];
        }else{
            $grid_val = 4;
        }
        
	    $i = 0;
	    $html = '';
	    while ( $prod_query->have_posts() ): $prod_query->the_post();
	        $product = new WC_Product(get_the_ID());

	        $dimond_no = get_post_meta(get_the_ID(), 'diamond_number', true);
	        $aws_key = get_post_meta(get_the_ID(), 'aws_key', true);

	        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

	        if(!empty($aws_key)){
	            $prod_img = "https://s3.us-east-2.amazonaws.com/darksidecomics/".$aws_key;
	          }else{
	            $prod_img = 'https://popupcomicshop.staging.wpengine.com/wp-content/uploads/woocommerce-placeholder.png';
	          }

	        $loader = "/wp-admin/images/spinner.gif";
	        
	        $grid = $grid_val;
			if($i == $grid || $i == 0){
				$class = "first";
			}else{
				$class = "";
			}

	        $html .='<div class="widget_col grid_1_of_'.$grid.' '.$class.'">
	            <div class="widget_product_column product-block">
	            <a target="_blank" href="'.get_permalink(get_the_ID()).'">
	                <div class="product-img" style="background-repeat: no-repeat;background-size: cover;background-position: center;height: 340px;background-image:url('.$prod_img.')">
	                        <div class="vyte-overlay"></div>
	                </div></a>
	                <div class="product-info">
	                    <div class="widget_prod_links">
	                        <a target="_blank" href="'.get_permalink(get_the_ID()).'">'.get_the_title().' </a>
	                    </div>
	                </div>
	            </div>
	        </div>';

	        if($i == $grid){
            	$i = 0;
            }
            
            $i++;
	        
	    endwhile; 
	    

	    $json['success'] = true;
	    $json['html'] = $html;
	    echo json_encode($json);

	    wp_die();
	}


	public function generate_file_handler(){
	    // $blog_id = 4;
	    $blog_id = get_current_blog_id();
	    $site = site_url();
	    
	    // vivid( plugins_url() );
	    $content = 'document.write("<div class=\'resp-container\'><iframe class=\'resp-iframe\' src=\''.$site.'/widget-script/\' allow=\'encrypted-media\' allowfullscreen style=\'position: absolute;top: 0;left: 0;width: 100%;height: 100%;border: 0;\'></iframe></div>");';

	    /* Create Dir And create File */
	    $this->setup_order_log_dir( $blog_id, $content );

	    /* Set content inti File */
	    $this->aws_upload_log( $blog_id, $content );

	    wp_die();
	}

	public function vvd_plugin_dir() {
	    return plugin_dir_path(__FILE__);
	}

	public function vvd_plugin_url() {
	    return plugin_dir_url(__FILE__);
	}

	public function aws_upload_log($blog_id, $str) {

	    $dir = $this->vvd_plugin_dir();
	    $plugin_url = $this->vvd_plugin_url();
	    $file_dir = $dir."file";
	    $blog_folder = $file_dir.'/'.$blog_id;

	    file_put_contents($blog_folder."/popupcomics_Widget_custom.js", "");
	    error_log($str.PHP_EOL, 3, $blog_folder."/popupcomics_Widget_custom.js");
	    
	  }

	public function setup_order_log_dir($blog_id, $content){

	    $dir = $this->vvd_plugin_dir();
	    $plugin_url = $this->vvd_plugin_url();
	    $file_dir = $dir."file";
	    $blog_folder = $file_dir.'/'.$blog_id;
	    
	    if ( ! is_dir( $blog_folder ) ) {
	      wp_mkdir_p( $blog_folder, 0777 );

	      if ( $file_handle = @fopen( trailingslashit( $blog_folder ) .'popupcomics_Widget_custom.js', 'w' ) ) {
	        fwrite( $file_handle, '' );
	        fclose( $file_handle );
	      }

	    }


	}



} /* End Class */

$ajax = new WidgetAjax();