<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $YWAU;
if ( ! function_exists( 'ywau_get_rule_values' ) ) {

	function ywau_get_rule_values( $values ) {
		global $upload_rule;

		$defaults = array(
			'rule-id'        => 0,
			'rule-label'     => '',
			'rule-extension' => '',
			'rule-max-size'  => 1,
			'rule-note'      => 0,
		);

		$args = wp_parse_args( $values, $defaults );

		return str_replace(
			array(
				'{rule-id}',
				'{rule-label}',
				'{rule-extension}',
				'{rule-max-size}',
				'{rule-note-checked}',
			),
			array(
				$args['rule-id'],
				$args['rule-label'],
				$args['rule-extension'],
				$args['rule-max-size'],
				( 1 == $args['rule-note'] ) ? 'checked' : '',
			),
			$upload_rule );
	}
}

$upload_type = isset( $upload_type ) ? $upload_type : 'global';
$strict_mode = isset( $strict_mode ) ? $strict_mode : false;
$item_id     = isset( $item_id ) ? $item_id : 0;

global $upload_rule;
$upload_rule = '<div class="upload-rule">' .
               '<input type="hidden" name="ywau_rule_id[]" value="{rule-id}">' .
               '<input type="hidden" name="ywau_rule_status[]" value="visible">' .
               '<div>' .
               '<fieldset>' .
               '<label>' . esc_html__( 'Label:', 'yith-woocommerce-additional-uploads' ) . '</label>' .
               '<input id="ywau_upload_label[]" name="ywau_upload_label[]" style="width:60%" type="text" required="required" value="{rule-label}">' .
               '</fieldset>' .
               '<fieldset>' .
               '<label>' . esc_html__( 'Allowed extensions:', 'yith-woocommerce-additional-uploads' ) . '</label>' .
               '<input id="ywau_allowed_extensions[]" name="ywau_allowed_extensions[]" type="text" value="{rule-extension}">' .
               '<span class="description">' . esc_html__( "Add the allowed file formats, writing the extensions divided by comma (e.g., jpg, png, gif).", 'yith-woocommerce-additional-uploads' ) . '</span>' .
               '</fieldset>' .
               '<fieldset>' .
               '<label>' . esc_html__( 'Max size:', 'yith-woocommerce-additional-uploads' ) . '</label>' .
               '<input id="ywau_max_size[]" name="ywau_max_size[]" type="number" style="" value="{rule-max-size}" class="" placeholder="" min="0" step="0.1" required="required">' .
               '<span class="description">' . esc_html__( "Maximum allowed size in MB", 'yith-woocommerce-additional-uploads' ) . '</span>' .
               '</fieldset>' .
               '<fieldset>' .
               '<label>' . esc_html__( 'Allow notes:', 'yith-woocommerce-additional-uploads' ) . '</label>' .
               '<input id="ywau_rule_note[{rule-id}]" name="ywau_rule_note[{rule-id}]" type="checkbox" {rule-note-checked} value="{rule-id}">' .
               '</fieldset>' .
               '</div>' .
               '<div class="delete-rule-section">' .
               '<a href ="#" class= "delete-rule" data-rule-id="{rule-id}" >' . esc_html__( 'Delete', 'yith-woocommerce-additional-uploads' ) . '</a>' .
               '</div>' .
               '</div>';

$rules = new YWAU_Rules();

$product_rules = $rules->get_rules_settings_by_type( $upload_type, $item_id, $strict_mode );
?>
<?php if ( 'global' == $upload_type ): ?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<?php echo esc_html( $value['title'] ) ?>
	</th>

	<td class="forminp plugin-option">
		<?php endif; ?>
		<div id="multiple-upload-section" class="tax-class-table">
			<input type="hidden" name="upload_type" value="<?php echo $upload_type; ?>" />
			<input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
			<a href="#" class="add-new-rule"><?php _e( "Add new rule", 'yith-woocommerce-additional-uploads' ); ?></a>
			<?php /* load previous rules, if exists or a empty rule */

			/** @var $product_rule YWAU_Rule */
			if ( count( $product_rules ) > 0 ) {
				foreach ( $product_rules as $product_rule /*$key => $value*/ ) {

					echo ywau_get_rule_values(
						array(
							"rule-id"        => $product_rule->rule_id,
							"rule-label"     => $product_rule->label,
							"rule-extension" => $product_rule->extension,
							"rule-note"      => $product_rule->enable_note,
							"rule-max-size"  => $product_rule->file_max_size,
						) );
				}
			}
			?>
		</div>
		<?php if ( 'global' == $upload_type ): ?>
	</td>
</tr>
<?php endif; ?>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		function get_upload_rule(rule_number) {

			var rule = '<?php echo $upload_rule; ?>';
			rule = rule.replace(/\{rule-id\}/g, rule_number);
			rule = rule.replace(/\{rule-label\}/g, '');
			rule = rule.replace(/\{rule-extension\}/g, '');
			rule = rule.replace(/\{rule-max-size\}/g, '1');

			return rule;
		}

		$('a.add-new-rule').on('click', function (e) {
			e.preventDefault();

			if ($("div.upload-rule").length) {
				$("div.upload-rule:last-child").after(get_upload_rule(0));
			}
			else {
				$("#multiple-upload-section").append(get_upload_rule(0));
			}
		});

		$(document).on('click', 'a.delete-rule', function (e) {
			e.preventDefault();

			$(this).closest("div.upload-rule").addClass("removed");
			$(this).closest('div.upload-rule').children('input[name^="ywau_rule_status"]').val("deleted");
		});
	});


</script>