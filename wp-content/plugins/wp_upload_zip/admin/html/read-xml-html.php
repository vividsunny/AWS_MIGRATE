<?php

    /* Add CSS File */
    wp_enqueue_style("bootstrap.min");
?>

<?php
    $file_url = $_POST['xml_url'];
    //$file_url = 'https://demo1.popupcomicshops.com/wp-content/plugins/wp_upload_zip/php/files/201905.xml';
?>

<style type="text/css" media="screen">
    .import_response{
        display: none;
    }
    .progress.hide {
        opacity: 0;
        transition: opacity 1.3s;
    }
    .progress {
        background: #4CAF50 !important;
        display: block;
        height: 20px;
        text-align: center;
        transition: width .3s;
        margin-top: 10px;
        width: 0;
        color: #ffffff;
        font-size: 16px;

    }
    .import_response{
        border: 1px solid #cfcfcf;
        padding: 10px;
        margin-top: 10px;
        max-height: 400px;
        overflow: auto;
        background:lavender;
    }
</style>


<!-- HTML -->
<script type="text/javascript">
    jQuery(document).ready(function(){

        //jQuery("#iod_date").datepicker({  maxDate: new Date() });
        jQuery("#iod_date").datepicker({ dateFormat: 'yy-mm-dd' });

        jQuery('.import_response').hide();
        var $start_pos  = 0;
        //var $url1        = '<?php echo $file_url; ?>';
        //console.log('URL-->'+$url);

        jQuery('#iod_btn').on('click', function(){

            var $url        = '<?php echo $file_url; ?>';
            if( $url != ''){
                console.log('In URL');

                var $iod_date = jQuery('#iod_date').val();
                if( $iod_date != ''){
                    console.log('In import');
                    jQuery('.import_message').text('');
                    jQuery('.import_message').text('Wait we are processing your file...');
                    run_cat_import($start_pos,$url,$iod_date);
                }else{
                    jQuery('.import_message').text('');
                    jQuery('.import_message').text('!Please select IOD date.');
                }

            }else{
                jQuery('.import_message').text('');
                jQuery('.import_message').text('File URL not found. !Please try again.');
                //console.log();
            }
        });



    });

    function run_cat_import($pos,fileurl='',iod_date){
        jQuery.ajax({
            url: '<?php echo admin_url( "admin-ajax.php" );?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'popupcomicshops_product_import',
                startpos: $pos,
                file_url : fileurl,
                iod_date : iod_date,
            },
            success: function( response ) {
                console.log(response);
                if ( response.success ) {
                    var newpos  = response.data.pos;
                    var fileurl = response.data.file_path;
                    var message = response.data.message;
                    var new_iod_date = response.data.iod_date;

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
                        run_cat_import(newpos,fileurl,new_iod_date);
                    }
                }else{
                    alert(response.data.message);
                }
            }
        });

    }
</script>
<div class="container">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <h3>Import Product</h3>

     <div class="iod va-importer-iod">
        <label>IOD Date : <input type="text" name="iod_date" id="iod_date" value=""></label>
        <button class="btn button-primary" value="Start Import" id="iod_btn"> Start Import </button>
    </div>

    <div class="progress va-importer-progress">
        <span class="va-progress" style="vertical-align: middle;"></span>
    </div>
    <div class="import_message">Please select IOD date...</div>
    <div class="import_response"></div>
</div>

<?php
    wp_enqueue_script("bootstrap.min");
?>