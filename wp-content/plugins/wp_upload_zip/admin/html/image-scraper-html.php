<?php 

    /* Add CSS File */
    //wp_enqueue_style("bootstrap.min");
   
?>

<style type="text/css" media="screen">
    .progress.hide {
        opacity: 0;
        transition: opacity 1.3s;
    } 
    .progress {
        /*background: #4CAF50 !important;*/
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

    #csvloading{
        display: none;
    }

    /* New */
    .btn-rounded {
    border-radius: 10em !important;
    }
    .btn-mdb-color {
        background-color: #59698d!important;
        color: #fff !important;
    }
    span {
        cursor: pointer !important;
    }
    .file-field {
        position: relative;
    }
    .file-field input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        bottom: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        cursor: pointer;
        opacity: 0;
    }
</style>

<div id="dashboard-widgets" class="metabox-holder" style="margin: 5px 15px 2px;">
    <div class="postbox-container-">
        <div class="meta-box-sortables ui-sortable">
            <div id="dashboard_right_now" class="postbox">
                <h2 class="hndle ui-sortable-handle">
                    <span>Image Scraper</span>
                </h2>

                <div class="inside">
                    <div class="main">
                        <button type="button" class="btn btn-primary" id="btn_image_scraper">
                            Image Scraper
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="col-md-12">
    <div class="progress va-importer-progress">
        <span class="va-progress" style="vertical-align: middle;"></span>
    </div>
    <div class="import_response" style="display: none;"></div>
</div> -->


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

<!-- HTML -->
<script type="text/javascript">
    jQuery('body').on('click','#btn_image_scraper',function(){
        jQuery.ajax({
            url: '<?php echo admin_url( "admin-ajax.php" );?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'create_aws_image_scraper_file',
            },
            success: function( response ) {
                
                
                var $start_pos  = 0;
                var $url        = response.json_url;

                if( $url != ''){
                    console.log('Bingo');
                    fun_img_scraper_script($start_pos,$url);
                }
            }   
        });
    });

    function fun_img_scraper_script($pos,fileurl=''){
        jQuery.ajax({
            url: '<?php echo admin_url( "admin-ajax.php" );?>',
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'fun_img_scraper_script_ajax',
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
                        jQuery('.va-importer-progress').css('width', '100%' );
                        jQuery('.va-importer-progress').css('background', '-webkit-linear-gradient(left, green, green '+response.data.percentage+'%, black 100%, black)' );

                        jQuery('.va-progress').html( response.data.percentage+'%' );
                        jQuery('.import_response').prepend(message +'</br>' );
                        fun_img_scraper_script(newpos,fileurl);
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