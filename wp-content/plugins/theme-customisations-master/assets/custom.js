jQuery(document).ready(function($){
	// Custom jQuery goes here

	jQuery('.products_wrapper').append('<section id="result" style="max-width: 1102px;margin: 0 auto 30px;"></section>');

	jQuery('.product_type_simple').on('click',function(){
		jQuery('.product_type_simple.active').removeClass('active');
		jQuery(this).addClass('active');
        var $this = jQuery(this);
        var fillter = jQuery(this).attr('data-filter');
	    var data_name = jQuery(this).attr('data-name');
	    if( fillter != ''){
            $this.text('loading...');
	    	// hash_product_script( fillter );
            var ajaxurl = custom_var.ajax_url;
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'hash_product_ajax',
                    fragment: fillter,
                },
                success: function( response ) {
                    jQuery('#vvd_preloader').fadeOut(150);
                    // console.log(response);
                    if ( response.success ) {
                        
                        jQuery('.woocommerce-result-count').fadeOut(150);
                        jQuery('.woocommerce-ordering').fadeOut(150);
                        jQuery('.woocommerce ul.products').fadeOut(150);
                        jQuery('.pager_wrapper').fadeOut(150);
                        console.log(response.success);
                        jQuery('#result').html('');
                        jQuery('#result').html( response.html );
                        $this.text( data_name );
                    }else{
                        console.log(response.data.message);
                    }
                }   
            });
	    }
	});

	function active_class(){
		jQuery('.product_type_simple').on('click', function(){
		    jQuery('.product_type_simple.active').removeClass('active');
		    jQuery(this).addClass('active');
		});

	}



	function hash_product_script($fillter){
		var ajaxurl = custom_var.ajax_url;
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'hash_product_ajax',
                fragment: $fillter,
            },
            success: function( response ) {
            	jQuery('#vvd_preloader').fadeOut(150);
                // console.log(response);
                if ( response.success ) {
                	
                	jQuery('.woocommerce-result-count').fadeOut(150);
                	jQuery('.woocommerce-ordering').fadeOut(150);
                	jQuery('.woocommerce ul.products').fadeOut(150);
                	jQuery('.pager_wrapper').fadeOut(150);
                    console.log(response.success);
                	jQuery('#result').html('');
                    jQuery('#result').html( response.html );
                    $this.text('Done');
                }else{
                    console.log(response.data.message);
                }
            }   
        });

    }


});



jQuery(document).ready(function(){
    jQuery('.filter_loader').hide();

    /* _submit_search_form */
    jQuery('body').on('click','.preview_submit_search_form',function(){
    // jQuery('.weekley_submit_search_form').on('click',function(){
        jQuery('.filter_loader').show();
        var fd = new FormData();
        var serializedValues = jQuery("#preview_prod_filter").serializeArray();
        jQuery.each(serializedValues, function (key, input) {
            fd.append(input.name, input.value);
        });

        var ajaxurl = invoice_script.ajax_url;
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) { 
                        //console.log(response); 
                        jQuery('.filter_loader').hide();
                        if(response.success == true){
                            console.log('Done'); 
                        }
                      if(response.error == true){
                       jQuery('#weeleky_cards').html();
                       jQuery('#weeleky_cards').html(response.message);
                      }

                   if(response.success){
                       jQuery('#weeleky_cards').html();
                       jQuery('#weeleky_cards').html(response.html);
                       if(jQuery('#misha_loadmore').length > 0){
                        jQuery('#misha_loadmore').remove();
                       }else{
                        jQuery('#weeleky_cards').after(response.loadmore);
                       }
                       
                   }
               },
           });

    });
}); /* Document ready */