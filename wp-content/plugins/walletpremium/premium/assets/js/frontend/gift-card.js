jQuery ( function ( $ ) {
    'use strict' ;

    var HRW_Gift_Card = {

        init : function () {
            $ ( document ).on ( 'change' , '#hrw_gift_card_select' , this.select_gift_amount ) ;
        } , select_gift_amount : function () {
            if ( $ ( this ).val ( ) == 'user-defined' ) {
                $ ( '.hrw_gift_card_amount' ).show () ;
                $ ( '.hrw_gift_card_amount' ).val ( '' ) ;
                return false ;
            }
            $ ( '.hrw_gift_card_amount' ).val ( Number ( $ ( this ).val ( ) ) ) ;
            $ ( '.hrw_gift_card_amount' ).hide () ;
        } ,
    } ;
    HRW_Gift_Card.init () ;
} ) ;
