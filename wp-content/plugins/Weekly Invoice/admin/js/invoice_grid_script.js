jQuery(document).ready(function(){
    jQuery('.filter_loader').hide();

    /* _submit_search_form */
    jQuery('body').on('click','.weekley_submit_search_form',function(){
    // jQuery('.weekley_submit_search_form').on('click',function(){
        jQuery('.filter_loader').show();
        var fd = new FormData();
        var serializedValues = jQuery("#weekley_prod_filter").serializeArray();
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
                       
                       if(jQuery('#misha_loadmore').length > 0 ){
                        jQuery('#misha_loadmore').remove();
                       }else{
                        jQuery('#weeleky_cards').after(response.loadmore);
                       }

                      if(response.morepost){
                        console.log('In More POst');
                        if(jQuery('#more_posts').length > 0 ){
                          jQuery('#more_posts').remove();
                          jQuery('#weeleky_cards').after(response.morepost);
                          console.log('In More POst > 0');
                          
                        }else{
                          console.log('In More POst ELSE ');
                           jQuery('#more_posts').remove();
                        }
                      }
                   }
               },
           });

    });
}); /* Document ready */