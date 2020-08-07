<?php 

    /* Add CSS File */
    //wp_enqueue_style("bootstrap.min");
    wp_enqueue_style("invoice_style");

?>


<style type="text/css" media="screen">
    .err_msg{
        border-color: #ff0000ab !important;
        border-width: 1px !important;
    }
    .error_message{
        display: block;
        color: #ff0000ab;
        font-weight: 600;
    }
</style>

<?php 
    //$file_url = $_POST['xml_url'];
    //$file_url = '/nas/content/live/popupcomicshop/wp-content/uploads/2019/09/20585_19032707411612_03-weekly-invoice.txt';

    $blog_id    = get_current_blog_id();
    $bytes      = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
    $size       = size_format( $bytes );

if(isset( $_FILES['import'] ) ){

    // vivid($_FILES);
    // vivid($_POST);
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


    // $update_existing = '';
    // $delimiter = $_POST['delimiter'];
    $invoice_date = $_POST['invoice_date'];

    $save_week_invoice_date = get_option( 'weekly_invoice_import_date');
    if( empty( $save_week_invoice_date ) ){
        $save_week_invoice_date = array();
        $save_week_invoice_date[] = $invoice_date;
        //update_option( 'weekly_invoice_import_date', $save_week_invoice_date );
    }else{
        $save_week_invoice_date[] = $invoice_date;
        //update_option( 'weekly_invoice_import_date', $save_week_invoice_date );
    }
    update_option( 'weekly_invoice_import_date', $save_week_invoice_date );
    if(!$error){
         $file_url = $upload['file'];
        // echo '$file_url--->'.$file_url;
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){

                var start_pos  = 1;
                var url        = '<?php echo $file_url; ?>';
                var delimiter  = '<?php echo $delimiter; ?>';
                var invoice_date  = '<?php echo $invoice_date; ?>';
                console.log('URL--> '+url);
                console.log('invoice_date--> '+invoice_date);

                jQuery('body').find('.notice').css('display','none');
                jQuery('body').find('.updated').css('display','none');
                jQuery('#upload-widgets').css('display','none');
                
                if( url != ''){
                   weekly_invoice_script( start_pos, url, invoice_date );
                }

            });
        </script>
        <?php
     
    }

}
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div id="upload-widgets" class="metabox-holder" style="margin: 5px 15px 2px;">
    <div class="postbox-container-">
        <div class="meta-box-sortables ui-sortable">
            <div id="dashboard_activity" class="postbox">
                <h2 class="hndle ui-sortable-handle">
                    <span>Weekly Invoice Import</span></br>
                    <small>This tool allows you to import weekly invoice.</small>
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

                                        <tr>
                                            <th scope="row">
                                                <label for="invoice_date">
                                                    <?php esc_html_e( 'Invoice Date :', 'woocommerce' ); ?>
                                                </label>
                                            </th>
                                            <td>
                                                <input type="text" name="invoice_date" id="invoice_to_date" required>
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
                                            <th><label><?php esc_html_e( 'Delimiter', 'woocommerce' ); ?></label><br/></th>
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

                                <p><button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Submit', 'woocommerce' ); ?>" name="save_step"><?php esc_html_e( 'Submit', 'woocommerce' ); ?></button>
                                    <img id="csvloading" src="<?php echo home_url(); ?>/wp-admin/images/spinner.gif" style="vertical-align: -webkit-baseline-middle;">
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

<div class="containAll">
    <div class="containLoader">
        <div class="circleGroup circle-1"></div>
        <div class="circleGroup circle-2"></div>
        <div class="circleGroup circle-3"></div>
        <div class="circleGroup circle-4"></div>
        <div class="circleGroup circle-5"></div>
        <div class="circleGroup circle-6"></div>
        <div class="circleGroup circle-7"></div>
        <div class="circleGroup circle-8"></div>
        
        <div class="innerText">
            <p>Loading...</p>
        </div>
    </div>
</div>

