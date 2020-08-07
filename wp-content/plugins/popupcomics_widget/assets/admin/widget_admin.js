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
				$this.closest('.ax_series_list_item_container').remove();
			}
		);
	});

	jQuery('#generate_script').on('click',function(){
	    console.log('Bingo...');

	    $this = jQuery(this);
		$this.text('Loading..');

	    var ajaxurl = widget_admin_script.ajax_url;
		jQuery.post(
			ajaxurl,
			{
				action : 'widger_generate_ajax',
				// user_id : user_id,
				// series_id : series_id,
			},function(response){
				console.log( response );
			}
		);

	});

	/* Generate File Ajax */
	jQuery('#generate').on('click',function(){
                
        $this = jQuery(this);
        $this.text('Loading..');
        /*var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';*/
        var ajaxurl = widget_admin_script.ajax_url;
        jQuery.post(
            ajaxurl,
            {
                action : 'generate_file',
            },function(response){
                $this.text('File Generated.');
                jQuery('body').find('.show_code').show();
            }
        );
    });


});

jQuery(document).ready(function($) {
	jQuery('.widget-color-field').wpColorPicker();
});