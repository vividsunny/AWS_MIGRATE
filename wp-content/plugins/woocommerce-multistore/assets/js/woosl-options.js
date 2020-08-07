;(function ( $, window, document, undefined ) {

	$( function() {
		jQuery( '.tips' ).tipTip( {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		} );

		var tabs = $( "#fields-control" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
		$( "#fields-control li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
		tabs.find( ".ui-tabs-nav" ).sortable({
			axis: "y",
			stop: function() {
				tabs.tabs( "refresh" );
			}
		});
	} );

})( $ms, window, document );
