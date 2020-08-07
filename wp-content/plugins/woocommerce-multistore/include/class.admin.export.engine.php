<?php

class WOO_MSTORE_EXPORT_ENGINE {
	public $errors_log = array();

	private $export_type = '';
	private $export_time_after = '';
	private $export_time_before = '';
	private $site_filter = '';
	private $order_status = array();
	private $row_format = '';
	private $export_fields = array();

	public function process() {
		ini_set( 'max_execution_time', 500 );

		//validate $settings
		$this->validate_settings();

		if ( $this->errors_log ) {
			return;
		}

		$this->output();
	}

	private function validate_settings() {
		$nonce = $_POST['woonet-orders-export-interface-nonce'];
		if ( ! wp_verify_nonce( $nonce, 'woonet-orders-export/interface-export' ) ) {
			$this->errors_log[] = "Invalid nonce";

			return;
		}

		if ( isset( $_POST['export_format'] ) && in_array( $_POST['export_format'], array( 'csv', 'xls' ) ) ) {
			$this->export_type = $_POST['export_format'];
		} else {
			$this->errors_log[] = "Invalid export format";
		}

		if ( empty( $_POST['export_time_after'] ) ) {
			$this->export_time_after = 0;
		} else {
			$this->export_time_after = strtotime( $_POST['export_time_after'] );

			if ( false === $this->export_time_after ) {
				$this->errors_log[] = "Invalid time After";
			}
		}

		if ( empty( $_POST['export_time_before'] ) ) {
			$this->export_time_before = 9999999999;
		} else {
			$this->export_time_before = strtotime( $_POST['export_time_before'] );

			if ( false === $this->export_time_before ) {
				$this->errors_log[] = "Invalid time Before";
			}
		}

		if ( isset( $_POST['site_filter'] ) && intval( $_POST['site_filter'] ) ) {
			$this->site_filter = $_POST['site_filter'];
		}

		if ( isset( $_POST['order_status'] ) && in_array( $_POST['order_status'], array_keys( wc_get_order_statuses() ) ) ) {
			$this->order_status = array( $_POST['order_status'] );
		} else {
			$this->order_status = array_keys( wc_get_order_statuses() );
		}

		if ( isset( $_POST['row_format'] ) && in_array( $_POST['row_format'], array( 'row_per_order', 'row_per_product' ) ) ) {
			$this->row_format = $_POST['row_format'];
		} else {
			$this->errors_log[] = "Invalid row export format";
		}

		$this->export_fields = empty( $_POST["export_fields"] ) ? array() : $_POST["export_fields"];

		update_site_option( 'mstore_orders_export_options', array(
			'export_type'        => $this->export_type,
			'export_time_after'  => $this->export_time_after,
			'export_time_before' => $this->export_time_before,
			'site_filter'        => $this->site_filter,
			'order_status'       => $this->order_status,
			'row_format'         => $this->row_format,
			'export_fields'      => $this->export_fields,
		) );
	}

	/**
	 * Retrieve the orders
	 *
	 */
	private function fetch_orders() {
		global $wpdb, $WOO_MSTORE;

		$sub_query = "
			SELECT %1\$d AS blog_id, ID AS order_id
			FROM %2\$s
			WHERE post_type = 'shop_order'
				AND post_status IN ('%3\$s')
				AND post_date BETWEEN '%4\$s' AND '%5\$s'
				ORDER BY ID ASC
			";

		if ( empty( $this->site_filter ) ) {
			$blog_ids = $WOO_MSTORE->functions->get_active_woocommerce_blog_ids();
		} else {
			$blog_ids = array( $this->site_filter );
		}

		$query = array();
		foreach ( $blog_ids as $blog_id ) {
			$query[] = sprintf(
				$sub_query,
				$blog_id,
				$wpdb->get_blog_prefix( $blog_id ) . 'posts',
				implode( "','", $this->order_status ),
				date( 'Y-m-d', $this->export_time_after ),
				date( 'Y-m-d', $this->export_time_before )
			);
		}

		$query = '(' . implode( ') UNION ALL (', $query ) . ')';

		$orders = $wpdb->get_results( $query, ARRAY_A );

		return $orders;
	}

