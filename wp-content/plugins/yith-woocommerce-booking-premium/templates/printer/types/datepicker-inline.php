<?php
$name_html         = !empty( $id ) ? " name='{$id}'" : '';
$name_html         = !empty( $name ) ? " name='{$name}'" : $name_html;
$id_html           = !empty( $id ) ? " id='{$id}'" : '';
$class_html        = !empty( $class ) ? " class='yith-wcbk-date-picker yith-wcbk-date-picker--inline {$class}'" : " class='yith-wcbk-date-picker yith-wcbk-date-picker--inline'";
$custom_attributes = ' ' . $custom_attributes;
$data_html         = '';
foreach ( $data as $data_key => $data_value ) {
    $data_html .= " data-{$data_key}='{$data_value}'";
}

$value = !empty( $value ) ? $value : '';
?>
<div class="yith-wcbk-date-picker-inline-wrapper yith-wcbk-clearfix">
    <div <?php echo $id_html . $name_html . $class_html . $custom_attributes . $data_html; ?> data-value="<?php echo $value; ?>"></div>
</div>