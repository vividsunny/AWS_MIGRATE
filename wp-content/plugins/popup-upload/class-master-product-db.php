<?php
/**
 * this class is handle all the database opreation for master products.
 */
class mpdb{
	
	private  $host = DB_HOST;
	private  $dbname = DB_NAME;
	private  $server = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
	private  $user = DB_USER;
	private  $pass = DB_PASSWORD;

	private $conn;
	public $product_table = 'master_products';

    public function __construct()
    {
       	$this->db_connect(); 
    }

    public function db_connect(){
    	try{
     		
     		$this->conn = new PDO( $this->server, $this->user, $this->pass );
      		
      		return $this->conn;

        }catch (PDOException $e){
            
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    public function db_close(){
    	$this->conn = null;
    }
	
	public function mp_query($param = ''){
        $fields =  isset( $param['fields'] ) ? $param['fields'] : '*' ;
        $page = isset( $param['page'] ) ? $param['page'] : 1;
        $per_page = isset( $param['per_page'] ) ? $param['per_page'] : 5;
        $order_by =  isset( $param['order_by'] ) ? $param['order_by'] : 'id' ;
        $order = isset( $param['order'] ) ? $param['order'] : 'desc' ; //ACS,desc

        $limit = $per_page;
        $starting_limit = ($page-1)*$limit;

        $final_query = "SELECT SQL_CALC_FOUND_ROWS $fields FROM $this->product_table";
        
        if( isset( $param['filter_query'] ) && is_array( $param['filter_query'] ) && count($param['filter_query']) > 0 ){
            
            $final_query .= " WHERE ";
            $filter_param = $param['filter_query'];
            
            $count = 1;
            foreach ($param['filter_query'] as $f_key => $filter) {
                $relation = ( $count == 1 ) ? "" : 'AND';
                if($f_key == 'price'){
                    $min = $filter['min'];
                    $max = $filter['max'];
                    $final_query .= " $relation price BETWEEN '$min' AND '$max'";
                }else if($f_key == 'name'){
                    $cat_name = $filter_param[$f_key]['name'];
                    $final_query .= " $relation $f_key LIKE '%$cat_name%' ";
                }else{
                    $cat_name = $filter_param[$f_key]['name'];
                    $final_query .= " $relation $f_key = '$cat_name' ";
                }
                $count++;

            }
            


        }
        $final_query .=" ORDER BY $order_by $order";
        $final_query .=" LIMIT $starting_limit, $limit";
        /*echo $final_query;
        die;*/
        $r = $this->conn->prepare($final_query);
        $r->execute();
        $products = array();
        $results['data'] = $r->fetchAll( PDO::FETCH_ASSOC );
        $statement = $this->conn->query('SELECT FOUND_ROWS()');
        $total_record = $statement->fetchColumn();
        $results['total_data'] = $total_record;
        if($fields != '*'){
            $return_fields = explode(',', $fields);
        }
        return $results;
    }

    public function mp_pagination($total_records,$per_page,$qrystr = '', $show_pagination = 'Show Pagination', $query_string_variable = 'page_id_all'){
        if ( $show_pagination <> 'Show Pagination' ) {

            return;
        } else if ( $total_records < $per_page ) {

            return;
        } else {

            $html = '';

            $dot_pre = '';

            $dot_more = '';

            $total_page = 0;

            if ( $per_page <> 0 )
                $total_page = ceil( $total_records / $per_page );

            $page_id_all = 0;

            if ( isset( $_GET[$query_string_variable] ) && $_GET[$query_string_variable] != '' ) {

                $page_id_all = $_GET[$query_string_variable];
            }

            $loop_start = $page_id_all - 2;

            $loop_end = $page_id_all + 2;

            if ( $page_id_all < 3 ) {

                $loop_start = 1;

                if ( $total_page < 5 )
                    $loop_end = $total_page;
                else
                    $loop_end = 5;
            }

            else if ( $page_id_all >= $total_page - 1 ) {

                if ( $total_page < 5 )
                    $loop_start = 1;
                else
                    $loop_start = $total_page - 4;

                $loop_end = $total_page;
            }

            $html .= "<ul class='pagination'>";

            if ( $page_id_all > 1 ) {

                $html .= "<li class='page-item'><a class='page-link' href='?$query_string_variable=" . ($page_id_all - 1) . "$qrystr' aria-label='Previous' ><span aria-hidden='true'><i class='icon-angle-left'></i> " . __( 'Previous', 'jobhunt' ) . " </span></a></li>";
            } else {

                $html .= "<li class='page-item'><a class='page-link' aria-label='Previous'><span aria-hidden='true'><i class='icon-angle-left'></i> " . __( 'Previous', 'jobhunt' ) . "</span></a></li>";
            }

            if ( $page_id_all > 3 and $total_page > 5 )
                $html .= "<li class='page-item'><a class='page-link' href='?$query_string_variable=1$qrystr'>1</a></li>";

            if ( $page_id_all > 4 and $total_page > 6 )
                $html .= "<li class='page-item'> <a class='page-link'>. . .</a> </li>";

            if ( $total_page > 1 ) {

                for ( $i = $loop_start; $i <= $loop_end; $i ++ ) {

                    if ( $i <> $page_id_all )
                        $html .= "<li class='page-item'><a class='page-link' href='?$query_string_variable=$i$qrystr'>" . $i . "</a></li>";
                    else
                        $html .= "<li class='page-item active'><a class='active page-link'>" . $i . "</a></li>";
                }
            }

            if ( $loop_end <> $total_page and $loop_end <> $total_page - 1 )
                $html .= "<li class='page-item'> <a class='page-link' >. . .</a> </li>";

            if ( $loop_end <> $total_page )
                $html .= "<li class='page-item'><a class='page-link' href='?$query_string_variable=$total_page$qrystr'>$total_page</a></li>";

            if ( $per_page > 0 and $page_id_all < $total_records / $per_page ) {

                $html .= "<li class='page-item'><a class='page-link' aria-label='Next' href='?$query_string_variable=" . ($page_id_all + 1) . "$qrystr' ><span aria-hidden='true'>" . __( 'Next', 'jobhunt' ) . " <i class='icon-angle-right'></i></span></a></li>";
            } else {

                $html .= "<li class='page-item'><a class='page-link' aria-label='Next'><span aria-hidden='true'>" . __( 'Next', 'jobhunt' ) . " <i class='icon-angle-right'></i></span></a></li>";
            }

            $html .= "</ul>";

            return $html;
        }
    }
    #rand product
    public function bs_rand_product(){
        $query = "SELECT name, wholesale_price, img_url FROM $this->product_table ORDER BY RAND() LIMIT 10";
        $s = $this->conn->prepare($query);
        $s->execute();
        $rand_product = $s->fetchAll();
        return $rand_product;
    }

    #get bsproduct by name
    public function bs_single_product($name){
        $query = "SELECT * FROM $this->product_table WHERE name LIKE '%$name%'";
       // echo "$query";
        $s = $this->conn->prepare($query);
        $s->execute();
        $product = $s->fetch();
        global $bsproduct;
        $bsproduct = $product;
        return $product;
    }

    public function total_bs_products(){
        $query = "SELECT * FROM $this->product_table";
        $s = $this->conn->prepare($query);
        $s->execute();
        $total_results = $s->rowCount();
        return $total_results;
    }

    public function bs_min_price(){
        $query = "SELECT MIN(`wholesale_price`) as min_price FROM $this->product_table";
        $s = $this->conn->prepare($query);
        $s->execute();
        $total_results = $s->fetch();
        return $total_results;
    }

    public function bs_max_price(){
        $query = "SELECT MAX(`wholesale_price`) as max_price FROM $this->product_table";
        $s = $this->conn->prepare($query);
        $s->execute();
        $total_results = $s->fetch();
        return $total_results;
    }

}
global $mpdb;
$mpdb =  new mpdb();
?>