	private function output() {
		$orders = $this->fetch_orders();

		// output the column headings
		$header = $this->get_header();
		$this->output_row( $header );

		$order_items_key = array_search( 'order_items', $header );

		$row = array();
		foreach ( $orders as $order_data ) {
			switch_to_blog( $order_data['blog_id'] );

			$order = wc_get_order( $order_data['order_id'] );

			$items = array();
			foreach ( $order->get_items() as $order_item ) {
				$order_item_product  = new WC_Order_Item_Product( $order_item->get_id() );
				$order_item_product  = $order_item_product->get_product();
				$order_item_coupon   = new WC_Order_Item_Coupon( $order_item->get_id() );
				$order_item_fee      = new WC_Order_Item_Fee( $order_item->get_id() );
				$order_item_shipping = new WC_Order_Item_Shipping( $order_item->get_id() );
				$order_item_tax      = new WC_Order_Item_Tax( $order_item->get_id() );

				$item = array();
				foreach ( $this->export_fields as $export_field => $export_field_column_name ) {
					list( $class_name, $field_name ) = explode( '__', $export_field );
					$get_field_value_function_name = 'get_' . $field_name;

					if ( isset( ${ $class_name } ) && method_exists( ${$class_name}, $get_field_value_function_name ) ) {
						$value = $this->maybe_jsonify( ${$class_name}->$get_field_value_function_name() );
					} elseif ( method_exists( $this, $get_field_value_function_name ) ) {
						$value = $this->maybe_jsonify( $this->$get_field_value_function_name() );
					} else {
						$value = null;
					}

					if ( 0 === strpos( $class_name, 'order_item' ) ) {
						$item[ $export_field_column_name ] = $value;

						if ( 'row_per_order' == $this->row_format ) {
							continue;
						}
					}

					$row[] = $value;
				}

				if ( 'row_per_order' == $this->row_format ) {
					$items[] = $item;

					continue;
				} else {
					if ( false !== $order_items_key ) {
						$row[ $order_items_key ] = json_encode( $item );
					}

					$this->output_row( $row );

					$row = array();
				}
			}

			if ( 'row_per_order' == $this->row_format ) {
				if ( false !== $order_items_key ) {
					$row[ $order_items_key ] = json_encode( $items );
				}

				$this->output_row( $row );

				$row = array();
			}

			restore_current_blog();
		}

		$this->save();
	}

	private function output_row( $row = null ) {
		static $spreadsheet, $current_row = 0;

		if ( empty( $spreadsheet ) ) {
			$this->register_loader();

			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		}

		if ( empty( $spreadsheet ) ) {
			return null;
		}

		if ( empty( $row ) ) {
			try {
				$this->set_html_headers();

				if ( $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter( $spreadsheet, ucfirst( $this->export_type ) ) ) {
					$writer->save( 'php://output' );
				}

				die();
			} catch ( PhpOffice\PhpSpreadsheet\Writer\Exception $exception ) {
				return null;
			}
		} else {
			try {
				$current_row++;
				$spreadsheet->getActiveSheet()->fromArray( $row, null, 'A' . $current_row );
			} catch ( Exception $exception ) {
				return null;
			}
		}

		return $current_row;
	}

	private function save() {
		$this->output_row();
	}

	private function register_loader() {
		require_once( WOO_MSTORE_PATH . 'include/dependencies/W8_Loader.php' );

		$root = WOO_MSTORE_PATH . 'include/dependencies/PhpSpreadsheet';

		if ( $loader = new W8_Loader() ) {
			$loader->register();

			$loader->addPrefix( 'Psr\SimpleCache', WOO_MSTORE_PATH . 'include/dependencies/simple-cache' );

			$loader->addPrefix( 'PhpOffice\PhpSpreadsheet', WOO_MSTORE_PATH . 'include/dependencies/PhpSpreadsheet' );

			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $root, RecursiveDirectoryIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::SELF_FIRST,
				RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
			);
			foreach ( $iterator as $path => $dir ) {
				if ( $dir->isDir() ) {
					$prefix = 'PhpOffice\PhpSpreadsheet' . str_replace( DIRECTORY_SEPARATOR, '\\', str_replace( $root, '', $path ) );
					$loader->addPrefix( $prefix, $path );
				}
			}
		}
	}

	private function set_html_headers() {
		$filename  = 'network_orders_export';
		$filename .= empty( $this->export_time_after )  ? '' : '_from_' . date( 'Ymd', $this->export_time_after );
		$filename .= empty( $this->export_time_before ) ? '' : '_to_' . date( 'Ymd', $this->export_time_before );

		if ( 'csv' == $this->export_type ) {
			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename=' . $filename . '.csv' );
		} elseif ( 'xls' == $this->export_type ) {
			header( 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
			header( 'Content-Disposition: attachment;filename="' . $filename . '.xls"' );
		}
		header( 'Content-Encoding: UTF-8' );
		header( 'Cache-Control: max-age=0' );
		header( 'Cache-Control: max-age=1' );
		header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' ); // Date in the past
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' ); // always modified
		header( 'Cache-Control: cache, must-revalidate' ); // HTTP/1.1
		header( 'Pragma: public' ); // HTTP/1.0
	}

	/*================================================================================================================*/
	/* Callback fields                                                                                                */
	/*================================================================================================================*/
	private function get_header() {
		$header = array_values( $this->export_fields );

		if ( 'row_per_order' == $this->row_format ) {
			$header = array_filter( $header, function( $header_field ) {
				return ( 0 !== strpos( $header_field, 'order_item_' ) );
			} );
		}

		return $header;
	}

	private function maybe_jsonify( $value ) {
		if ( is_array( $value ) ) {
			$value = json_encode( $value );
		}

		return $value;
	}

	private function get_site_id() {
		return get_current_blog_id();
	}
}
