jQuery(document).ready(function(){
    jQuery('.widget_loader').hide();

    /* _submit_search_form */
    jQuery('.widget_submit_search_form').on('click',function(){
        jQuery('.widget_loader').show();
        var fd = new FormData();
        var serializedValues = jQuery("#widget_prod_filter").serializeArray();
        jQuery.each(serializedValues, function (key, input) {
            fd.append(input.name, input.value);
        });

        var ajaxurl = widget_script.ajax_url;
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) { 
                        //console.log(response); 
                        jQuery('.widget_loader').hide();
                        if(response.success == true){
                          console.log('Done'); 
                      }
                      if(response.error == true){
                       jQuery('#widget_section').html();
                       jQuery('#widget_section').html(response.message);
                      }

                   if(response.success){
                       jQuery('#widget_section').html();
                       jQuery('#widget_section').html(response.html);
                       if(jQuery('#widget_loadmore').length > 0){
                        jQuery('#widget_loadmore').remove();
                       }else{
                        jQuery('#widget_section').after(response.loadmore);
                       }
                       
                   }
               },
           });

    });
}); /* Document ready */