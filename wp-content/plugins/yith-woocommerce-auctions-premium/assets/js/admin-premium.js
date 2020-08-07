jQuery(document).ready(function($) {


	$('select#product-type').on('change',function(){
	    /*var value = $(this).val();
	    if (value == 'auction'){
	    	$('#_regular_price').val('');
	    	$('#_sale_price').val('');
			//$('.stock_fields ').css('display','none');
	    }*/
	});

	$('#reshedule_button').on('click',function(){
		$('#yith_auction_settings').block({message:null, overlayCSS:{background:"#fff",opacity:.6}});
		var post_data = {
			'id': object.id,
			//security: object.search_post_nonce,
			action: 'yith_wcact_reshedule_product'
		};

		$.ajax({
			type    : "POST",
			data    : post_data,
			url     : object.ajaxurl,
			success : function ( response ) {
				$('#yith_auction_settings').unblock();
				$('#reshedule_button').hide();
				$('#yith-reshedule-notice-admin').show();
				$('#_stock_status').val('instock');
				//window.location.reload();
				// On Success
			},
			complete: function () {
			}
		});
	});

	$('.yith-wcact-delete-bid').on('click',function(e){
		e.preventDefault();
		$('#yith-wcgpf-auction-bid-list').block({message:null, overlayCSS:{background:"#fff",opacity:.6}});

		if(window.confirm(object.confirm_delete_bid)){
			var post_data = {
				'user_id': $(this).data('user-id'),
				'product_id': $(this).data('product-id'),
				'date' : $(this).data('date-time'),
				'bid': $(this).data('bid'),
				action: 'yith_wcact_delete_customer_bid'
			};

			$.ajax({
				type    : "POST",
				data    : post_data,
				url     : object.ajaxurl,
				success : function ( response ) {
					current_target          = $( e.target );
					parent                  = current_target.closest( '.yith-wcact-row' );
					parent.remove();
					$('#yith-wcgpf-auction-bid-list').unblock();
				},
				complete: function () {
				}
			});

		} else {
			$('#yith-wcgpf-auction-bid-list').unblock();
		}

	})


	$(document.body).on('yith_wcact_send_winner_email_button',function () {

        $('#yith-wcact-send-winner-email').on('click',function(){
            $('.yith-wcact-admin-auction-status').block({message:null, overlayCSS:{background:"#fff",opacity:.6}});
            var post_data = {
                'id': object.id,
                //security: object.search_post_nonce,
                action: 'yith_wcact_resend_winner_email'
            };

            $.ajax({
                type    : "POST",
                data    : post_data,
                url     : object.ajaxurl,
                success : function ( response ) {
                    $('.yith-wcact-admin-auction-status').empty();
                    $('.yith-wcact-admin-auction-status').html( response['resend_winner_email'] );
                    $(document.body).trigger('yith_wcact_send_winner_email_button');
                    $('.yith-wcact-admin-auction-status').unblock();
                },
                complete: function () {
                }
            });
        });
    });


	$(document.body).trigger('yith_wcact_send_winner_email_button');

	$( document ).find( '._tax_status_field' ).closest( 'div' ).addClass( 'show_if_auction' );
	$( 'select#product-type' ).trigger('change');

});
