<?php
add_action( 'wp_ajax_video_games_import_script', 'va_video_games_import_script' );
add_action( 'wp_ajax_nopriv_video_games_import_script', 'va_video_games_import_script' );
function va_video_games_import_script() {
	$obj_class = new Wp_Video_Games();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];


	$d          = date( 'j-M-Y H:i:s' );
	$total_data = $obj_class->video_games_count_total_file_row( $file_url );

	/* Remove header row */
	$total_data = $total_data - 1;

	$row = 0;
	if ( ( $handle = fopen( $file_url, 'r' ) ) !== false ) {
		$parse_data = array();
		$header     = fgetcsv( $handle, 0 );

		// $header = array(
		// 	'0' => 'order_id',
		// 	'1' => 'name',
		// 	'2' => 'status',
		// );

		while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
			$num = count( $data );
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach ( $header as $i => $key ) {
				$key                = strtolower( $key );
				$key                = str_replace( ' ', '_', $key );
				$parse_data[ $key ] = $data[ $i ];

			}
			$end_pos          = $startpos + 1;
			$total_percentage = $obj_class->video_games_get_percent_complete( $total_data, $end_pos );

			$row++;

			if ( $total_data <= $startpos ) {

				$message = '[' . $d . '] - Done ';

				wp_send_json_success(
					array(
						'pos'        => 'done',
						'file_path'  => $file_url,
						'percentage' => $obj_class->video_games_get_percent_complete( $total_data, $end_pos ),
						'message'    => $message,

					)
				);
			} elseif ( $row == $end_pos ) {

				if ( isset( $parse_data['product-name'] ) && ! empty( $parse_data['product-name'] ) ) {

					$message = '[' . $d . '] - '.$obj_class->import_video_games( $parse_data );
					// $message = '[' . $d . '] - - '.$parse_data['product-name'] ;

					wp_send_json_success(
						array(
							'pos'        => $end_pos,
							'file_path'  => $file_url,
							'percentage' => $obj_class->video_games_get_percent_complete( $total_data, $end_pos ),
							'message'    => $message,
						)
					);

				} else {
					$message = '[' . $d . '] - NO Data Found!';

					wp_send_json_success(
						array(
							'pos'        => $end_pos,
							'file_path'  => $file_url,
							'percentage' => $obj_class->video_games_get_percent_complete( $total_data, $end_pos ),
							'message'    => $message,
						)
					);

				}
			}
		}
		fclose( $handle );
	}
	wp_die();
}
