/* global hrw_stripe_params */

jQuery( function( $ ) {
    'use strict' ;

    // hrw_stripe_params is required to continue, ensure the object exists
    if( typeof hrw_stripe_params === 'undefined' ) {
        return false ;
    }

    var hrw_stripe = {
        stripeClient : null ,
        stripeElements : null ,
        stripeCard : null ,
        stripeExp : null ,
        stripeCVC : null ,
        styles : {
            base : {
                color : '#32325d' ,
                lineHeight : '18px' ,
                fontFamily : '"Helvetica Neue", Helvetica, sans-serif' ,
                fontSmoothing : 'antialiased' ,
                fontSize : '16px' ,
                '::placeholder' : {
                    color : '#aab7c4'
                }
            } ,
            invalid : {
                color : '#fa755a' ,
                iconColor : '#fa755a'
            }
        } ,
        init : function() {

            if( $( 'form#order_review' ).length ) {
                this.form = $( 'form#order_review' ) ;
            } else if( $( 'form#add_payment_method' ).length ) {
                this.form = $( 'form#add_payment_method' ) ;
            } else {
                this.form = $( 'form.checkout' ) ;
            }

            if( 0 === this.form.length ) {
                return false ;
            }

            this.initElements() ;

            if( $( 'form#order_review' ).length || $( 'form#add_payment_method' ).length ) {
                this.form.on( 'submit' , this.createPaymentMethod ) ;
                this.mayBeMountElements() ;
                this.onVerifyIntentHash() ;
            } else {
                this.form.on( 'checkout_place_order' , this.createPaymentMethod ) ;
                $( document.body ).on( 'updated_checkout' , this.mayBeMountElements ) ;
                window.addEventListener( 'hashchange' , this.onVerifyIntentHash ) ;
            }

            $( document.body ).on( 'checkout_error' , this.onCheckoutErr ) ;
        } ,
        isStripeChosen : function() {
            if( hrw_stripe_params.payment_method === $( '.payment_methods input[name="payment_method"]:checked' ).val() ) {
                return true ;
            }
            return false ;
        } ,
        initElements : function() {
            if( 'inline_cc_form' === hrw_stripe_params.checkoutmode ) {
                hrw_stripe.stripeCard = hrw_stripe.stripeElements.create( 'card' , { style : hrw_stripe.styles , hidePostalCode : true } ) ;

                hrw_stripe.stripeCard.addEventListener( 'change' , hrw_stripe.onChangeElements ) ;
            } else {
                hrw_stripe.stripeCard = hrw_stripe.stripeElements.create( 'cardNumber' , { style : hrw_stripe.styles } ) ;
                hrw_stripe.stripeExp = hrw_stripe.stripeElements.create( 'cardExpiry' , { style : hrw_stripe.styles } ) ;
                hrw_stripe.stripeCVC = hrw_stripe.stripeElements.create( 'cardCvc' , { style : hrw_stripe.styles } ) ;

                hrw_stripe.stripeCard.addEventListener( 'change' , hrw_stripe.onChangeElements ) ;
                hrw_stripe.stripeExp.addEventListener( 'change' , hrw_stripe.onChangeElements ) ;
                hrw_stripe.stripeCVC.addEventListener( 'change' , hrw_stripe.onChangeElements ) ;
            }
        } ,
        onChangeElements : function( event ) {
            hrw_stripe.reset() ;

            if( event.brand ) {
                hrw_stripe.updateCardBrand( event.brand ) ;
            }

            if( event.error ) {
                hrw_stripe.throwErr( event.error ) ;
            }
        } ,
        mayBeMountElements : function() {
            if( hrw_stripe.stripeCard ) {
                hrw_stripe.unmountElements() ;

                if( $( '#wc-hrw_stripe-cc-form' ).length ) {
                    hrw_stripe.mountElements() ;
                }
            }
        } ,
        updateCardBrand : function( brand ) {
            var brandClass = {
                'visa' : 'hrw-stripe-visa-brand' ,
                'mastercard' : 'hrw-stripe-mastercard-brand' ,
                'amex' : 'hrw-stripe-amex-brand' ,
                'discover' : 'hrw-stripe-discover-brand' ,
                'diners' : 'hrw-stripe-diners-brand' ,
                'jcb' : 'hrw-stripe-jcb-brand' ,
                'unknown' : 'hrw-stripe-credit-card-brand'
            } ;

            var imageElement = $( '.hrw-stripe-card-brand' ) ,
                    imageClass = 'hrw-stripe-credit-card-brand' ;

            if( brand in brandClass ) {
                imageClass = brandClass[ brand ] ;
            }

            $.each( brandClass , function( i , el ) {
                imageElement.removeClass( el ) ;
            } ) ;

            imageElement.addClass( imageClass ) ;
        } ,
        mountElements : function() {
            if( 'inline_cc_form' === hrw_stripe_params.checkoutmode ) {
                hrw_stripe.stripeCard.mount( '#hrw-stripe-card-element' ) ;
            } else {
                hrw_stripe.stripeCard.mount( '#hrw-stripe-card-element' ) ;
                hrw_stripe.stripeExp.mount( '#hrw-stripe-exp-element' ) ;
                hrw_stripe.stripeCVC.mount( '#hrw-stripe-cvc-element' ) ;
            }
        } ,
        unmountElements : function() {
            if( 'inline_cc_form' === hrw_stripe_params.checkoutmode ) {
                hrw_stripe.stripeCard.unmount( '#hrw-stripe-card-element' ) ;
            } else {
                hrw_stripe.stripeCard.unmount( '#hrw-stripe-card-element' ) ;
                hrw_stripe.stripeExp.unmount( '#hrw-stripe-exp-element' ) ;
                hrw_stripe.stripeCVC.unmount( '#hrw-stripe-cvc-element' ) ;
            }
        } ,
        hasPaymentMethod : function() {
            return hrw_stripe.form.find( 'input[name="hrw_stripe_pm"]' ).length > 0 ? true : false ;
        } ,
        savedPaymentMethodChosen : function() {
            return $( '#payment_method_hrw_stripe' ).is( ':checked' )
                    && $( 'input[name="wc-hrw_stripe-payment-token"]' ).is( ':checked' )
                    && 'new' !== $( 'input[name="wc-hrw_stripe-payment-token"]:checked' ).val() ;
        } ,
        createPaymentMethod : function() {

            if( ! hrw_stripe.isStripeChosen() ) {
                hrw_stripe.reset() ;
                return true ;
            }

            if( hrw_stripe.savedPaymentMethodChosen() ) {
                hrw_stripe.reset() ;
                return true ;
            }

            hrw_stripe.reset( 'no' ) ;

            if( hrw_stripe.hasPaymentMethod() ) {
                return true ;
            }

            hrw_stripe.reset() ;
            hrw_stripe.blockFormOnSubmit() ;
            hrw_stripe.stripeClient.createPaymentMethod( 'card' , hrw_stripe.stripeCard ).then( hrw_stripe.handlePaymentMethodResponse ) ;
            return false ;
        } ,
        handlePaymentMethodResponse : function( response ) {
            if( response.error ) {
                hrw_stripe.throwErr( response.error ) ;
            } else {
                hrw_stripe.form.append( '<input type="hidden" class="hrw-stripe-paymentMethod" name="hrw_stripe_pm" value="' + response.paymentMethod.id + '"/>' ) ;
                hrw_stripe.form.submit() ;
            }
        } ,
        onVerifyIntentHash : function() {
            var hash = window.location.hash.match( /^#?confirm-hrw-stripe-intent-([^:]+):(.+):(.+):(.+)$/ ) ;

            if( ! hash || 5 > hash.length ) {
                return ;
            }

            var intentClientSecret = hash[1] ,
                    intentObj = hash[2] ,
                    endpoint = hash[3] ,
                    redirectURL = decodeURIComponent( hash[4] ) ;

            //Allow only when the endpoint contains either 'checkout' or 'pay-for-order' or 'add-payment-method'
            if( 'checkout' !== endpoint && 'pay-for-order' !== endpoint && 'add-payment-method' !== endpoint ) {
                return ;
            }

            hrw_stripe.blockFormOnSubmit() ;
            window.location.hash = '' ;

            if( 'setup_intent' === intentObj ) {
                hrw_stripe.onConfirmSi( intentClientSecret , redirectURL , endpoint ) ;
            } else if( 'payment_intent' === intentObj ) {
                hrw_stripe.onConfirmPi( intentClientSecret , redirectURL , endpoint ) ;
            }
            return ;
        } ,
        onConfirmSi : function( intentClientSecret , redirectURL , endpoint ) {

            hrw_stripe.stripeClient.handleCardSetup( intentClientSecret )
                    .then( function( response ) {
                        if( response.error ) {
                            throw response.error ;
                        }

                        //Allow only when the Intent succeeded 
                        if( ! response.setupIntent || 'succeeded' !== response.setupIntent.status ) {
                            return ;
                        }

                        window.location = redirectURL ;
                    } )
                    .catch( function( error ) {
                        hrw_stripe.reset() ;

                        if( 'pay-for-order' === endpoint || 'add-payment-method' === endpoint ) {
                            return window.location = redirectURL ;
                        }

                        hrw_stripe.throwErr( error ) ;

                        // Report back to the server.
                        $.get( redirectURL + '&is_ajax' ) ;
                    } ) ;
        } ,
        onConfirmPi : function( intentClientSecret , redirectURL , endpoint ) {

            hrw_stripe.stripeClient.handleCardPayment( intentClientSecret )
                    .then( function( response ) {
                        if( response.error ) {
                            throw response.error ;
                        }

                        //Allow only when the Intent succeeded 
                        if( ! response.paymentIntent || 'succeeded' !== response.paymentIntent.status ) {
                            return ;
                        }

                        window.location = redirectURL ;
                    } )
                    .catch( function( error ) {
                        hrw_stripe.reset() ;

                        if( 'pay-for-order' === endpoint || 'add-payment-method' === endpoint ) {
                            return window.location = redirectURL ;
                        }

                        hrw_stripe.throwErr( error ) ;

                        // Report back to the server.
                        $.get( redirectURL + '&is_ajax' ) ;
                    } ) ;
        } ,
        onCheckoutErr : function() {
            hrw_stripe.reset( 'yes' , 'no' ) ;
        } ,
        blockFormOnSubmit : function() {
            if( ! hrw_stripe.form ) {
                return ;
            }

            hrw_stripe.form.block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.6
                }
            } ) ;
        } ,
        throwErr : function( error ) {
            hrw_stripe.reset() ;

            if( error.message ) {
                if( $( '.woocommerce-SavedPaymentMethods' ).length ) {
                    var $selected_saved_pm = $( 'input[name="wc-hrw_stripe-payment-token"]' ).filter( ':checked' ).closest( '.woocommerce-SavedPaymentMethods-token' ) ;

                    if( $selected_saved_pm.length && $selected_saved_pm.find( '.hrw-stripe-card-errors' ).length ) {
                        $selected_saved_pm.find( '.hrw-stripe-card-errors' ).text( error.message ) ;
                    } else {
                        $( '#wc-hrw_stripe-cc-form' ).find( '.hrw-stripe-card-errors' ).text( error.message ) ;
                    }
                } else {
                    $( '.hrw-stripe-card-errors' ).text( error.message ) ;
                }
            }

            if( $( '.hrw-stripe-card-errors' ).length ) {
                $( 'html, body' ).animate( {
                    scrollTop : ( $( '.hrw-stripe-card-errors' ).offset().top - 200 )
                } , 200 ) ;
            }

            if( hrw_stripe.form ) {
                hrw_stripe.form.removeClass( 'processing' ) ;
                hrw_stripe.form.unblock() ;
            }
        } ,
        reset : function( remove_pm , remove_notices ) {
            remove_pm = remove_pm || 'yes' ;
            remove_notices = remove_notices || 'yes' ;

            $( '.hrw-stripe-card-errors' ).text( '' ) ;

            if( 'yes' === remove_pm && 'no' === remove_notices ) {
                $( 'input.hrw-stripe-paymentMethod' ).remove() ;
            } else if( 'no' === remove_pm && 'yes' === remove_notices ) {
                $( 'div.woocommerce-notices-wrapper, div.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove() ;
            } else {
                $( 'input.hrw-stripe-paymentMethod, div.woocommerce-notices-wrapper, div.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message' ).remove() ;
            }
        } ,
    } ;

    try {
        // Create a Stripe client.
        hrw_stripe.stripeClient = Stripe( hrw_stripe_params.key ) ;
        // Create an instance of Elements.
        hrw_stripe.stripeElements = hrw_stripe.stripeClient.elements() ;
        // Init
        hrw_stripe.init() ;
    } catch( error ) {
        console.log( error ) ;
        return false ;
    }
} ) ;
