jQuery(function(){

  var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");

  jQuery('.view_child').click(function(e) {
    e.preventDefault();
    var $this = jQuery(this);
    var order_id = jQuery(this).attr('data-id');
    console.log(order_id);


    jQuery("body").append(appendthis);
    jQuery(".modal-overlay").fadeTo(500, 0.7);
    var modalBox = $this.attr('data-modal-id');
    jQuery('#'+modalBox).fadeIn($this.data());
    jQuery('#'+modalBox).find('.modal-body').html('');
    jQuery('#'+modalBox).find('.modal-body').html('Loading...');
    
    if(order_id != ''){
      var ajaxurl = va_tamberra.ajax_url;
      jQuery.post(
        ajaxurl, 
        {
          'action'  : 'get_parent_child_ajax',
          'order_id'     :   order_id,
        }, 
        function(response){
          // console.log(response);

          // jQuery("body").append(appendthis);
          // jQuery(".modal-overlay").fadeTo(500, 0.7);
          // var modalBox = $this.attr('data-modal-id');
          // console.log(modalBox);
          jQuery('#tamberra_popup').find('.modal-body').html('');
          jQuery('#tamberra_popup').find('.modal-body').html(response);
          // jQuery('#'+modalBox).fadeIn($this.data());
        }
        );
    }else{

    }

  });  


  /* All Active Series */
    jQuery('.view_product').click(function(e) {
    e.preventDefault();
    var $this = jQuery(this);
    var order_id = jQuery(this).attr('data-id');
    console.log(order_id);

    if(order_id != ''){
      var ajaxurl = va_tamberra.ajax_url;
      jQuery.post(
        ajaxurl, 
        {
          'action'  : 'get_active_series_child_ajax',
          'order_id'     :   order_id,
        }, 
        function(response){
          console.log(response);

          jQuery("body").append(appendthis);
          jQuery(".modal-overlay").fadeTo(500, 0.7);
          var modalBox = $this.attr('data-modal-id');
          console.log(modalBox);
          jQuery('#tamberra_popup').find('.modal-body').html('');
          jQuery('#tamberra_popup').find('.modal-body').html(response);
          jQuery('#'+modalBox).fadeIn($this.data());
        }
        );
    }else{

    }

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