
jQuery( document ).ready( function () {
    jQuery( '#hrr_request_fromdate' ).datepicker( {
        changeMonth : true ,
        changeYear : true ,
        onClose : function ( selectedDate ) {
            var maxDate = new Date( Date.parse( selectedDate ) ) ;
            maxDate.setDate( maxDate.getDate() + 1 ) ;
            jQuery( '#hrr_request_todate' ).datepicker( 'option' , 'minDate' , maxDate ) ;
        }
    } ) ;
    jQuery( '#hrr_request_todate' ).datepicker( {
        changeMonth : true ,
        changeYear : true ,
        onClose : function ( selectedDate ) {
            jQuery( '#hrr_request_fromdate' ).datepicker( 'option' , 'maxDate' , selectedDate ) ;
        }
    } ) ;
} ) ;
