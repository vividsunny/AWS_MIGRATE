<tr>
	<th><label for="widget_grid_setting"><?php echo __('Display Grid','widget'); ?></label></th>
	<td>
		<?php
			$x_position_arr = array(
				'1' => __('1', 'widget'),
				'2' => __('2', 'widget'),
				'3' => __('3', 'widget'),
				'4' => __('4', 'widget'),
				'5' => __('5', 'widget'),
			);
		?>
		<select name="widget_grid_setting">
			<option value=""><?php echo __('Please select grid', 'widget'); ?></option>
			<?php 
				foreach($x_position_arr as $x_key => $x_val){
					?>
					<option value="<?php echo $x_key; ?>" <?php selected( $settings['widget_grid_setting'], $x_key ); ?> ><?php echo $x_val; ?></option>
					<?php
				}
			?>
		</select>
	</td>
</tr>

<tr>
	<th><label for="widget_per_page"><?php echo __('Per Page','widget'); ?></label></th>
	<td>
		<input type="number" class="" name="widget_per_page" value="<?php if($settings['widget_per_page']) echo $settings['widget_per_page']; ?>">
	</td>

</tr>
