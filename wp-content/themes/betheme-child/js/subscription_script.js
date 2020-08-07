jQuery(document).ready(function(){
	jQuery('.sub_remove_user').on('click',function(){
		var series_id,user_id,$this;
		series_id = jQuery(this).attr('data-seriesid');
		user_id = jQuery(this).attr('data-user_id');
		console.log(series_id);
		console.log(user_id);
		$this = jQuery(this);
		$this.text('Loading..');
		var ajaxurl = subscription.ajax_url;
		jQuery.post(
			ajaxurl,
			{
				action : 'subscribtion_remove_',
				user_id : user_id,
				series_id : series_id,
			},function(response){
				//$this.closest('.ax_series_list_item_container').remove();
                document.location.reload();  
			}
		);
	});

    /* Subscription */
    jQuery('.add_parent_product_id').click(function(){
    	
    	var parent_product_id = jQuery(this).attr('data-product_id');
    	var user_id = jQuery(this).attr('data-user_id');
    	var ajaxurl = subscription.ajax_url;
    	var $this = jQuery(this).find('.button');
        $this.text('Loading..');
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
                    //$this.text('Done');
                    document.location.reload();   
                    //$this.text(response.message);
    			}

    		});
    	return false;

    }); /* End */
    
});