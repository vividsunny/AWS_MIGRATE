/* global hrr_upgrade_params, ajaxurl */

jQuery( function ( $ ) {
    'use strict' ;

    var HRR_Upgrade = {
        init : function ( ) {

            //Display Upgrade percentage
            this.diplay_upgrade_percentage() ;
            
        } , diplay_upgrade_percentage : function () {

            if ( !$( 'div.hrr_prograss_bar_wrapper' ).length )
                return ;

            var data = {
                action : 'hrr_background_process_count' ,
                hrr_security : hrr_upgrade_params.upgrade_nonce
            } ;
            $.ajax( {
                type : 'POST' ,
                url : ajaxurl ,
                data : data ,
                dataType : 'json' ,
            } ).done( function ( res ) {
                if ( true === res.success ) {
                    if ( res.data.completed === 'no' ) {
                        $( '#hrr_prograss_bar_current_status' ).html( res.data.percentage ) ;
                        $( '.hrr_prograss_bar_inner' ).css( "width" , res.data.percentage + "%" ) ;
                        HRR_Upgrade.diplay_upgrade_percentage() ;
                    } else {
                        $( '#hrr_prograss_bar_label' ).css( "display" , "none" ) ;
                        $( '.hrr_prograss_bar_inner' ).css( "width" , "100%" ) ;
                        $( '#hrr_prograss_bar_current_status' ).html( res.data.msg ) ;
                        window.location.href = res.data.redirect_url ;
                    }
                }
            } ) ;
        } , block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } , unblock : function ( id ) {
            $( id ).unblock() ;
        } ,
    } ;
    HRR_Upgrade.init( ) ;
} ) ;