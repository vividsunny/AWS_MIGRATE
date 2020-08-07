<?php
add_action( 'wp_ajax_stock_list_import_script', 'va_stock_list_import_script' );
add_action( 'wp_ajax_nopriv_stock_list_import_script', 'va_stock_list_import_script' );
function va_stock_list_import_script() {
	$obj_class = new Wp_Stock_List();

	$startpos = $_POST['startpos'];
	$file_url = $_POST['file_url'];


	$d          = date( 'j-M-Y H:i:s' );
	$total_data = $obj_class->stock_list_count_total_file_row( $file_url );

	$row = 0;
	if ( ( $handle = fopen( $file_url, 'r' ) ) !== false ) {
		$parse_data = array();
		// $header     = fgetcsv( $handle, 0 );

		$header = array(
			'0' => 'order_id',
			'1' => 'name',
			'2' => 'status',
		);

		while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
			$num = count( $data );
			// echo "<p> $num fields in line $row: <br /></p>\n";

			foreach ( $header as $i => $key ) {
				$key                = strtolower( $key );
				$key                = str_replace( ' ', '_', $key );
				$parse_data[ $key ] = $data[ $i ];

			}
			$end_pos          = $startpos + 1;
			$total_percentage = $obj_class->stock_list_get_percent_complete( $total_data, $end_pos );

			$row++;

			if ( $total_data <= $startpos ) {
				$message = '[' . $d . '] - Done ';

				// $sql = "DELETE FROM `wptr_13_postmeta` WHERE `meta_key` LIKE 'item_in_list%'";

				wp_send_json_success(
					array(
						'pos'        => 'done',
						'file_path'  => $file_url,
						'percentage' => $obj_class->stock_list_get_percent_complete( $total_data, $end_pos ),
						'message'    => $message,
						'redirect'    => admin_url( 'admin.php?page=not_in_list' ),

					)
				);
			} elseif ( $row == $end_pos ) {

				if ( isset( $parse_data['order_id'] ) && ! empty( $parse_data['order_id'] ) ) {

					$message = '[' . $d . '] - ' . $obj_class->stock_list_import_product_update_status( $parse_data );

					wp_send_json_success(
						array(
							'pos'        => $end_pos,
							'file_path'  => $file_url,
							'percentage' => $obj_class->stock_list_get_percent_complete( $total_data, $end_pos ),
							'message'    => $message,
						)
					);

				} else {
					$message = '[' . $d . '] - NO Data Found!';

					wp_send_json_success(
						array(
							'pos'        => $end_pos,
							'file_path'  => $file_url,
							'percentage' => $obj_class->stock_list_get_percent_complete( $total_data, $end_pos ),
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


add_action( 'wp_ajax_not_in_list_import_script', 'va_not_in_list_import_script' );
add_action( 'wp_ajax_nopriv_not_in_list_import_script', 'va_not_in_list_import_script' );
function va_not_in_list_import_script() {
	$obj_class = new Wp_Stock_List();

	$startpos = $_POST['startpos'];
	$total_record = $_POST['total_record'];


	$d          = date( 'j-M-Y H:i:s' );
	$total_data = $total_record;
	$parse_data = array();

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'paged' => $startpos,
		'order'    => 'DESC',
		// 'page' => 1,
		'meta_query'     => array(
				'relation' => 'OR',
				array(
			        'key'       => 'item_in_list',
			        'value'   	=> 'in_list_file',
			        'compare'   => 'NOT EXISTS',
			    )
		),

	);

	$query = new WP_Query( $args );

	$count = $query->found_posts;

	// $exist_posts = array();
	if ( $query->have_posts() ) {
		$parse_data = $query->posts;
	}

	$end_pos          = $startpos + 1;
	$total_percentage = $obj_class->stock_list_get_percent_complete( $total_data, $end_pos );

	$row++;

	if ( $startpos > $total_data ) {
		$message = '[' . $d . '] - Done ';
		global $wpdb;


		$sql = "DELETE FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` LIKE 'item_in_list%'";
		$wpdb->query( $sql );
		
		wp_send_json_success(
			array(
				'pos'        => 'done',
				'file_path'  => $file_url,
				'percentage' => $obj_class->stock_list_get_percent_complete( $total_data, $end_pos ),
				'message'    => $message,
				'redirect'    => admin_url( 'admin.php?page=not_in_list' ),

			)
		);
	} else{

		if ( ! empty( $parse_data ) ) {

			// $message = '[' . $d . '] - Innnn --->'.$startpos;
			$message = '[' . $d . '] - ' . $obj_class->not_list_import_product_update_status( $parse_data );

			wp_send_json_success(
				array(
					'pos'        => $end_pos,
					'percentage' => $obj_class->stock_list_get_percent_complete( $total_data, $end_pos ),
					'message'    => $message,
					'total_record' => $total_data, 
				)
			);

		} else {
			$message = '[' . $d . '] - NO Data Found!';

			wp_send_json_success(
				array(
					'pos'        => $end_pos,
					'percentage' => $obj_class->stock_list_get_percent_complete( $total_data, $end_pos ),
					'message'    => $message,
					'total_record' => $total_data,
				)
			);

		}
	}
		
	wp_die();
}