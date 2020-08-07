<?php
$blog_id = get_current_blog_id();
$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
$size       = size_format( $bytes );



if(isset( $_FILES['import'] ) ){

  $filetype=array(
    'csv' => 'text/csv',
    'txt' => 'text/plain',
  );
  $overrides = array(
    'test_form' => false,
    'mimes'     => $filetype,
  );
  $import    = $_FILES['import']; /*WPCS: sanitization ok, input var ok.*/
  $upload    = wp_handle_upload( $import, $overrides );
  $error     = false;

  if ( isset( $upload['error'] ) ) {
    echo $upload['error'];
    $error = true;
  }else{
    echo '<p class="va-processing-file"><b>Wait we are processing your file.</b></p>';
  }

  /*Construct the object array.*/
  $object = array(
    'post_title'     => basename( $upload['file'] ),
    'post_content'   => $upload['url'],
    'post_mime_type' => $upload['type'],
    'guid'           => $upload['url'],
    'context'        => 'import',
    'post_status'    => 'private',
  );

  /*Save the data.*/
  $id = wp_insert_attachment( $object, $upload['file'] );

/*
* Schedule a cleanup for one day from now in case of failed
* import or missing wp_import_cleanup() call.
*/
wp_schedule_single_event( time() + DAY_IN_SECONDS, 'importer_scheduled_cleanup', array( $id ) );

if(isset($_POST['import_type'])){
  $type = $_POST['import_type'];
}else{
  $type = $_POST['import_type'];
}

$update_existing = '';
$delimiter = '';

//exit;

if(!$error){
  wp_localize_script(
    'va-import', 'va_import_params', array(
      'import_nonce'    => wp_create_nonce( 'wc-import' ),
      'file'            => $upload['file'],
      'update_existing' => $update_existing,
      'delimiter'       => $delimiter,
      'import_type'       => $type,
      'imported' => 0,
      'failed' => 0,
      'updated' => 0,
      'skipped' => 0,
    )
  );
  wp_enqueue_script( 'va-import' );
}


}

?>
<style type="text/css" media="screen">
.progress {
  background: #4CAF50;
  display: block;
  height: 20px;
  text-align: center;
  transition: width .3s;
  margin-top: 10px;
  width: 0;
  color: #ffffff;
  font-size: 16px;

}
#va_import_log {
  border: 1px solid #cfcfcf;
  padding: 10px;
  margin-top: 10px;
  max-height: 400px;
  overflow: auto;
}

.progress.hide {
  opacity: 0;
  transition: opacity 1.3s;
}  
#csvloading{
  display: none;
}
#va_reports {
  float: right;
  margin-right: 15px;
  font-size: 16px;
  font-weight: 700;
}
</style>
<div class="woocommerce-progress-form-wrapper">
  <form class="wc-progress-form-content va-customer-import va-importer" enctype="multipart/form-data" method="post">
    <input type="hidden" name="import_type" value="csv_customer_import">
    <header>
      <h2>Import Customer from a CSV file</h2>
      <p>This tool allows you to import your customer to your store from a CSV file.</p>
    </header>
    <section>
      <table class="form-table woocommerce-importer-options">
        <tbody>
          <tr>
            <th scope="row">
              <label for="upload">
                <?php esc_html_e( 'Choose a CSV file from your computer:', 'woocommerce' ); ?>
              </label>
            </th>
            <td>
              <?php
              if ( ! empty( $upload_dir['error'] ) ) {
                ?>
                <div class="inline error">
                  <p><?php esc_html_e( 'Before you can upload your import file, you will need to fix the following error:', 'woocommerce' ); ?></p>
                  <p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
                </div>
                <?php
              } else {
                ?>
                <input type="file" id="upload" name="import" size="25" />
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
                <br>
                <small>
                  <?php
                  printf(
                    /* translators: %s: maximum upload size */
                    esc_html__( 'Maximum size: %s', 'woocommerce' ),
                    esc_html( $size )
                  );
                  ?>
                </small>
                <?php
              }
              ?>
            </td>
          </tr>

          <tr class="woocommerce-importer-advanced hidden">
            <th>
              <label for="woocommerce-importer-file-url"><?php esc_html_e( 'Alternatively, enter the path to a CSV file on your server:', 'woocommerce' ); ?></label>
            </th>
            <td>
              <label for="woocommerce-importer-file-url" class="woocommerce-importer-file-url-field-wrapper">
                <code><?php echo esc_html( ABSPATH ) . ' '; ?></code><input type="text" id="woocommerce-importer-file-url" name="file_url" />
              </label>
            </td>
          </tr>
          <tr class="woocommerce-importer-advanced hidden">
            <th><label><?php esc_html_e( 'CSV Delimiter', 'woocommerce' ); ?></label><br/></th>
            <td><input type="text" name="delimiter" placeholder="," size="2" /></td>
          </tr>

          <tr class="woocommerce-importer-advanced hidden">
            <th><label><?php esc_html_e( 'Use previous column mapping preferences?', 'woocommerce' ); ?></label><br/></th>
            <td><input type="checkbox" id="woocommerce-importer-map-preferences" name="map_preferences" value="1" /></td>
          </tr>
        </tbody>
      </table>
    </section>
    <div class="wc-actions">

      <button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Submit', 'woocommerce' ); ?>" name="save_step"><?php esc_html_e( 'Submit', 'woocommerce' ); ?></button>
      <img id="csvloading" src="<?php echo home_url(); ?>/wp-admin/images/spinner.gif" style="vertical-align: -webkit-baseline-middle;">
      <?php wp_nonce_field( 'woocommerce-csv-importer' ); ?>
      <span id="va_reports" class="hidden">
        Inserted : <span id="va_inserted">0</span>
        Updated : <span id="va_updated">0</span>
        Skipped : <span id="va_skipped">0</span>
        Failed : <span id="va_failed">0</span>
      </span>

    </div>
    <div class="progress va-importer-progress">
      <span class="va-progress" style="vertical-align: middle;"></span>
    </div>

  </form>
  <div id="va_import_log" class="va-import-log hidden">

  </div>


</div>
