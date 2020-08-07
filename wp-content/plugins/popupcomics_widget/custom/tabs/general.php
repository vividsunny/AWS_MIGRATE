<tr>
	<th><label for="widget_text_color"><?php echo __('Text Color','widget'); ?></label></th>
	<td>
		<input type="text" class="widget-color-field" name="widget_text_color" value="<?php if($settings['widget_text_color']) echo $settings['widget_text_color']; ?>">
		<div class="description"><?php echo __('Set text color.','widget'); ?></div>
	</td>
</tr>

<tr>
	<th><label for="widget_column_shadow_color"><?php echo __('Text Shadow Color','widget'); ?></label></th>
	<td>
		<input type="text" class="widget-color-field" name="widget_column_shadow_color" value="<?php if($settings['widget_column_shadow_color']) echo $settings['widget_column_shadow_color']; ?>">
		<div class="description"><?php echo __('Set column shadow color.','widget'); ?></div>
	</td>
</tr>

<tr>
	<th><label for="widget_text_size"><?php echo __('Text Size','widget'); ?></label></th>
	<td>
		<input type="number" class="" name="widget_text_size" value="<?php if($settings['widget_text_size']) echo $settings['widget_text_size']; ?>">
		<div class="description"><?php echo __('Set font size.','widget'); ?></div>
	</td>

</tr>