<style type="text/css" media="screen">
    .containAll {
      display: flex;
      height: 90vh;
      width: 100%;
      align-items: center;
      justify-content: center;
    }
    .containAll .containLoader {
      position: relative;
      height: 60px;
      width: 60px;
      margin: auto;
    }
    .containAll .containLoader .circleGroup {
      position: absolute;
      height: 100%;
      width: 100%;
    }
    .containAll .containLoader .circle-1 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 30ms;
      animation-delay: 30ms;
    }
    .containAll .containLoader .circle-2 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 60ms;
      animation-delay: 60ms;
    }
    .containAll .containLoader .circle-3 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 90ms;
      animation-delay: 90ms;
    }
    .containAll .containLoader .circle-4 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 120ms;
      animation-delay: 120ms;
    }
    .containAll .containLoader .circle-5 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 150ms;
      animation-delay: 150ms;
    }
    .containAll .containLoader .circle-6 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 180ms;
      animation-delay: 180ms;
    }
    .containAll .containLoader .circle-7 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 210ms;
      animation-delay: 210ms;
    }
    .containAll .containLoader .circle-8 {
      border: 7px solid #0073aa;
      border-radius: 50%;
      box-sizing: border-box;
      border-right: 7px solid transparent;
      border-bottom: 7px solid transparent;
      border-left: 7px solid transparent;
      -webkit-animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      animation: rotate 1.2s cubic-bezier(0, 0.58, 1, 0.3) infinite;
      -webkit-animation-delay: 240ms;
      animation-delay: 240ms;
    }
    .containAll .containLoader .innerText {
      position: absolute;
      font-family: "Quicksand", sans-serif;
      bottom: -40px;
      -webkit-animation: flash 1.2s ease-in-out infinite;
              animation: flash 1.2s ease-in-out infinite;
      pointer-events: none;
    }

    @-webkit-keyframes rotate {
      50% {
        border: 1px solid #0073aa;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
      }
      100% {
        -webkit-transform: rotatez(360deg);
                transform: rotatez(360deg);
      }
    }

    @keyframes rotate {
      50% {
        border: 1px solid #0073aa;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
      }
      100% {
        -webkit-transform: rotatez(360deg);
                transform: rotatez(360deg);
      }
    }
    @-webkit-keyframes flash {
      0% {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
    @keyframes flash {
      0% {
        opacity: 0;
      }
      50% {
        opacity: 1;
      }
      100% {
        opacity: 0;
      }
    }
    
</style>
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
<section class="popup-center">
  <div class="_popup">
    <br/>
    <a class="js-open-modal btn" id="va_popup" href="#" data-modal-id="popup2_qty" style="display: none;"> Pop Up Two</a> 
  </div>
</section>

<div id="popup2_qty" class="modal-box">
  <header> <a href="#" class="js-modal-close close">×</a>
    <h3 class="popup_prod_title">Modal Popup</h3>
  </header>
  <div class="modal-body">
    <form id="update_qty" name="update_qty" method="post" action="">
        <div class="form-group">
            <label for="usr">Qty :</label>
            <input type="text" class="form-control" name="qty_value" id="qty_value">
            <small class="error_message"></small>
            <small>Required Qty : <b class="req_qty"></b></small>
        </div>
        <input type="hidden" name="prod_name" value="" class="prod_name">
        <input type="hidden" name="prod_id" value="" class="prod_id">
        <input type="hidden" name="popup_next_pos" value="" class="popup_next_pos">
        <input type="hidden" name="popup_file_url" value="" class="popup_file_url">
        <input type="hidden" name="required_qty" value="" class="required_qty">
        <input type="hidden" name="popup_invoice_date" value="" class="popup_invoice_date">
        <input type="button" value="Update Qty" class="btn btn-primary import_popup_btn">
    </form>
  </div>
  <!-- <footer> <a href="#"  class="btn btn-small js-modal-close">Close</a> </footer> -->
</div>

<script>

    // jQuery("#invoice_to_date").datepicker({  maxDate: new Date() });
    jQuery("#invoice_to_date").datepicker( { dateFormat: 'mm/dd/yy' } );

jQuery(function(){

var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

    jQuery('a[data-modal-id]').click(function(e) {
        e.preventDefault();
    jQuery("body").append(appendthis);
    jQuery(".modal-overlay").fadeTo(500, 0.7);
    //jQuery(".js-modalbox").fadeIn(500);
        var modalBox = jQuery(this).attr('data-modal-id');
        jQuery('#'+modalBox).fadeIn(jQuery(this).data());
    });  
  
  
jQuery(".js-modal-close, .modal-overlay").click(function() {
    jQuery(".modal-box, .modal-overlay").fadeOut(500, function() {
        jQuery(".modal-overlay").remove();
    });
 
});
 
jQuery(window).resize(function() {
    jQuery(".modal-box").css({
        top: (jQuery(window).height() - jQuery(".modal-box").outerHeight()) / 2,
        left: (jQuery(window).width() - jQuery(".modal-box").outerWidth()) / 2
    });
});
 
jQuery(window).resize();
 
});
</script>

<script type="text/javascript">
    function weekly_invoice_script(pos,fileurl='',invoice_date){
        jQuery.ajax({
            url: '<?php echo admin_url( "admin-ajax.php" );?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'weekly_invoice_import_script',
                startpos: pos,
                file_url : fileurl,
                invoice_date : invoice_date,
            },
            success: function( response ) {
                console.log(response);
                jQuery('.containAll').css('display','none');
                if ( response.success ) {
                    var newpos = response.data.pos;
                    var fileurl = response.data.file_path;
                    var message = response.data.message;
                    var popup = response.data.popup;
                    var invoice_date = response.data.invoice_date;

                    if(newpos == 'done'){
                        jQuery('.import_message').hide();
                        jQuery('.va-importer-progress').css('width', response.data.percentage+'%' );
                        jQuery('.import_response').prepend(message +'</br>' );
                        jQuery('.va-progress').html( 'Done' );
                    }else if(popup == 'true'){
                        jQuery('.prod_name').val(response.data.prod_name);
                        jQuery('.prod_id').val(response.data.prod_id);

                        jQuery('.popup_prod_title').html();
                        jQuery('.popup_prod_title').html(response.data.prod_name);

                        jQuery('.req_qty').html();
                        jQuery('.req_qty').html(response.data.req_qty);
                        jQuery('.required_qty').val(response.data.req_qty);
                        //alert('Bingo');
                        jQuery('.popup_next_pos').val(response.data.pos);
                        jQuery('.popup_file_url').val(response.data.file_path);
                        jQuery('.popup_invoice_date').val(response.data.invoice_date);

                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('#va_popup').trigger('click');
                        
                        setTimeout(function(){
                           //$('.import_popup_btn').trigger('click');
                        },2500);

                    }else{
                        //jQuery('.import_message').hide();
                        jQuery('.import_response').show();
                                // background: -webkit-linear-gradient(left, green, green 0%, black 100%, black);
                        jQuery('.va-importer-progress').css('background', '-webkit-linear-gradient(left, green, green '+response.data.percentage+'%, black 100%, black)' );        
                        jQuery('.va-importer-progress').css('width', '100%' );
                        jQuery('.va-progress').html( response.data.percentage+'%' );
                        jQuery('.import_response').prepend(message +'</br>' );
                        weekly_invoice_script(newpos,fileurl,invoice_date);
                    }
                }else{
                    alert(response.data.message);
                }
            }   
        });

    }

    jQuery('body').on('focus','#qty_value',function(){
        jQuery(this).removeClass('err_msg');
        jQuery('.error_message').text('');
    });

    jQuery('body').on('click','.import_popup_btn',function(){
        var _this = jQuery(this);
        var pos = jQuery('.popup_next_pos').val();
        var fileurl = jQuery('.popup_file_url').val();
        var prod_id = jQuery('.prod_id').val();
        var required_qty = jQuery('.required_qty').val();
        var popup_prod_title = jQuery('.prod_name').val();
        var invoice_date = jQuery('.popup_invoice_date').val();

        var qty_value = jQuery('#qty_value').val();
        console.log(qty_value);
        if(qty_value == ''){
            jQuery('#qty_value').addClass('err_msg');
        }else if( qty_value <= required_qty ){
            jQuery('#qty_value').addClass('err_msg');
            jQuery('.error_message').text('Add Qty grather than '+required_qty);
        }else{
            console.log('Continue Script');

        _this.attr("disabled", true);
        _this.val('Loading...');
            jQuery.ajax({
                url: '<?php echo admin_url( "admin-ajax.php" );?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    action : 'weekly_invoice_import_update_qty',
                    startpos: pos,
                    file_url : fileurl,
                    prod_id : prod_id,
                    popup_prod_title : popup_prod_title,
                    required_qty : required_qty,
                    qty_value : qty_value,
                    invoice_date : invoice_date,
                },
                success: function( response ) {
                   var newpos = response.data.pos;
                   var fileurl = response.data.file_path;
                   var message = response.data.message;
                   _this.val('Done');
                   jQuery('.close').trigger('click');
                    jQuery('.import_response').show();
                    jQuery('.va-importer-progress').css('background', '-webkit-linear-gradient(left, green, green '+response.data.percentage+'%, black 100%, black)' );        
                    jQuery('.va-importer-progress').css('width', '100%' );
                    jQuery('.va-progress').html( response.data.percentage+'%' );
                    jQuery('.import_response').prepend(message +'</br>' );
                    weekly_invoice_script(newpos,fileurl,invoice_date);
                }
            });
        }
    });
</script>
<?php 
    wp_enqueue_script("bootstrap.min");
    //wp_enqueue_script("invoice_upload");
?>