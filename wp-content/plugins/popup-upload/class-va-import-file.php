<?php
/**
 * class for import all type of file reading that file
 */
class vividImport{
	
	function __construct(){
		
	}

	public function get_percent_complete($total_row,$end_pos) {
            //return absint( min( round( ( $end_pos / $total_row ) * 100 ), 100 ) );
            return  min( round( ( $end_pos / $total_row ) * 100 , 2 ), 100 );
    }
    /**
     * [set_custom_post This function will be used to insert post when we read the date form file]
     * @param [type] $args [ post date ]
     */
    public function set_custom_post( $args ){

    }
}

?>