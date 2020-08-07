/* global hrw_gift_card_args, ajaxurl */

jQuery ( function ( $ ) {
    'use strict' ;

    var file_frame ;
    $ ( 'body' ).on ( 'click' , '#hrw_gift_card_logo_image_url' , function ( e ) {
        e.preventDefault ( ) ;
        var $button = $ ( this ) ;
        var formfield = $ ( this ).prev () ;
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open ( ) ;
            return ;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media ( {
            frame : 'select' ,
            title : $button.data ( 'title' ) ,
            multiple : false ,
            library : {
                type : 'image'
            } ,
            button : {
                text : $button.data ( 'button' )
            }
        } ) ;
        // When an image is selected, run a callback.
        file_frame.on ( 'select' , function ( ) {
            var attachment = file_frame.state ( ).get ( 'selection' ).first ( ).toJSON ( ) ;
            formfield.val ( attachment.url ) ;
            $ ( '.hrw_uploaded_file_path' ).html ( attachment.url ) ;
            $ ( '.hrw_uploaded_file_key' ).val ( attachment.url ) ;
        } ) ;
        // Finally, open the modal
        file_frame.open ( ) ;
    } ) ;

    var HRW_Gift_Card = {
        init : function ( ) {
            this.trigger_on_load () ;
            $ ( document ).on ( 'change' , '#hrw_gift_card_code_type' , this.toggle_gift_card_code_type ) ;
            $ ( document ).on ( 'change' , '#hrw_gift_card_gift_product_type' , this.toggle_product_selection ) ;
            $ ( document ).on ( 'click' , '#hrw_gift_card_create_product_btn' , this.create_gift_product ) ;
            $ ( document ).on ( 'change' , '#hrw_gift_card_file_name_method' , this.toggle_file_name_format ) ;
            //Amount Type Toggle   
            $ ( document ).on ( 'change' , '#hrw_gift_card_amount_type' , this.toggle_gift_card_amount_type ) ;
        } , trigger_on_load : function () {
            HRW_Gift_Card.product_selection ( '#hrw_gift_card_gift_product_type' ) ;
            HRW_Gift_Card.file_name_format ( '#hrw_gift_card_file_name_method' ) ;
            HRW_Gift_Card.toggle_gift_card_amount_type_event ( '#hrw_gift_card_amount_type' ) ;
            HRW_Gift_Card.gift_card_code_type ( '#hrw_gift_card_code_type' ) ;
        } , toggle_gift_card_code_type : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Gift_Card.gift_card_code_type ( $this ) ;
        } , gift_card_code_type : function ( $this ) {
            $ ( '.hrw_alphanumeric_field' ).closest ( 'tr' ).hide () ;
            if ( $ ( $this ).val () === '3' ) {
                $ ( '.hrw_alphanumeric_field' ).closest ( 'tr' ).show () ;
            } else {
                $ ( '.hrw_alphanumeric_field' ).closest ( 'tr' ).hide () ;
            }
        } , toggle_product_selection : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Gift_Card.product_selection ( $this ) ;
        } ,
        product_selection : function ( $this ) {
            $ ( '.hrw_gift_product_field' ).closest ( 'tr' ).hide () ;
            if ( $ ( $this ).val () === '1' ) {
                $ ( '#hrw_gift_card_create_product_btn' ).closest ( 'tr' ).show () ;
                $ ( '#hrw_gift_card_gift_product_name' ).closest ( 'tr' ).show () ;
            } else {
                $ ( '.hrw_product_selection' ).closest ( 'tr' ).show () ;
            }
        } , create_gift_product : function ( event ) {
            event.preventDefault ( ) ;

            var gift_product_name = $ ( '#hrw_gift_card_gift_product_name' ).val () ;
            gift_product_name = ( gift_product_name == '' ) ? 'Wallet Product' : gift_product_name ;

            var data = {
                action : 'hrw_create_gift_product' ,
                gift_product_name : gift_product_name ,
                hrw_security : hrw_gift_card_args.gift_product_nonce
            }
            $.post ( ajaxurl , data , function ( res ) {
                if ( true === res.success ) {
                    location.reload ( true ) ;
                } else {
                    alert ( res.data.error ) ;
                }
            } ) ;
        } ,
        toggle_file_name_format : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Gift_Card.file_name_format ( $this ) ;
        } , file_name_format : function ( $this ) {
            if ( $ ( $this ).val () == '2' ) {
                $ ( '#hrw_gift_card_default_format' ).closest ( 'tr' ).hide ( ) ;
                $ ( '.hrw_gift_card_advanced_fields' ).closest ( 'tr' ).show ( ) ;
            } else {
                $ ( '#hrw_gift_card_default_format' ).closest ( 'tr' ).show ( ) ;
                $ ( '.hrw_gift_card_advanced_fields' ).closest ( 'tr' ).hide ( ) ;
            }

        } , toggle_gift_card_amount_type : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Gift_Card.toggle_gift_card_amount_type_event ( $this ) ;
        } ,
        toggle_gift_card_amount_type_event : function ( $this ) {
            $ ( '.gift_input_fields' ).closest ( 'tr' ).hide () ;
            if ( $ ( $this ).val () === '1' ) {
                $ ( '.hrw_gift_card_userdefined_field' ).closest ( 'tr' ).show () ;
            } else if ( $ ( $this ).val () === '2' ) {
                $ ( '.hrw_gift_card_predefined_field' ).closest ( 'tr' ).show () ;
            } else if ( $ ( $this ).val () === '3' ) {
                $ ( '.gift_input_fields' ).closest ( 'tr' ).show () ;
            }
        } ,
        block : function ( id ) {
            $ ( id ).block ( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } , unblock : function ( id ) {
            $ ( id ).unblock () ;
        } ,
    } ;
    HRW_Gift_Card.init ( ) ;
} ) ;