jQuery(document).ready(function(){
    jQuery('.filter_loader').hide();

    /* _submit_search_form */
    jQuery('._submit_search_form').on('click',function(){
        jQuery('.filter_loader').show();
        var fd = new FormData();
        var serializedValues = jQuery("#prod_filter").serializeArray();
        jQuery.each(serializedValues, function (key, input) {
            fd.append(input.name, input.value);
        });

        var ajaxurl = shortcode_ajax.ajax_url;
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
                       jQuery('#cards').html();
                       jQuery('#cards').html(response.message);
                   }

                   if(response.success){
                       jQuery('#cards').html();
                       jQuery('#cards').html(response.html);
                   }
               },
           });

    });

    /* _submit_search_form */
    jQuery('._submit_import_date_cat').on('click',function(){
        jQuery('#import_date_cat .filter_loader').show();
        var fd = new FormData();
        var serializedValues = jQuery("#import_date_cat").serializeArray();
        jQuery.each(serializedValues, function (key, input) {
            fd.append(input.name, input.value);
        });

        var ajaxurl = shortcode_ajax.ajax_url;
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (response) { 
                        //console.log(response); 
                        jQuery('#import_date_cat .filter_loader').hide();
                        if(response.success == true){
                          console.log('Done'); 
                      }
                      if(response.error == true){
                       jQuery('#import_date_category').html();
                       jQuery('#import_date_category').html(response.message);
                   }

                   if(response.success){
                       jQuery('#import_date_category').html();
                       jQuery('#import_date_category').html(response.html);
                   }
               },
           });

    });/* End */


    /* Subscription */

    jQuery('.add_parent_product_id').click(function(){
      
      var parent_product_id = jQuery(this).attr('data-product_id');
      var user_id = jQuery(this).attr('data-user_id');
      var ajaxurl = shortcode_ajax.ajax_url;
      var $this = jQuery(this);
      jQuery('#sub_btn').text('Loading..');
      jQuery.post(
        ajaxurl, 
        {
          'action'   : 'add_customer_into_series',
          'parent_product_id'   : parent_product_id,
          'user_id'   : user_id,
        }, 
        function(response){

          var response = jQuery.parseJSON(response);
          console.log(response);
          if(response.success){
            console.log('Success');
            jQuery('#sub_btn').text('Done');
            jQuery('#sub_btn').text(response.message);
          }

        });
      return false;

    }); /* End */

}); /* Document ready */