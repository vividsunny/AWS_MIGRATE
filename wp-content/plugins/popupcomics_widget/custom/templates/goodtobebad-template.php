<?php
/*
 * Template Name: Good to Be Bad
 * Description: A Page Template with a darker design.
 */
get_header();
?>
<style type="text/css">
	html{
		background-image: unset;
    	background-color: rgba(51,51,51,0.5);
	}
	#Header_wrapper, #Footer{
		display:none;
	}
	.layout-boxed #Wrapper{
		box-shadow: none;
	}

	.iframe-container {
	  overflow: hidden;
	  /*// Calculated from the aspect ration of the content (in case of 16:9 it is 9/16= 0.5625)*/
	  padding-top: 56.25%;
	  position: relative;
	}
 
	.iframe-container iframe {
	   border: 0;
	   height: 100%;
	   left: 0;
	   position: absolute;
	   top: 0;
	   width: 100%;
	}

	.resp-container {
	    /*position: relative;
	    overflow: hidden;
	    padding-top: 56.25%;*/
	}

	.resp-iframe {
    	position: absolute;
	    top: 0;
	    left: 0;
	    width: 100%;
	    height: 100%;
	    border: 0;
	}

</style>

<div class="resp-container">
    <div class="resp-container">
	    <?php echo do_shortcode('[widget_weekly_product]'); ?>
	</div>
</div>


<?php
get_footer();
?>
