<tr>
	<td>
		<a id="generate" class="button-primary" href="javascript:void(0);">Generate Script</a>
	</td>
</tr>

<tr class="show_code">
	<th><label for="ilc_script">Script</label></th>
	<td>
		<?php 
		$blog_id = get_current_blog_id();

		
		$site_url = site_url();
		$content = '<script type="text/javascript" src="'.$site_url.'/wp-content/plugins/popupcomics_widget/class/file/'.$blog_id.'/popupcomics_Widget_custom.js"></script>';
		?>
		<textarea id="ilc_script" name="ilc_script" cols="100" rows="3" readonly><?php echo trim($content," "); ?></textarea><br/>
		<span class="description"></span>
	</td>
</tr>