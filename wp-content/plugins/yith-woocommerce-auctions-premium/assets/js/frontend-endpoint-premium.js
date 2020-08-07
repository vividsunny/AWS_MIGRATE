jQuery(document).ready(function($) {

    if ( object.time_check  > 0 ) {
        setInterval(live_auctions, object.time_check);
        function live_auctions() {
            $('.yith_wcact_my_auctions_table').block({message: null, overlayCSS: {background: "#fff", opacity: .6}});

            var post_data = {
                //security: object.search_post_nonce,
                currency: $('#yith_wcact_currency').val(),
                action: 'yith_wcact_update_my_account_auctions'
            };

            $.ajax({
                type: "POST",
                data: post_data,
                url: object.ajaxurl,
                success: function (response) {
                    change_price_and_status(response);
                    $('.yith_wcact_my_auctions_table').unblock();
                },
                complete: function () {
                }
            });
        }
    }

    function change_price_and_status(response) {

        $('.yith-wcact-auction-endpoint').remove();
        for ( var i = 0; i<= Object.keys(response).length-1; i++) {
            var row_td = '<td class="order-number yith-wcact-auction-image" data-title="Image">'+response[i].image+'</td>' +
                         '<td class="order-status" data-title="Product"><a href="'+response[i].product_url+'">'+response[i].product_name+'</a></td>' +
                         '<td class="yith-wcact-my-bid-endpoint yith-wcact-my-auctions order-date '+response[i].color+'"  data-title="Your bid">'+response[i].my_bid+'</td>' +
                         '<td class="yith-wcact-current-bid-endpoint yith-wcact-my-auctions order-total" data-title="Current bid">'+response[i].price+'</td>' +
                         '<td class="yith-wcact-auctions-status yith-wcact-my-auctions order-status" data-title="Status">'+response[i].status+'</td>';
            var row_tr = $('<tr class="yith-wcact-auction-endpoint"></tr>').append(row_td);
            $('.yith_wcact_my_auctions_table').append(row_tr);
        }
    }
});