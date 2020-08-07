<?php
/*
Template name: Product Template
*/
get_header();
?>


<?php 

	global $wpdb;

    $sql = "SELECT * FROM `{$wpdb->prefix}posts` WHERE `post_date_gmt` >= date_sub(now(), interval 6 month) LIMIT 10";

    $result = $wpdb->get_results(  $sql, 'ARRAY_A' );

    vivid( count( $result ) );
    // vivid( $result );

    foreach ($result as $key => $value) {

    	$prod_id  = $value['ID'];

    	vivid( $value );
    	vivid( $value['ID'] );
    	
    }
?>
<?php
	get_footer();
?>