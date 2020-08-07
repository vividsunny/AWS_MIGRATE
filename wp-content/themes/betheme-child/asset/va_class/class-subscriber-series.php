<?php

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Subscriber_series_List_Table extends WP_List_Table {
	
	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Customer', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Customers', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

	}

	/**
	 * Retrieve customer’s data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$sql = "SELECT * FROM series_subscription";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";

		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		$i = 0;
		foreach ($result as $key => $value) {
			# code...
			//vivid($value['code']);
			$code		 = $value['code'];
			$active		 = $value['active'];
			$description = $value['description'];
			$action		 = sprintf('<a href="javascript:void(0);" data-id="%s" class="js-open-modal view_product" data-modal-id="tamberra_popup">View Products</a>',$code);

			if($active == trim('Y')){
				$active = 'Active';
			}else{
				$active = 'Inactive';	
			}

			$arr[$i]['code'] = $code;
			$arr[$i]['active'] = $active;
			$arr[$i]['description'] = $description;
			$arr[$i]['action'] = $action;
			$i++;
		}

		//vivid($arr);
		return $arr;
	}

	public static function fetch_table_data() {
		global $wpdb;
		$wpdb_table = 'series_subscription';		
		$orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'code';
		$order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';
		$user_query = "SELECT * FROM $wpdb_table ORDER BY $orderby $order";

      	#query output_type will be an associative array with ARRAY_A.
		$query_results = $wpdb->get_results( $user_query, ARRAY_A  );

		$i = 0;
		foreach ($query_results as $key => $value) {
			#code 
			$code		 = $value['code'];
			$active		 = $value['active'];
			$description = $value['description'];
			$action		 = sprintf('<a href="javascript:void(0);" data-id="%s" class="js-open-modal view_product" data-modal-id="tamberra_popup">View Products</a>',$code);

			if($active == trim('Y')){
				$active = 'Active';
			}else{
				$active = 'Inactive';	
			}

			$arr[$i]['code'] = $code;
			$arr[$i]['active'] = $active;
			$arr[$i]['description'] = $description;
			$arr[$i]['action'] = $action;
			$i++;
		}

      // return result array to prepare_items.
      return $arr;		
    }	


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"series_subscription",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM series_subscription";

		return $wpdb->get_var( $sql );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No customers avaliable.', 'sp' );
	}

	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_name( $item ) {

	  // create a nonce
	  $delete_nonce = wp_create_nonce( 'sp_delete_customer' );

	  $title = '<strong>' . $item['name'] . '</strong>';

	  $actions = [
	    'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
	  ];

	  return $title . $this->row_actions( $actions );
	}


	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'code':
			case 'active':
			case 'description':
			case 'action':
			return $item[ $column_name ];
			default:
			return print_r( $item, true ); 
		}
	}


	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			//'cb'      => '<input type="checkbox" />',
			'code'    => __( 'Series Code', 'sp' ),
			'active'    => __( 'Status', 'sp' ),
			'description' => __( 'Description', 'sp' ),
			'action'    => __( 'Action', 'sp' )
		];

		return $columns;
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'code' => array( 'code', true ),
			'description' => array( 'description', true )
		);

		return $sortable_columns;
	}


	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	/*public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}*/


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		// check if a search was performed.
		$_search_key = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = 20;//$this->get_items_per_page( 'customers_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, 
			'per_page'    => $per_page 
		] );


		$this->items = self::get_customers( $per_page, $current_page );
		$table_data = self::fetch_table_data();


		if( $_search_key ) {
			$this->items = $this->filter_table_data($table_data, $_search_key );
		}

	}

	// filter the table data based on the search key
	public function filter_table_data( $table_data, $search_key ) {

		$filtered_table_data = array_values( array_filter( $table_data, function( $row ) use( $search_key ) {
			foreach( $row as $row_val ) {
				if( stripos( $row_val, $search_key ) !== false ) {
					return true;
				}				
			}			
		} ) );

		return $filtered_table_data;

	}

	public function process_bulk_action() {

	  //Detect when a bulk action is being triggered...
	  if ( 'delete' === $this->current_action() ) {

	    // In our file that handles the request, verify the nonce.
	    $nonce = esc_attr( $_REQUEST['_wpnonce'] );

	    if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
	      die( 'Go get a life script kiddies' );
	    }
	    else {
	      self::delete_customer( absint( $_GET['customer'] ) );

	      wp_redirect( esc_url( add_query_arg() ) );
	      exit;
	    }

	  }

	  // If the delete bulk action is triggered
	  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
	       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
	  ) {

	    $delete_ids = esc_sql( $_POST['bulk-delete'] );

	    // loop over the array of record IDs and delete them
	    foreach ( $delete_ids as $id ) {
	      self::delete_customer( $id );

	    }

	    wp_redirect( esc_url( add_query_arg() ) );
	    exit;
	  }
	}

}