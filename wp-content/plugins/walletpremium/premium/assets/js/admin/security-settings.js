
jQuery ( function ( $ ) {

    var HRW_Security_Settings = {
        init : function () {
            this.trigger_onload_function () ;
            $ ( document ).on ( 'change' , '#hrw_security_settings_topup_restriction_enabled' , this.topup_restriction ) ;
            $ ( document ).on ( 'change' , '#hrw_security_settings_usage_restriction_enabled' , this.usage_restriction ) ;
            $ ( document ).on ( 'change' , '#hrw_security_settings_withdrawal_restriction_enabled' , this.withdrawal_restriction ) ;
            $ ( document ).on ( 'change' , '#hrw_security_settings_cashback_restriction_enabled' , this.cashback_restriction ) ;
        } ,
        trigger_onload_function : function ( ) {
            this.topup_restriction_toggle ( '#hrw_security_settings_topup_restriction_enabled' ) ;
            this.usage_restriction_toggle ( '#hrw_security_settings_usage_restriction_enabled' ) ;
            this.withdrawal_restriction_toggle ( '#hrw_security_settings_withdrawal_restriction_enabled' ) ;
            this.cashback_restriction_toggle ( '#hrw_security_settings_cashback_restriction_enabled' ) ;
        } ,
        topup_restriction : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Security_Settings.topup_restriction_toggle ( $this ) ;
        } ,
        topup_restriction_toggle : function ( $this ) {
            if ( $ ( $this ).is ( ':checked' ) ) {
                $ ( '.hrw_security_settings_topup_fileds' ).closest ( 'tr' ).show () ;
            } else {
                $ ( '.hrw_security_settings_topup_fileds' ).closest ( 'tr' ).hide () ;
            }
        } ,
        usage_restriction : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Security_Settings.usage_restriction_toggle ( $this ) ;

        } ,
        usage_restriction_toggle : function ( $this ) {
            if ( $ ( $this ).is ( ':checked' ) ) {
                $ ( '.hrw_security_settings_usage_fileds' ).closest ( 'tr' ).show () ;
            } else {
                $ ( '.hrw_security_settings_usage_fileds' ).closest ( 'tr' ).hide () ;
            }
        } ,
        cashback_restriction : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Security_Settings.cashback_restriction_toggle ( $this ) ;

        } ,
        cashback_restriction_toggle : function ( $this ) {
            if ( $ ( $this ).is ( ':checked' ) ) {
                $ ( '.hrw_security_settings_cashback_fileds' ).closest ( 'tr' ).show () ;
            } else {
                $ ( '.hrw_security_settings_cashback_fileds' ).closest ( 'tr' ).hide () ;
            }
        } ,
        withdrawal_restriction : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            HRW_Security_Settings.withdrawal_restriction_toggle ( $this ) ;
        } ,
        withdrawal_restriction_toggle : function ( $this ) {
            if ( $ ( $this ).is ( ':checked' ) ) {
                $ ( '.hrw_security_settings_withdrawal_fileds' ).closest ( 'tr' ).show () ;
            } else {
                $ ( '.hrw_security_settings_withdrawal_fileds' ).closest ( 'tr' ).hide () ;
            }
        } ,

    } ;
    HRW_Security_Settings.init ( ) ;
} ) ;