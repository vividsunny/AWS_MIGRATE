jQuery( function( $ ) {
    'use strict' ;

    $( '#woocommerce_hrw_stripe_testmode' ).change( function() {
        $( '#woocommerce_hrw_stripe_testsecretkey' ).closest( 'tr' ).hide() ;
        $( '#woocommerce_hrw_stripe_testpublishablekey' ).closest( 'tr' ).hide() ;
        $( '#woocommerce_hrw_stripe_livesecretkey' ).closest( 'tr' ).show() ;
        $( '#woocommerce_hrw_stripe_livepublishablekey' ).closest( 'tr' ).show() ;

        if( this.checked ) {
            $( '#woocommerce_hrw_stripe_testsecretkey' ).closest( 'tr' ).show() ;
            $( '#woocommerce_hrw_stripe_testpublishablekey' ).closest( 'tr' ).show() ;
            $( '#woocommerce_hrw_stripe_livesecretkey' ).closest( 'tr' ).hide() ;
            $( '#woocommerce_hrw_stripe_livepublishablekey' ).closest( 'tr' ).hide() ;
        }
    } ).change() ;
} ) ;