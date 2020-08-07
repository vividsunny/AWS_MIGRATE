<?php
/**
 * Muffin Builder 3.1 | Backend
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 *
 * @changelog
 *
 * 3.1
 * added: unique IDs for all builder elements
 */

/**
 * PRINT functions
 * Print: fields, items, wraps, sections
 */

if (! function_exists('mfn_meta_field_input')) {

	/**
	 * PRINT single FIELD
	 */

	function mfn_meta_field_input($field, $meta, $type = 'meta')
	{
		global $MFN_Options;

		if (isset($field['type'])) {

			// class for single option table row

			if (isset($field['class'])) {
				$class = $field['class'];
			} else {
				$class = '';
			}

			// output -----

			echo '<tr class="'. $class .'">';

				// label

				echo '<th>';

					if (key_exists('title', $field)) {
						echo $field['title'];
					}
					if (key_exists('sub_desc', $field)) {
						echo '<span class="description">'. $field['sub_desc'] .'</span>';
					}

				echo '</th>';

				// field

				echo '<td>';

					$field_class = 'MFN_Options_'.$field['type'];
					require_once($MFN_Options->dir.'fields/'.$field['type'].'/field_'.$field['type'].'.php');

					if (class_exists($field_class)) {
						$field_object = new $field_class($field, $meta);
						$field_object->render($type);
					}

				echo '</td>';

			echo '</tr>';
		}
	}
}

