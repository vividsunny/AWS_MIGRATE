
jQuery( function( $ ) {
    $( '#hrw_auto_topup_amount' ).change( function() {

        if( 'user-defined' === this.value ) {
            $( this )
                    .removeProp( 'name' )
                    .next( 'input' )
                    .slideDown()
                    .prop( 'name' , 'hrw_auto_topup[amount]' )
                    .prop( 'min' , $( this ).next( 'input' ).data( 'min' ) )
                    .prop( 'max' , $( this ).next( 'input' ).data( 'max' ) ) ;
        } else {
            $( this )
                    .prop( 'name' , 'hrw_auto_topup[amount]' )
                    .next( 'input' )
                    .slideUp()
                    .removeProp( 'name' )
                    .removeProp( 'min' )
                    .removeProp( 'max' ) ;
        }
    } ).change() ;

    $( '#hrw_auto_topup_threshold_amount' ).change( function() {

        if( 'user-defined' === this.value ) {
            $( this )
                    .removeProp( 'name' )
                    .next( 'input' )
                    .slideDown()
                    .prop( 'name' , 'hrw_auto_topup[threshold_amount]' )
                    .prop( 'min' , $( this ).next( 'input' ).data( 'min' ) )
                    .prop( 'max' , $( this ).next( 'input' ).data( 'max' ) ) ;
        } else {
            $( this )
                    .prop( 'name' , 'hrw_auto_topup[threshold_amount]' )
                    .next( 'input' )
                    .slideUp()
                    .removeProp( 'name' )
                    .removeProp( 'min' )
                    .removeProp( 'max' ) ;
        }
    } ).change() ;
} ) ;