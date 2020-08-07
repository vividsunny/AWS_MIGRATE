jQuery(function($) {

    wooMP.currentAction        = null;
    wooMP.transactionSucceeded = false;

    var currentPanel           = $('#woo-mp [data-current-panel]').data('panel');
    var previousPanel          = 'main';

    wooMP.$main                = $('#woo-mp #woo-mp-main');
    wooMP.$cardNum             = $('#woo-mp #cc-num');
    wooMP.$cardExp             = $('#woo-mp #cc-exp');
    wooMP.$cardCVC             = $('#woo-mp #cc-cvc');

    var $chargeAmount          = $('#woo-mp #charge-amount');
    var $chargeBtn             = $('#woo-mp #charge-btn');

    var noticeTemplate         = null;

    function init() {
        window.addEventListener('error', handleJSError);

        $('#post').on('change', ':input:not(#woo-mp *)', warnOrderChangesUnsaved);

        $('#woo-mp [data-open-panel]').click(openPanel);
        $('#woo-mp [data-close-panel]').click(closePanel);

        $(document).on('click', '[data-toggle="collapse"]', toggleCollapse);

        wooMP.$cardNum.payment('formatCardNumber');
        wooMP.$cardExp.payment('formatCardExpiry');
        wooMP.$cardCVC.payment('formatCardCVC');

        initChargeAmountAutofill();
        $chargeAmount.on('input', updateButton);
        updateButton();

        $('#charge').keypress(chargeEnter);
        $chargeBtn.click(submit);

        $( '#woo-mp .woo-mp-rating-request a' ).click(rated);
    }

    function handleJSError(error) {
        try {
            if (wooMP.currentAction === 'process_transaction') {
                var basicMessage =
                    'Sorry, there was an error. The transaction appears to have ' +
                    (wooMP.transactionSucceeded ? 'been successful' : 'failed') +
                    '. You can check your payment processor account to confirm.';

                var errorLocation = error.filename + ':' + error.lineno + (error.colno ? ':' + error.colno : '');
                var stackTrace    = (error.error || {}).stack;

                var fullMessage = basicMessage + '<br><br>' + formatErrorData({ Error: error.message });

                var details = '<p>' + formatErrorData({
                    Location:      errorLocation,
                    'Stack Trace': stackTrace
                }) + '</p>';

                wooMP.handleError(fullMessage, null, details);
            }
        } catch (secondaryError) {
            console.error(secondaryError);

            alert(
                basicMessage +
                '\n\nError:\n' + error.message +
                '\n\nLocation:\n' + errorLocation +
                (stackTrace ? '\n\nStack Trace:\n' + stackTrace : '')
            );

            location.reload();
        }
    }

    function warnOrderChangesUnsaved() {
        globalNotice(
            'warning',
            "It looks like you've edited the order. " +
            'If you process a transaction before saving the order, your changes will be lost.',
            null,
            true
        );
    }

    function openPanel() {
        previousPanel = currentPanel;
        currentPanel  = this.dataset.openPanel;

        $('[data-panel="' + currentPanel + '"]').slideDown();
        $('[data-panel="' + previousPanel + '"]').slideUp();
    }

    function closePanel() {
        oldPreviousPanel = previousPanel;
        previousPanel    = currentPanel;
        currentPanel     = oldPreviousPanel;

        $('[data-panel="' + previousPanel + '"]').slideUp();
        $('[data-panel="' + currentPanel + '"]').slideDown();
    }

    function globalNotice(type, message, details, isDismissible, raw) {
        notice($('#woo-mp .global-notice'), type, message, details, isDismissible, raw);
    }

    function panelNotice(type, message, details, isDismissible, raw) {
        notice($('[data-panel="' + currentPanel + '"] .panel-notice'), type, message, details, isDismissible, raw);
    }

    function notice($elem, type, message, details, isDismissible, raw) {
        if (! noticeTemplate) {
            noticeTemplate = wooMP.template($('#notice-template').html());
        }

        var html = noticeTemplate({
            id:            Math.floor((Math.random() * 9999999)),
            type:          type,
            message:       message,
            details:       details,
            isDismissible: Boolean(isDismissible),
            raw:           Boolean(raw)
        });

        $elem.html(html).slideDown('fast');

        $elem.find('.notice-dismiss').click(function () {
            $elem.slideUp('fast');
        });
    }

    function toggleCollapse() {
        var $this = $(this);

        $('#' + $this.attr('aria-controls').replace(' ', ', #')).slideToggle('fast');
        $this.attr('aria-expanded', $this.attr('aria-expanded') === 'true' ? 'false' : 'true');
    }

    function updateButton() {
        var amount = $chargeAmount.val();

        $chargeBtn.text('Charge ' + wooMP.formatMoney(amount > 0 ? amount : 0));
    }

    function initChargeAmountAutofill() {
        var amount               = null;
        var $button              = $('#woo-mp-charge-amount-autofill-btn');
        var orderTotalsTableHTML = $('.wc-order-totals-items').html();

        // If we can't find the totals element, then we'll update the amount every 10 seconds.
        var updateInterval = orderTotalsTableHTML ? 5000 : 10000;

        function updateAmount() {
            $.get(wooMP.AJAXURL, {
                action:   'woo_mp_get_unpaid_order_balance',
                order_id: $('#post_ID').val(),
                currency: wooMP.currency
            })
            .done(function (response) {
                function handleError(message) {
                    updateAutofillButton('An error occured while retrieving the charge amount suggestion.');

                    console.error('Error attempting to retrieve unpaid balance: ' + message);
                }

                if (response.success === undefined) {
                    return handleError('Invalid response: ' + JSON.stringify(response));
                }

                if (! response.success) {
                    return handleError(response.data);
                }

                amount = response.data.amount;

                updateAutofillButton(response.data.title, amount);
            })
            .fail(function (jqXHR) {
                updateAutofillButton('An error occured while retrieving the charge amount suggestion.');

                console.error(jqXHR);
            });
        }

        function updateAutofillButton(title, amount) {
            var formattedAmount = wooMP.formatMoney(amount);
            var html            = 'of ' + formattedAmount;

            if (! amount) {
                html = '<span class="dashicons dashicons-editor-help"></span>';
            }

            $button.html(html).attr('title', title.replace('%amount%', formattedAmount)).prop('disabled', ! amount).show();
        }

        updateAmount();

        setInterval(function () {
            var currentHTML = $('.wc-order-totals-items').html();

            if (currentHTML !== orderTotalsTableHTML || ! currentHTML) {
                updateAmount();
            }

            orderTotalsTableHTML = currentHTML;
        }, updateInterval);

        $button.click(function () {
            $chargeAmount.val(amount);

            updateButton();
        });
    }

    function chargeEnter(event) {
        if (event.keyCode == 13) {
            event.preventDefault();

            submit();
        }
    }

    function submit() {
        if (wooMP.currentAction) {
            return;
        }

        wooMP.currentAction        = 'process_transaction';
        wooMP.transactionSucceeded = false;

        if (! valid()) return;

        wooMP.blockUI();

        wooMP.beginProcessing();
    }

    function valid() {
        $('#woo-mp #charge input').removeClass('invalid');

        if (! wooMP.automaticValidation('#charge')) {
            return false;
        }

        if (! $.payment.validateCardNumber(wooMP.$cardNum.val())) {
            wooMP.handleError('Sorry, the card number is not valid.', wooMP.$cardNum);
            return false;
        }

        if (! $.payment.validateCardExpiry(
            wooMP.$cardExp.payment('cardExpiryVal').month,
            wooMP.$cardExp.payment('cardExpiryVal').year
        )) {
            wooMP.handleError('Sorry, the expiration date is not valid.', wooMP.$cardExp);
            return false;
        }

        if (
            wooMP.$cardCVC.val() &&
            ! $.payment.validateCardCVC(wooMP.$cardCVC.val(), $.payment.cardType(wooMP.$cardNum.val()))
        ) {
            wooMP.handleError('Sorry, the security code is not valid.', wooMP.$cardCVC);
            return false;
        }

        if (Number($chargeAmount.val()) === 0) {
            wooMP.handleError('Please enter a charge amount.', $chargeAmount);
            return false;
        }

        // Allow for payment processors to implement their own validation.
        if (wooMP.valid) {
            if (! wooMP.valid()) return false;
        }

        return true;
    }

    wooMP.template = function (text) {

        // To avoid conflicts with ASP-style PHP tags.
        var settings = {
            interpolate: /{!([\s\S]+?)!}/g, // {! raw output !}
            escape:      /{{([\s\S]+?)}}/g, // {{ HTML-escaped output }}
            evaluate:    /{%([\s\S]+?)%}/g  // {% evaluated code %}
        };

        var versionParts = _.VERSION.split('.');

        // Underscore.js versions below 1.7.0 have a different signature for the _.template() function.
        if (versionParts[0] === '0' || (versionParts[0] === '1' && versionParts[1] < 7)) {
            return _.template(text, null, settings);
        }

        return _.template(text, settings);
    };

    wooMP.automaticValidation = function (form) {
        var message, field;

        $(form).find('input').each(function () {

            // We're not using the 'required' attribute because the whole page is in a post form
            // and we don't want the validation to fail when the user submits it.
            if (this.dataset.required !== undefined && this.value === '' && ! this.validity.badInput) {
                var article = /^[aeiou]/i.test(this.dataset.fieldName[0]) ? 'an' : 'a';

                message = 'Please enter ' + article + ' ' + this.dataset.fieldName + '.';
            } else if (this.type === 'number') {
                if (this.validity.badInput) {
                    message = 'Please enter a number without any other symbols in the ' + this.dataset.fieldName + ' field.';
                } else if ($.isNumeric(this.step) && this.step % 1 === 0 && this.value % 1 !== 0) {
                    message = 'Please enter an integer in the ' + this.dataset.fieldName + ' field.';
                } else if (this.validity.rangeUnderflow) {
                    if (this.min === '0') {
                        message = 'Please enter a positive number in the ' + this.dataset.fieldName + ' field.';
                    } else {
                        message = 'The minimum value for the ' + this.dataset.fieldName + ' field is ' + this.min + '.';
                    }
                } else if (this.validity.rangeOverflow) {
                    message = 'The maximum value for the ' + this.dataset.fieldName + ' field is ' + this.max + '.';
                }
            }

            if (message) {
                field = this;

                return false;
            }
        });

        if (message) {
            wooMP.handleError(message, field);

            return false;
        }

        return true;
    };

    wooMP.processPayment = function (paymentData, successCallback) {
        var data = {
            transaction_type: 'charge',
            amount:           $chargeAmount.val(),
            currency:         wooMP.currency,
            last_4:           wooMP.$cardNum.val().slice(-4)
        };

        $.extend(data, paymentData);

        wooMP.processTransaction(data, successCallback);
    };

    wooMP.processTransaction = function (transactionData, successCallback) {
        var data = {
            action:     'woo_mp_process_transaction',
            _wpnonce:   wooMP.nonces.woo_mp_process_transaction,
            gateway_id: wooMP.gatewayID,
            order_id:   $('#post_ID').val()
        };

        if (typeof transactionData === 'string') {
            data = $.param(data) + '&' + transactionData;
        } else {
            $.extend(data, transactionData);
        }

        successCallback = successCallback || doSuccess;

        $.post(wooMP.AJAXURL, data)
            .done(function (response) {
                if (!response) {
                    wooMP.handleError(wooMP.generateUnknownTransactionErrorMessage(
                        'Sorry, there was no response from the server.'
                    ));

                    return;
                }

                if (!response.status) {
                    var message = wooMP.generateUnknownTransactionErrorMessage(
                        "Sorry, we can't determine the status of the operation.",
                        {Response: response}
                    );

                    wooMP.handleError(message);

                    return;
                }

                if (response.status !== 'success') {
                    if (response.message) {
                        wooMP.catchError(response.message, response.code, response.data);
                    } else {
                        var message = wooMP.generateUnknownTransactionErrorMessage(
                            'Sorry, there was an error.',
                            {Response: response}
                        );

                        wooMP.handleError(message);
                    }

                    return;
                }

                successCallback(response);
            })
            .fail(function (jqXHR) {
                var message = '';

                if (jqXHR.status === 403) {
                    message = 'Sorry, it appears your session has expired. Please refresh the page and try again.';
                } else {
                    message = wooMP.generateUnknownTransactionErrorMessage('Sorry, there was an error.', {
                        Error:    jqXHR.statusText,
                        Response: jqXHR.responseText
                    });
                }

                wooMP.handleError(message);
            });
    };

    wooMP.blockUI = function () {
        wooMP.$main.block({
            message:    null,
            overlayCSS: {
                background: "#fff",
                opacity:    .6
            }
        });
    };

    wooMP.generateUnknownTransactionErrorMessage = function (basicMessage, data) {
        var message = basicMessage +
            " We don't know whether the transaction was successful. " +
            'Please check your payment processor account to confirm. ' +
            'You may be able to find additional information in your PHP error log.';

        var formattedData = formatErrorData(data);

        if (formattedData) {
            message += '<br><br>' + formattedData;
        }

        return message;
    };

    wooMP.handleError = function (message, field, details, raw) {
        panelNotice('error', message, details, false, raw);

        wooMP.currentAction        = null;
        wooMP.transactionSucceeded = false;

        if (field) {
            $(field).addClass('invalid').focus();
        }

        wooMP.$main.unblock();
    };

    wooMP.formatMoney = function (amount, params) {
        return accounting.formatMoney(amount, $.extend({
			symbol:    wooMP.currencySymbol,
			decimal:   woocommerce_admin_meta_boxes.currency_format_decimal_sep,
			thousand:  woocommerce_admin_meta_boxes.currency_format_thousand_sep,
			precision: 2,
			format:    woocommerce_admin_meta_boxes.currency_format
		}, params))
    };

    function formatErrorData(data) {
        var formattedData = '';

        $.each(data, function (key, value) {
            if (value || value === 0) {
                if (typeof value === 'object' || typeof value === 'array') {
                    value = JSON.stringify(value, null, 4);
                }

                formattedData += key + ':<code class="raw-error">' + _.escape(value) + '</code>';
            }
        });

        return formattedData;
    }

    function doSuccess() {
        wooMP.transactionSucceeded = true;

        // If we're on the 'Add new order' page, then location.reload() would create a new order.
        // Using location.href also has the added benefit of scrolling the page to the top, where
        // the user can see the success notice and the order note.
        location.href = 'post.php?post=' + $('#post_ID').val() + '&action=edit';
    }

    function rated() {
        $.post( wooMP.AJAXURL, { action: 'woo_mp_rated' } );
        $('#woo-mp .woo-mp-rating-request').html('Thank you <span class="emoji">☺</span>');
    }

    init();

});
