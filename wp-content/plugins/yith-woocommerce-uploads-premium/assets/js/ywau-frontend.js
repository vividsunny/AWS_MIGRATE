jQuery(document).ready(function ($) {

    $(document).on('click', '.select-file', function (e) {
        
        var rule_id = $(this).attr('data-rule-id');
        var item_id = $(this).attr('data-item-id');
        var element = $(this).closest('form[name^="rules_form_"]').find('input#uploadFile_' + rule_id + '_' + item_id);
        $(element).click();
    });

    $(document).on('change', '.file-chooser', function (e) {

        var form = $(this).closest('form[name^="rules_form_"]');

        var filename = $(this).val().replace(/C:\\fakepath\\/i, '');

        $(this).closest('div.upload-items').find('span.uploaded-filename').remove();

        $(this).after('<span class="uploaded-filename">' + filename + '</span>');

        form.block({
            message: null,
            overlayCSS: {
                background: "#fff url(" + ywau.loader + ") no-repeat center",
                opacity: .6
            }
        });

        setTimeout(function() {
            form.unblock();
        }, 1500);

    });

//  let the user to delete a file that was uploaded
    $(document).on('click', '.delete-uploaded-file', function (e) {
        var form = $(this).closest('form[name^="rules_form_"]');

        var product_id = form.find('input[name="ywau_product"]').val();
        var order_id = form.find('input[name="ywau_order"]').val();
        var item_id = form.find('input[name="ywau-order-item"]').val();
        var variation_id = form.find('input[name="ywau_variation"]').val();
        var scope = form.find('input[name="ywau_scope"]').val();
        var rule_id = $(this).attr('data-rule-id');

        var data = {
            'action': 'delete_uploaded_file',
            'product_id': product_id,
            'order_id': order_id,
            'item_id': item_id,
            'variation_id' : variation_id,
            'rule_id': rule_id,
            'scope' : scope,
            'return_path': window.location.href
        };

        var current_element = this;
        var parent_element = $(this).closest("div.upload-rules");

        form.block({
            message: null,
            overlayCSS: {
                background: "#fff url(" + ywau.loader + ") no-repeat center",
                opacity: .6
            }
        });

        $.post(ywau.ajax_url, data, function (response) {

            if (1 == response.code) {
                $("div.upload-rules").replaceWith(response.items.html_to_show_removed_files);
                $("a.rules_product_" + product_id + "_" + variation_id).text(response.button);

                $(".submit-files input").click();
            }
            else if (-1 == response.code) {
                $('span.unable-to-delete').remove();
                $(current_element).after('<span class="unable-to-delete">' + ywau.unable_delete + '</span>');
            }

            form.unblock();
        });



    });
});