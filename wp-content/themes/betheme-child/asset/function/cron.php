<?php 

	if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
	// Cron
		function popupcomicshops_get_import_secret_key(){
			$import_secret_key = 'V6lGfwnjxn';
			return $import_secret_key;
		}
	
		function popupcomicshops_cron_trigger(){		// Needs to be run once daily
			$import_secret_key = popupcomicshops_get_import_secret_key();
			exec( 'wget -q -O /dev/null "http://popupcomicshop.wpengine.com/wp-cron.php?import_key=' . $import_secret_key . '&import_id=1&action=trigger"' );	
		}
		
		function popupcomicshops_cron_processing(){		// Needs to be run every two minutes
			$import_secret_key = popupcomicshops_get_import_secret_key();
			exec( 'wget -q -O /dev/null "http://popupcomicshop.wpengine.com/wp-cron.php?import_key=' . $import_secret_key . '&import_id=1&action=processing"' );	
		}
		
		add_action( 'popupcomicshops_cron_trigger_hook', 'popupcomicshops_cron_trigger' );		
		add_action( 'popupcomicshops_cron_processing_hook', 'popupcomicshops_cron_processing' );

		if ( !wp_next_scheduled( 'popupcomicshops_cron_trigger_hook' ) ) {
			wp_schedule_event( time(), 'daily', 'popupcomicshops_cron_trigger_hook' );
		}
		if ( !wp_next_scheduled( 'popupcomicshops_cron_processing_hook' ) ) {
			wp_schedule_event( time(), 'everytwominutes', 'popupcomicshops_cron_processing_hook' );
		}