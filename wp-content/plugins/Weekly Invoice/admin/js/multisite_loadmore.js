var canBeLoaded;

jQuery(function($){
 
	/*
	 * Button click event
	 */
	$('body').on('click', '#misha_loadmore', function(){
		var button = $(this),
		    queryArgs = button.data( 'args' ),
		    maxPage = button.data( 'max-page' ),
		    currentPage = button.data( 'current-page' );
 
		$.ajax({
			url : misha_loadmore_params.ajaxurl, // AJAX handler
			data : {
				'action': 'multisiteloadmore', // the parameter for admin-ajax.php
				'query': queryArgs,
				'page' : currentPage,
			},
			//contentType: false,
            //processData: false,
            dataType: 'JSON',
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Loading...'); // some type of preloader
			},
			success : function( data ){
 
				currentPage++;
				// canBeLoaded = true;
				jQuery('#canBeLoaded').val('true');
				// console.log( canBeLoaded );
				//button.text('More Posts').data( 'current-page', currentPage ).before(data.html);
				button.text('More Posts').data( 'current-page', currentPage );
 				jQuery('#weeleky_cards').append(data.html);
				if( currentPage == maxPage ) {
					button.remove();
				}
 				
			}
		});
		return false;
	});
 
});

jQuery(function($){
	var bottomOffset = 850; // the distance (in px) from the page bottom when you want to load more posts
 	
 	// var $el = jQuery('#misha_loadmore');  //record the elem so you don't crawl the DOM everytime  
	// var bottomOffset = $el.position().top + $el.outerHeight(true);
	
	$(window).scroll(function(){
		canBeLoaded = jQuery('#canBeLoaded').val();
		// console.log('canBeLoaded --> ' + canBeLoaded);
		if( $(document).scrollTop() > ( $(document).height() - bottomOffset ) && canBeLoaded == 'true' ){
			// console.log('Inn IFFF');
			// console.log( 'Offset --> '+ $(document).height() - bottomOffset );

			if($('#misha_loadmore').length){
				jQuery('#misha_loadmore').trigger('click');
				canBeLoaded = 'false';
				jQuery('#canBeLoaded').val('false');
			}else{
				// console.log("misha_loadmore does not exists");
			}

			
		}
		// return false;
	});
});