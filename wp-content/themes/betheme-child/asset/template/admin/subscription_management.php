<?php 

?>

	<h1>Subscription Management</h1>
	
	<h2>Series</h2>
	
	<?php 
		global $wpdb;
		$sql = "SELECT DISTINCT(meta_value) 
			FROM wptr_postmeta 
			WHERE meta_key = 'series_code';";	// Get series list from main website, not from subsite
		$series = $wpdb->get_col( $sql );
		
		foreach ( $series as $key => $value ){
			//var_dump( $series );
			echo $series[ $key ] . '<br />';
		}