jQuery( function ($) {

    var yith_wc_as3s_url = window.location.href;

    // ===============================================
    // ======= Actions in media library mode grid ========

    // ======= Mode: gird. Adding buttons to the media library mode grid - hidden by default ========

    $(".media-toolbar-secondary").append("<button type='button' data-doaction='Copy_to_S3' class='button media-button button-primary button-large hidden YITH_WC_amazon_s3_storage_button' disabled='disabled'>Copy to S3</button>");

    $(".media-toolbar-secondary").append("<button type='button' data-doaction='Remove_from_S3' class='button media-button button-primary button-large hidden YITH_WC_amazon_s3_storage_button' disabled='disabled'>Remove from S3</button>");

    $(".media-toolbar-secondary").append("<button type='button' data-doaction='Copy_to_server_from_S3' class='button media-button button-primary button-large hidden YITH_WC_amazon_s3_storage_button' disabled='disabled'>Copy to server from S3</button>");

    $(".media-toolbar-secondary").append("<button type='button' data-doaction='Remove_from_server' class='button media-button button-primary button-large hidden YITH_WC_amazon_s3_storage_button' disabled='disabled'>Remove from server</button>");

    // ======= Mode: gird. Activating the buttons ========

    $(".media-toolbar-secondary").on("click", ".select-mode-toggle-button", function () {

        $(".media-toolbar-secondary .YITH_WC_amazon_s3_storage_button").toggle();
        $(".media-toolbar-secondary .YITH_WC_amazon_s3_storage_button").attr("disabled", "disabled");

    });

    // ======= Mode: gird. Deactivating the buttons ========

    $(".media-toolbar-secondary").on("click", ".delete-selected-button", function () {

        if ($(this).hasClass('hidden')) {
            $(".media-toolbar-secondary .YITH_WC_amazon_s3_storage_button").toggle();
            $(".media-toolbar-secondary .YITH_WC_amazon_s3_storage_button").attr("disabled", "disabled");
        }

    });

    // ===============================================

    // ===============================================
    // ======= Uploading to the media library or from products ========

    // ========= Checking - Copy file to S3 ============

    $("body").on("click", "input:checkbox[name=YITH_WC_amazon_s3_storage_copy_file_s3_checkbox]", function () {

        if ( ! $( this ).is( ':checked' ) ) {

            $( 'body input:checkbox[name=YITH_WC_amazon_s3_storage_remove_from_server_checkbox]' ).attr( 'checked', false );
            $( 'body input:radio[name=YITH_WC_amazon_s3_storage_private_public_radio_button]' ).each( function () {
                if ( $( this ).val() == 'public' )
                    $( this ).attr( 'checked', true );
                else
                    $( this ).attr( 'checked', false );
            });
            $( '.YITH_WC_amazon_s3_storage_private_public_html' ).hide();

        }
        else{
            $( 'body input:radio[name=YITH_WC_amazon_s3_storage_private_public_radio_button]' ).each( function () {
                if ($( this ).val() == 'public')
                    $( this ).attr( 'checked', false );
                else
                    $( this ).attr( 'checked', true );
            });
            $( '.YITH_WC_amazon_s3_storage_private_public_html' ).show();
            $( '#YITH_WC_amazon_s3_message_warning_remove_from_server' ).hide();
        }

    });

    // ========= Checking - Remove from the server ============

    $( "body" ).on( "click", "input:checkbox[name=YITH_WC_amazon_s3_storage_remove_from_server_checkbox]", function () {

        if ( $( this ).is( ':checked' ) ){

            if ( ! $( 'body input:checkbox[name=YITH_WC_amazon_s3_storage_copy_file_s3_checkbox]' ).attr( 'checked' ) ){

                $( this ).attr( 'checked', false );
                $( '#YITH_WC_amazon_s3_message_warning_remove_from_server' ).css( "display", "inline-block" );

            }

        }

    });

    // == Close the warning message of the checkbox Remove from the server
    $( "body" ).on( "click", "#YITH_WC_amazon_s3_message_warning_remove_from_server_button", function () {

        $( '#YITH_WC_amazon_s3_message_warning_remove_from_server' ).hide();

    });

    $( "body" ).on( "click", "#YITH_WC_amazon_s3_storage_bad_settings_input_hidden", function () {

        $( ".media-modal-close" ).trigger( "click" );

    });

    // == Function to show the warning in case bad settings
    function Yith_WC_AS3S_checking_settings(){

        // ======= Mode: gird. Individual media, adding a div to load an ajax on it with the actions ========
        $( "#wpwrap" ).append( "<div id='YITH_WC_amazon_s3_storage_bad_settings_ID' class='YITH_WC_amazon_s3_storage_bad_settings hidden'></div>" );
        $( "#wpwrap" ).append( "<input type='hidden' id='YITH_WC_amazon_s3_storage_bad_settings_input_hidden'>" );

        var data = {
            action: 'Yith_wc_as3s_Ajax_Admin_check_settings'
        }
       Yith_WC_AS3S_AjaxGo( data, '#YITH_WC_amazon_s3_storage_bad_settings_ID' );

    }

    // == Function to add the ajax for process bar of actions
    function Yith_WC_AS3S_Create_ajax_load_for_process_bar(){

        // ======= Mode: gird. Individual media, adding a div to load an ajax on it with the actions ========
        $( "#wpwrap" ).append( "<div class='YITH_WC_amazon_s3_storage_button_AJAX hidden'></div>" );

        var data = {
            action: 'Yith_wc_as3s_Ajax_Admin_button_action_mode_grid_loader'
        }
        Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_button_AJAX' );

    }

    // ================ ACTIONS IN MEDIA LIBRARY =======================

    // == Unbinding the envet of WP and modifying to add the process bar
    $( '#doaction, #doaction2' ).off();
    $( '#doaction, #doaction2' ).click( function( event ) {

        $( 'select[name^="action"]' ).each( function() {
            var optionValue = $( this ).val();

            if ( 'attach' === optionValue ) {
                event.preventDefault();
                findPosts.open();
            } else if ( 'delete' === optionValue ) {
                if ( ! showNotice.warn() ) {

                    event.preventDefault();
                }
                else
                    Yith_wc_as3s_Bar_Process_do_action( $( "#bulk-action-selector-top option:selected" ).val() );
            }
            else
                Yith_wc_as3s_Bar_Process_do_action( $( "#bulk-action-selector-top option:selected" ).val() );
        });

    });

    // == Media library, click in one of the individual action
    // == Over writing the code of common.js to add the process bar in case of deleting
    showNotice = {
        warn : function() {
            var msg = commonL10n.warnDelete || '';
            if ( confirm(msg) ) {
                Yith_wc_as3s_Bar_Process_do_action( 'delete' );
                return true;
            }

            return false;
        },

        note : function(text) {
            alert(text);
        }
    };

    // == Mode: List. Click in one of the individual actions
    $( 'body' ).on( 'click', '.row-actions span', function() {

        Yith_wc_as3s_Bar_Process_do_action( $( this ).attr( 'class' ) );

    });

    // == Getting the number of items when selecting
    var items_selected;
    $( "body" ).on( "click", "#__attachments-view-42 li", function () {

        items_selected = 0;
        $( 'body #__attachments-view-42 li' ).each( function () {

            if ( $( this ).hasClass( 'selected' ) )
                items_selected++;

        });

    });

    // == Function to close the process bar of deleting checking the number of li elements
    function Yith_wc_as3s_deleting_done_mode_grid( total_items ){

        if ( $( 'body #__attachments-view-42 li' ).length <= total_items )
            $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).hide();
        else
            setTimeout( function(){
                Yith_wc_as3s_deleting_done_mode_grid( total_items );
            }, 1000 );

    }

    // == Media library mode grid, click on the delete button
    $( ".media-toolbar-secondary" ).on( "click", '.delete-selected-button', function () {

        var total_items = $( 'body #__attachments-view-42 li' ).length - items_selected;

        var items_selected_now = 0;
        $( 'body #__attachments-view-42 li' ).each( function () {

            if ( $( this ).hasClass( 'selected' ) )
                items_selected_now++;

        });

        if ( items_selected_now == 0 ){

            Yith_wc_as3s_Bar_Process_do_action( 'delete' );

            setTimeout( function(){
                Yith_wc_as3s_deleting_done_mode_grid( total_items );
            }, 1000 );

        }

    });


    // == Function to check if the number of elements are different to hide the process bar
    function Yith_wc_as3s_deleting_done_attachment_details( init_elements ){

        if ( init_elements != $( 'body #__attachments-view-42 li' ).length )
            $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).hide();
        else
            setTimeout( function(){
                Yith_wc_as3s_deleting_done_attachment_details( init_elements );
            }, 1000 );

    }

    // == Showing the process bar in delete permanently of mode grid attachment details
    // == After creating a new button to delete we show the process bar when the attachment details is close
    $( 'body' ).on( 'click', '.media-modal-content .actions .yith_wc_as3s_deleting_button', function () {

        var init_elements = $( 'body #__attachments-view-42 li' ).length;

        $( "body .media-modal-content .actions .delete-attachment" ).trigger( 'click' );

        if ( ! $( 'body .media-modal' ).hasClass( 'media-modal' ) ){

            Yith_wc_as3s_Bar_Process_do_action( 'delete' );
            setTimeout( function(){
                Yith_wc_as3s_deleting_done_attachment_details( init_elements );
            }, 1000 );

        }

    });

    // == Function to close the process bar of uploading files
    function Yith_wc_as3s_uploading_done( mode ){

        var items_in_progress = 0;

        if ( mode == 'list' )
            $( 'body #media-items > div' ).each( function () {

                if ( $( this ).find( 'div' ).hasClass( 'progress' ) )
                    items_in_progress++;

            });
        else
            $( 'body #__attachments-view-42 li' ).each( function () {

                if ( $( this ).hasClass( 'uploading' ) )
                    items_in_progress++;

            });

        if ( items_in_progress > 0 )
            setTimeout( function(){
                Yith_wc_as3s_uploading_done( mode );
            }, 1000 );
        else
            $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).hide();

    }

    /*== Checking if we are in media library ==*/
    if ( ( yith_wc_as3s_url.indexOf( 'media-new.php' ) != -1 ) || ( yith_wc_as3s_url.indexOf( 'upload.php' ) != -1 ) ) {

        // == Showing the actions of attachment details and information about S3
        function Yith_WC_AS3S_Add_doactions_to_Attachment_details_mode_grid() {

            var yith_wc_as3s_url = window.location.href;

            if ( yith_wc_as3s_url.indexOf( 'upload.php?item' ) != -1) {
                var res = yith_wc_as3s_url.split("=");
                if ( $( "body .attachment-info div" ).hasClass( "actions" ) ) {

                    // == Adding informatin about S3 in case it is in S3
                    $(".media-modal-content .details").append("<div class='YITH_WC_amazon_s3_storage_ajax_container_s3_details'></div>");

                    var data = {
                        action: 'Yith_wc_as3s_ajax_admin_show_s3_details',
                        post_id: $( ".media-modal-content .attachment-details" ).data( 'id' ),
                    }

                    var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'> <p> <strong>Searching for details</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>";

                    Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_ajax_container_s3_details', beforesend );

                    // == Adding the actions
                    $( ".media-modal-content" ).append( "<div class='YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail hidden'></div>" );

                    var data = {
                        action: 'Yith_wc_as3s_Ajax_Admin_button_action_mode_grid_loader'
                    }
                   Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail' );

                    // == We hide the delere button and create our own one to be able to manage the deleting process
                    $( "body .attachment-info .actions" ).append( "<span class='yith_wc_as3s_span_doactions_attachment_details_mode_grid'></span>" );

                    $( "body .media-modal-content .actions .delete-attachment" ).hide();

                    $( "body .media-modal-content .actions .delete-attachment" ).after( "<button type='button' class='button-link yith_wc_as3s_deleting_button'>Delete Permanently</button>" );

                    var data = {
                        action : 'Yith_wc_as3s_Ajax_Admin_Add_doactions_to_Attachment_details_mode_grid',
                        post_id: res[1],
                    }
                    Yith_WC_AS3S_AjaxGo( data, '.yith_wc_as3s_span_doactions_attachment_details_mode_grid' );

                    $( "body .attachment-info .actions .yith_wc_as3s_span_doactions_attachment_details_mode_grid" ).on( "click", "a", function () {

                        Yith_wc_as3s_Bar_Process_do_action( $( this ).attr( 'class' ) );

                    });
                }
                else
                    setTimeout( Yith_WC_AS3S_Add_doactions_to_Attachment_details_mode_grid, 300 );

            }

        }

        // == Checking Settings
        Yith_WC_AS3S_checking_settings();

        // == Calling the function to add actions of attachment details and information about S3
        Yith_WC_AS3S_Add_doactions_to_Attachment_details_mode_grid();

        // == Calling the function to add actions of attachment details and information about S3
        // == when clicking in the buttons previous and forward
        $( "body" ).on( "click", ".edit-media-header button", function () {

            Yith_WC_AS3S_Add_doactions_to_Attachment_details_mode_grid();

        });

        // == Calling the function to add actions of attachment details and information about S3
        // == when clicking in a media element
        $( "#__attachments-view-42" ).on( "click", "li", function () {

            Yith_WC_AS3S_Add_doactions_to_Attachment_details_mode_grid();

        });

        // == Adding the process bar ajax load


        //Yith_WC_AS3S_Create_ajax_load_for_process_bar();

        function Yith_WC_AS3S_Adding_Files_media_library(){

            /*== We set the sessions of the product before uploading the file ==*/
            Yith_WC_AS3S_setting_sessions('media');

            // == Charging the bar process of the action
            //Yith_wc_as3s_Bar_Process_do_action( 'Uploading_File' );

            /*== We launch the ajax to check in a variable session if the file was uploaded ==*/
            var data = {
                action: 'YITH_WC_amazon_s3_storage_input_hidden_uploading_file'
            }

            setTimeout( function(){
              //  Yith_WC_AS3S_AjaxGo( data, '#YITH_WC_amazon_s3_storage_span_action_files' );
            }, 1000 );

        }

        if ( ( yith_wc_as3s_url.indexOf( 'media-new.php' ) != -1 ) )
            var items_ulploaded = $( 'body #media-items > div' ).length;

        $( 'body' ).on( 'change', 'input:file', function () {

            // == If we are not uploading with the old html
            if ( ! $( 'body .media-upload-form' ).hasClass( 'html-uploader' ) )
                Yith_WC_AS3S_Adding_Files_media_library();

        });

        // == When click on the button of the old html to upload files
        $( 'body' ).on( 'click', '#html-upload', function () {

            Yith_WC_AS3S_Adding_Files_media_library();

        });

        $( 'body' ).on( 'drop', function(){

            Yith_WC_AS3S_Adding_Files_media_library();

        });

        $( 'body' ).on( 'click', '#YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Uploaded', function () {

            // == Checking if the file or files are already uploaded in mode list
            if ( ( yith_wc_as3s_url.indexOf( 'media-new.php' ) != -1 ) )
                setTimeout( function(){
                    Yith_wc_as3s_uploading_done( 'list' );
                }, 1000 );

            // == Checking if the file or files are already uploaded in mode grid
            if ( ( yith_wc_as3s_url.indexOf( 'upload.php' ) != -1 ) )
                setTimeout( function(){
                    Yith_wc_as3s_uploading_done( 'grid' );
                }, 1000 );

        });

        $( 'body' ).on( 'click', '#YITH_WC_amazon_s3_storage_input_hidden_uploading_file_Searching', function () {

            // == Checking if the file or files are already uploaded in mode list
            if ( ( yith_wc_as3s_url.indexOf( 'media-new.php' ) != -1 ) ){

                var data = {
                    action: 'YITH_WC_amazon_s3_storage_input_hidden_uploading_file'
                }
                var Found = false;

                $( 'body #media-items > div' ).each( function () {

                    if ( $( this ).find( 'div' ).hasClass( 'error' ) )
                        Found = true;

                });

                if ( Found ) // == If we find an error class we check with javascript
                    setTimeout( function(){
                        Yith_wc_as3s_uploading_done( 'list' );
                    }, 1000 );
                else // == We launch again the ajax to check in a variable session if the file was uploaded
                    setTimeout( function(){
                      //  Yith_WC_AS3S_AjaxGo( data, '#YITH_WC_amazon_s3_storage_span_action_files' );
                    }, 1000 );

            }

            // == Checking if the file or files are already uploaded in mode grid
            if ( ( yith_wc_as3s_url.indexOf( 'upload.php' ) != -1 ) ){

                var data = {
                    action: 'YITH_WC_amazon_s3_storage_input_hidden_uploading_file'
                }

                var Found = false;

                if ( $( 'body .media-uploader-status' ).hasClass( 'errors' ) )
                    Found = true;

                if ( Found ) // == If we find an error class we check with javascript
                    setTimeout( function(){
                        Yith_wc_as3s_uploading_done( 'grid' );
                    }, 1000 );
                else // == We launch again the ajax to check in a variable session if the file was uploaded
                    setTimeout( function(){
                      //  Yith_WC_AS3S_AjaxGo( data, '#YITH_WC_amazon_s3_storage_span_action_files' );
                    }, 1000 );

            }

        });

    }

    // ========================================================

    // ========= Setting back sessions to the default value  ============
    $("body").on("click", ".media-menu-item", function () {

        if ( $( this ).text() == 'Upload Files' ){
            var data = {
                action : 'YITH_WC_amazon_s3_storage_setting_back_sessions'
            }
            //Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_dev_null' );
        }

    });

    // ========= Setting sessions to the default value when uploading a file from anywhere ============

    function Yith_WC_AS3S_setting_sessions(rute=''){

        if ( $( 'body input:checkbox[name=YITH_WC_amazon_s3_storage_copy_file_s3_checkbox]' ).attr( 'checked' ) )
            var copy_file_s3 = 'on';
        else
            var copy_file_s3 = 'off';

        var private_public = 'private';
        $( 'body input:radio[name=YITH_WC_amazon_s3_storage_private_public_radio_button]' ).each( function () {

            if ( $(this).is( ':checked' ) )
                private_public = $( this ).val();

        });

        if ( $( 'body input:checkbox[name=YITH_WC_amazon_s3_storage_remove_from_server_checkbox]' ).attr( 'checked' ) )
            var remove_from_server = 'on';
        else
            var remove_from_server = 'off';

        var data = {
            action : 'YITH_WC_amazon_s3_storage_setting_sessions',
            copy_file_s3 : copy_file_s3,
            private_public : private_public,
            remove_from_server : remove_from_server,
            rute : rute
        }

        var yith_wc_as3s_url = window.location.href;

        if ( ( yith_wc_as3s_url.indexOf( 'post.php' ) != -1 ) )
            data['type'] = 'product';
        else
            data['type'] = 'media';

        //Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_dev_null' );

    }

    // ===============================================

    // =============== WooCommerce post page ===================
    // ========= Checking the downloadable products ============

    function Yith_WC_AS3S_activate_Amazon_S3_tab() {

        $( ".media-menu-item" ).removeClass( "active" );
        $( ".yith_wc_as3s_activate_S3_file_manager" ).addClass( "active" );

        $( ".media-toolbar-primary .media-button-select" ).hide();

        // == Adding the new div container for ajax result in case is not already added ==
        if ( ! $( "#YITH_WC_amazon_s3_storage_Container_S3_file_manager_ID" ).hasClass( 'YITH_WC_amazon_s3_storage_Container_S3_file_manager' ) )
            $( ".media-frame-content" ).append( "<div id='YITH_WC_amazon_s3_storage_Container_S3_file_manager_ID' class='YITH_WC_amazon_s3_storage_Container_S3_file_manager'></div>" );

        // == Adding the new button to insert files in case is not already added ==
        if ( ! $( "#YITH_WC_amazon_s3_storage_Inser_File_ID" ).hasClass( 'YITH_WC_amazon_s3_storage_Insert_File' ) )
            $( ".media-toolbar-primary" ).append( "<a type='button' id='YITH_WC_amazon_s3_storage_Inser_File_ID' class='button media-button button-primary button-large YITH_WC_amazon_s3_storage_Insert_File'>Insert file url</a>" );

        $( ".media-toolbar-primary .YITH_WC_amazon_s3_storage_Insert_File" ).show();


        $( ".YITH_WC_amazon_s3_storage_Container_S3_file_manager" ).show();

    }

    /*== Checking if we are in a order and we replace all of the url downloads of amazon with the right urls ==*/
    if ( ( yith_wc_as3s_url.indexOf( 'post.php?post' ) != -1 ) && ( yith_wc_as3s_url.indexOf( 'action=edit' ) != -1 ) && ( $( "#woocommerce-order-downloads .wc-metaboxes .wc-metabox" ).length != 0 ) ){

        var res = yith_wc_as3s_url.split( "?post=" );
        res = res[1].split( "&action" );
        var order_id = res[0];

        $( "#woocommerce-order-downloads .wc-metaboxes" ).after( '<div id="yith_wc_amazon_s3_storage_result_ajax_download_admin_orders"></div>' );

        $( "#woocommerce-order-downloads .wc-metaboxes .wc-metabox" ).each( function ( index ) {
            $( this ).find( 'strong' ).attr( "id", "yith_wc_amazon_s3_storage_admin_orders_strong_" + index );
            $( this ).find( '.wc-metabox-content a' ).attr( "id", "yith_wc_amazon_s3_storage_admin_orders_a_" + index );
        });

        var data = {
            action: 'yith_wc_amazon_s3_storage_result_ajax_show_downloads_url_of_admin_order',
            order_id: order_id,
        }

       // Yith_WC_AS3S_AjaxGo( data, '#yith_wc_amazon_s3_storage_result_ajax_download_admin_orders' );

    }

    /*== Checking if we are in admin product page ==*/
    if ( ( ( yith_wc_as3s_url.indexOf( 'post.php?post' ) != -1 ) && ( yith_wc_as3s_url.indexOf( 'action=edit' ) != -1 ) ) || ( yith_wc_as3s_url.indexOf( 'post-new.php?post_type=product' ) != -1 ) ) {

        // == This event is triggered when the ajax function find in the session that the file was uploaded ==
        $( 'body' ).on( 'click', '#YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded', function () {

            if ( global_remove_from_server_checkbox ){

                // == Removing and hiding the html of the file uploaded in the media library ==
                $( "body ul.attachments" ).find( "li" ).first().remove();
                $( "body div.attachment-details" ).hide();

            }

            // == Opening the Amazon S3 tab ==
            Yith_WC_AS3S_activate_Amazon_S3_tab();

            $( "#__wp-uploader-id-2" ).show();

            $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).hide();

            // == Open the file manager with the file just uploaded with the path to the file on S3 ==
            var data = {
                action: 'Yith_wc_as3s_Ajax_Admin_S3_File_Manager',
                S3_Path_To_File: $( 'body #YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded' ).val(),
            }

            var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'> <p> <strong>SEARCHING FOR FILES IN AMAZON S3 STORAGE</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

           // Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_Container_S3_file_manager', beforesend );

        });

        // == This event is triggered when the ajax function doesn't find in the session that the file was uploaded ==
        $( 'body' ).on( 'click', '#YITH_WC_amazon_s3_storage_input_hidden_searching_path_file_to_uploaded', function () {

            /*== We launch again the ajax to check in a variable session if the file was uploaded and the path to open the amazon S3 tab ==*/
            var data = {
                action: 'YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded'
            }

            setTimeout( function(){
               // Yith_WC_AS3S_AjaxGo( data, '#YITH_WC_amazon_s3_storage_ajax_hidden_path_file_uploaded' );
            }, 1000 );

        });

        var global_remove_from_server_checkbox;
        function Yith_wc_as3s_Adding_Files_from_products(){

            if ( document.querySelector( '.yith_wc_as3s_activate_S3_file_manager' ) !== null ) {

                // == Setting this global variable to check later if we remove the attachment from the ul li of media
                global_remove_from_server_checkbox = $('body input:checkbox[name=YITH_WC_amazon_s3_storage_remove_from_server_checkbox]').attr('checked');

                // == We set the sessions of the product before uploading the file ==
                Yith_WC_AS3S_setting_sessions('products');

                if ($('body input:checkbox[name=YITH_WC_amazon_s3_storage_copy_file_s3_checkbox]').attr('checked')) {

                    Yith_wc_as3s_Bar_Process_do_action('Uploading_File');

                    //$("#__wp-uploader-id-2").hide();

                    // == We launch the ajax to check in a variable session if the file was uploaded and the path to open the amazon S3 tab ==
                    var data = {
                        action: 'YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded'
                    }

                   // Yith_WC_AS3S_AjaxGo(data, '#YITH_WC_amazon_s3_storage_ajax_hidden_path_file_uploaded');

                }

            }

        }

        $( 'body' ).on( 'drop', function(){

            Yith_wc_as3s_Adding_Files_from_products();

        });


        /*== Adding the event change for the input file uploaded. This event is triggered when the file is selected ==*/
        $( 'body' ).on( 'change', 'input:file', function () {

            Yith_wc_as3s_Adding_Files_from_products();

        });

        // ================== INDIVIDUAL ACTIONS  ========================

        $("body").on( "click", "#YITH_WC_amazon_s3_storage_input_hidden_message_of_action", function () {

            $( "body #YITH_WC_amazon_s3_storage_paragraph_message_of_action" ).text( $( "body #YITH_WC_amazon_s3_storage_input_hidden_message_of_action" ).val() );
            $( ".media-sidebar .attachment-details .notice-success" ).show();

        });

        $("body").on( "click", ".media-sidebar .attachment-details button", function () {

            $( ".media-sidebar .attachment-details .notice-success" ).hide();

        });

        // == Function to add action links for individual media
        function YITH_WC_amazon_s3_storage_individual_actions_media_from_products( post_id ){

            $( ".media-sidebar .details .delete-attachment" ).after( "<span class='YITH_WC_amazon_s3_storage_Container_doactions_individual'></span>" );
            $( ".media-sidebar .attachment-details h2" ).before( "<div class='notice notice-success is-dismissible hidden yith_wc_as3s_copied_to_s3'><p id='YITH_WC_amazon_s3_storage_paragraph_message_of_action'></p><button type='button' class='notice-dismiss' data-who='yith_wc_as3s_copied_to_s3'><span class='screen-reader-text'>Dismiss this notice.</span></button></div>" );
            $( ".media-sidebar .attachment-details h2" ).before( "<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_message_of_action'>" );

            var data = {
                action: 'Yith_wc_as3s_Ajax_Admin_show_individual_actions',
                post_id: post_id,
            }

            var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'><p><strong>Searching for details</strong></p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

            //Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_Container_doactions_individual', beforesend );

        }

        // == Adding action links for individual media
        $( "body" ).on( "click", ".media-frame-content .attachments li", function () {

            YITH_WC_amazon_s3_storage_individual_actions_media_from_products( $( this ).data( 'id' ) );

        });

        $( "body" ).on( "click", ".media-sidebar .details .YITH_WC_amazon_s3_storage_Container_doactions_individual a", function ( event ) {

            event.preventDefault();
            //Yith_wc_as3s_Bar_Process_do_action( $( this ).attr( 'class' ) );

            var doaction = $( this ).attr( 'class' );
            var data = {
                action: 'Yith_wc_as3s_Ajax_Admin_do_individual_actions',
                post_id: $( this ).data( 'post_id' ),
                doaction: doaction,
            }

            switch ( doaction ) {
                case 'Copy_to_S3':
                    var doaction_String = 'Copying to S3';
                    break;
                case 'Remove_from_S3':
                    var doaction_String = 'Removing from S3';
                    break;
                case 'Copy_to_server_from_S3':
                    var doaction_String = 'Copying to server from S3';
                    break;
                case 'Remove_from_server':
                    var doaction_String = 'Removing from server';
                    break;
                case 'Uploading_File':
                    var doaction_String = 'Uploading';
                    break;
                case 'delete':
                    var doaction_String = 'Deleting permanently';
                    break;
            }

            var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_individual_action'> <p> <strong>" + doaction_String + "</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

           // Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_Container_doactions_individual', beforesend );

        });

        // ===============================================================

        /*var upload_file_button_clicked = false;

        var input_file_url;
        var input_file_name;

        $( "body" ).on( "click", ".upload_file_button", function () {
            // == Checking Settings
            Yith_WC_AS3S_checking_settings();

            // == Adding the process bar ajax load
            if ( ! upload_file_button_clicked )
                Yith_WC_AS3S_Create_ajax_load_for_process_bar();

            input_file_url = $( this ).parent().prev().find( "input" );
            input_file_name = $( this ).parent().prev().prev().find( "input" );

            var yith_wc_as3s_fill_the_inputs = function () {

                var Name = $( 'input:radio[name=S3_File]:checked ').val();
                var Key = $( 'input:radio[name=S3_File]:checked ').data( 'key' );

                $( ".media-modal-close" ).trigger( "click" );

                var shortcode = '[yith_wc_amazon_s3_storage key="' + Key + '" name="' + Name + '"]';
                $( input_file_url ).val( shortcode );

            }

            if ( ! upload_file_button_clicked )
                $( ".media-toolbar-primary" ).on( "click", '#YITH_WC_amazon_s3_storage_Inser_File_ID', yith_wc_as3s_fill_the_inputs );

            if ( ! $( ".media-router a" ).hasClass( 'yith_wc_as3s_activate_S3_file_manager' ) ){

                $( ".media-router" ).append( "<a href='#' class='media-menu-item yith_wc_as3s_activate_S3_file_manager'>Amazon S3</a>" );
                $( ".media-router" ).append( "<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded'>" );
                $( ".media-router" ).append( "<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_searching_path_file_to_uploaded'>" );
                $( ".media-router" ).append( "<span id='YITH_WC_amazon_s3_storage_ajax_hidden_path_file_uploaded'></span>" );

            }

            if ( ! upload_file_button_clicked )
                $( ".media-menu-item" ).on( "click", function () {

                    if ( $( this ).hasClass( 'yith_wc_as3s_activate_S3_file_manager' ) ) {

                        Yith_WC_AS3S_activate_Amazon_S3_tab();

                        var data = {
                            action: 'Yith_wc_as3s_Ajax_Admin_S3_File_Manager'
                        }

                        var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'> <p> <strong>SEARCHING FOR FILES IN AMAZON S3 STORAGE</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

                        Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_Container_S3_file_manager', beforesend );

                    }
                    else {

                        $( this ).addClass( "active" );
                        $( ".yith_wc_as3s_activate_S3_file_manager" ).removeClass( "active" );
                        $( ".YITH_WC_amazon_s3_storage_Container_S3_file_manager" ).hide();
                        $( ".media-toolbar-primary .media-button-select" ).show();
                        $( ".media-toolbar-primary .YITH_WC_amazon_s3_storage_Insert_File" ).hide();

                    }

                });


            upload_file_button_clicked = true;

        });*/

    }

    //ADD amazon s3 section on featured image section

    /**
     * TODO Check this function and try to apply the upload to product section
     * **/

   $( "#set-post-thumbnail" ).click( function (e) {
        //Yith_WC_AS3S_checking_settings();

        if ( ! $( ".media-router a" ).hasClass( 'yith_wc_as3s_activate_S3_file_manager' ) ) {

            var checkExist = setInterval(function() {
                if ($('.media-router').length) {
                    clearInterval(checkExist);
                    $(".media-router").append("<a href='#' class='media-menu-item yith_wc_as3s_activate_S3_file_manager yith_wcamz_post_thumbnail'></a>");
                    $(".media-router").append("<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_path_file_to_uploaded'>");
                    $(".media-router").append("<input type='hidden' value='none' id='YITH_WC_amazon_s3_storage_input_hidden_searching_path_file_to_uploaded'>");
                    $(".media-router").append("<span id='YITH_WC_amazon_s3_storage_ajax_hidden_path_file_uploaded'></span>");


                    $(".media-menu-item").on("click", function () {
                        // Amazon S3 tab clicked
                        if ($(this).hasClass('yith_wc_as3s_activate_S3_file_manager')) {
                            Yith_WC_AS3S_activate_Amazon_S3_tab();

                            var data = {
                                action: 'Yith_wc_as3s_Ajax_Admin_S3_File_Manager'
                            }

                            var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'> <p> <strong>SEARCHING FOR FILES IN AMAZON S3 STORAGE</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

                         //   Yith_WC_AS3S_AjaxGo(data, '.YITH_WC_amazon_s3_storage_Container_S3_file_manager', beforesend);

                        }
                         //'Upload files' or 'Media Library' tab clicked
                        else {

                            $(this).addClass("active");
                            $(".yith_wc_as3s_activate_S3_file_manager").removeClass("active");
                            $(".YITH_WC_amazon_s3_storage_Container_S3_file_manager").hide();
                            $(".media-toolbar-primary .media-button-select").show();
                            $(".media-toolbar-primary .YITH_WC_amazon_s3_storage_Insert_File").hide();

                        }

                    });
                }
            }, 100);
        }
    });
        // ==========================================================

    // == This function shows up the process bar of the action executing
    function Yith_wc_as3s_Bar_Process_do_action( doaction ) {

        switch ( doaction ) {
            case 'Copy_to_S3':
                var doaction_String = 'Copying to S3';
                $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
                $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();
                break;
            case 'Remove_from_S3':
                var doaction_String = 'Removing from S3';
                $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
                $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();
                break;
            case 'Copy_to_server_from_S3':
                var doaction_String = 'Copying to server from S3';
                $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
                $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();
                break;
            case 'Remove_from_server':
                var doaction_String = 'Removing from server';
                $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
                $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();
                break;
            case 'Uploading_File':
                var doaction_String = 'Uploading';
                $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
                $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();
                break;
            case 'delete':
                var doaction_String = 'Deleting permanently';
                $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
                $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();
                break;
        }

        var data = {
            action  : 'Yith_wc_as3s_Ajax_Admin_wc_add_notice',
            doaction: doaction,
        }
        Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_dev_null' );

        var top = $( window ).scrollTop() + 200;

        $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX .YITH_WC_amazon_s3_storage_sub_button_AJAX" ).css( 'margin-top', top + 'px' );
        $( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX .YITH_WC_amazon_s3_storage_sub_button_AJAX h1" ).text( doaction_String );

        $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail .YITH_WC_amazon_s3_storage_sub_button_AJAX" ).css( 'margin-top', '100px' );
        $( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail .YITH_WC_amazon_s3_storage_sub_button_AJAX h1" ).text( doaction_String );

    }

    $( ".media-frame-content" ).on( "click", "ul li", function (event) {

        var total_selected = 0;
        $( ".media-frame-content ul li" ).each( function () {
            if ( $( this ).attr( "aria-checked" ) == 'true' )
                total_selected = total_selected + 1;
        });
        if ( total_selected != 0 )
            $( ".media-toolbar-secondary .YITH_WC_amazon_s3_storage_button" ).removeAttr( "disabled" );
        else
            $( ".media-toolbar-secondary .YITH_WC_amazon_s3_storage_button" ).attr( "disabled", "disabled" );

    });


    // == When click in the file manager folder
    $( "body" ).on( "click", "#Yith_WC_as3s_Show_Keys_of_a_Folder_Bucket_Result_ID a", function ( event ) {

        event.preventDefault();

        var Region = $( this ).data( 'region' );
        var Current_folder = $( this ).data( 'current_folder' );

        var data = {
            action        : 'Yith_wc_as3s_Ajax_Admin_Show_Keys_of_a_Folder_Bucket',
            Region        : Region,
            Current_folder: Current_folder,
        }

        var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'> <p> <strong>SEARCHING FOR FILES IN AMAZON S3 STORAGE</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

       Yith_WC_AS3S_AjaxGo( data, 'body #Yith_WC_as3s_Show_Keys_of_a_Folder_Bucket_Result_ID', beforesend);


    });

    $( "body" ).on( "click", ".nav-tab", function ( event ) {

        if ( $( this ).text() == 'Settings' ){

            $( '#yith_wc_as3s_process_bar_to_go_settings_bar' ).toggle();
            $( '#yith_wc_as3s_main_div' ).toggle();

        }

    });

    $( "body" ).on( "click", ".button-primary", function ( event ) {

        if ( $( '#YITH_WC_amazon_s3_storage_Checking_Credentials_ID' ).length) {

            $( '#YITH_WC_amazon_s3_storage_Checking_Credentials_ID' ).toggle();
            $( '#YITH_WC_amazon_s3_storage_connection_status' ).toggle();

        }

    });


    function Yith_WC_AS3S_AjaxGo( data, result, beforesendhtml) {

        $.ajax({
            data      : data,
            url       : yith_wc_amazong_s3_storage_object.ajax_url,
            type      : 'post',
            beforeSend: function () {
                if ( beforesendhtml != null )
                    $( result ).html( beforesendhtml );
            },
            error     : function ( response ) {
                console.log( 'ERROR - Yith_WC_AS3S_AjaxGo' );
                console.log( response );
            },
            success   : function ( response ) {
                $( result ).html( response );
            }
        });
    }

    $( ".YITH_WC_Amazon_S3_Storage_Uploading_File" ).submit( function ( event ) {

        var array_option = $( ".Yith_WC_as3s_Buckets_List_select" ).val().split( "_yith_wc_as3s_separator_" );

        var Bucket = array_option[0];
        var Region = array_option[1];

        $( this ).find( 'input[name="YITH_WC_Bucket_To_Upload"]' ).val( Bucket );
        $( this ).find( 'input[name="YITH_WC_Region_To_Upload"]' ).val( Region );

    });

    $( ".YITH_WC_amazon_s3_storage_admin_parent_wrap .Yith_WC_as3s_Buckets_List_select" ).select2({
        placeholder: "Choose a bucket"
    });


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




    //Add buttons in grid mode when you click on each attachment

    $("body").on( "click", '.attachments-browser li',function() {

        Yith_WC_AS3S_Add_doactions_to_Attachment_details_mode_grid();

    });


    // Add file when you click on downloadable section on product page

    var input_file_url;
    var input_file_name;

    $( "body" ).on( "click", ".upload_file_button", function () {

        input_file_url = $( this ).parent().prev().find( "input" );
        input_file_name = $( this ).parent().prev().prev().find( "input" );

        if ( ! $( ".media-router a" ).hasClass( 'yith_wc_as3s_activate_S3_file_manager' ) ){

            $( ".media-router" ).append( "<a href='#' class='media-menu-item yith_wc_as3s_activate_S3_file_manager'>Amazon S3</a>" );

        } else if(  $( ".media-router a" ).hasClass( 'yith_wcamz_post_thumbnail' ) ) {

            $('.yith_wcamz_post_thumbnail').remove();
            $( ".media-router" ).append( "<a href='#' class='media-menu-item yith_wc_as3s_activate_S3_file_manager'>Amazon S3</a>" );
        }

        //This code allow us to activate the file manager button if s3tab is activated by default
        if ( $('.yith_wc_as3s_activate_S3_file_manager').hasClass('active')  ) {

            $( ".media-toolbar-primary .media-button-select" ).hide();
            $( ".media-toolbar-primary .YITH_WC_amazon_s3_storage_Insert_File" ).show();

        }

        $( ".media-menu-item" ).on( "click", function () {

            if ( $( this ).hasClass( 'yith_wc_as3s_activate_S3_file_manager' ) ) {


                Yith_WC_AS3S_activate_Amazon_S3_tab();

                var data = {
                    action: 'Yith_wc_as3s_Ajax_Admin_S3_File_Manager'
                }

                var beforesend = "<div class='YTIH_WC_amazon_s3_storage_ajax_loading_S3_files'> <p> <strong>SEARCHING FOR FILES IN AMAZON S3 STORAGE</strong> </p> <p> <img class='Ajax_Loader' src='" + yith_wc_amazong_s3_storage_object.ajax_loader + "' alt='cerrar'> </p> </div>"

                Yith_WC_AS3S_AjaxGo( data, '.YITH_WC_amazon_s3_storage_Container_S3_file_manager', beforesend );

            }
            else {
                $( this ).addClass( "active" );
                $( ".yith_wc_as3s_activate_S3_file_manager" ).removeClass( "active" );
                $( ".YITH_WC_amazon_s3_storage_Container_S3_file_manager" ).hide();
                $( ".media-toolbar-primary .media-button-select" ).show();
                $( ".media-toolbar-primary .YITH_WC_amazon_s3_storage_Insert_File" ).hide();
            }

        });


        var yith_wc_as3s_fill_the_inputs = function () {


            var Name = $( 'input:radio[name=S3_File]:checked ').val();
            var Key = $( 'input:radio[name=S3_File]:checked ').data( 'key' );

            $( ".media-modal-close" ).trigger( "click" );


            if (typeof Name !== 'undefined') {

                var shortcode = '[yith_wc_amazon_s3_storage key="' + Key + '" name="' + Name + '"]';
                $(input_file_url).val(shortcode);
                $(input_file_url).change();

                //Show again the media button select after add the shortcode.
                $( ".media-toolbar-primary .YITH_WC_amazon_s3_storage_Insert_File" ).hide();
                $( ".media-toolbar-primary .media-button-select" ).show();

            }

        }

        $( ".media-toolbar-primary" ).on( "click", '#YITH_WC_amazon_s3_storage_Inser_File_ID', yith_wc_as3s_fill_the_inputs );


    });

    //Remove tab amazon and content when you click on set product image and tab

    $( "#set-post-thumbnail" ).click( function (e) {


        $(".yith_wc_as3s_activate_S3_file_manager").each(function( index ) {
            $(this).remove();
        });
        $( ".YITH_WC_amazon_s3_storage_Container_S3_file_manager" ).each(function( index ) {
            $(this).remove();
        });

        //remove Insert file url button
        $("#YITH_WC_amazon_s3_storage_Inser_File_ID").each(function( index ) {

            $(this).remove();

        });
    });

    /*$( "body" ).on( "click", '.media-modal-close', function () {

        $(".yith_wc_as3s_activate_S3_file_manager").each(function( index ) {
            $(this).remove();
        });
        $( ".YITH_WC_amazon_s3_storage_Container_S3_file_manager" ).each(function( index ) {
            $(this).remove();
        });


        //remove Insert file url button
        $("#YITH_WC_amazon_s3_storage_Inser_File_ID").each(function( index ) {

            $(this).remove();

        });

    } );*/

    // == Media library mode grid, click on any of the action buttons
    $( ".media-toolbar-secondary" ).on( "click", ".YITH_WC_amazon_s3_storage_button", function () {

        var doaction = $( this ).data( "doaction" );

        //Yith_wc_as3s_Bar_Process_do_action( doaction );
        yith_wcas3_add_progress_bar(doaction);

        var post_ids = new Array();
        $( ".media-frame-content ul li" ).each( function () {
            if ( $( this ).attr( "aria-checked" ) == 'true' )
                post_ids.push( $( this ).data( "id" ) );
        });
        var data = {
            action  : 'Yith_wc_as3s_Ajax_Admin_button_action_mode_grid',
            doaction: doaction,
            post_ids: post_ids,
        }

        ajax_upload_data(data,'.YITH_WC_amazon_s3_storage_button_AJAX',doaction,'reload');

    });



});

