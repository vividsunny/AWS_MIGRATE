jQuery(function($){
 
	/*
	 * Button click event
	 */
	$('body').on('click', '#widget_loadmore', function(){
		var button = $(this),
		    queryArgs = button.data( 'args' ),
		    maxPage = button.data( 'max-page' ),
		    currentPage = button.data( 'current-page' );
 
		$.ajax({
			url : widget_loadmore_params.ajaxurl, // AJAX handler
			data : {
				'action': 'widget_loadmore', // the parameter for admin-ajax.php
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
				// console.log('currentPage --> ' + currentPage);
				// button.text('More Posts').data( 'current-page', currentPage ).before(data.html);
				button.text('More Posts').data( 'current-page', currentPage );
 				jQuery('#widget_section').append(data.html);
				if( currentPage == maxPage ) {
					button.remove();
				}
 
			}
		});
		return false;
	});
 
});