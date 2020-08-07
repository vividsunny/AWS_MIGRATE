/*
 * jQuery AWS Product Import Upload JS
 */

/* global $, window */

$(function () {
    'use strict';

    $('#wpbody-content').on('click','.import_xml_product', function(){
        var _this       = $(this);
        var _url        = $(this).data("url");
        var _file_name  = $(this).data("title");
        
        jQuery.ajax({
            url: admin_upload.ajax_url,
            type: 'POST',
            data: {action: 'xml_product_import_function',_url:_url,_plugin_dir:admin_upload.plugin_dir,_file_name:_file_name},
            datType:'json',
            beforeSend: function(){

            },
            success: function(response) {
                console.log('Success');
            },
            complete: function() {
                alert('Complete');
            }
        });
        
        return false;
    });

});
