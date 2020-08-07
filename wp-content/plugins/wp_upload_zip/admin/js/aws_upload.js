/*
 * jQuery AWS Upload JS
 */

/* global $, window */

$(function () {
    'use strict';

    $('#wpbody-content').on('click','.aws_upload', function(){
        var _this   = $(this);
        var _url    = $(this).data("url");
        var _title  = $(this).data("title");
        var _parent = $(this).closest('tr').attr('data-tr');
        

        if( _url != ''){
            _this.text('Loading...');
            _this.attr("disabled", true);

            jQuery.ajax({
                url: admin_upload.ajax_url,
                type: 'POST',
                data: {action: 'aws_upload_function',_url:_url,_plugin_dir:admin_upload.plugin_dir,_title:_title},
                datType:'json',
                beforeSend: function(){

                },
                success: function(response) {
                    _this.removeClass('btn-primary');
                    _this.addClass('btn-success');
                    //console.log('Success');
                    var $start_pos  = 0;
                    run_image_aws_import($start_pos,response,_parent,_title);
                },
                complete: function() {
                    
                    _this.html('<i class="glyphicon glyphicon-ok-circle"></i> <span>Please wait...</span>');
                    _this.attr("disabled", false);
                }
            });

        }
       
        return false;
    });

        /* Upload image on AWS Script */
    function run_image_aws_import($pos,fileurl='',$parent_id,_url){
        jQuery.ajax({
            url: admin_upload.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'aws_image_import',
                startpos: $pos,
                file_url : fileurl,
                _plugin_dir:admin_upload.plugin_dir,
                _parent : $parent_id,
                _zip_file_url: _url,
            },
            success: function( response ) {
                console.log(response);
                if ( response.success ) {
                    var newpos = response.data.pos;
                    var fileurl = response.data.file_path;
                    var message = response.data.message;
                    var parent_id = response.data._parent;
                    var _zip_file_url = response.data._zip_file_url;

                    if(newpos == 'done'){
                        jQuery('.import_message').hide();
                        jQuery('.tr_'+$parent_id+' .va-importer-progress').css('width', response.data.percentage+'%' );
                        jQuery('.tr_'+$parent_id+' .import_progress').prepend(message +'</br>' );
                        jQuery('.va-progress').html( 'Done' );

                        jQuery('.tr_'+$parent_id).find('.aws_upload').html('<i class="glyphicon glyphicon-ok-circle"></i> <span>Done</span>');
                    }else{
                        //jQuery('.import_message').hide();
                        jQuery('.import_progress').show();
                        jQuery('.tr_'+$parent_id+' .va-importer-progress').css('width', response.data.percentage+'%' );
                        jQuery('.tr_'+$parent_id+' .va-progress').html( response.data.percentage+'%' );
                        jQuery('.import_progress').prepend(message +'</br>' );
                        run_image_aws_import(newpos,fileurl,$parent_id,_zip_file_url);
                    }
                }else{
                    alert(response.data.message);
                }
            }   
        });

    } /* End */

    $('#wpbody-content').on('click','.image_scrapper', function(){
        var _this   = $(this);
        var _url    = $(this).data("url");
        var _title  = $(this).data("title");
        var _parent = $(this).closest('tr').attr('data-tr');
       // console.log(_parent);
        //return false;
        _this.attr("disabled", true);
        
        jQuery.ajax({
            url: admin_upload.ajax_url,
            type: 'POST',
            data: {action: 'product_image_scrapper',_url:_url,_plugin_dir:admin_upload.plugin_dir,_title:_title},
            datType:'json',
            beforeSend: function(){

            },
            success: function(response) {
                //alert('Success');
                
                var response = jQuery.parseJSON(response);
                if(response.error){
                    alert('Product not found !');
                }else{
                    var $start_pos  = 0;
                    run_image_scrapper_aws_import($start_pos,response,_parent);
                    
                }
                
            },
            complete: function() {
                 _this.attr("disabled", false);
                 //_this.cloest('.va-importer-progress').show();
                //console.log('Complete');
                //location.reload();
            }
        });
        
        return false;
    });

            /* Upload image on AWS Script */
    function run_image_scrapper_aws_import($pos,fileurl='',$parent_id){
        jQuery.ajax({
            url: admin_upload.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'product_image_scrapper_aws_meta',
                startpos: $pos,
                file_url : fileurl,
                _plugin_dir:admin_upload.plugin_dir,
                _parent : $parent_id,
            },
            success: function( response ) {
                console.log(response);
                if ( response.success ) {
                    var newpos = response.data.pos;
                    var fileurl = response.data.file_path;
                    var message = response.data.message;
                    var parent_id = response.data._parent;

                    if(newpos == 'done'){
                        jQuery('.import_message').hide();
                        jQuery('.tr_'+$parent_id+' .va-importer-progress').css('width', response.data.percentage+'%' );
                        //jQuery('.tr_'+$parent_id+' .import_progress').prepend(message +'</br>' );
                        jQuery('.va-progress').html( 'Done' );

                        jQuery('.tr_'+$parent_id).find('.aws_upload').html('<i class="glyphicon glyphicon-ok-circle"></i> <span>Done</span>');
                    }else{
                        //jQuery('.import_message').hide();
                        jQuery('.import_progress').show();
                        jQuery('.tr_'+$parent_id+' .va-importer-progress').css('width', response.data.percentage+'%' );
                        jQuery('.tr_'+$parent_id+' .va-progress').html( response.data.percentage+'%' );
                        //jQuery('.import_progress').prepend(message +'</br>' );
                        run_image_scrapper_aws_import(newpos,fileurl,$parent_id);
                    }
                }else{
                    alert(response.data.message);
                }
            }   
        });

    } /* End */

});/* End Main */
