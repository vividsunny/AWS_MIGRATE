/*
 * jQuery AWS Upload JS
 */

/* global $, window */

jQuery(function () {
    'use strict';

    function weekly_invoice_script($pos,fileurl='',delimiter=','){
        jQuery.ajax({
            url: admin_upload.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action : 'weekly_invoice_import_script',
                startpos: $pos,
                file_url : fileurl,
                delimiter : delimiter,
            },
            success: function( response ) {
                console.log(response);
                if ( response.success ) {
                    var newpos = response.data.pos;
                    var fileurl = response.data.file_path;
                    var message = response.data.message;
                    var delimiter = response.data.delimiter;

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
                        weekly_invoice_script(newpos,fileurl,delimiter);
                    }
                }else{
                    alert(response.data.message);
                }
            }   
        });

    }


});
