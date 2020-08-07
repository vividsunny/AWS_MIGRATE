/* global hrr_license_handler_params */

jQuery( function ( $ ) {

    var HRR_License_Tab = {

        init : function () {
            $( document ).on( 'click' , '#hrr-license-verification-btn' , this.license_handler ) ;
        } ,
        license_handler : function ( event ) {
            event.preventDefault() ;
            if ( $( '#hrr_license_key' ).val() == '' ) {
                $( '.hrr-error' ).html( hrr_license_handler_params.license_empty_message ) ;
                return false ;
            }

            HRR_License_Tab.block( '.hrr-license-activation-content' ) ;
            var data = {
                action : 'hrr_license_handler' ,
                license_key : $( '#hrr_license_key' ).val() ,
                handler : $( '#hrr_license_handler_value' ).val() ,
                hrr_security : hrr_license_handler_params.license_security
            } ;

            $.post( hrr_license_handler_params.ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $( '.hrr-success' ).html( response.data.success_msg ) ;
                    window.location.reload() ;
                } else {
                    $( '.hrr-error' ).html( response.data.error_msg ) ;
                }
                HRR_License_Tab.unblock( '.hrr-license-activation-content' ) ;
            } ) ;
        } ,
        block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.6
                }
            } ) ;
        } ,
        unblock : function ( id ) {
            $( id ).unblock() ;
        } ,
    } ;
    HRR_License_Tab.init() ;
} ) ;
