(function ($) {

    //$( document ).on( 'click', 'a[rel="prettyPhoto[ywau_upload_rules]"]', function (e) {

        $( document ).on( 'click', 'a.ywau_pp_no_loaded', function (e) {


            if ( $( this ).hasClass( 'ywau_pp_no_loaded' ) ){

            $( this ).prettyPhoto({
                markup: '<div id="ywau_upload_modal" class="pp_pic_holder"> \
    						<div class="ppt">&nbsp;</div> \
    						<div class="pp_top"> \
    							<div class="pp_left"></div> \
    							<div class="pp_middle"></div> \
    							<div class="pp_right"></div> \
    						</div> \
    						<div class="pp_content_container"> \
    							<div class="pp_left"> \
    							<div class="pp_right"> \
    								<div class="pp_content"> \
    									<div class="pp_loaderIcon"></div> \
    									<div class="pp_fade"> \
    										<div id="pp_full_res"></div> \
    										<div class="pp_details"> \
    											<a class="pp_close" href="#">Close</a> \
    										</div> \
    									</div> \
    								</div> \
    							</div> \
    							</div> \
    						</div> \
    						<div class="pp_bottom"> \
    							<div class="pp_left"></div> \
    							<div class="pp_middle"></div> \
    							<div class="pp_right"></div> \
    						</div> \
    					</div> \
    					<div class="pp_overlay"></div>',
                gallery_markup: '<div class="pp_gallery"> \
    								<a href="#" class="pp_arrow_previous">Previous</a> \
    								<div> \
    									<ul> \
    										{gallery} \
    									</ul> \
    								</div> \
    								<a href="#" class="pp_arrow_next">Next</a> \
    							</div>',
                hook: 'rel',
                social_tools: false,
                theme: 'pp_woocommerce',
                default_width: '500px',
                default_height: '80%',
                horizontal_padding: 20,
                opacity: 0.8,
                deeplinking: false,
                keyboard_shortcuts: true
            });

            $( this ).removeClass( 'ywau_pp_no_loaded' )

            $( this ).click();
        }

    });

})
(jQuery);
