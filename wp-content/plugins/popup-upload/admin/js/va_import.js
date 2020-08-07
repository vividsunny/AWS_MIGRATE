;(function ( $, window ) {

	/**
	 * productImportForm handles the import process.
	 */
	var ImportForm = function( $form ) {
		this.$form           = $form;
		this.popup_ajax		 = va_import_params.popup_ajax;
		this.xhr             = false;
		this.mapping         = va_import_params.mapping;
		this.position        = 0;
		this.file            = va_import_params.file;
		this.update_existing = va_import_params.update_existing;
		this.delimiter       = va_import_params.delimiter;
		this.security        = va_import_params.import_nonce;

		// Number of import successes/failures.
		this.imported = 0;
		this.failed   = 0;
		this.updated  = 0;
		this.skipped  = 0;


		// Initial state.
		this.$form.find('.va-importer-progress').val( 0 );
		this.$form.find('.va-importer-progress').css('width', '1%' );
		this.$form.find('.va-progress').html( '1%' );

		$('#va_import_log').removeClass('hidden');
		$('#va_reports').removeClass('hidden');


		this.run_import = this.run_import.bind( this );

		// Start importing.
		this.run_import();
	};

	/**
	 * Run the import in batches until finished.
	 */
	ImportForm.prototype.run_import = function() {
		var $this = this;
		$.ajax( {
			type: 'POST',
			url: $this.popup_ajax,
			data: {
				action          : 'va_do_ajax__import',
				position        : $this.position,
				mapping         : $this.mapping,
				file            : $this.file,
				update_existing : $this.update_existing,
				delimiter       : $this.delimiter,
				security        : $this.security,
				imported        : $this.imported,
				failed        : $this.failed,
				updated        : $this.updated,
				skipped        : $this.skipped,
			},
			dataType: 'json',
			success: function( response ) {
				if ( response.success ) {
					$this.position  = response.data.position;
					$this.imported = response.data.imported;
					$this.failed   = response.data.failed;
					$this.updated  = response.data.updated;
					$this.skipped  = response.data.skipped;
					//$this.$form.find('.va-importer-progress').val( response.data.percentage );
					$this.$form.find('.va-importer-progress').css('width', response.data.percentage+'%' );
					$this.$form.find('.va-progress').html( response.data.percentage+'%' );
					$('#va_import_log').prepend(response.data.va_message+'</br>');
					
					
					$this.$form.find('#va_inserted').html(response.data.imported);
					$this.$form.find('#va_updated').html(response.data.updated);
					$this.$form.find('#va_skipped').html(response.data.skipped);
					$this.$form.find('#va_failed').html(response.data.failed);
					if ( 'done' === response.data.position ) {
						alert('done');
						//window.location = response.data.url + '&products-imported=' + parseInt( $this.imported, 10 ) + '&products-failed=' + parseInt( $this.failed, 10 ) + '&products-updated=' + parseInt( $this.updated, 10 ) + '&products-skipped=' + parseInt( $this.skipped, 10 );
					} else {
						$this.run_import();
					}
				}
			}
		} ).fail( function( response ) {
			window.console.log( response );
		} );
	};

	/**
	 * Function to call productImportForm on jQuery selector.
	 */
	$.fn.va_importer = function() {
		new ImportForm( this );
		return this;
	};

	$( '.va-importer' ).va_importer();

})( jQuery, window );