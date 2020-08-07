;(function ( $, window, document, undefined ) {
	$( function() {

		// Quick edit
		$('#the-list').on('click', '.editinline', function () {
			inlineEditPost.revert();

			// show all republish fields
			$('p._woonet_publish_to', '.inline-edit-row').show();
			$('p._woonet_publish_to input', '.inline-edit-row').prop('checked', false);

			const post_id         = $(this).closest('tr').attr('id').replace('post-', ''),
				  $wm_inline_data = $('#woocommerce_multistore_inline_' + post_id);

			$('div', $wm_inline_data).each(function (index, element) {
				const name = $(element).attr('class'),
					  value = $(element).text();

				if ('_is_master_product' === name) {
					$('input[name="_is_master_product"]', '.inline-edit-row').val(value);
				} else if ('master_blog_id' === name) {
					$('input[name="master_blog_id"]', '.inline-edit-row').val(value);

					// hide republish settings for master blog
					$('p[data-group-id="' + value + '"]', '.inline-edit-row').hide();

					// show categories appropriate to selected blog
					if (typeof blog_categories !== 'undefined' && blog_categories) {
						$('ul.cat-checklist.product_cat-checklist', '.inline-edit-row').html(blog_categories[value]);
					}
				} else if ('product_blog_id' === name) {
					$('input[name="product_blog_id"]', '.inline-edit-row').val(value);
				} else {
					$('input[name="' + name + '"]', '.inline-edit-row').prop('checked', 'yes' === value);
				}
			});
		});

		$('.inline-edit-row #woonet_toggle_all_sites').change(function () {
			const checked = $(this).is(":checked");

			$('.inline-edit-row .woonet_sites input[type="checkbox"]._woonet_publish_to').each(function () {
				if (jQuery(this).prop('disabled') === false) {
					jQuery(this).attr('checked', checked);
					jQuery(this).trigger('change');
				}
			});
		});

		// on
		$('.ptitle').on('focus', function (e) {
			const row = $(this).closest('tr.inline-editor');

			if ('yes' === $('input[name="_is_master_product"]', row).val()) {
				$("#woonet-quick-edit-fields-slave", row).remove();
			} else {
				$("#woonet-quick-edit-fields", row).remove();
			}

			$('input[name$="_child_stock_synchronize"]', row).prop('disabled', 'yes' === woonet_options['synchronize-stock']);
		});

		// Bulk edit
		$( '#wpbody' ).on( 'click', '#doaction, #doaction2', function() {
			// get action name
			const action = $( this ).is( '#doaction' ) ? $( '#bulk-action-selector-top' ).val() : $( '#bulk-action-selector-bottom' ).val();

			// do nothing if not bulk edit
			if ( 'edit' !== action ) {
				return true;
			}

			// clone multistore fields from "quick edit" form
			$('fieldset.woocommerce-multistore-fields', '#bulk-edit').remove();
			$('#woonet-quick-edit-fields', '#inline-edit').clone().attr("id","woonet-bulk-edit-fields").insertBefore( $( "div.submit", '#bulk-edit' ) );
			$('#woonet-quick-edit-fields-slave', '#inline-edit').clone().attr("id","woonet-bulk-edit-fields-slave").insertBefore( $( "div.submit", '#bulk-edit' ) );

			// show all republish fields
			$( 'p._woonet_publish_to', '.inline-edit-row' ).show();
			$( 'p._woonet_publish_to input', '.inline-edit-row' ).prop( 'checked', false );

			// Check selected products are master from one store
			let master_blog_ids  = [],
				product_blog_ids = [];
			// for each selected product
			$( 'tbody th.check-column input[type="checkbox"]' ).each( function() {
				if ( $(this).prop('checked') ) {
					// get product master blog id
					let id              = $(this).val(),
						master_blog_id  = $('div.master_blog_id', '#woocommerce_multistore_inline_' + id).text(),
						product_blog_id = $('div.product_blog_id', '#woocommerce_multistore_inline_' + id).text();

					if ( master_blog_id ) {
						// hide republish settings for blog_id
						$('p[data-group-id="' + master_blog_id + '"]', '.inline-edit-row').hide();
						// collect selected product blog id
						master_blog_ids.push( master_blog_id );
					}

					product_blog_ids.push( product_blog_id );
				}
			} );
			// get unique blog ids
			master_blog_ids = master_blog_ids.filter(function (x, i, a) {
				return a.indexOf(x) === i;
			});
			// if selected products more then from one blog
			if ( master_blog_ids.length > 1 ) {
				alert("Selection contains parent products from multiple stores.\nYou can bulk edit only products that are parent products in the same store at the same time.");
				return inlineEditPost.revert();
			}

			// get unique blog ids
			product_blog_ids = product_blog_ids.filter(function (x, i, a) {
				return a.indexOf(x) === i;
			});
			// if selected products more then from one blog
			if ( product_blog_ids.length > 1 ) {
				alert("Selection contains parent products from multiple stores.\nYou can bulk edit only products that are parent products in the same store at the same time.");
				return inlineEditPost.revert();
			}

			const row = $('tr.inline-editor');
			if ( master_blog_ids[0] === product_blog_ids[0] ) {
				$("#woonet-bulk-edit-fields-slave", row ).remove();
			} else {
				$("#woonet-bulk-edit-fields", row ).remove();
			}

			// replace "inputs" with "selects"
			$( 'input', '#bulk-edit p._woonet_publish_to, #bulk-edit #woonet-bulk-edit-fields-slave' ).replaceWith( function() {
				const options = [
					{ value : '',    text: '— Use Product Settings —' },
					{ value : 'yes', text: 'Yes' },
					{ value : 'no',  text: 'No' }
				];

				let $select = $('<select/>').attr( {
					'class': $( this ).attr('class'),
					'name': $( this ).attr('name')
				} );
				$( options ).each( function( index, option ) {
					$select.append( $('<option/>').attr( 'value', option.value ).text( option.text ) );
				});

				return $select;
			} );

			$('select[name$="_child_stock_synchronize"]', row ).prop('disabled', 'yes' === woonet_options['synchronize-stock']);

			// show categories appropriate to selected blog
			if ( typeof blog_categories !== 'undefined' && blog_categories ) {
				$( 'ul.cat-checklist.product_cat-checklist', '.inline-edit-row' ).html( blog_categories[ master_blog_ids[0] ] );
			}

			$( '#woonet_toggle_all_sites' ).change( function() {
				$( '#bulk-edit p._woonet_publish_to select._woonet_publish_to' ).val( $( this ).is( ':checked' ) ? 'yes' : '' );
			});
		});

	} );
})( jQuery, window, document );

;(function ( $, window, document, undefined ) {
	$( function() {

		$('#doaction, #doaction2').click(function(e){
			let n      = $( this ).attr( 'id' ).substr( 2 ),
				action = $( 'select[name="' + n + '"]' ).val();

			if ( -1 !== $.inArray( action, ['trash', 'untrash', 'delete'] ) ) {
				e.preventDefault();

				// Check selected products are master from one store
				let form_data = $('#posts-filter').serialize();
				// for each selected product
				$( 'tbody th.check-column input[type="checkbox"]' ).each( function() {
					if ( $(this).prop('checked') ) {
						// get product master blog id
						let id              = $(this).val(),
							product_blog_id = $('div.product_blog_id', '#woocommerce_multistore_inline_' + id).text();

						form_data += '&blog_ids%5B%5D=' + product_blog_id;
					}
				} );

				window.location.replace( window.location.href + '&' + form_data );
			}
		});

	} );
})( jQuery, window, document );
