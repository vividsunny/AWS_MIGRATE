/* global hrw_premium_info_params */

jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Premium_Info = {
        init : function ( ) {
            this.trigger_on_page_load( ) ;

            $( document ).on( 'click' , '.hrw_premium_info_settings' , this.display_premium_info_notice ) ;
        } , trigger_on_page_load : function () {
            $( ".hrw_premium_info_settings" ).attr( 'disabled' , 'disabled' ) ;

            var hide_input = '<div class="hrw_premium_info_settings"></div>' ;

            if ( $( ".hrw_premium_info_settings" ).closest( 'td' ).length > 0 ) {
                $( ".hrw_premium_info_settings" ).closest( 'td' ).append( hide_input ) ;
            }

        } , display_premium_info_notice : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;

            $( 'div.hrw_premium_info_message' ).remove() ;
            $( $this ).after( '<div class="hrw_premium_info_message"><p><i class="fa fa-info-circle"></i> ' + hrw_premium_info_params.premium_info_msg + '<p></div>' ) ;
        }
    } ;
    HRW_Premium_Info.init( ) ;
} ) ;
