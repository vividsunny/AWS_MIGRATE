jQuery(document).ready(function ($) {

    function allow_upload(status) {
        if (status) {
            $("#multiple-upload-section div.hide-settings").remove();
            $("#multiple-upload-section").removeClass('disable-upload');
        }
        else {
            $("#multiple-upload-section").append('<div class="hide-settings"></div>');
            $("#multiple-upload-section").addClass('disable-upload');

        }
    }

    if ($('input[name="disable_upload"]').attr('checked')) {
        allow_upload(false);
    }

    $(document).on('change', '.disable_upload', function (e) {

        allow_upload(!$(this).attr('checked'));

    });

    //  let the admin to send a message to order customer
    $(document).on('click', '#send-order-message', function (e) {
        $('.ywau_order_message_result').remove();

        var message = $('#ywau_order_message').val();
        var post_id = $('#post_ID').val();

        var data = {
            'action': 'sent_message',
            'message': message,
            'post_id': post_id
        };

        var parent_element = $(this).closest("div.order-message-section");

        parent_element.block({
            message: null,
            overlayCSS: {
                background: "#fff url(" + ywau.loader + ") no-repeat center",
                opacity: .6
            }
        });

        $.post(ywau.ajax_url, data, function (response) {
            if (-1 == response.code) {
                $('#ywau_order_message').after('<span class="ywau_order_message_result">' +
                    ywau.order_message_fail + '</span>');
            }
            else if (1 == response.code) {

                $('#ywau_order_message').after('<span class="ywau_order_message_result">' +
                    ywau.order_message_success + '</span>');
            }

            parent_element.unblock();
        });
    });

    /**
     * Set a file as accepted or rejected and let the admin to write a message to be sent to the customer
     */
    $('.set-upload-file-status').on('click', function (e) {
        e.preventDefault();
        var parent_element = $(this).closest("div.rule-actions");

        var item_id = $(this).attr("data-item-id");
        var rule_id = $(this).attr("data-rule-id");
        var action = $(this).attr("data-action");

        parent_element.find(".set-upload-file-status.accept, .set-upload-file-status.reject").css('display', 'none');

        if ("accept" == action) {
            //  Do something
            parent_element.find("span.upload-file-status.accepted").css('display', 'inline');
            parent_element.find('input[name^="ywau_order_file_status"]').val(1);

        } else if ("reject" == action) {
            //  Do something
            parent_element.find("span.upload-file-status.rejected").css('display', 'inline');
            parent_element.find('textarea[name^="ywau_order_file_note"]').css('display', 'inline');
            parent_element.find('textarea[name^="ywau_order_file_note"]').prop('disabled', false);
            parent_element.find('input[name^=ywau_order_file_status]').val(-1);
        }
        else {
            //  Nothing to do
        }
        parent_element.find("a.reset-upload-file-status").css('display', 'inline');
    });

    /**
     * Cancel a previous accepted or rejected status
     */
    $('.reset-upload-file-status').on('click', function (e) {
        e.preventDefault();
        var parent_element = $(this).closest("div.rule-actions");
        parent_element.find("span.upload-file-status.accepted, span.upload-file-status.rejected").css('display', 'none');
        parent_element.find(".set-upload-file-status.accept, .set-upload-file-status.reject").css('display', 'inline');
        parent_element.find('textarea[name^="ywau_order_file_note"]').css('display', 'none');
        parent_element.find('textarea[name^="ywau_order_file_note"]').prop('disabled', true);
        parent_element.find('input[name^="ywau_order_file_status"]').val(0);

        $(this).css('display', 'none');
    });
});