if (! function_exists('mfn_builder_section')) {

	/**
	 * PRINT single SECTION
	 */

	function mfn_builder_section($section = false, $uids = false)
	{

		// change section visibility

		if ($section && key_exists('attr', $section) && key_exists('hide', $section['attr']) && $section['attr']['hide']) {
			$hide = 'hide';
			$icon = 'hidden';
		} else {
			$hide = false;
			$icon = 'visibility';
		}

		// attributes

		if( $section && key_exists('attr', $section) && key_exists('title', $section['attr']) ){
			$section_label = $section['attr']['title'];
		} else {
			$section_label = '';
		}

		// section ID

		if( $section ){

			// section exists
			if( ! empty($section['uid']) ){
				// has unique ID
				$section_id = $section['uid'];
			} else {
				// without unique ID
				$section_id = mfn_uniqueID( $uids );
			}

			$uids[] = $section_id;

		} else {

			// default empty section
			$section_id = false;

		}

		// form fields names - only for existing sections, NOT for new sections

		$n_row_id = $section ? 'mfn-row-id[]' : '';

		// output -----

		echo '<div class="mfn-element mfn-row '. $hide .'" data-title="'. __('Section', 'mfn-opts') .'">';

			// section | content

			echo '<div class="mfn-element-content">';

				echo '<input type="hidden" class="mfn-row-id" name="'. $n_row_id .'" value="'. $section_id .'" />';

				// section | header

				echo '<div class="mfn-element-header mfn-row-header">';

					echo '<div class="mfn-element-btns">';
						echo '<a class="mfn-element-btn mfn-add-wrap" href="javascript:void(0);">'. __('Add Wrap', 'mfn-opts') .'</a>';
						echo '<a class="mfn-element-btn mfn-add-divider" href="javascript:void(0);">'. __('Add Divider', 'mfn-opts') .'</a>';
					echo '</div>';

					echo '<span class="mfn-item-label">'. $section_label .'</span>';

					echo '<div class="mfn-element-tools">';
						echo '<a class="mfn-element-btn mfn-element-edit dashicons dashicons-edit" title="'. __('Edit', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-element-clone mfn-row-clone dashicons dashicons-share-alt2" title="'. __('Clone', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-element-hide dashicons dashicons-'. $icon .'" title="'. __('Hide', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-element-delete dashicons dashicons-no" title="'. __('Delete', 'mfn-opts') .'" href="javascript:void(0);"></a>';
					echo '</div>';

				echo '</div>';

				// section | sortable

				echo '<div class="mfn-sortable mfn-sortable-row clearfix">';

					// section | existing wraps

					if ($section) {

						// FIX | Muffin Builder 2 compatibility
						// there were no wraps inside section in Muffin Builder 2

						if (! key_exists('wraps', $section) && is_array($section['items'])) {
							$fix_wrap = array(
								'size'	=> '1/1',
								'items'	=> $section['items'],
							);
							$section['wraps'] = array( $fix_wrap );
						}

						// print inside wraps

						if (key_exists('wraps', $section) && is_array($section['wraps'])) {
							foreach ($section['wraps'] as $wrap) {
								$uids = mfn_builder_wrap($wrap, $section_id, $uids);
							}
						}
					}

				echo '</div>';

			echo '</div>';

			// section | meta data

			echo '<div class="mfn-element-meta">';

				echo '<table class="form-table">';
					echo '<tbody>';

					// section | meta fields

					$section_fields = mfn_get_fields_section();

					foreach ($section_fields as $field) {

						// values for existing sections

						if ($section && key_exists($field['id'], $section['attr'])) {
							$meta = $section['attr'][$field['id']];
						} else {
							$meta = false;
						}

						// default values

						if (! key_exists('std', $field)) {
							$field['std'] = false;
						}

						if (( ! $meta ) && ( '0' !== $meta )) {
							$meta = stripslashes(htmlspecialchars($field['std'], ENT_QUOTES));
						}

						// field ID

						$field['id'] = 'mfn-rows['. $field['id'] .']';

						// field ID except accordion, faq & tabs

						if ($field['type'] != 'tabs') {
							$field['id'] .= '[]';
						}

						// PRINT single FIELD

						if ($section) {
							$input_type = 'existing';
						} else {
							$input_type = 'new';
						}

						mfn_meta_field_input($field, $meta, $input_type);
					}

					echo '</tbody>';
				echo '</table>';

			echo '</div>';

		echo '</div>';

		return $uids;
	}
}

if (! function_exists('mfn_builder_wrap')) {

	/**
	 * PRINT single WRAP
	 */

	function mfn_builder_wrap($wrap = false, $parent_id = false, $uids = false)
	{

		$sizes = array( '1/6' => 0.1666, '1/5' => 0.2, '1/4' => 0.25, '1/3' => 0.3333, '2/5' => 0.4, '1/2' => 0.5, '3/5' => 0.6, '2/3' => 0.6667, '3/4' => 0.75, '4/5' => 0.8, '5/6' => 0.8333, '1/1' => 1, 'divider' => 1 );
		$size = $wrap ? $wrap['size'] : '1/1';

		// form fields names - only for existing wraps, NOT for new wrap

		$n_wrap_id = $wrap ? 'mfn-wrap-id[]' : '';
		$n_wrap_parent = $wrap ? 'mfn-wrap-parent[]' : '';
		$n_wrap_size = $wrap ? 'mfn-wrap-size[]' : '';

		// wrap ID

		if( $wrap ){

			// wrap exists
			if( ! empty($wrap['uid']) ){
				// has unique ID
				$wrap_id = $wrap['uid'];
			} else {
				// without unique ID
				$wrap_id = mfn_uniqueID( $uids );
			}

			$uids[] = $wrap_id;

		} else {

			// default empty wrap
			$wrap_id = false;

		}

		// attributes

		$class = '';
		if ($wrap && ($wrap['size'] == 'divider')) {
			$class .= ' divider';
		}

		// output -----

		echo '<div class="mfn-element mfn-wrap '. $class .'" data-size="'. $sizes[$size] .'" data-title="'. __('Wrap', 'mfn-opts') .'">';

			// wrap | content

			echo '<div class="mfn-element-content">';

				echo '<input type="hidden" class="mfn-wrap-id" name="'. $n_wrap_id .'" value="'. $wrap_id .'" />';
				echo '<input type="hidden" class="mfn-wrap-parent" name="'. $n_wrap_parent .'" value="'. $parent_id .'" />';
				echo '<input type="hidden" class="mfn-wrap-size" name="'. $n_wrap_size .'" value="'. $size .'" />';

				// wrap | header

				echo '<div class="mfn-element-header mfn-wrap-header">';

					echo '<div class="mfn-item-size">';
						echo '<a class="mfn-element-btn mfn-item-size-dec" href="javascript:void(0);">-</a>';
						echo '<a class="mfn-element-btn mfn-item-size-inc" href="javascript:void(0);">+</a>';
						echo '<a class="mfn-element-btn mfn-add-item" href="javascript:void(0);">Add Item</a>';
						echo '<span class="mfn-element-btn mfn-item-desc">'. $size .'</span>';
					echo '</div>';

					echo '<div class="mfn-element-tools">';
						echo '<a class="mfn-element-btn mfn-element-edit mfn-wrap-edit dashicons dashicons-edit" title="'. __('Edit', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-element-clone mfn-wrap-clone dashicons dashicons-share-alt2" title="'. __('Clone', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-element-delete dashicons dashicons-no" title="'. __('Delete', 'mfn-opts') .'" href="javascript:void(0);"></a>';
					echo '</div>';

				echo '</div>';

				// wrap | sortable

				echo '<div class="mfn-sortable mfn-sortable-wrap clearfix">';

					if ($wrap && key_exists('items', $wrap) && is_array($wrap['items'])) {
						foreach ($wrap['items'] as $item) {
							$uids = mfn_builder_item($item['type'], $item, $wrap_id, $uids);
						}
					}

				echo '</div>';

			echo '</div>';

			// wrap | meta

			echo '<div class="mfn-element-meta">';

				echo '<table class="form-table">';
					echo '<tbody>';

						// wrap | meta fields

						$wrap_fields = mfn_get_fields_wrap();

						foreach ($wrap_fields as $field) {

							// values for existing wraps

							if ($wrap && key_exists('attr', $wrap) && key_exists($field['id'], $wrap['attr'])) {
								$meta = $wrap['attr'][$field['id']];
							} else {
								$meta = false;
							}

							// default values

							if (! key_exists('std', $field)) {
								$field['std'] = false;
							}

							if (( ! $meta ) && ( '0' !== $meta )) {
								$meta = stripslashes(htmlspecialchars($field['std'], ENT_QUOTES));
							}

							// field ID

							$field['id'] = 'mfn-wraps['. $field['id'] .']';

							// field ID except accordion, faq & tabs

							if ($field['type'] != 'tabs') {
								$field['id'] .= '[]';
							}

							// PRINT single FIELD

							if ($wrap) {
								$input_type = 'existing';
							} else {
								$input_type = 'new';
							}

							mfn_meta_field_input($field, $meta, $input_type);
						}

					echo '</tbody>';
				echo '</table>';

			echo '</div>';

		echo '</div>';

		return $uids;
	}
}

if (! function_exists('mfn_builder_item')) {

	/**
	 * PRINT single ITEM
	 */

	function mfn_builder_item($item_type, $item = false, $parent_id = false, $uids = false)
	{

		$item_std = mfn_get_fields_item($item_type);

		$sizes = array( '1/6' => 0.1666, '1/5' => 0.2, '1/4' => 0.25, '1/3' => 0.3333, '2/5' => 0.4, '1/2' => 0.5, '3/5' => 0.6, '2/3' => 0.6667, '3/4' => 0.75, '4/5' => 0.8, '5/6' => 0.8333, '1/1' => 1 );
		$item_std['size'] = $item['size'] ? $item['size'] : $item_std['size'];

		// form fields names - only for existing items, NOT for new items

		$n_item_type = $item ? 'mfn-item-type[]' : '';
		$n_item_id = $item ? 'mfn-item-id[]' : '';
		$n_item_size = $item ? 'mfn-item-size[]' : '';
		$n_item_parent = $item ? 'mfn-item-parent[]' : '';

		// item ID

		if( $item ){

			// item exists
			if( ! empty($item['uid']) ){
				// has unique ID
				$item_id = $item['uid'];
			} else {
				// without unique ID
				$item_id = mfn_uniqueID( $uids );
			}

			$uids[] = $item_id;

		} else {

			// default empty item
			$item_id = false;

		}

		// output -----

		echo '<div class="mfn-element mfn-item mfn-item-'. $item_std['type'] .'" data-size="'. $sizes[$item_std['size']] .'" data-title="'. $item_std['title'] .'">';

			echo '<div class="mfn-element-content">';

				echo '<input type="hidden" class="mfn-item-type" name="'. $n_item_type .'" value="'. $item_std['type'] .'">';
				echo '<input type="hidden" class="mfn-item-id" name="'. $n_item_id .'" value="'. $item_id .'" />';
				echo '<input type="hidden" class="mfn-item-parent" name="'. $n_item_parent .'" value="'. $parent_id .'" />';
				echo '<input type="hidden" class="mfn-item-size" name="'. $n_item_size .'" value="'. $item_std['size'] .'">';

				echo '<div class="mfn-element-header">';

					echo '<div class="mfn-item-size">';
						echo '<a class="mfn-element-btn mfn-item-size-dec" href="javascript:void(0);">-</a>';
						echo '<a class="mfn-element-btn mfn-item-size-inc" href="javascript:void(0);">+</a>';
						echo '<span class="mfn-element-btn mfn-item-desc">'. $item_std['size'] .'</span>';
					echo '</div>';

					echo '<div class="mfn-element-tools">';
						echo '<a class="mfn-element-btn mfn-fr mfn-element-edit dashicons dashicons-edit" title="'. __('Edit', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-fr mfn-element-clone mfn-item-clone dashicons dashicons-share-alt2" title="'. __('Clone', 'mfn-opts') .'" href="javascript:void(0);"></a>';
						echo '<a class="mfn-element-btn mfn-fr mfn-element-delete dashicons dashicons-no" title="'. __('Delete', 'mfn-opts') .'" href="javascript:void(0);"></a>';
					echo '</div>';

				echo '</div>';

				echo '<div class="mfn-item-content">';

					echo '<div class="mfn-item-inside">';

						echo '<div class="mfn-item-icon"></div>';
						echo '<div class="mfn-item-inside-desc">';

							echo '<span class="mfn-item-title">'. $item_std['title'] .'</span>';

							$item_label = ($item && key_exists('fields', $item) && key_exists('title', $item['fields'])) ? $item['fields']['title'] : '';
							echo '<span class="mfn-item-label">'. $item_label .'</span>';

						echo '</div>';

					echo '</div>';

					if ($item && key_exists('fields', $item) && key_exists('content', $item['fields'])) {

						$item_excerpt = strip_shortcodes(strip_tags($item['fields']['content']));

						$item_excerpt = preg_split('/\b/', $item_excerpt, 16 * 2 + 1);
						$item_excerpt_waste = array_pop($item_excerpt);
						$item_excerpt = implode($item_excerpt);

						echo '<p class="mfn-item-excerpt">'. esc_html($item_excerpt) .'</p>';
					}

				echo '</div>';

			echo '</div>';

			echo '<div class="mfn-element-meta">';

				echo '<table class="form-table">';
					echo '<tbody>';

						// fields

						foreach ($item_std['fields'] as $field) {

							// values for existing items

							if ($item && key_exists('fields', $item) && key_exists($field['id'], $item['fields'])) {
								$meta = $item['fields'][$field['id']];
							} else {
								if (! key_exists('std', $field)) {
									$field['std'] = false;
								}
								$meta = stripslashes(htmlspecialchars($field['std'], ENT_QUOTES));
							}

							// field ID

							$field['id'] = 'mfn-items['. $item_std['type'] .']['. $field['id'] .']';

							// field ID except accordion, faq & tabs

							if ($field['type'] != 'tabs') {
								$field['id'] .= '[]';
							}

							// PRINT single FIELD

							if ($item) {
								$input_type = 'existing';
							} else {
								$input_type = 'new';
							}

							mfn_meta_field_input($field, $meta, $input_type);
						}

					echo '</tbody>';
				echo '</table>';

			echo '</div>';

		echo '</div>';

		return $uids;
	}
}

/**
 * Muffin Builder
 * Main backend builder function
 */

if (! function_exists('mfn_builder_show')) {

	/**
	 * PRINT Muffin Builder
	 */

	function mfn_builder_show()
	{
		global $post;

		$uids = array();

		// hide builder if current user does not have a specific capability

		if ($visibility = mfn_opts_get('builder-visibility')) {
			if ($visibility == 'hide' || (! current_user_can($visibility))) {
				return false;
			}
		}

		// GET items

		$mfn_items = get_post_meta($post->ID, 'mfn-page-items', true);

		// FIX | Muffin Builder 2 compatibility

		if ($mfn_items && ! is_array($mfn_items)) {
			$mfn_items = unserialize(call_user_func('base'.'64_decode', $mfn_items));
		}

		// debug
		// print_r( $mfn_items );

		?>

		<div id="mfn-builder">

			<input type="hidden" name="mfn-items-save" value="1"/>
			<a id="mfn-go-to-top" class="dashicons dashicons-arrow-up-alt" href="javascript:void(0);"></a>

			<div id="mfn-content">

				<!-- add section | first -->

				<div class="mfn-row-add">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<td>
									<a class="mfn-row-add-btn add-first" href="javascript:void(0);">
										<span class="dashicons dashicons-align-center"></span>
										<?php _e('<strong>Add Section</strong> as the first section', 'mfn-opts'); ?>
									</a>
									<div class="logo">Muffin Group | Muffin Builder</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- builder desktop -->

				<div id="mfn-desk" class="clearfix">

					<?php
						$class_add_row = 'hide';

						// print_r($mfn_items);

						if (is_array($mfn_items)) {
							foreach ($mfn_items as $section) {
								$uids = mfn_builder_section($section, $uids);
							}

							if(count($mfn_items)) {
								$class_add_row = false;
							}

						}
					?>

				</div>

				<!-- add section | last -->

				<div class="mfn-row-add last <?php echo $class_add_row; ?>">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<td>
									<a class="mfn-row-add-btn add-last" href="javascript:void(0);">
										<span class="dashicons dashicons-align-center"></span>
										<?php _e('<strong>Add Section</strong> as the last section', 'mfn-opts'); ?>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- section | default new -->

				<div id="mfn-rows" class="clearfix">
					<?php mfn_builder_section(); ?>
				</div>

				<!-- wrap | default new -->

				<div id="mfn-wraps" class="clearfix">
					<?php mfn_builder_wrap(); ?>
				</div>

				<!-- items | default new -->

				<div id="mfn-items" class="clearfix">
					<?php
						$items = mfn_get_fields_item();
						foreach ($items as $item) {
							mfn_builder_item($item['type']);
							echo "\n";
						}
					?>
				</div>

				<!-- add new item popup -->

				<div id="mfn-item-add" class="mfn-popup mfn-popup-item-add">
					<div class="mfn-popup-inside">

						<div class="mfn-popup-header">

							<div class="mfn-ph-left">
								<span class="mfn-ph-btn mfn-ph-desc"><?php _e('Add Item', 'mfn-opts'); ?></span>
							</div>

							<div class="mfn-ph-right">
								<a class="mfn-ph-btn mfn-ph-close dashicons dashicons-no" title="<?php _e('Close', 'mfn-opts'); ?>" href="javascript:void(0);"></a>
							</div>

						</div>

						<div class="mfn-popup-content">

							<div class="mfn-popup-subheader">

								<ul class="mfn-popup-tabs">
									<li data-filter="*" class="active"><a href="javascript:void(0);"><?php _e('All', 'mfn-opts'); ?></a></li>
									<li data-filter="typography"><a href="javascript:void(0);"><?php _e('Typography', 'mfn-opts'); ?></a></li>
									<li data-filter="boxes"><a href="javascript:void(0);"><?php _e('Boxes & Infographics', 'mfn-opts'); ?></a></li>
									<li data-filter="blocks"><a href="javascript:void(0);"><?php _e('Content Blocks', 'mfn-opts'); ?></a></li>
									<li data-filter="elements"><a href="javascript:void(0);"><?php _e('Content Elements', 'mfn-opts'); ?></a></li>
									<li data-filter="loops"><a href="javascript:void(0);"><?php _e('Loops', 'mfn-opts'); ?></a></li>
									<li data-filter="other"><a href="javascript:void(0);"><?php _e('Other', 'mfn-opts'); ?></a></li>
								</ul>

								<div class="mfn-popup-search">
									<span class="dashicons dashicons-search"></span>
									<input class="mfn-search-item" placeholder="<?php _e('Search Item', 'mfn-opts'); ?>" />
								</div>

							</div>

							<ul class="mfn-popup-items clear">
								<?php
									$items = mfn_get_fields_item();
									foreach ($items as $item) {
										$category = isset($item['cat']) ? 'category-'. $item['cat'] : '' ;

										echo '<li class="mfn-item-'. $item['type'] .' '. $category .'" data-type="'. $item['type'] .'">';
											echo '<a data-type="'. $item['type'] .'" href="javascript:void(0);">';
												echo '<span class="title">'. $item['title'] .'</span>';
												echo '<div class="mfn-item-icon"></div>';
											echo '</a>';
										echo '</li>';
									}
								?>
							</ul>

						</div>

					</div>
				</div>

				<!-- migrate -->

				<div id="mfn-migrate">

					<a href="javascript:void(0);" class="mfn-btn-migrate btn-seo"><?php _e('Builder to SEO', 'mfn-opts'); ?></a>

					<div class="btn-wrapper">
						<a href="javascript:void(0);" class="mfn-btn-migrate btn-exp"><?php _e('Export', 'mfn-opts'); ?></a>
						<a href="javascript:void(0);" class="mfn-btn-migrate btn-imp"><?php _e('Import', 'mfn-opts'); ?></a>
						<a href="javascript:void(0);" class="mfn-btn-migrate btn-tem btn-primary"><?php _e('Templates', 'mfn-opts'); ?></a>
					</div>

					<div class="migrate-wrapper export-wrapper hide">
						<textarea id="mfn-items-export" placeholder="Muffin Builder data processing..."></textarea>
						<span class="description"><?php _e('Copy to clipboard: Ctrl+C (Cmd+C for Mac)', 'mfn-opts'); ?></span>
					</div>

					<div class="migrate-wrapper import-wrapper hide">

						<textarea id="mfn-items-import" placeholder="Paste import data here."></textarea>
						<a href="javascript:void(0);" class="mfn-btn-migrate btn-primary btn-import"><?php _e('Import', 'mfn-opts'); ?></a>

						<select id="mfn-items-import-type">
							<option value="before"><?php _e('Insert BEFORE current builder content', 'mfn-opts'); ?></option>
							<option value="after"><?php _e('Insert AFTER current builder content', 'mfn-opts'); ?></option>
							<option value="replace"><?php _e('REPLACE current builder content', 'mfn-opts'); ?></option>
						</select>

					</div>

					<div class="migrate-wrapper templates-wrapper hide">

						<a href="javascript:void(0);" class="mfn-btn-migrate btn-primary btn-template"><?php _e('Use Template', 'mfn-opts'); ?></a>

						<select id="mfn-items-import-template-type">
							<option value="before"><?php _e('Insert BEFORE current builder content', 'mfn-opts'); ?></option>
							<option value="after"><?php _e('Insert AFTER current builder content', 'mfn-opts'); ?></option>
							<option value="replace"><?php _e('REPLACE current builder content', 'mfn-opts'); ?></option>
						</select>

						<select id="mfn-items-import-template">
							<option value=""><?php _e('-- Select --', 'mfn-opts'); ?></option>
							<?php
								$args = array(
									'post_type' => 'template',
									'posts_per_page'=> -1,
								);
								$templates = get_posts($args);

								if (is_array($templates)) {
									foreach ($templates as $v) {
										echo '<option value="'. $v->ID .'">'. $v->post_title .'</options>';
									}
								}
							?>
						</select>
					</div>

				</div>

			</div>

			<!-- builder to SEO -->

			<div id="mfn-items-seo">
				<?php
					$mfn_items_seo = get_post_meta($post->ID, 'mfn-page-items-seo', true);
					echo '<textarea id="mfn-items-seo-data">'. esc_attr($mfn_items_seo) .'</textarea>';
				?>
			</div>

		</div>

		<?php
	}
}

if (! function_exists('mfn_builder_save')) {

	/**
	 * SAVE Muffin Builder
	 */

	function mfn_builder_save($post_id)
	{

		// debug
		// print_r($_POST);
		// exit;

		// Fix | Visual Composer Frontend

		if (isset($_POST['vc_inline'])) {
			return false;
		}

		// variables

		$mfn_items = array();
		$mfn_wraps = array();

		// LOOP sections

		if (key_exists('mfn-row-id', $_POST) && is_array($_POST['mfn-row-id'])) {

			foreach ($_POST['mfn-row-id'] as $sectionID_k => $sectionID) {

				$section = array();

				$section['uid'] = $_POST['mfn-row-id'][$sectionID_k];

				// $section['attr'] - section attributes

				if (key_exists('mfn-rows', $_POST) && is_array($_POST['mfn-rows'])) {
					foreach ($_POST['mfn-rows'] as $section_attr_k => $section_attr) {
						$section['attr'][$section_attr_k] = stripslashes($section_attr[$sectionID_k]);
					}
				}

				$section['wraps'] = ''; // $section['wraps'] - section wraps will be added in next loop

				$mfn_items[] = $section;
			}

			$row_IDs = $_POST['mfn-row-id'];
			$row_IDs_key = array_flip($row_IDs);
		}

		// LOOP wraps

		if (key_exists('mfn-wrap-id', $_POST) && is_array($_POST['mfn-wrap-id'])) {

			foreach ($_POST['mfn-wrap-id'] as $wrapID_k => $wrapID) {

				$wrap = array();

				$wrap['uid'] = $_POST['mfn-wrap-id'][$wrapID_k];
				$wrap['size'] = $_POST['mfn-wrap-size'][$wrapID_k];
				$wrap['items'] = ''; // $wrap['items'] - items will be added in the next loop

				// $wrap['attr'] - wrap attributes

				if (key_exists('mfn-wraps', $_POST) && is_array($_POST['mfn-wraps'])) {
					foreach ($_POST['mfn-wraps'] as $wrap_attr_k => $wrap_attr) {
						$wrap['attr'][$wrap_attr_k] = $wrap_attr[$wrapID_k];
					}
				}

				$mfn_wraps[$wrapID] = $wrap;
			}

			$wrap_IDs = $_POST['mfn-wrap-id'];
			$wrap_IDs_key = array_flip($wrap_IDs);
			$wrap_parents = $_POST['mfn-wrap-parent'];
		}

		// LOOP items

		if (key_exists('mfn-item-type', $_POST) && is_array($_POST['mfn-item-type'])) {

			$count = array();
			$tabs_count = array();

			$seo_content = '';

			foreach ($_POST['mfn-item-type'] as $type_k => $type) {

				$item = array();
				$item['type'] = $type;
				$item['uid'] = $_POST['mfn-item-id'][$type_k];
				$item['size'] = $_POST['mfn-item-size'][$type_k];

				// init count for specified item type

				if (! key_exists($type, $count)) {
					$count[$type] = 0;
				}

				// init count for specified tab type

				if (! key_exists($type, $tabs_count)) {
					$tabs_count[$type] = 0;
				}

				if (key_exists($type, $_POST['mfn-items'])) {
					foreach ((array) $_POST['mfn-items'][$type] as $attr_k => $attr) {

						if ($attr_k == 'tabs') {

							// accordion, FAQ & tabs

							$item['fields']['count'] = $attr['count'][$count[$type]];

							if ($item['fields']['count']) {
								for ($i = 0; $i < $item['fields']['count']; $i++) {
									$tab = array();
									$tab['title'] 	= stripslashes($attr['title'][$tabs_count[$type]]);

									if (mfn_opts_get('builder-storage')) {
										$tab['content'] = stripslashes($attr['content'][$tabs_count[$type]]);
									} else {
										// core.trac.wordpress.org/ticket/34845
										$tab['content'] = preg_replace('~\R~u', "\n", stripslashes($attr['content'][$tabs_count[$type]]));
									}

									$item['fields']['tabs'][] = $tab;

									// FIX | Yoast SEO

									$seo_val = trim($attr['title'][$tabs_count[$type]]);
									if ($seo_val && $seo_val != 1) {
										$seo_content .= $attr['title'][$tabs_count[$type]] .": ";
									}
									$seo_val = trim($attr['content'][$tabs_count[$type]]);
									if ($seo_val && $seo_val != 1) {
										$seo_content .= $attr['content'][$tabs_count[$type]] ."\n\n";
									}

									$tabs_count[$type]++;
								}
							}

						} else {

							// all other items

							if (mfn_opts_get('builder-storage')) {
								$item['fields'][$attr_k] = stripslashes($attr[$count[$type]]);
							} else {
								// core.trac.wordpress.org/ticket/34845
								$item['fields'][$attr_k] = preg_replace('~\R~u', "\n", stripslashes($attr[$count[$type]]));
							}

							// FIX | Yoast SEO

							$seo_val = trim($attr[$count[$type]]);

							if ($seo_val && $seo_val != 1) {
								if (in_array($attr_k, array( 'image', 'src' ))) {
									// image
									$seo_content .= '<img src="'. $seo_val .'" alt="'. mfn_get_attachment_data($seo_val, 'alt') .'"/>'."\n";
								} elseif ($attr_k == 'link') {
									// link
									$seo_content .= '<a href="'. $seo_val .'">'. $seo_val .'</a>'."\n";
								} else {
									$seo_content .= $seo_val ."\n";
								}
							}

						}
					}

					$seo_content .= "\n";
				}

				// increase count for specified item type

				$count[$type] ++;

				// parent wrap

				$parent_wrap_ID = $_POST['mfn-item-parent'][$type_k];

				if (! isset($mfn_wraps[ $parent_wrap_ID ]['items']) || ! is_array($mfn_wraps[ $parent_wrap_ID ]['items'])) {
					$mfn_wraps[ $parent_wrap_ID ]['items'] = array();
				}
				$mfn_wraps[ $parent_wrap_ID ]['items'][] = $item;
			}
		}

		// assign wraps with items to sections

		foreach ($mfn_wraps as $wrap_ID => $wrap) {

			$wrap_key = $wrap_IDs_key[ $wrap_ID ];
			$section_ID = $wrap_parents[ $wrap_key ];
			$section_key = $row_IDs_key[ $section_ID ];

			if (! isset($mfn_items[ $section_key ]['wraps']) || ! is_array($mfn_items[ $section_key ]['wraps'])) {
				$mfn_items[ $section_key ]['wraps'] = array();
			}
			$mfn_items[ $section_key ]['wraps'][] = $wrap;

		}

		// debug
		// print_r($mfn_items);
		// exit;

		// prepare data to save

		if ($mfn_items) {
			if (mfn_opts_get('builder-storage') == 'encode') {
				$new = call_user_func('base'.'64_encode', serialize($mfn_items));
			} else {
				// codex.wordpress.org/Function_Reference/update_post_meta
				$new = wp_slash($mfn_items);
			}
		}

		// SAVE data

		if (key_exists('mfn-items-save', $_POST)) {

			$field['id'] = 'mfn-page-items';
			$old = get_post_meta($post_id, $field['id'], true);

			if (isset($new) && $new != $old) {

				// update post meta if there is at least one builder section
				update_post_meta($post_id, $field['id'], $new);

			} elseif ($old && (! isset($new) || ! $new)) {

				// delete post meta if builder is empty
				delete_post_meta($post_id, $field['id'], $old);

			}

			// FIX | Yoast SEO

			if (isset($new)) {
				update_post_meta($post_id, 'mfn-page-items-seo', $seo_content);
			}

		}
	}
}

/**
 * Helper functions
 * GET data for some meta fields
 */

/**
 * Unique ID
 * Generate unique ID and check for collisions
 */
function mfn_uniqueID( $uids = array() ) {

	if (function_exists('openssl_random_pseudo_bytes')) {

		// openssl_random_pseudo_bytes

		$uid = substr(bin2hex(openssl_random_pseudo_bytes(5)), 0, 9);

	} else {

		// fallback

		$keyspace = '0123456789abcdefghijklmnopqrstuvwxyz';
		$keyspace_length = 36;
		$uid = '';

		for ($i = 0; $i < 9; $i++) {
			$uid .= $keyspace[rand(0, $keyspace_length - 1)];
    }

	}

 	if( in_array( $uid, $uids ) ){
 		return mfn_uniqueID($uids);
 	}

 	return $uid;
}

if (! function_exists('mfn_get_animations')) {

	/**
	 * GET animations
	 * Entrance animations for items
	 */

	function mfn_get_animations()
	{
		$array = array(
			'' => __('- Not Animated -', 'mfn-opts'),
			'fadeIn' => __('Fade In', 'mfn-opts'),
			'fadeInUp' => __('Fade In Up', 'mfn-opts'),
			'fadeInDown' => __('Fade In Down ', 'mfn-opts'),
			'fadeInLeft' => __('Fade In Left', 'mfn-opts'),
			'fadeInRight' => __('Fade In Right ', 'mfn-opts'),
			'fadeInUpLarge' => __('Fade In Up Large', 'mfn-opts'),
			'fadeInDownLarge' => __('Fade In Down Large', 'mfn-opts'),
			'fadeInLeftLarge' => __('Fade In Left Large', 'mfn-opts'),
			'fadeInRightLarge' => __('Fade In Right Large', 'mfn-opts'),
			'zoomIn' => __('Zoom In', 'mfn-opts'),
			'zoomInUp' => __('Zoom In Up', 'mfn-opts'),
			'zoomInDown' => __('Zoom In Down', 'mfn-opts'),
			'zoomInLeft' => __('Zoom In Left', 'mfn-opts'),
			'zoomInRight' => __('Zoom In Right', 'mfn-opts'),
			'zoomInUpLarge' => __('Zoom In Up Large', 'mfn-opts'),
			'zoomInDownLarge' => __('Zoom In Down Large', 'mfn-opts'),
			'zoomInLeftLarge' => __('Zoom In Left Large', 'mfn-opts'),
			'bounceIn' => __('Bounce In', 'mfn-opts'),
			'bounceInUp' => __('Bounce In Up', 'mfn-opts'),
			'bounceInDown' => __('Bounce In Down', 'mfn-opts'),
			'bounceInLeft' => __('Bounce In Left', 'mfn-opts'),
			'bounceInRight' => __('Bounce In Right', 'mfn-opts'),
		);

		return $array;
	}
}

if (! function_exists('mfn_get_categories')) {

	/**
	 * GET Categories
	 * Categories for posts or specified taxonomy
	 */

	function mfn_get_categories($category)
	{
		$categories = get_categories(array( 'taxonomy' => $category ));

		$array = array( '' => __('All', 'mfn-opts') );
		foreach ($categories as $cat) {
			if (is_object($cat)) {
				$array[$cat->slug] = $cat->name;
			}
		}

		return $array;
	}
}

if (! function_exists('mfn_get_sliders')) {

	/**
	 * GET Sliders | Revolution Slider
	 */

	function mfn_get_sliders()
	{
		global $wpdb;

		$sliders = array( 0 => __('-- Select --', 'mfn-opts') );

		// check if Revolution Slider is activated

		if (function_exists('rev_slider_shortcode')) {

			// table prefix

			$table_prefix = mfn_opts_get('table_prefix', 'base_prefix');
			if ($table_prefix == 'base_prefix') {
				$table_prefix = $wpdb->base_prefix;
			} else {
				$table_prefix = $wpdb->prefix;
			}

			$table_name = $table_prefix . "revslider_sliders";

			$rs5 = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'type'");
			if ($rs5) {
				// Revolution Slider 5.x
				$array = $wpdb->get_results("SELECT * FROM $table_name WHERE type != 'template' ORDER BY title ASC");
			} else {
				// Revolution Slider 4.x
				$array = $wpdb->get_results("SELECT * FROM $table_name ORDER BY title ASC");
			}

			if (is_array($array)) {
				foreach ($array as $v) {
					$sliders[$v->alias] = $v->title;
				}
			}
		}

		return $sliders;
	}
}

if (! function_exists('mfn_get_sliders_layer')) {

	/**
	 * GET Sliders | Layer Slider
	 */

	function mfn_get_sliders_layer()
	{
		global $wpdb;

		$sliders = array( 0 => __('-- Select --', 'mfn-opts') );

		// check if Layer Slider is activated

		if (function_exists('layerslider')) {

			// table prefix

			$table_prefix = mfn_opts_get('table_prefix', 'base_prefix');
			if ($table_prefix == 'base_prefix') {
				$table_prefix = $wpdb->base_prefix;
			} else {
				$table_prefix = $wpdb->prefix;
			}

			$table_name = $table_prefix . "layerslider";

			$array = $wpdb->get_results("SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY name ASC");

			if (is_array($array)) {
				foreach ($array as $v) {
					$sliders[$v->id] = $v->name;
				}
			}
		}

		return $sliders;
	}
}

if (! function_exists('mfn_builder_enqueue')) {

	/**
	 * Enqueue styles and scripts
	 */

	function mfn_builder_enqueue()
	{
		wp_enqueue_style('mfn-builder', LIBS_URI. '/builder/css/style.css', false, time(), 'all');
		wp_enqueue_script('mfn-builder', LIBS_URI. '/builder/js/scripts.js', array( 'jquery' ), time(), true);
	}
}
add_action('admin_print_styles', 'mfn_builder_enqueue');