var checkbox_private_public;
//Private public button change on media library
jQuery("body").on("change", "input:radio[name=YITH_WC_amazon_s3_storage_private_public_radio_button]", function () {
    jQuery( 'body input:radio[name=YITH_WC_amazon_s3_storage_private_public_radio_button]' ).attr('checked',false);
    jQuery( this ).attr( 'checked', true );
    checkbox_private_public = jQuery( this ).val();
});

//Upload directly file to media library without use sessions. It'll work on media library section
var yith_wcamz_data_added;
var items_to_upload = 0;

if (typeof( wp.Uploader ) != 'undefined') {


    jQuery.extend(wp.Uploader.prototype, {

        success: function ($e) {

            console.log('callback success');
            yith_wcamz_data_added['file_id'] = $e.id;

            upload_to_amazon_s3(yith_wcamz_data_added);


        },
        added: function () {
            console.log('callback added');

            var copy = jQuery('body input:checkbox[name=YITH_WC_amazon_s3_storage_copy_file_s3_checkbox]'),
                remove = jQuery('body input:checkbox[name=YITH_WC_amazon_s3_storage_remove_from_server_checkbox]');

            items_to_upload++;

            if (items_to_upload == 1 && copy.is(':checked')) {

                yith_wcas3_add_progress_bar();
            }


            if (checkbox_private_public) {

                private_public = checkbox_private_public;

            } else {
                private_public = jQuery('body input:radio[name=YITH_WC_amazon_s3_storage_private_public_radio_button]:checked').val();
            }


            yith_wcamz_data_added = {
                action: 'wccr_upload_data_to_s3',
                copy_file_s3: copy.is(':checked'),
                private_public: private_public,
                remove_from_server: remove.is(':checked'),
            }
        }

    });
}

