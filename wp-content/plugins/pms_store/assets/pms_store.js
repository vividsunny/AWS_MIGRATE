jQuery(document).ready(function() {

	jQuery(".site_blog").on('click', function() {
		var fields = '';
		jQuery(".site_blog").each(function() {
			if ( this.checked ) {
				jQuery(".sync_button").text('Sync Product');
				jQuery(".sync_button").css('background','#007cba');
				jQuery(".sync_button").css('border-color','#007cba');
				fields += jQuery( this ).val() + ',';
			}
		});
		jQuery('#blog_ids').val( jQuery.trim( fields ) )
	});


	jQuery(".sync_button").on('click', function() {
		var blog_ids = jQuery("#blog_ids").val();
		var current_post = jQuery("#current_post").val();
		$this = jQuery(this);
		console.log( blog_ids );

		if( blog_ids == ''){
			$this.text('Please select subsite.');
			$this.css('box-shadow','none');
			$this.css('background','#a00');
			$this.css('border-color','#a00');
		}else{
			$this.css('background','#007cba');
			$this.css('border-color','#007cba');
			$this.text('Updating...');
			$this.attr('disabled','disabled');
			
			jQuery('.pms_toolbar .spinner').addClass('is-active');
			jQuery.ajax({
				url: pms_store_script.ajax_url,
				type: "POST",
				data:{	
					blog_ids:blog_ids, 
					current_post:current_post, 
					action:'sync_product',
				},
				dataType: "json",
				success:function(data){
					$this.text('Done');
					jQuery('.pms_toolbar .spinner').removeClass('is-active');
					console.log('Done');
				}
			});
		}
		

	});

});