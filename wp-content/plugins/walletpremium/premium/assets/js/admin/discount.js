/* global hrw_discount_params, ajaxurl */
jQuery( function ( $ ) {
    'use strict' ;

    var HRW_Discount = {

        init : function ( ) {
            this.trigger_on_load_function( ) ;

            $( document ).on( 'change' , '#hrw_discount_hide_discount_notice' , this.hide_discount_notice ) ;
            $( document ).on( 'change' , '.hrw_user_filter_type' , this.user_filter_type ) ;
            $( document ).on( 'change' , '.hrw_product_filter_type' , this.product_filter_type ) ;
            $( document ).on( 'change' , '.hrw_purchase_history_type' , this.purchase_history_type ) ;

            $( document ).on( 'click' , '.hrw_toggle_rule' , this.toggle_rule ) ;
            $( document ).on( 'click' , '.hrw_add_discount_popup' , this.add_discount_popup ) ;
            $( document ).on( 'click' , '.hrw_close_popup_wrapper' , this.close_discount_popup ) ;
            $( document ).on( 'click' , '.hrw_add_discount_rule' , this.add_discount_rule ) ;
            $( document ).on( 'click' , '.hrw_add_discount_local_rule' , this.add_discount_local_rule ) ;
            $( document ).on( 'click' , '.hrw_remove_discount_rule' , this.remove_local_rule ) ;
            $( document ).on( 'click' , '.hrw_delete_discount_rule' , this.delete_discount_rule ) ;
        } , trigger_on_load_function : function ( ) {
            $( '.hrw_user_filter_type' ).each( function () {
                HRW_Discount.show_or_hide_user_filter( $( this ) ) ;
            } ) ;

            $( '.hrw_product_filter_type' ).each( function () {
                HRW_Discount.show_or_hide_product_filter( $( this ) ) ;
            } ) ;

            $( '.hrw_purchase_history_type' ).each( function () {
                HRW_Discount.show_or_hide_purchase_history_type( $( this ) ) ;
            } ) ;

            $( '.hrw_discount_rule_content_wrapper' ).each( function () {
                HRW_Discount.toggle_rule_content( $( this ) ) ;
            } ) ;

            HRW_Discount.hide_discount_notice() ;
        } , hide_discount_notice : function () {
            if ( $( '#hrw_discount_hide_discount_notice' ).val() == '1' ) {
                $( '#hrw_discount_discount_notice' ).closest( 'tr' ).show() ;
            } else {
                $( '#hrw_discount_discount_notice' ).closest( 'tr' ).hide() ;
            }
        } , toggle_rule : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Discount.toggle_rule_content( $this ) ;
        } , toggle_rule_content : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_discount_rule' ) ;
            rule_div.find( '.hrw_discount_rule_content_wrapper' ).toggle() ;
        } , user_filter_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Discount.show_or_hide_user_filter( $this ) ;
        } , show_or_hide_user_filter : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_discount_rule' ) ;
            if ( $( $this ).val( ) == '1' ) {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else if ( $( $this ).val( ) == '2' ) {
                rule_div.find( '.hrw_inc_user_selection' ).show() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else if ( $( $this ).val( ) == '3' ) {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).show() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else if ( $( $this ).val( ) == '4' ) {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).show() ;
                rule_div.find( '.hrw_exc_user_role' ).hide() ;
            } else {
                rule_div.find( '.hrw_inc_user_selection' ).hide() ;
                rule_div.find( '.hrw_exc_user_selection' ).hide() ;
                rule_div.find( '.hrw_inc_user_role' ).hide() ;
                rule_div.find( '.hrw_exc_user_role' ).show() ;
            }
        } , product_filter_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Discount.show_or_hide_product_filter( $this ) ;
        } , show_or_hide_product_filter : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_discount_rule' ) ;
            if ( ( $( $this ).val( ) == '1' ) || ( $( $this ).val( ) == '4' ) || ( $( $this ).val( ) == '7' ) ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '2' ) {
                rule_div.find( '.hrw_inc_product_selection' ).show() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '3' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).show() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '5' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).show() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '6' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).show() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else if ( $( $this ).val( ) == '8' ) {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).show() ;
                rule_div.find( '.hrw_exc_tag_selection' ).hide() ;
            } else {
                rule_div.find( '.hrw_inc_product_selection' ).hide() ;
                rule_div.find( '.hrw_exc_product_selection' ).hide() ;
                rule_div.find( '.hrw_inc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_exc_cat_selection' ).hide() ;
                rule_div.find( '.hrw_inc_tag_selection' ).hide() ;
                rule_div.find( '.hrw_exc_tag_selection' ).show() ;
            }
        } , purchase_history_type : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ;
            HRW_Discount.show_or_hide_purchase_history_type( $this ) ;
        } , show_or_hide_purchase_history_type : function ( $this ) {
            var rule_div = $( $this ).closest( '.hrw_discount_rule' ) ;
            if ( $( $this ).val( ) == '1' ) {
                rule_div.find( '.hrw_no_of_order' ).show() ;
                rule_div.find( '.hrw_total_amount' ).hide() ;
            } else {
                rule_div.find( '.hrw_no_of_order' ).hide() ;
                rule_div.find( '.hrw_total_amount' ).show() ;
            }
        } , add_discount_popup : function ( event ) {
            event.preventDefault( ) ;
            $( '.hrw_discount_rule_popup_wrapper' ).find( '#hrw_rule_name' ).val( '' ) ;
            $( '.hrw_discount_rule_popup_wrapper' ).show( ) ;
        } , close_discount_popup : function ( e ) {
            e.preventDefault( ) ;
            var $this = $( e.currentTarget ) ,
                    popup = $( $this ).closest( 'div.hrw_discount_rule_popup_wrapper' ) ;
            popup.hide( ) ;
        } , add_discount_rule : function ( event ) {
            event.preventDefault( ) ;
            HRW_Discount.block( '.hrw_discount_rule_wrapper' ) ;
            var data = {
                action : 'hrw_add_discount_rule' ,
                rule_name : $( '#hrw_rule_name' ).val( ) ,
                hrw_security : hrw_discount_params.discount_nonce
            } ;
            $.post( ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( '.hrw_discount_rule_wrapper' ).append( res.data.field ) ;
                    $( '.hrw_close_popup_wrapper' ).closest( 'div.hrw_discount_rule_popup_wrapper' ).hide( ) ;
                    HRW_Discount.show_or_hide_user_filter( '.hrw_user_filter_type' ) ;
                    HRW_Discount.show_or_hide_product_filter( '.hrw_product_filter_type' ) ;
                    HRW_Discount.show_or_hide_purchase_history_type( '.hrw_purchase_history_type' ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                $( document.body ).trigger( 'hrw-enhanced-init' ) ;
                HRW_Discount.unblock( '.hrw_discount_rule_wrapper' ) ;
            } ) ;
        } , add_discount_local_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Discount.block( '.hrw_discount_table_wrapper' ) ;
            var data = {
                action : 'hrw_add_discount_local_rule' ,
                postid : $( this ).attr( 'data-postid' ) ,
                hrw_security : hrw_discount_params.discount_nonce
            } ;
            $.post( ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( $this ).closest( '.hrw_discount_table' ).find( '.hrw_discount_table_wrapper' ).append( res.data.field ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                HRW_Discount.unblock( '.hrw_discount_table_wrapper' ) ;
            } ) ;
        } , delete_discount_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            HRW_Discount.block( '.hrw_discount_rule_wrapper' ) ;
            var data = {
                action : 'hrw_delete_discount_rule' ,
                postid : $( this ).attr( 'data-postid' ) ,
                hrw_security : hrw_discount_params.discount_nonce
            } ;
            $.post( ajaxurl , data , function ( res ) {
                if ( res.success === true ) {
                    $( $this ).closest( '.hrw_discount_rule' ).remove( ) ;
                } else {
                    window.alert( res.data.error ) ;
                }
                HRW_Discount.unblock( '.hrw_discount_rule_wrapper' ) ;
            } ) ;
        } , remove_local_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            $( $this ).closest( 'tr' ).remove( ) ;
        } , block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } , unblock : function ( id ) {
            $( id ).unblock( ) ;
        }
    } ;
    HRW_Discount.init( ) ;
} ) ;