jQuery(function($) {

    wooMP.beginProcessing = function () {
        try {
            Stripe.setPublishableKey(wooMP.publishableKey);
            Stripe.card.createToken({
                number: wooMP.$cardNum.val(),
                exp:    wooMP.$cardExp.val(),
                cvc:    wooMP.$cardCVC.val()
            }, stripeResponseHandler);
        } catch (error) {
            wooMP.handleError(error.message);
        }
    };

    function stripeResponseHandler(status, response) {
        if (response.error) {
            wooMP.catchError(response.error.message);
        } else {
            wooMP.processPayment({
                token: response.id
            });
        }
    }

    wooMP.catchError = function (message, code) {
        switch (code) {
            case 'moto_incorrectly_enabled':
                wooMP.handleError($('#woo-mp-stripe-notice-template-moto-incorrectly-enabled').html(), null, null, true);
                return;
            case 'auth_required_moto_disabled':
                var message = $('#woo-mp-stripe-notice-template-auth-required-moto-disabled').html();
                var details = $('#woo-mp-stripe-notice-template-partial-invoice-instructions').html();

                wooMP.handleError(message, null, details, true);
                return;
            case 'auth_required_moto_enabled':
                var message = $('#woo-mp-stripe-notice-template-auth-required-moto-enabled').html();
                var details = $('#woo-mp-stripe-notice-template-partial-invoice-instructions').html();

                wooMP.handleError(message, null, details, true);
                return;
        }

        switch (message) {

            // Stripe returns this error when an expiration date is very far in the future.
            case "Your card's expiration year is invalid.":
                wooMP.handleError('Sorry, the expiration date is not valid.', wooMP.$cardExp);
                break;
            case "Your card's security code is incorrect.":
                wooMP.handleError('Sorry, the security code is not valid.', wooMP.$cardCVC);
                break;
            default:
                wooMP.handleError(message);
                break;
        }
    };

});