function ajax_upload_data( data, result, beforesendhtml, sucess) {
    jQuery.ajax({
        data      : data,
        url       : yith_wc_amazong_s3_storage_object.ajax_url,
        type      : 'post',
        beforeSend: function () {
            if ( beforesendhtml != null ) {
                yith_wc_as3s_bar_process(beforesendhtml);
            }
        },
        error     : function ( response ) {
            console.log( 'ERROR - Yith_WC_AS3S_AjaxGo' );
            console.log( response );
        },
        success   : function ( response ) {

            jQuery( result ).html( response );

            if ( sucess == 'sucess' ) {
                items_to_upload--
                finnish_upload_to_amazon_s3();
            }

            if ( sucess == 'reload' ) {
                location.reload();
            }

        }
    });
}



//Add the progress bar
function yith_wcas3_add_progress_bar($type=''){

    // ======= Mode: gird. Individual media, adding a div to load an ajax on it with the actions ========
    jQuery( "#wpwrap" ).append( "<div class='YITH_WC_amazon_s3_storage_button_AJAX hidden'></div>" );

    if(typeof $type !== "undefined") {
        $type = 'Uploading_File';
    }

    var data = {
        action:'add_progress_bar_loader',
        type: $type,
        top: jQuery( window ).scrollTop() + 200,
    }
    ajax_upload_data( data, '.YITH_WC_amazon_s3_storage_button_AJAX' );

}


