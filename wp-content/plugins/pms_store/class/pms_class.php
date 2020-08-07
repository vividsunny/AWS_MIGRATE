<?php

/**
 * 
 */
class pms_class
{
	
	function __construct()
	{
		# code...
		add_action('admin_head', array( &$this, 'PMS_store_css_icon' ) );
		add_filter('woocommerce_product_data_tabs', array( &$this, 'PMS_store_product_data_tabs' ) );
		add_action('woocommerce_product_data_panels',array( &$this, 'PMS_store_product_data_panels' ) );
	}

	public function PMS_store_css_icon(){
		echo '<style>
		#woocommerce-product-data ul.wc-tabs li.blog_sync_options.blog_sync_tab a:before{
			content: "\f487";
		}
		</style>';
	}

	public function PMS_store_product_data_tabs($tabs) {
		$tabs['blog_sync'] = [
			'label' => __('Popupcomics Multistore', 'txtdomain'),
			'target' => 'blog_sync_product_data',
			'class' => ['hide_if_external'],
			'priority' => 25
		];
		return $tabs;
	}

	public function PMS_store_product_data_panels( $post_id ) { ?>
		<div id="blog_sync_product_data" class="panel woocommerce_options_panel hidden">
		<?php
	 	
	 	global $post, $post_id;

	    // get post by post id
	    $post_id = $post->ID;

		$all_blog = wp_get_sites();

		foreach ($all_blog as $key => $current_blog) {
			// switch to each blog to get the posts

			$blog_id = $current_blog['blog_id'];
			$current_blog_details = get_blog_details( array( 'blog_id' => $blog_id ) );
			$blog_name = $current_blog_details->blogname;

			if ( is_main_site($blog_id) ){ ?>
				<p class="form-field hide_if_grouped">
					<label for="dummy_checkbox"><?php echo $blog_name.' - (Main blog)';  ?></label>
					<input type="checkbox" class="site_blog pms_disable" name="site_blog" value="<?php echo $blog_id; ?>" data-post_id="<?php echo $post_id; ?>" onclick="return false;"> 
				</p>
				<?php
			}else{ ?>
				<p class="form-field hide_if_grouped">
					<label for="dummy_checkbox"><?php echo $blog_name; ?></label>
					<input type="checkbox" class="site_blog" name="site_blog" value="<?php echo $blog_id; ?>" data-post_id="<?php echo $post_id; ?>"> 
				</p>
				<?php
			}
			

		}
	 
		?>
		<div class="pms_toolbar">
			<input type="hidden" name="blog_ids" value="" id="blog_ids">
			<input type="hidden" name="current_post" value="<?php echo $post_id; ?>" id="current_post">
			<button type="button" class="button sync_button button-primary">Sync Product</button>
			<span class="spinner"></span>
		</div>
		</div>
		<?php
	}

}

$pms_class = new pms_class();