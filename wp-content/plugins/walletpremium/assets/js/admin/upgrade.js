/* global hrw_upgrade_params, ajaxurl */

jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Upgrade = {
        init : function ( ) {

            //Activate/deactive license key
            $( document ).on( 'click' , '#hrw_license_verification_btn' , this.activate_deactive_license_key ) ;

            //Display Upgrade percentage
            this.diplay_upgrade_percentage() ;
        } , activate_deactive_license_key : function ( event ) {
            event.preventDefault( ) ;

            var $this = $( event.currentTarget ) ,
                    wrapper = $( $this ).closest( 'div.hrw_license_verification_wrapper' ) ,
                    sucess_msg = wrapper.find( '.hrw_success' ) ,
                    error_msg = wrapper.find( '.hrw_error' ) ,
                    license_key = wrapper.find( '#hrw_license_key' ).val() ;

            if ( !license_key ) {
                error_msg.html( hrw_upgrade_params.empty_msg ) ;
                return ;
            }

            HRW_Upgrade.block( wrapper ) ;

            var data = {
                action : 'hrw_license_handler' ,
                license_key : license_key ,
                handler : wrapper.find( '#hrw_license_verification_type' ).val() ,
                hrw_security : hrw_upgrade_params.upgrade_nonce
            }
            $.post( ajaxurl , data , function ( res ) {
                HRW_Upgrade.unblock( wrapper ) ;
                if ( true === res.success ) {
                    sucess_msg.html( res.data.success_msg ) ;
                    location.reload( true ) ;
                } else {
                    error_msg.html( res.data.error_msg ) ;
                }
            } ) ;
        } , diplay_upgrade_percentage : function () {

            if ( !$( 'div.hrw_prograss_bar_wrapper' ).length )
                return ;

            var data = {
                action : 'hrw_background_process_count' ,
                hrw_security : hrw_upgrade_params.upgrade_nonce
            } ;
            $.ajax( {
                type : 'POST' ,
                url : ajaxurl ,
                data : data ,
                dataType : 'json' ,
            } ).done( function ( res ) {
                if ( true === res.success ) {
                    if ( res.data.completed === 'no' ) {
                        $( '#hrw_prograss_bar_current_status' ).html( res.data.percentage ) ;
                        $( '.hrw_prograss_bar_inner' ).css( "width" , res.data.percentage + "%" ) ;
                        HRW_Upgrade.diplay_upgrade_percentage() ;
                    } else {
                        $( '#hrw_prograss_bar_label' ).css( "display" , "none" ) ;
                        $( '.hrw_prograss_bar_inner' ).css( "width" , "100%" ) ;
                        $( '#hrw_prograss_bar_current_status' ).html( res.data.msg ) ;
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
    HRW_Upgrade.init( ) ;
} ) ;