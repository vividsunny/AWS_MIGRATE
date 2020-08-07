

<div id="popupcomic-upload" class="metabox-holder">
    <div class="postbox-container-">
        <div class="meta-box-sortables ui-sortable">
            <div id="dashboard_activity" class="postbox">
                <h2 class="hndle ui-sortable-handle">
                    <span>Popup Product Upload</span></br>
                    <small>This tool allows you to upload the product in master table.</small>
                </h2>
                
                <div class="inside">
                    <div class="col-md-12">
                        <div class="woocommerce-progress-form-wrapper">
                            <form class="wc-progress-form-content va-customer-import va-importer" enctype="multipart/form-data" method="post">
                                <section>
                                    <table class="form-table woocommerce-importer-options">
                                        <tbody>
                                            <tr>
                                                <th scope="row">
                                                    <label for="upload">
                                                        <?php esc_html_e( 'Choose a file :', 'woocommerce' ); ?>
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
                                                                <span>Choose file</span>
                                                                <input type="file" id="upload" name="import" size="25">
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
                                        
                                    </tbody>
                                </table>
                            </section>
                            <div class="wc-actions">

                                <p><button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Submit', 'woocommerce' ); ?>" name="save_step"><?php esc_html_e( 'Submit', 'woocommerce' ); ?></button>
                                    <img id="csvloading" src="<?php echo home_url(); ?>/wp-admin/images/spinner.gif" class="hidden" style="vertical-align: -webkit-baseline-middle;">
                                    <?php wp_nonce_field( 'woocommerce-csv-importer' ); ?></p>

                                </div>
                                

                            </form>
                            
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Import Progress -->
<div id="import-widgets" class="metabox-holder" style="margin: 5px 15px 2px;">
    <div class="postbox-container-">
        <div class="meta-box-sortables ui-sortable">
            <div class="progress va-importer-progress">
                <span class="va-progress" style="vertical-align: middle;"></span>
            </div>
            <div class="import_response" style="display: none;"></div>
        </div>
    </div>
</div>