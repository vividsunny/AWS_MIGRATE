<?php
$name_html         = !empty( $id ) ? " name='{$id}'" : '';
$name_html         = !empty( $name ) ? " name='{$name}'" : $name_html;
$id_html           = !empty( $id ) ? " id='{$id}'" : '';
$class_html        = !empty( $class ) ? " class='{$class}'" : '';
$custom_attributes = ' ' . $custom_attributes;
$data_html         = '';
foreach ( $data as $data_key => $data_value ) {
    $data_html .= " data-{$data_key}='{$data_value}'";
}

$checkbox_value = !empty( $checkbox_value ) ? $checkbox_value : 'yes';
$label          = !empty( $label ) ? $label : '';
?>
<label class="yith-wcbk-checkbox">
    <input type="checkbox" <?php echo $id_html . $name_html . $class_html . $custom_attributes . $data_html . ' ' . checked( $value, $checkbox_value ); ?> value="<?php echo $checkbox_value; ?>">
    <span class="yith-wcbk-checkbox__checkbox"></span>
    <span class="yith-wcbk-checkbox__label"><?php echo $label ?></span>
</label>