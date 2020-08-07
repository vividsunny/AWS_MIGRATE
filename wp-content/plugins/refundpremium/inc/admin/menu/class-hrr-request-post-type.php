<?php
/**
 * Admin Refund Request Custom Post Type.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'HRR_Request_Post_Type' ) ) {

	/**
	 * HRR_Request_Post_Type Class.
	 */
	class HRR_Request_Post_Type {

		/**
		 * Object.
		 */
		private static $object ;

		/**
		 * Post type.
		 */
		private static $post_type = HRR_Register_Post_Type::REQUEST_POSTTYPE ;

		/**
		 * HRR_Request_Post_Type Class initialization.
		 */
		public static function init() {
						//body class 
						add_filter( 'admin_body_class' , array( __CLASS__ , 'custom_body_class' ) , 10 , 1 ) ;
			//Remove View Action.
			add_action( 'views_edit-' . self::$post_type , array( __CLASS__ , 'remove_views' ) ) ;
			//Add Date Filter field.
			add_action( 'restrict_manage_posts' , array( __CLASS__ , 'date_filter' ) ) ;
			//Add Custom Date filter Query.
			add_action( 'posts_where' , array( __CLASS__ , 'date_filter_query' ) , 10 , 2 ) ;
			//Query for OrderBy Column.
			add_action( 'posts_orderby' , array( __CLASS__ , 'orderby_filter_query' ) , 10 , 2 ) ;
			//Render Data for All Columns.
			add_action( 'manage_' . self::$post_type . '_posts_custom_column' , array( __CLASS__ , 'render_column' ) , 10 , 2 ) ;
			//Add Search Action Query.
			add_filter( 'posts_search' , array( __CLASS__ , 'search_action' ) ) ;
			//Add Search Action Query.
			add_filter( 'parse_query' , array( __CLASS__ , 'orderby_filter' ) ) ;
			//Handle Custom Row Action.
			add_filter( 'post_row_actions' , array( __CLASS__ , 'handle_post_row_actions' ) , 10 , 2 ) ;
			//Remove Month filter dropdown.
			add_filter( 'disable_months_dropdown' , array( __CLASS__ , 'remove_month_dropdown' ) , 10 , 2 ) ;
			//Define Custom Column to be displayed.
			add_filter( 'manage_' . self::$post_type . '_posts_columns' , array( __CLASS__ , 'define_columns' ) ) ;
			//Handle Custom options for Bulk action.
			add_filter( 'bulk_actions-edit-' . self::$post_type , array( __CLASS__ , 'handle_bulk_actions' ) , 10 , 1 ) ;
			//Define Sortable Column.
			add_filter( 'manage_edit-' . self::$post_type . '_sortable_columns' , array( __CLASS__ , 'sortable_columns' ) ) ;
		}
				
				/**
				* Add custom class in body.
				*/

		public static function custom_body_class( $class ) {
			global $post ;

			if ( ! is_object( $post ) ) {
				return $class ;
			}

			if ( $post->post_type == self::$post_type ) {
				return $class . ' hrr-body-content' ;
			}

			return $class ;
		}

		/**
		 * Define custom columns.
		 */
		public static function define_columns( $columns ) {
			return array(
				'cb'              => $columns[ 'cb' ] ,
				'hrr_id'          => esc_html__( 'ID' , 'refund' ) ,
				'hrr_user_name'   => esc_html__( 'User Name/Email' , 'refund' ) ,
				'hrr_order_id'    => esc_html__( 'Order ID' , 'refund' ) ,
				'hrr_refund_mode' => esc_html__( 'Refund Request as' , 'refund' ) ,
				'hrr_status'      => esc_html__( 'Refund Request Status' , 'refund' ) ,
				'hrr_type'        => esc_html__( 'Refund Type' , 'refund' ) ,
				'hrr_reason'      => esc_html__( 'Refund Reason' , 'refund' ) ,
					) ;
		}

		/**
		 * Sortable Columns.
		 */
		public static function sortable_columns( $columns ) {
			$array = array(
				'hrr_id'           => 'hrr_id' ,
				'hrr_user_name'    => 'hrr_user_name' ,
				'hrr_order_id'     => 'hrr_order_id' ,
				'hrr_request_date' => 'hrr_request_date' ,
					) ;

			return wp_parse_args( $array , $columns ) ;
		}

		/**
		 * Modify a row post actions.
		 */
		public static function handle_post_row_actions( $actions, $post ) {
			if ( $post->post_type == self::$post_type ) {
				unset( $actions[ 'inline hide-if-no-js' ] ) ;
				$url = add_query_arg( array( 'post' => $post->ID , 'action' => 'edit' ) , admin_url( 'post.php' ) ) ;

				$actions[ 'edit' ] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'View Request' , 'refund' ) . '</a>' ;
			}
			return $actions ;
		}

		/**
		 * Modify Bulk post actions.
		 */
		public static function handle_bulk_actions( $actions ) {
			global $post ;
			if ( isset( $post->post_type ) && ( $post->post_type == self::$post_type ) ) {
				unset( $actions[ 'edit' ] ) ;
			}

			return $actions ;
		}

		/*
		 * Remove Custom Post Type Views.
		 */

		public static function remove_views( $views ) {
			unset( $views[ 'mine' ] ) ;
			return $views ;
		}

		/**
		 * Remove month dropdown .
		 */
		public static function remove_month_dropdown( $bool, $post_type ) {
			return $post_type == self::$post_type ? true : $bool ;
		}

		/**
		 * Add Date filter.
		 */
		public static function date_filter( $post_type ) {
			if ( 'hrr_request' != $post_type ) {
				return ;
			}

			//Display date filter for Recovered Order table.
			$fromdate = '' ;
			$todate   = '' ;
			if ( isset( $_REQUEST[ 'filter_action' ] ) ) {
				$fromdate = isset( $_REQUEST[ 'hrr_request_fromdate' ] ) ? wc_clean( $_REQUEST[ 'hrr_request_fromdate' ] ) : '' ;
				$todate   = isset( $_REQUEST[ 'hrr_request_todate' ] ) ? wc_clean( $_REQUEST[ 'hrr_request_todate' ] ) : '' ;
			}
			?>
			<input id='hrr_request_fromdate' type='text' name='hrr_request_fromdate' value="<?php echo esc_attr( $fromdate ) ; ?>" placeholder="<?php esc_attr_e( 'From Date' , 'refund' ) ; ?>"/>
			<input id='hrr_request_todate' type='text' name='hrr_request_todate' value="<?php echo esc_attr( $todate ) ; ?>" placeholder="<?php esc_attr_e( 'To Date' , 'refund' ) ; ?>"/>
			<?php
		}

		/**
		 * Prepare Request Row Data.
		 */
		public static function prepare_row_data( $postid ) {

			if ( empty( self::$object ) || self::$object->get_id() != $postid ) {
				self::$object = hrr_get_request( $postid ) ;
			}

			return self::$object ;
		}

		/**
		 * Display each column data in Refund Request table.
		 */
		public static function render_column( $column, $postid ) {
			self::prepare_row_data( $postid ) ;
			$function = 'render_' . $column . '_column' ;

			if ( method_exists( __CLASS__ , $function ) ) {
				self::$function() ;
			}
		}

		/**
		 * Render Id column.
		 */
		public static function render_hrr_id_column() {
			echo '<a href=' . esc_url( admin_url( 'post.php?post=' . self::$object->get_id() . '&action=edit' ) ) . '>#' . esc_html( self::$object->get_id() ) . '</a>' ;
		}

		/**
		 * Render User Name column.
		 */
		public static function render_hrr_user_name_column() {
			echo self::$object->get_user()->display_name ;
		}

		/**
		 * Render Order Id column.
		 */
		public static function render_hrr_order_id_column() {
			echo '<a href=' . esc_url( admin_url( 'post.php?post=' . self::$object->get_order_id() . '&action=edit' ) ) . '>#' . esc_html( self::$object->get_order_id() ) . '</a>' ;
		}

		/**
		 * Render Request As column.
		 */
		public static function render_hrr_refund_mode_column() {
			echo self::$object->get_mode() ;
		}

		/**
		 * Render Status column.
		 */
		public static function render_hrr_status_column() {
			echo hrr_display_status( self::$object->get_status() ) ;
		}

		/**
		 * Render Type column.
		 */
		public static function render_hrr_type_column() {
			echo self::$object->get_type() ;
		}

		/**
		 * Render Reason column.
		 */
		public static function render_hrr_reason_column() {
			$message = self::$object->get_reason() ;
			if ( strlen( $message ) > 80 ) {
				echo substr( $message , 0 , 80 ) ;
				echo '.....' ;
			} else {
				echo $message ;
			}
		}

		/**
		 * Searching Functionality.
		 */
		public static function search_action( $where ) {
			global $pagenow , $wpdb , $wp ;

			if ( 'edit.php' != $pagenow || ! is_search() || ! isset( $wp->query_vars[ 's' ] ) || self::$post_type != $wp->query_vars[ 'post_type' ] ) {
				return $where ;
			}

			$search_ids = array() ;
			$terms      = explode( ',' , $wp->query_vars[ 's' ] ) ;

			foreach ( $terms as $term ) {
				$term          = $wpdb->esc_like( wc_clean( $term ) ) ;
				$meta_array    = array(
					'hrr_order_id' ,
					'hrr_refund_mode' ,
					'hrr_type' ,
					'hrr_user_name' ,
					'hrr_user_email'
						) ;
				$implode_array = implode( "','" , $meta_array ) ;
				if ( isset( $_GET[ 'post_status' ] ) && 'all' != wc_clean($_GET[ 'post_status' ]) ) {
					$post_status = wc_clean($_GET[ 'post_status' ]) ;
				} else {
					$post_status_array = array(
						'hrr-new' ,
						'hrr-accept' ,
						'hrr-reject' ,
						'hrr-processing' ,
						'hrr-on-hold'
							) ;
					$post_status       = implode( "','" , $post_status_array ) ;
				}

				$search_ids = $wpdb->get_col( $wpdb->prepare(
								'SELECT DISTINCT ID FROM '
								. "{$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as pm "
								. 'ON p.ID = pm.post_id '
								. 'WHERE (p.post_status IN ( %s )) AND (p.ID LIKE %s '
								. 'OR p.post_title LIKE %s '
								. 'OR p.post_content LIKE %s '
								. 'OR (pm.meta_key IN ( %s ) '
								. 'AND pm.meta_value LIKE %s))' , esc_html($post_status) , '%' . $term . '%' , '%' . $term . '%' , '%' . $term . '%' , esc_html($implode_array) , '%' . $term . '%' ) ) ;
			}
			$search_ids = array_filter( array_unique( array_map( 'absint' , $search_ids ) ) ) ;
			if ( count( $search_ids ) > 0 ) {
				$where      = str_replace( 'AND (((' , "AND ( ({$wpdb->posts}.ID IN (" . implode( ',' , $search_ids ) . ')) OR ((' , $where ) ;
			}

			return $where ;
		}

		/**
		 * Filter Functionality.
		 */
		public static function orderby_filter( $query ) {
			global $typenow ;
			if ( isset( $query->query[ 'post_type' ] ) && $query->query[ 'post_type' ] == self::$post_type ) {
				if ( ( self::$post_type == $typenow ) && isset( $_GET[ 'orderby' ] ) && ( 'hrr_id' != wc_clean( $_GET[ 'orderby' ]) ) ) {
					$query->query_vars[ 'meta_key' ] = wc_clean($_GET[ 'orderby' ]) ;
				}
			}
		}

		/**
		 * Date Filter Functionality.
		 */
		public static function date_filter_query( $where, $wp_query ) {
			if ( isset( $wp_query->query[ 'post_type' ] ) && $wp_query->query[ 'post_type' ] != self::$post_type ) {
				return $where ;
			}

			if ( isset( $_REQUEST[ 'filter_action' ] ) && isset( $_REQUEST[ 'post_type' ] ) && $_REQUEST[ 'post_type' ] == self::$post_type ) {
				global $wpdb ;
				$fromdate = isset( $_REQUEST[ 'hrr_request_fromdate' ] ) ? wc_clean($_REQUEST[ 'hrr_request_fromdate' ]) : null ;
				$todate   = isset( $_REQUEST[ 'hrr_request_todate' ] ) ? wc_clean($_REQUEST[ 'hrr_request_todate' ]) : null ;

				if ( $fromdate ) {
					$from_strtotime = strtotime( $fromdate ) ;
					$fromdate       = date( 'Y-m-d' , $from_strtotime ) . ' 00:00:00' ;
					$where          .= " AND $wpdb->posts.post_date >= '$fromdate'" ;
				}
				if ( $todate ) {
					$to_strtotime = strtotime( $todate ) ;
					$todate       = date( 'Y-m-d' , $to_strtotime ) . ' 23:59:59' ;
					$where        .= " AND $wpdb->posts.post_date <= '$todate'" ;
				}
			}
			return $where ;
		}

		/**
		 * Order By Functionality.
		 */
		public static function orderby_filter_query( $order_by, $wp_query ) {
			if ( isset( $wp_query->query[ 'post_type' ] ) && $wp_query->query[ 'post_type' ] != self::$post_type ) {
				return $order_by ;
			}

			if ( isset( $_REQUEST[ 'post_type' ] ) && $_REQUEST[ 'post_type' ] == self::$post_type ) {
				global $wpdb ;
				if ( ! isset( $_REQUEST[ 'order' ] ) && ! isset( $_REQUEST[ 'orderby' ] ) ) {
					$order    = get_user_option( 'hrr_request_asc_desc' ) ;
					if ( $order ) {
						$order_by = "{$wpdb->posts}.ID " . $order ;
					}
				} else {
					$decimal_column = array(
						'order_id' ,
							) ;
					if ( in_array( $_REQUEST[ 'orderby' ] , $decimal_column ) ) {
						$order_by = "CAST({$wpdb->postmeta}.meta_value AS DECIMAL) " . wc_clean($_REQUEST[ 'order' ]) ;
					}
				}
			}
			return $order_by ;
		}

	}

	HRR_Request_Post_Type::init() ;
}
