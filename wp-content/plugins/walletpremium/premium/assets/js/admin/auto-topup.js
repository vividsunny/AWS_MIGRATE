/* global ajaxurl */

jQuery( function( $ ) {
    'use strict' ;

    var Auto_Topup = {
        init : function() {
            this.toggleAmtType() ;
            this.toggleAmtType( 'threshold_' ) ;
            this.togglePrivacyPolicy() ;
        } ,
        toggleAmtType : function( $threshold ) {
            $threshold = $threshold || '' ;

            $( '#hrw_auto_topup_' + $threshold + 'amount_type' ).change( function() {
                $( '#hrw_auto_topup_predefined_' + $threshold + 'amount' ).closest( 'tr' ).hide() ;
                $( '#hrw_auto_topup_min_' + $threshold + 'amount' ).closest( 'tr' ).hide() ;
                $( '#hrw_auto_topup_max_' + $threshold + 'amount' ).closest( 'tr' ).hide() ;

                switch( $( this ).val() ) {
                    case 'pre-defined':
                        $( '#hrw_auto_topup_predefined_' + $threshold + 'amount' ).closest( 'tr' ).show() ;
                        break ;
                    case 'user-defined':
                        $( '#hrw_auto_topup_min_' + $threshold + 'amount' ).closest( 'tr' ).show() ;
                        $( '#hrw_auto_topup_max_' + $threshold + 'amount' ).closest( 'tr' ).show() ;
                        break ;
                    case 'both':
                        $( '#hrw_auto_topup_predefined_' + $threshold + 'amount' ).closest( 'tr' ).show() ;
                        $( '#hrw_auto_topup_min_' + $threshold + 'amount' ).closest( 'tr' ).show() ;
                        $( '#hrw_auto_topup_max_' + $threshold + 'amount' ).closest( 'tr' ).show() ;
                        break ;
                }
            } ).change() ;
        } ,
        togglePrivacyPolicy : function() {

            $( '#hrw_auto_topup_display_privacy_policy_link' ).change( function() {
                $( '#hrw_auto_topup_privacy_policy_url' ).closest( 'tr' ).hide() ;
                $( '#hrw_auto_topup_privacy_policy_content' ).closest( 'tr' ).hide() ;

                if( this.checked ) {
                    $( '#hrw_auto_topup_privacy_policy_url' ).closest( 'tr' ).show() ;
                    $( '#hrw_auto_topup_privacy_policy_content' ).closest( 'tr' ).show() ;
                }
            } ).change() ;
        } ,
        block : function( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.7
                }
            } ) ;
        } ,
        unblock : function( id ) {
            $( id ).unblock() ;
        } ,
    } ;
    Auto_Topup.init( ) ;
} ) ;