//Show Progress Bar
function yith_wc_as3s_bar_process( doaction ) {

        jQuery( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).show();
        jQuery( ".media-modal-content .YITH_WC_amazon_s3_storage_button_AJAX_attachment_detail" ).show();

}

//Remove the progress ba
function hide_upload_bar_process( mode ){

    jQuery( "#wpwrap .YITH_WC_amazon_s3_storage_button_AJAX" ).hide();
}

function upload_to_amazon_s3( $data ) {

    ajax_upload_data( yith_wcamz_data_added, '#YITH_WC_amazon_s3_storage_dev_null','Uploading_File','sucess' );

}

function finnish_upload_to_amazon_s3() {

    if (items_to_upload <= 0 ) {

        if( typeof wp.media.frame.content.get().options.selection != 'undefined' ) {

            wp.media.frame.content.get().options.selection.reset();
            wp.media.frame.content.get().collection.props.set({ignore: (+new Date())});
        }
        hide_upload_bar_process();

    }
}

/*mOxie.plupload.bind('FileUploaded',function ($file,$object) {
    console.log('bindend');
    console.log(file);
});*/

/*jQuery.extend(plupload.Uploader.prototype, {

    onFileUploaded: function() {
        console.log("This is file uploaded");
    }


});*/

/*uploader.bind( 'FileUploaded', function( up, file, response ) {
    console.log(up);
    //uploadSuccess( file, response.response );
});*/

