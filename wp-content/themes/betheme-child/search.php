<?php
/**
* The search template file.
*
* @package Betheme
* @author Muffin group
* @link http://muffingroup.com
*/

get_header();

$translate['search-title'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-title','Ooops...') : __('Ooops...','betheme');
$translate['search-subtitle'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-subtitle','No results found for:') : __('No results found for:','betheme');

$translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');
$translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');
$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
?>

<div id="Content">
	<div class="content_wrapper clearfix">


		<!-- .sections_group -->
		<div class="sections_group">

			<div class="section">
				<div class="section_wrapper clearfix">

					<?php if( have_posts() && trim( $_GET['s'] ) ): ?>

					<div class="column one column_blog">	
						<div class="blog_wrapper isotope_wrapper">

							<div class="posts_group classic">
								<?php
								while ( have_posts() ):
									the_post();
									?>
									<div id="post-<?php the_ID(); ?>" <?php post_class( array('post-item', 'clearfix', 'no-img') ); ?>>

										<div class="post-desc-wrapper">
											<div class="post-desc">

												<?php if( mfn_opts_get( 'blog-meta' ) ): ?>
													<div class="post-meta clearfix">
														<div class="author-date">
															<span class="author"><span><?php echo $translate['published']; ?> </span><i class="icon-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author_meta( 'display_name' ); ?></a></span>
															<span class="date"><span><?php echo $translate['at']; ?> </span><i class="icon-clock"></i> <?php echo get_the_date(); ?></span>
														</div>
													</div>
												<?php  endif; ?>


												<div class="post-title">
													<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
												</div>

												<div class="post-thumbnail">
													<?php
													$id = get_the_ID();
													$thumb = find_thumbnail($id);
													if(!empty($thumb)){
														echo $thumb;
													}else{
														if ( has_post_thumbnail() ) { 
												            the_post_thumbnail();
												        } else {
												            //your default img
												        }
													}
												?>
												</div>

												<div class="post-excerpt">
													<?php the_excerpt(); ?>
												</div>

												<div class="post-footer">
													<div class="post-links">
														<i class="icon-doc-text"></i> <a href="<?php the_permalink(); ?>" class="post-more"><?php echo $translate['readmore']; ?></a>
													</div>
												</div>

											</div>
										</div>
									</div>
									<?php
								endwhile;
								?>
							</div>

							<?php	
// pagination
							if(function_exists( 'mfn_pagination' )):
								echo mfn_pagination();
							else:
								?>
								<div class="nav-next"><?php next_posts_link(__('&larr; Older Entries', 'betheme')) ?></div>
								<div class="nav-previous"><?php previous_posts_link(__('Newer Entries &rarr;', 'betheme')) ?></div>
								<?php
							endif;
							?>

						</div>
					</div>

					<?php else: ?>

						<div class="column one search-not-found">

							<div class="snf-pic">
								<i class="themecolor <?php mfn_opts_show( 'error404-icon', 'icon-traffic-cone' ); ?>"></i>
							</div>

							<div class="snf-desc">
								<h2><?php echo $translate['search-title']; ?></h2>
								<h4><?php echo $translate['search-subtitle'] .' '. esc_html( $_GET['s'] ); ?></h4>
							</div>	

						</div>	

					<?php endif; ?>

				</div>
			</div>

		</div>


		<!-- .four-columns - sidebar -->
		<?php if( is_active_sidebar( 'mfn-search' ) ):  ?>
			<div class="sidebar four columns">
				<div class="widget-area clearfix <?php mfn_opts_show( 'sidebar-lines' ); ?>">
					<?php dynamic_sidebar( 'mfn-search' ); ?>
				</div>
			</div>
		<?php endif; ?>


	</div>
</div>


<?php 
function find_thumbnail($post_id){
			$path = get_post_meta($post_id ,"Image Path", true);

			if(!empty($path)){
				$pro = $path;
			}else{
				$pro = get_post_meta($post_id ,"Image-Path", true);
			}

			if(!empty($pro)){
				$pro = str_replace("diamondcomics.com","",$pro);
				$s3_image = substr($pro, 6);

				require(get_stylesheet_directory().'/aws/vendor/autoload.php');
				$s3 = new Aws\S3\S3Client([
					'region'  => 'us-east-2',
					'version' => 'latest',
					'credentials' => [
						'key'    => 'AKIAWAJYLDONJ4G3V3NJ',
						'secret' => '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',
					]
				]); 
				$bucket = 'darksidecomics';
				$filename  = "previews/".$s3_image;
				$info = $s3->doesObjectExist($bucket, $filename);

				$zip_filename  = "zip-image/".$s3_image;
				$zip_file = $s3->doesObjectExist($bucket, $zip_filename);

				if($info)
				{

					$html = '<a href="'.get_permalink($post_id).'"><img width="97" height="146" src="https://s3.us-east-2.amazonaws.com/darksidecomics/'.$filename.'" alt="product image" class="wp-post-image"></a>';
					
				}else if($zip_file){

					$html = '<a href="'.get_permalink($post_id).'"><img width="97" height="146" src="https://s3.us-east-2.amazonaws.com/darksidecomics/'.$zip_filename.'" alt="product image" class="wp-post-image"></a>';
					
				}else{

					$html ='<a href="'.get_permalink($post_id).'"><img width="97" height="146" src="https://popshopcom.s3.amazonaws.com/uploads/2018/09/comingsooncolor.jpg" alt="Awaiting product image" class="wp-post-image"></a>';
				}

				return $html;
			}
			

			
}
get_footer();

// Omit Closing PHP Tags