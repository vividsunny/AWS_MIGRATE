jQuery( function ( $ ) {

    var HRR_Toggle = {
        init : function ( ) {
            this.trigger_on_page_load() ;

            $( document ).on( 'click' , ".hrr-section-start h2" , this.toggle_section ) ;
            $( document ).on( 'click' , ".hrr-save-msg img" , this.save_notice ) ;
            $( document ).on( 'click' , ".hrr_reset_msg img" , this.reset_notice ) ;
            //Hide Message
            $( document ).on( 'click' , '.hrr-save-close' , this.hide_message_popup ) ;
        } ,

        trigger_on_page_load : function () {
            $( '.hrr-section-start h2' ).attr( 'data-section_close' , 'no' ) ;
            $( '.hrr-section-start h2' ).addClass( 'hrr_section_open' ) ;
            $( this ).css( 'border-bottom-left-radius' , '3px' ) ;
            $( this ).css( 'border-bottom-right-radius' , '3px' ) ;
        } ,
        hide_message_popup : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            $( $this ).closest( 'div.hrr-save-msg' ).remove() ;

        },
        toggle_section : function () {
            if ( $( this ).attr( 'data-section_close' ) === 'yes' ) {
                $( this ).attr( 'data-section_close' , 'no' ) ;
                $( this ).removeClass( 'hrr_section_close' ) ;
                $( this ).addClass( 'hrr_section_open' ) ;
                $( this ).css( 'border-bottom-left-radius' , '0px' ) ;
                $( this ).css( 'border-bottom-right-radius' , '0px' ) ;
            } else {
                $( this ).attr( 'data-section_close' , 'yes' ) ;
                $( this ).removeClass( 'hrr_section_open' ) ;
                $( this ).addClass( 'hrr_section_close' ) ;
                $( this ).css( 'border-bottom-left-radius' , '3px' ) ;
                $( this ).css( 'border-bottom-right-radius' , '3px' ) ;
            }
            $( this ).nextUntil( 'h2' ).toggle() ;
        } ,
        save_notice : function () {
            jQuery( ".hrr-save-msg" ).css( "display" , "none" ) ;
        } ,
        reset_notice : function () {
            jQuery( ".hrr_reset_msg" ).css( "display" , "none" ) ;
        } ,
    } ;
    HRR_Toggle.init( ) ;
} ) ;
