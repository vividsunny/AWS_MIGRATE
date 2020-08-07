
<?php 

	/* Add CSS File */
	wp_enqueue_style("bootstrap.min");
	wp_enqueue_style("upload_style");
	wp_enqueue_style("blueimp-gallery.min");
	wp_enqueue_style("jquery.fileupload");
	wp_enqueue_style("jquery.fileupload-ui");
	//wp_enqueue_style("jquery.fileupload-noscript");
	//wp_enqueue_style("jquery.fileupload-ui-noscript");



?>
<style type="text/css">
    #upload_intro li.list-group-item {
        list-style-type: upper-roman;
        display: list-item;
        padding: 10px 0px;
        margin-bottom: -1px;
        border: none; 
    }
</style>
<!-- HTML -->

<div class="container">
    <h2>File Upload</h2>
    <div class="container">
        <ul class="list-group" id="upload_intro">
            <li class="list-group-item">.zip files directly to server via FTP</li>
            <li class="list-group-item">Click AWS Upload in the following order: 1. Low Res 2. Everything Else 3.</li>
            <li class="list-group-item">Premier Look for the .xml file that will now show after the previous step.</li>
            <li class="list-group-item">Click Import Product &amp; Image Scraper.</li>
        </ul>
    </div>
    
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" action="https://jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
</div>
<?php 

/*$modify_arr = get_option('vvd_last_edit_file');
vivid( $modify_arr );

function get_value_from_key($key){
    $modify_arr = get_option('vvd_last_edit_file');
    return $modify_arr[$key];
}

$value = get_value_from_key('demo.zip');

vivid( $value );*/
    
?>
<!-- END HTML -->
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            {% if (window.innerWidth > 480 || !o.options.loadImageFileTypes.test(file.type)) { %}
                <p class="name">{%=file.name%}</p>
            {% } %}
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade tr_{%=i%}" data-tr={%=i%}>
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            {% if (window.innerWidth > 480 || !file.thumbnailUrl) { %}
                <p class="name">
                    {% if (file.url) { %}
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                    {% } else { %}
                        <span>{%=file.name%}</span>
                    {% } %}
                </p>
            {% } %}
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <input type="checkbox" name="delete" value="1" class="toggle">
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        
            {% if (file.xml) { %}
            <form action="admin.php?page=read_large_xml" method="post" style="display:inline-flex">
              <input type="hidden" name="xml_url" value="{%=file.url%}"><br>
              <input class="btn btn-primary" type="submit" value="Import Product">
          </form>
               

            <button class="btn btn-primary image_scrapper" {% if (file.url) { %}
                data-url="{%=file.url%}" data-title="{%=file.name%}" data-download="{%=file.name%}"
                {% } %} >
                <i class="glyphicon glyphicon-upload"></i>
                <span>Image Scraper</span>
            </button>

            {% } else { %}
            <button class="btn btn-primary aws_upload" {% if (file.url) { %}
                data-url="{%=file.url%}" data-title="{%=file.name%}" data-download="{%=file.name%}"
                {% } %} >
                <i class="glyphicon glyphicon-upload"></i>
                <span>AWS Upload</span>
            </button>
            {% } %}


            <div class="v_progress va-importer-progress">
                <span class="va-progress" style="vertical-align: middle;"></span>
            </div>
            
        </td>
        <td class="last_edit_">
        </td>
    </tr>
{% } %}
</script>
<?php 
    $modify_arr = get_option('vvd_last_edit_file');
    $modify_data = json_encode($modify_arr);
    //vivid($modify_arr);
    //vivid($modify_data);
?>
<script type="text/javascript">
    setTimeout(function(){
        //console.log('Bingo');
        jQuery('#wpbody-content').find('.aws_upload').each( function (index, value) {
            var file = jQuery(this).attr('data-download');
            //console.log(file);
            var edit_time = <?php echo json_encode($modify_data) ?>;
            var modify_data = jQuery.parseJSON(edit_time);
            //console.log(modify_data);
            //console.log(modify_data[file]);
            if( modify_data[file] ){
                var ind = jQuery(this).closest('tr').attr('data-tr');
                //console.log( ind );
                //jQuery('.tr_'+$parent_id+' .import_progress').prepend(message +'</br>' );
                jQuery('.tr_'+ind+' .last_edit_').html('Last edit :'+modify_data[file]);
            }
        });
    },5000);
    
</script>
<div class="import_progress d-none"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" integrity="sha384-xBuQ/xzmlsLoJpyjoggmTEz8OWUFM0/RC5BsqQBDX2v5cMvDHcMakNTNrHIW2I5f" crossorigin="anonymous"></script> 

<?php 
	/* Add js File */
	wp_enqueue_script("tmpl.min");
	wp_enqueue_script("load-image.all.min");
	wp_enqueue_script("canvas-to-blob.min");

	wp_enqueue_script("jquery.ui.widget");
	wp_enqueue_script("bootstrap.min");
	wp_enqueue_script("jquery.blueimp-gallery.min");
	wp_enqueue_script("jquery.iframe-transport");
	wp_enqueue_script("jquery.fileupload");
	wp_enqueue_script("jquery.fileupload-process");
	wp_enqueue_script("jquery.fileupload-image");
	wp_enqueue_script("jquery.fileupload-audio");
	wp_enqueue_script("jquery.fileupload-video");
	wp_enqueue_script("jquery.fileupload-validate");
	wp_enqueue_script("jquery.fileupload-ui");

    wp_enqueue_script("upload_main");
    wp_enqueue_script("aws_upload");
	wp_enqueue_script("aws_import_product");
?>