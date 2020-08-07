<?php 

	if ( ! defined( "ABSPATH" ) ) exit; // Exit if accessed directly

	add_action( 'admin_post_popupcomics_report.csv', 'popupcomics_report_csv' );

	function popupcomics_report_csv(){
		if ( ! current_user_can( 'manage_woocommerce' ) )
			return;
		
		try {
			$data = array();
			popupcomics_export_zip( $data );
		} catch ( Exception $e ) {
			error_log( "Error in Bulk Export " . $e->getMessage() );
		} 		
	}

	function popupcomics_export_zip( $data ){	
		# create zip file
		$f = 'reports_'.time().'.zip';
		$upload_dir = wp_upload_dir();
		$dir = $upload_dir['path'];
		$filename = $dir.'/'.$f;
		
		if ($upload_dir['error']) { 
			error_log( "Error: " . $upload_dir['error'] );
			exit(0);
		}
		if (! is_dir($dir)) {	
			error_log( "Upload directory: " . $dir );
			error_log( "ERROR: Directory does not exist." );
			exit(0);
		}
		if (! is_writable($dir)) {
			error_log( "Upload directory: " . $dir );
			error_log( "ERROR: Directory is not writable." );
			exit(0);        
		}

		$url = $upload_dir['url'] . '/'. $f;
		$zip = new ZipArchive;
		$res = $zip->open( $filename, ZipArchive::CREATE ) or die( "Could not create file." );
	
		if ( $res == TRUE ) {

			$i = 0; // unique counter for filename if no slug specified
			
				$zip_name = "report.csv";
				
				try {						
					$data = 'test data';	
				} catch ( Exception $e ) {
					error_log( "HTML Decode Error: " . $e->getMessage() );
				}
					
				try {						
					$zip->addFromString( $zip_name, $data );
				} catch ( Exception $e ) {
					error_log( "Add to Zip Error: " . $e->getMessage() );
				}

				$i++;
				$id = get_the_ID();
			
			//wp_reset_postdata(); // Restore original Post Data 
			$zip->close();  

			// Force download zip file
			header( "Content-Type: application/zip" );
			header( "Content-Disposition: attachment; filename=".$f );
			header( "Content-Length: " . filesize( $url ) );
			// add these two lines
			ob_clean();   // added this to remove extra byte pdf corruption
			flush();      // added this to remove extra byte pdf corruption
			readfile( $url );
			exit;
		} else {
			error_log( "Could not create zip file for export." );
		}
	}