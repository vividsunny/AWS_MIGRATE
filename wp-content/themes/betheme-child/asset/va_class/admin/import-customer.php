<?php
$blog_id = get_current_blog_id();
echo "=====>>>".$blog_id;
$bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
$size       = size_format( $bytes );
?>
<style type="text/css" media="screen">
.progress {
  background: red;
  display: block;
  height: 20px;
  text-align: center;
  transition: width .3s;
  width: 0;
}

.progress.hide {
  opacity: 0;
  transition: opacity 1.3s;
}  
#csvloading{
  display: none;
}
</style>
<div class="woocommerce-progress-form-wrapper">
    <form class="wc-progress-form-content va-customer-import" enctype="multipart/form-data" method="post">
      
      <header>
        <h2>Import Customer Subscription from a CSV file</h2>
        <p>This tool allows you to import your customer subscription data to your store from a CSV file.</p>
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
                  <input type="file" id="upload" name="buyseason_file" size="25" />
                  
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
      </div>
    </form>
    <div class="progress"></div>
  </div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		jQuery('.va-customer-import').submit(function(){
			alert('submit');
			return false;
		});
	});
</script>