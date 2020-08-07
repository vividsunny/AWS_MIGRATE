<?php

if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Subscriber_List_Table extends WP_List_Table {

		/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'subscriber', 'sp' ), //singular name of the listed records
			'plural'   => __( 'subscribers', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );

		//add_action( 'admin_init', array( $this, 'remove_wp_http_referer' ) );
	}

	public function remove_wp_http_referer() {

	    // If we're on an admin page with the referer passed in the QS, prevent it nesting and becoming too long.
	    global $pagenow;

	        if( 'admin.php' === $pagenow && isset( $_GET['_wp_http_referer'] ) && preg_match( '/_wp_http_referer/', $_GET['_wp_http_referer'] ) ) :
	            wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
	            exit;
	        endif;

	}

	/**
	 * Retrieve Subscribers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_subscribers( $per_page = 5, $page_number = 1 ) {

		global $wpdb;

		$blog_id = get_current_blog_id();
		$sql = "SELECT * FROM subscribers_data WHERE `blog_id` = $blog_id";

		

		$product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : 'series_id';
		
		if ( ! empty( $_GET['product_name'] ) ) {

			$myposts   = get_post( $product_name );
			$product_id = $myposts->ID;

			$series_code = get_post_meta( $product_id, '_sku', true );
			$sql .= " AND `series_id` = $series_code";
		}

		$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : 'user_id';
		if ( ! empty( $_GET['user_id'] ) ) {

			$sql .= " AND `user_id` = $user_id";
		}

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$sql .= " LIMIT $per_page";

		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		$i = 0;
		foreach ($result as $key => $value) {
			#code 
			$series_id = $value['series_id'];
			$email	   = $value['email'];
			$time	   = $value['create_date'];
			$status	   = $value['status'];
			$action		 = sprintf('<a href="javascript:void(0);" data-id="%s" class="js-open-modal view_child" data-modal-id="tamberra_popup">View</a>',$series_id);

			if( $status == trim('active')){
				$time	   = $value['create_date'];
			}else{
				$time	   = $value['delete_data'];
			}

			$user_id  = $value['user_id'];
			$author_obj = get_user_by( 'id', $user_id );
			$username  	= $author_obj->user_login;
			$email  	= $author_obj->user_email;

			$arr[$i]['series_id'] = $series_id;
			$arr[$i]['username'] = $username;
			$arr[$i]['email'] = $email;
			$arr[$i]['time'] = $time;
			$arr[$i]['status'] = ucfirst($status);
			$arr[$i]['add_remove'] = $action;
			$i++;
		}

		//vivid($arr);
		return $arr;
	}

	public static function fetch_table_data() {
		global $wpdb;

		$blog_id = get_current_blog_id();
		$wpdb_table = 'subscribers_data';		
		$orderby = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'series_id';
		$order = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'ASC';
		$user_query = "SELECT * FROM $wpdb_table WHERE `blog_id` = $blog_id ORDER BY $orderby $order";

		$product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : 'series_id';
		

		if( $product_name ){

			$myposts   = get_post( $product_name );
			$product_id = $myposts->ID;
			$series_code = get_post_meta( $product_id, '_sku', true );

			//$user_query = "SELECT * FROM $wpdb_table WHERE `blog_id` = $blog_id AND `series_id` = $series_code ORDER BY $orderby $order";

			$user_query .= " AND `series_id` = $series_code";
		}
		
		$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : 'user_id';
		if ( ! empty( $_GET['user_id'] ) ) {

			$user_query .= " AND `user_id` = $user_id";
		}

		#query output_type will be an associative array with ARRAY_A.
		$query_results = $wpdb->get_results( $user_query, ARRAY_A  );

		$i = 0;
		foreach ($query_results as $key => $value) {
			#code 
			$series_id = $value['series_id'];
			$email	   = $value['email'];
			
			$status	   = $value['status'];
			$action		 = sprintf('<a href="javascript:void(0);" data-id="%s" class="js-open-modal view_child" data-modal-id="tamberra_popup">View</a>',$series_id);

			if( $status == trim('active')){
				$time	   = $value['create_date'];
			}else{
				$time	   = $value['delete_data'];
			}
			
			$user_id  = $value['user_id'];
			$author_obj = get_user_by( 'id', $user_id );
			$username  	= $author_obj->user_login;
			$email  	= $author_obj->user_email;
			

			$arr[$i]['series_id'] = $series_id;
			$arr[$i]['username'] = $username;
			$arr[$i]['email'] = $email;
			$arr[$i]['time'] = $time;
			$arr[$i]['status'] = ucfirst($status);
			$arr[$i]['add_remove'] = $action;
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
		$blog_id = get_current_blog_id();
		$sql = "SELECT COUNT(*) FROM subscribers_data WHERE `blog_id` = $blog_id";

		$product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : 'series_id';
		if ( ! empty( $_GET['product_name'] ) ) {

			$myposts   = get_post( $product_name );
			$product_id = $myposts->ID;

			$series_code = get_post_meta( $product_id, '_sku', true );
			$sql .= " AND `series_id` = $series_code";
		}

		$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : 'user_id';
		if ( ! empty( $_GET['user_id'] ) ) {

			$sql .= " AND `user_id` = $user_id";
		}


		return $wpdb->get_var( $sql );
	}

	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No subscriber avaliable.', 'sp' );
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
			case 'series_id':
			case 'username':
			case 'email':
			case 'time':
			case 'status':
			case 'add_remove':
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
			'series_id'    => __( 'Series ID', 'sp' ),
			'username'    => __( 'Username', 'sp' ),
			'email' => __( 'User Email', 'sp' ),
			'time'    => __( 'Date', 'sp' ),
			'status'    => __( 'Status', 'sp' ),
			'add_remove'    => __( 'Action', 'sp' )
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
			'series_id' => array( 'series_id', true ),
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

		$per_page     = 10;//$this->get_items_per_page( 'customers_per_page', 5 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, 
			'per_page'    => $per_page 
		] );


		$this->items = self::get_subscribers( $per_page, $current_page );
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

	public function extra_tablenav($which){
	//debug($which);
			if ( $which == 'top' ) {

				$m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0;
				$product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : '';
				$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : '';
				$args_url = '';

				if ( $m ) {
					$args_url .= '&m=' . $m;
				}
				if ( $product_name ) {
					$args_url .= '&product_name=' . $product_name;
				}
				if ( $user_id ) {
					$args_url .= '&user_id=' . $user_id;
				}
				echo '<div class="alignleft actions" style="margin-bottom:15px;">';

				echo '<a class="button download_csv" href="' . wp_nonce_url( admin_url( 'admin.php?action=download_csv' . $args_url ), 'download_csv', '_wpnonce' ) . '">' . __( 'Export to CSV', 'sp' ) . '</a>';
				// Months drop down
				$this->months_dropdown( 'month' );

				$this->product_title_dropdown( 'product' );

				$this->all_user_dropdown( 'user' );

				submit_button(
					__( 'Filter', 'sp' ), false, false, false, array(
						'id' => 'post-query-submit',
						'name' => 'do-filter',
					)
				);
				//submit_button( __( 'Clear', 'sp' ), 'secondary', 'reset', false, array( 'type' => 'reset' ) );
				
				echo '<a class="button reset_form" href="' . wp_nonce_url( admin_url( 'admin.php?page=subscription_subscribers' ), 'reset_form', '_wpnonce' ) . '">' . __( 'Reset', 'sp' ) . '</a>';

				

				echo '</div>';
			}
		}

	public function product_title_dropdown( $post_type ) {

		
		$user_args = array( 'fields' => array( 'ID', 'display_name' ) );
		$product_name = isset( $_GET['product_name'] ) ? $_GET['product_name'] : '';

		$reset = ! empty( $_REQUEST['reset'] ) ? esc_attr( $_REQUEST['reset'] ) : '';

		if( isset( $_REQUEST['reset'] ) ){
			$product_name = '';
		}

		// Generate the drop down
		$placeholder ='Filter by Title';
		$output = '<select style="width:250px;" name="product_name" id="product_name" class="wc-enhanced-select" data-placeholder="'.$placeholder.'" >';
		$output .= '<option></option>';
		$output .= $this->va_product_drop_down_options( $product_name );
		$output .= '</select>';
		$output .= '<script type="text/javascript">jQuery(function() { jQuery("#product_name").select2(); } );</script>';

		echo $output;

	} // vendor_dropdown()


	public function va_product_drop_down_options( $product_name ){

		
		$output = '';
		$status = array('publish', 'draft');
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => $status,
		);
		$query = new WP_Query( $args );
		$all_post = $query->posts;

		foreach ($all_post as $value) {
			# code...
			$id = $value->ID;
			$name = $value->post_title;
			$select = selected( $id, $product_name, false );
			$output .= "<option value='".$id."' $select>".$name."</option>";
		}

		return $output;
	}

	public function all_user_dropdown( $post_type ) {

		
		$user_args = array( 'fields' => array( 'ID', 'display_name' ) );
		$user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : '';

		$reset = ! empty( $_REQUEST['reset'] ) ? esc_attr( $_REQUEST['reset'] ) : '';

		if( isset( $_REQUEST['reset'] ) ){
			$user_id = '';
		}

		// Generate the drop down
		$placeholder ='Filter by customer';
		$output = '<select style="width:250px;" name="user_id" id="user_id" class="wc-enhanced-select" data-placeholder="'.$placeholder.'" >';
		$output .= '<option></option>';
		$output .= $this->va_user_drop_down_options( $user_id );
		$output .= '</select>';
		$output .= '<script type="text/javascript">jQuery(function() { jQuery("#user_id").select2(); } );</script>';

		echo $output;

	}

	public function va_user_drop_down_options( $user_id ){
		
		$output = '';
		$user_args = array( 'fields' => array( 'ID', 'display_name' ) );

		$users = get_users( $user_args );

		foreach ($users as $value) {
			# code...
			$id = $value->ID;
			$name = $value->display_name;
			$select = selected( $id, $user_id, false );
			$output .= "<option value='".$id."' $select>".$name."</option>";
		}

		return $output;
	}

	public function months_dropdown( $post_type ) {

		global $wpdb, $wp_locale;

		$table_name = 'subscribers_data';

		$months = $wpdb->get_results(
			"
			SELECT DISTINCT YEAR( create_date ) AS year, MONTH( create_date ) AS month
			FROM $table_name
			ORDER BY create_date DESC
		"
		);

		$month_count = count( $months );

		if ( ! $month_count || ( 1 == $month_count && 0 == $months[0]->month ) ) {
			return;
		}

		$m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0;
		?>
		<select name="m" id="filter-by-date" class="wc-enhanced-select-nostd" style="min-width:150px;">
			<option<?php selected( $m, 0 ); ?> value='0'><?php _e( 'Show all dates', 'wc-vendors' ); ?></option>
			<?php
			foreach ( $months as $arc_row ) {
				if ( 0 == $arc_row->year ) {
					continue;
				}

				$month = zeroise( $arc_row->month, 2 );
				$year  = $arc_row->year;

				printf(
					"<option %s value='%s'>%s</option>\n",
					selected( $m, $year . $month, false ),
					esc_attr( $arc_row->year . $month ),
					/* translators: 1: month name, 2: 4-digit year */
					sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
				);
			}
			?>
		</select>

		<?php
	}

}
