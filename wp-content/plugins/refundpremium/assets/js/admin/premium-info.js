/* global hrr_premium_info_params */

jQuery( function ( $ ) {
    'use strict' ;

    var HRR_Premium_Info = {
        init : function ( ) {
            this.trigger_on_page_load( ) ;

            $( document ).on( 'click' , '.hrr-premium-info-settings' , this.display_premium_info_notice ) ;
        } , trigger_on_page_load : function () {
            $( ".hrr-premium-info-settings" ).attr( 'disabled' , 'disabled' ) ;

            var hide_input = '<div class="hrr-premium-info-settings"></div>' ;

            if ( $( ".hrr-premium-info-settings" ).closest( 'td' ).length > 0 ) {
                $( ".hrr-premium-info-settings" ).closest( 'td' ).append( hide_input ) ;
            }

        } , display_premium_info_notice : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;

            $( 'div.hrr-premium-info-message' ).remove() ;
            $( $this ).after( '<div class="hrr-premium-info-message"><p><i class="fa fa-info-circle"></i> ' + hrr_premium_info_params.premium_info_msg + '<p></div>' ) ;
        }
    } ;
    HRR_Premium_Info.init( ) ;
} ) ;
