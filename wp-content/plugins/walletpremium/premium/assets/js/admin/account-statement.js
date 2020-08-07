/* global hrw_account_statement, ajaxurl */

jQuery( function ( $ ) {
    'use strict' ;
    
    var file_frame ;
    $( 'body' ).on( 'click' , '#hrw_wallet_account_statement_logo_image_url' , function ( e ) {
        e.preventDefault( ) ;
        var $button = $( this ) ;
        var formfield = $( this ).prev( ) ;
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open( ) ;
            return ;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media( {
            frame : 'select' ,
            title : $button.data( 'title' ) ,
            multiple : false ,
            library : {
                type : 'image'
            } ,
            button : {
                text : $button.data( 'button' )
            }
        } ) ;
        // When an image is selected, run a callback.
        file_frame.on( 'select' , function ( ) {
            var attachment = file_frame.state( ).get( 'selection' ).first( ).toJSON( ) ;
            formfield.val( attachment.url ) ;
            var img = $( '<img />' ) ;
            img.attr( 'src' , attachment.url ) ;
        } ) ;
        // Finally, open the modal
        file_frame.open( ) ;
    } ) ;

    var HRW_Account_Statement = {
        init : function ( ) {
            this.trigger_onload_function() ;
            $( document ).on( 'change' , '#hrw_wallet_account_statement_statement_method' , this.toggle_statement_frequency ) ;
            $( document ).on( 'change' , '#hrw_wallet_account_statement_yearly_notification' , this.toggle_year_from_field ) ;
            $( document ).on( 'change' , '#hrw_wallet_account_statement_file_name_method' , this.toggle_file_name_format ) ;
        } , trigger_onload_function : function ( ) {
            this.statement_frequency( '#hrw_wallet_account_statement_statement_method' ) ;
            this.file_name_format( '#hrw_wallet_account_statement_file_name_method' ) ;
        } , toggle_statement_frequency : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Account_Statement.statement_frequency( $this ) ;
        } , toggle_year_from_field : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Account_Statement.year_from_field( $this ) ;
        } , toggle_file_name_format : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Account_Statement.file_name_format( $this ) ;
        } , statement_frequency : function ( $this ) {
            if ( $( $this ).val() == '3' ) {
                $( '#hrw_wallet_account_statement_yearly_notification' ).closest( 'tr' ).hide( ) ;
                $( '#hrw_wallet_account_statement_year_start_date' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#hrw_wallet_account_statement_yearly_notification' ).closest( 'tr' ).show( ) ;
                $( '#hrw_wallet_account_statement_year_start_date' ).closest( 'tr' ).hide( ) ;
                HRW_Account_Statement.year_from_field( '#hrw_wallet_account_statement_yearly_notification' ) ;
            }

        } , year_from_field : function ( $this ) {
            if ( $( $this ).prop( "checked" ) ) {
                $( '#hrw_wallet_account_statement_year_start_date' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#hrw_wallet_account_statement_year_start_date' ).closest( 'tr' ).hide( ) ;
            }
        } , file_name_format : function ( $this ) {
            if ( $( $this ).val() == '2' ) {
                $( '#hrw_wallet_account_statement_default_format' ).closest( 'tr' ).hide( ) ;
                $( '.hrw_wallet_account_statement_advanced_fields' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#hrw_wallet_account_statement_default_format' ).closest( 'tr' ).show( ) ;
                $( '.hrw_wallet_account_statement_advanced_fields' ).closest( 'tr' ).hide( ) ;
            }

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
    HRW_Account_Statement.init( ) ;
} ) ;