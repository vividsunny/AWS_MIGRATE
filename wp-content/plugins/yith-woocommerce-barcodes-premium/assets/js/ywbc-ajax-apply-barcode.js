(function ($) {

    $(document).ready(function ($) {

        $(document).on('click', '.ywbc_apply_barcode', function (e) {
            e.preventDefault();

            var item_ids = $(this).data('item_ids'),
                ajax_action = $(this).data('barcode_action'),
                progress_bar = $('#ywbc_apply_progressbar_all'),
                progress_bar_perc = $('#ywbc_apply_progressbar_percent_all'),
                message_container = $('.ywbc_apply_messages'),
                current_index = 0;

            message_container.hide();

            //initialize progressbar
            progress_bar.progressbar();
            progress_bar_perc.html("0% (0/" + item_ids.length + ")");
            progress_bar.show();

            var item_length = item_ids.length;

            $(this).prop('disabled', true);

            function applyBarcode(current_index, item_id) {

                var data = {
                    ywbc_item_id: item_id,
                    action: ajax_action
                };

                $.ajax({

                    type: 'POST',
                    url: ywbc_params.ajax_url,
                    data: data,
                    dataType: 'json',
                    success: function (response) {

                        if (response.result == 'barcode_created') {

                            progress_bar.progressbar("value", ( (current_index + 1) / item_length ) * 100);
                            progress_bar_perc.html(Math.round(( (current_index + 1) / item_length ) * 1000) / 10 + "%" +  " (" + (current_index + 1) + "/" + item_ids.length + ")");

                            if (current_index < item_length - 1) {
                                current_index++;
                                applyBarcode(current_index, item_ids[current_index]);
                            } else {
                                message_container.addClass('complete_all_task');
                                message_container.find('.ywbc_apply_icon').addClass('dashicons dashicons-yes');
                                message_container.find('.ywbc_apply_text').html(ywbc_params.messages.complete_task);
                                message_container.show();
                                progress_bar.hide();
                                $('.ywbc_apply_barcode').prop('disabled', false);
                            }

                        } else {

                            message_container.addClass('error_task');
                            message_container.find('.ywbc_apply_icon').addClass('dashicons dashicons-no');
                            message_container.find('.ywbc_apply_text').html(ywbc_params.messages.error_task);
                            message_container.show();
                            progress_bar.hide();
                            $('.ywbc_apply_barcode').prop('disabled', false);
                        }
                    }

                });
            }

            applyBarcode(current_index, item_ids[current_index]);

        });


        $(document).on('click', '.ywbc-print-barcode', function (e) {
            e.preventDefault();

            var item_ids = $(this).data('item_ids');

            var ajax_zone = $('.ywbc-print-barcode-tr');

                var data = {
                  action: 'print_product_barcodes_document',
                  ywbc_item_ids: item_ids,
                };

                ajax_zone.block({message: null, overlayCSS: {background: "#f1f1f1", opacity: .7}});

                $.ajax({
                    type: 'POST',
                    url: ywbc_params.ajax_url,
                    data: data,
                    success: function (response) {
                        console.log('Processing, do not cancel');

                        $('#aux-print-barcodes').append(response['result']);

                        var divToPrint=document.getElementById("aux-print-barcodes");
                        newWin= window.open("");
                        newWin.document.write(divToPrint.outerHTML);
                        newWin.print();
                        newWin.close();
                        $('#aux-print-barcodes').html("");
                        ajax_zone.unblock();
                    },
                    error: function (response) {
                        console.log("ERROR");
                        console.log(response);
                        ajax_zone.unblock();
                        return false;
                    }
                });
        });

    });
})
(jQuery);
