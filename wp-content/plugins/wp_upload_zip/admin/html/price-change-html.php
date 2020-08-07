<?php 

    /* Add CSS File */
    wp_enqueue_style("bootstrap.min");
    wp_enqueue_style("upload-css");

    //$file_url = $_POST['xml_url'];
    // $file_url = '/nas/content/live/popupcomicshop/wp-content/uploads/2020/03/titlecha.csv';

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
        //echo '<p class="va-processing-file"><b>Wait we are processing your file.</b></p>';
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


    $update_existing = '';
    $delimiter = '';

    if(!$error){
        $file_url = $upload['file'];
        
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){

                var $start_pos  = 1;
                var $url        = '<?php echo $file_url; ?>';
                console.log('URL-->'+$url);

                if( $url != ''){
                    run_price_change_import($start_pos,$url);
                }

            });
        </script>
        <?php
     
    }

}
?>
<div class="col-md-12">

    <div class="woocommerce-progress-form-wrapper">
        <form class="wc-progress-form-content va-customer-import va-importer" enctype="multipart/form-data" method="post">

            <header>
                <h2>Price Change Import</h2>
                <p>This tool allows you to change price ofproduct to your store from a Text file.</p>
            </header>
            <section>
                <table class="form-table woocommerce-importer-options">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="upload">
                                    <?php esc_html_e( 'Choose a CSV file :', 'woocommerce' ); ?>
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
                                    <div class="file-field">
                                        <div class="d-flex justify-content-center">
                                          <div class="btn btn-mdb-color btn-rounded float-left">
                                            <span>Choose file</span>
                                            <input type="file" id="upload" name="import" size="25">
                                        </div>
                                        </div>
                                    </div>
                                    

                                    <!-- <input type="file" id="upload" name="import" size="25" /> -->
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

            </div>
            <div class="progress va-importer-progress">
                <span class="va-progress" style="vertical-align: middle;"></span>
            </div>

        </form>
        <div class="import_response" style="display: none;"></div>
    </div>
</div>



<!-- HTML -->
<script type="text/javascript">

    function run_price_change_import($pos,fileurl=''){
        jQuery.ajax({
            url: '<?php echo admin_url( "admin-ajax.php" );?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'change_product_price_import',
                startpos: $pos,
                file_url : fileurl,
            },
            success: function( response ) {
                console.log(response);
                if ( response.success ) {
                    var newpos = response.data.pos;
                    var fileurl = response.data.file_path;
                    var message = response.data.message;

                    if(newpos == 'done'){
                        jQuery('.import_message').hide();
                        jQuery('.va-importer-progress').css('width', response.data.percentage+'%' );
                        jQuery('.import_response').prepend(message +'</br>' );
                        jQuery('.va-progress').html( 'Done' );
                    }else{
                        //jQuery('.import_message').hide();
                        jQuery('.import_response').show();
                        jQuery('.va-importer-progress').css('width', response.data.percentage+'%' );
                        jQuery('.va-progress').html( response.data.percentage+'%' );
                        jQuery('.import_response').prepend(message +'</br>' );
                        run_price_change_import(newpos,fileurl);
                    }
                }else{
                    alert(response.data.message);
                }
            }   
        });

    }
</script>

<?php 
    wp_enqueue_script("bootstrap.min");
?>