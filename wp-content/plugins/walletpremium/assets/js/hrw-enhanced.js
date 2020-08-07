/* global hrw_enhanced_select_params */

jQuery( function ( $ ) {
    'use strict' ;

    try {
        $( document.body ).on( 'hrw-enhanced-init' , function () {
            if ( $( 'select.hrw_select2' ).length ) {
                //Select2 with customization
                $( 'select.hrw_select2' ).each( function () {
                    var select2_args = {
                        allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
                        placeholder : $( this ).data( 'placeholder' ) ,
                        minimumResultsForSearch : 10 ,
                    } ;
                    $( this ).select2( select2_args ) ;
                } ) ;
            }
            if ( $( 'select.hrw_select2_search' ).length ) {
                //Multiple select with ajax search
                $( 'select.hrw_select2_search' ).each( function () {
                    var select2_args = {
                        allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
                        placeholder : $( this ).data( 'placeholder' ) ,
                        minimumInputLength : $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : 3 ,
                        escapeMarkup : function ( m ) {
                            return m ;
                        } ,
                        ajax : {
                            url : hrw_enhanced_select_params.ajaxurl ,
                            dataType : 'json' ,
                            delay : 250 ,
                            data : function ( params ) {
                                return {
                                    term : params.term ,
				    include : $( this ).data( 'include' ) ,
                                    include_roles : $( this ).data( 'include_roles' ) ,
                                    exclude : $( this ).data( 'exclude' ) ,
                                    exclude_roles : $( this ).data( 'exclude_roles' ) ,
                                    action : $( this ).data( 'action' ) ? $( this ).data( 'action' ) : '' ,
                                    hrw_security : $( this ).data( 'nonce' ) ? $( this ).data( 'nonce' ) : hrw_enhanced_select_params.search_nonce ,
                                } ;
                            } ,
                            processResults : function ( data ) {
                                var terms = [ ] ;
                                if ( data ) {
                                    $.each( data , function ( id , term ) {
                                        terms.push( {
                                            id : id ,
                                            text : term
                                        } ) ;
                                    } ) ;
                                }
                                return {
                                    results : terms
                                } ;
                            } ,
                            cache : true
                        }
                    } ;

                    $( this ).select2( select2_args ) ;
                } ) ;
            }

            if ( $( '#hrw_from_date' ).length ) {
                $( '#hrw_from_date' ).each( function ( ) {

                    $( this ).datepicker( {
                        altField : $( this ).next( ".hrw_alter_datepicker_value" ) ,
                        altFormat : 'yy-mm-dd' ,
                        changeMonth : true ,
                        changeYear : true ,
                        onClose : function ( selectedDate ) {
                            var maxDate = new Date( Date.parse( selectedDate ) ) ;
                            maxDate.setDate( maxDate.getDate() + 1 ) ;
                            $( '#hrw_to_date' ).datepicker( 'option' , 'minDate' , maxDate ) ;
                        }
                    } ) ;

                } ) ;
            }

            if ( $( '#hrw_to_date' ).length ) {
                $( '#hrw_to_date' ).each( function ( ) {

                    $( this ).datepicker( {
                        altField : $( this ).next( ".hrw_alter_datepicker_value" ) ,
                        altFormat : 'yy-mm-dd' ,
                        changeMonth : true ,
                        changeYear : true ,
                        onClose : function ( selectedDate ) {
                            $( '#hrw_from_date' ).datepicker( 'option' , 'maxDate' , selectedDate ) ;
                        }
                    } ) ;

                } ) ;
            }

            if ( $( '.hrw_datepicker' ).length ) {
                $( '.hrw_datepicker' ).on( 'change' , function ( ) {
                    if ( $( this ).val() === '' ) {
                        $( this ).next( ".hrw_alter_datepicker_value" ).val( '' ) ;
                    }
                } ) ;

                $( '.hrw_datepicker' ).each( function ( ) {
                    $( this ).datepicker( {
                        altField : $( this ).next( ".hrw_alter_datepicker_value" ) ,
                        altFormat : 'yy-mm-dd' ,
                        changeMonth : true ,
                        changeYear : true
                    } ) ;
                } ) ;
            }

            if ( $( '.hrw_colorpicker' ).length ) {
                $( '.hrw_colorpicker' ).each( function ( ) {

                    $( this ).iris( {
                        change : function ( event , ui ) {
                            $( this ).css( { backgroundColor : ui.color.toString( ) } ) ;
                        } ,
                        hide : true ,
                        border : true
                    } ) ;

                    $( this ).css( 'background-color' , $( this ).val() ) ;
                } ) ;

                $( document ).on( 'click' , function ( e ) {
                    if ( !$( e.target ).is( ".hrw_colorpicker, .iris-picker, .iris-picker-inner" ) ) {
                        $( '.hrw_colorpicker' ).iris( 'hide' ) ;
                    }
                } ) ;

                $( '.hrw_colorpicker' ).on( 'click' , function ( e ) {
                    $( '.hrw_colorpicker' ).iris( 'hide' ) ;
                    $( this ).iris( 'show' ) ;
                } ) ;
            }
        } ) ;

        $( document.body ).trigger( 'hrw-enhanced-init' ) ;
    } catch ( err ) {
        window.console.log( err ) ;
    }

} ) ;