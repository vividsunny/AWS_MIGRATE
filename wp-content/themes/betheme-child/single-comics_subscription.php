<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); 

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
?>
<!-- #Content -->
<div id="Content">
    <div class="content_wrapper clearfix">

        <!-- .sections_group -->
        <div class="sections_group">
            <div class="section">
                <div class="section_wrapper clearfix">
                    <div class="items_group clearfix">
                        <div class="column one">
                            <?php
                            // Start the loop.
                            while ( have_posts() ) : the_post();
                                
                                ?>
                                <div class="subscription_wrapper clearfix">
                                    <div class="column one-second subscription_image_wrapper">
                                        <?php echo mfn_post_thumbnail( get_the_ID() ); ?>
                                    </div>
                                    <div class="summary entry-summary column one-second">
                                        <h3><?php the_title(); ?></h3>

                                        <p>
                                            <button type="button" class="comics_subscribe_btn button alt" data-userid="<?php echo $user_id; ?>" data-postid="<?php echo get_the_ID(); ?>">Subscribe</button>
                                        </p>
                                    </div>
                                </div>
                                <?php
                            // End the loop.
                            endwhile;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- .four-columns - sidebar -->
        <?php get_sidebar(); ?>

    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        console.log('BINGO');
        /* Subscribe comics */
        jQuery('.comics_subscribe_btn').click(function(){
            var userid = jQuery(this).attr('data-userid');
            var postid = jQuery(this).attr('data-postid');
            console.log(userid);
            console.log(postid);

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        jQuery.post(
            ajaxurl, 
            {
                'action'      : 'comics_subscribe_data',
                'userid'   : userid,
                'postid'   : postid,
            }, 
            function(response){

                var response = jQuery.parseJSON(response);
                console.log(response);
            }
        );
     return false;
        });
    });
</script>
<?php get_footer();
