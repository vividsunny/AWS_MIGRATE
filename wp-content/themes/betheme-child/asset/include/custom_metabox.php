<?php
/* Add Metabox Function */
add_action('add_meta_boxes', 'add_custom_meta_boxes');
function add_custom_meta_boxes(){
	add_meta_box(
		'series_settings',
		'Series Setting',
		'series_settings',
		'comics_subscription'
	);
}


function series_settings($post){
	$post_id = $post->ID;
    $series_data = get_post_meta($post_id,'subscription_series_data',true);
    ?>
    <div id="general_serires_data" class="panel woocommerce_options_panel1" style="display: block;">
    	<p class="form-field coupon_amount_field">
    		<label>Series Code</label>
    		<input type="text" name="_series_code" class="form-control" value="<?php echo $series_data['_series_code']; ?>" >
    	</p>

    	<p class="form-field coupon_amount_field">
    		<label>Series Status</label>
    		<input type="text" name="_series_active" class="form-control" value="<?php echo $series_data['_series_active']; ?>" >
    	</p>

    	<p class="form-field coupon_amount_field">
    		<label>Series Publisher</label>
    		<input type="text" name="_series_publisher" class="form-control" value="<?php echo $series_data['_series_publisher']; ?>" >
    	</p>

    	<p class="form-field coupon_amount_field">
    		<label>Series Numissues</label>
    		<input type="text" name="_series_numissues" class="form-control" value="<?php echo $series_data['_series_numissues']; ?>" >
    	</p>

    	<p class="form-field coupon_amount_field">
    		<label>Series Frequencycode</label>
    		<input type="text" name="_series_frequencycode" class="form-control" value="<?php echo $series_data['_series_frequencycode']; ?>" >
    	</p>

    	<p class="form-field coupon_amount_field">
    		<label>Series Override</label>
    		<input type="text" name="_series_override" class="form-control" value="<?php echo $series_data['_series_override']; ?>" >
    	</p>

    	<p class="form-field coupon_amount_field">
    		<label>Series Notes</label>
    		<input type="text" name="_series_notes" class="form-control" value="<?php echo $series_data['_series_notes']; ?>" >
    	</p>
    </div>
    <?php
}

add_action('save_post','coupon_meta_save');
function coupon_meta_save($post_id){
    extract($_POST);
    $data = array(
        '_series_code' 			=> $_series_code, 
        '_series_active' 		=> $_series_active, 
        '_series_publisher' 	=> $_series_publisher, 
        '_series_numissues' 	=> $_series_numissues, 
        '_series_frequencycode' => $_series_frequencycode, 
        '_series_override' 		=> $_series_override, 
        '_series_notes' 		=> $_series_notes, 
      
    );

    update_post_meta($post_id,'subscription_series_data',$data);
}

?>