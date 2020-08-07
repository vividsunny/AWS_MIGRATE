
jQuery(document).ready(function($) {
  var timer;



    //Datetimeformat in product auction
    var utcSeconds =  parseInt($('#timer_auction').data('finish'));
    var d = new Date(0); // The 0 there is the key, which sets the date to the epoch
    d.setUTCSeconds(utcSeconds);
    string = format_date(d);

    $("#dateend").text(string);

    $(document.body).on('yith_wcact_timer',function () {
        var result = parseInt($('#timer_auction').data('remaining-time'));
        //console.log(result);
        //Timeleft
        timer = setInterval(function() {
            timeBetweenDates(result);
            result--
        }, 1000);
    });

    $(document.body).trigger('yith_wcact_timer');


  function timeBetweenDates(result) {
      //console.log(result);
    if (result <= 0) {

      // Timer done

      clearInterval(timer);
      window.location.reload(true);
    
    } else {
      
      var seconds = Math.floor(result);
      var minutes = Math.floor(seconds / 60);
      var hours = Math.floor(minutes / 60);
      var days = Math.floor(hours / 24);

      hours %= 24;
      minutes %= 60;
      seconds %= 60;

      $("#days").text(days);
      $("#hours").text(hours);
      $("#minutes").text(minutes);
      $("#seconds").text(seconds);
    }
  }

  //Button up or down bid
    var current = $('#time').data('current');
    $(".bid").click(function(e){
        e.preventDefault();
        var actual_bid = $('#_actual_bid').val();
        if($(this).hasClass("button_bid_add")){
            if(!actual_bid){
                actual_bid = current;
            }
            if ( actual_bid === '' ) {
                actual_bid = 0;
            }
            actual_bid = parseInt( actual_bid ) + parseInt(date_params.actual_bid_add_value);
            $('#_actual_bid').val(actual_bid);
        } else {
            if(actual_bid){
                actual_bid = parseInt( actual_bid ) - parseInt(date_params.actual_bid_add_value);;
                if (actual_bid >= current){
                    $('#_actual_bid').val(actual_bid);
                }else{
                    $('#_actual_bid').val(current);
                }
            }
        }
    });

//Button bid
//
    $( document ).off( 'click', '.auction_bid' ).on( 'click', '.auction_bid', function( e ) {
    //var target = $( e.target ); // this code get the target of the click -->  $('.bid')
        $('#yith-wcact-form-bid').block({message:null, overlayCSS:{background:"#fff",opacity:.6}});
        var form = $( this ).closest( '.cart' );
        var post_data = {
              'bid': form.find( '#_actual_bid').val(),
              'product' : form.find('#time').data('product'),
              'currency': $('#yith_wcact_currency').val(),
              security: object.add_bid,
              action: 'yith_wcact_add_bid'
          };

        $.ajax({
               type    : "POST",
               data    : post_data,
               url     : object.ajaxurl,
               success : function ( response ) {
                    //console.log(response.url);
                   $('#yith-wcact-form-bid').unblock();
                     window.location = response.url;

                     //window.location.reload(true);
                   // On Success
               },
               complete: function () {
               }
            });
  } );

    //Disable enter in input
    $("#_actual_bid").keydown(function( event ) {
        if ( event.which == 13 ) {
            event.preventDefault();
        }
    });
    //Change the datetime format to locale
    $( '.yith_auction_datetime' ).each( function ( index ) {
        var current_date     = change_datetime_format($(this).text());
        $( this ).text( current_date );
    } );

    //Live auctions on product page
    if ( object.live_auction_product_page  > 0 ) {
        setInterval(live_auctions,object.live_auction_product_page);
        function live_auctions(){
            live_auctions_template();
        }

        function live_auctions_template() {
            $('#tab-yith-wcact-bid-tab').block({message:null, overlayCSS:{background:"#fff",opacity:.6}});

            var post_data = {
                //security: object.search_post_nonce,
                product: $(':hidden#yith-wcact-product-id').val(),
                currency: $('#yith_wcact_currency').val(),
                action: 'yith_wcact_update_list_bids'
            };

            $.ajax({
                type    : "POST",
                data    : post_data,
                url     : object.ajaxurl,
                success : function ( response ) {

                    $('.yith-wcact-table-bids').empty();
                    $('.yith-wcact-table-bids').html( response['list_bids'] );
                    //Change the datetime format to locale
                    $( '.yith_auction_datetime' ).each( function ( index ) {
                        var current_date     = change_datetime_format($(this).text());
                        $( this ).text( current_date );
                    } );
                    $('#tab-yith-wcact-bid-tab').unblock();
                    $('p.price span:first-child').html( response['current_bid'] );
                    $('#yith-wcact-max-bidder').empty();
                    $('#yith-wcact-max-bidder').html( response['max_bid'] );
                    $('#yith_wcact_reserve_and_overtime').empty();
                    $('#yith_wcact_manuel_bid_increment').empty();
                    $('#yith_wcact_reserve_and_overtime').html( response['reserve_price_and_overtime'] );
                    if( 'timeleft' in response ) {
                        $('#yith-wcact-auction-timeleft').empty();
                        clearInterval(timer);
                        $('#yith-wcact-auction-timeleft').html(response['timeleft']);
                        var utcSeconds =  parseInt($('#timer_auction').data('finish'));
                        var d = new Date(0); // The 0 there is the key, which sets the date to the epoch
                        d.setUTCSeconds(utcSeconds);
                        string = format_date(d);
                        $('#dateend').text(string);

                        $(document.body).trigger('yith_wcact_timer');
                    }
                },
                complete: function () {
                }
            });
        }
    }

    function format_date( date ) {
        var dateFormat    = date_params.format,
            formattedDate = dateFormat,
            day           = date.getDate(),
            fullDay       = ('0' + day).slice( -2 ),
            month         = date.getMonth() + 1,
            fullMonth     = ('0' + month).slice( -2 ),
            year          = date.getYear(),
            fullYear      = date.getFullYear(),
            hours         = date.getHours(),
            hours12       = hours % 12,
            meridiem      = hours < 12 ? 'am' : 'pm',
            meridiemUp    = hours < 12 ? 'AM' : 'PM',
            fullHours     = ('0' + hours).slice( -2 ),
            fullHours12   = ('0' + hours12).slice( -2 ),
            minutes       = date.getMinutes(),
            fullMinutes   = ('0' + minutes).slice( -2 ),
            seconds       = date.getSeconds(),
            fullSeconds   = ('0' + seconds).slice( -2 );
        formattedDate =  formattedDate.replace( /d|j|n|m|M|F|Y|y|h|H|i|s|a|A|G|g|/g, function(x){
            var toReturn = x;
            switch(x){
                case 'd':
                    toReturn = fullDay;
                    break;
                case 'j':
                    toReturn = day;
                    break;

                case 'n':
                    toReturn = month;
                    break;
                case 'm':
                    toReturn = fullMonth;
                    break;
                case 'M':
                    toReturn = date_params.month_abbrev[ date_params.month[ fullMonth ] ];
                    break;
                case 'F':
                    toReturn = date_params.month[ fullMonth ];
                    break;

                case 'Y':
                    toReturn = fullYear;
                    break;
                case 'y':
                    toReturn = year;
                    break;
                case 'h':
                    if( '00' == fullHours12 ) {
                        fullHours12 = '12';
                    }
                    toReturn = fullHours12;
                    break;
                case 'H':
                    toReturn = fullHours;
                    break;

                case 'i':
                    toReturn = fullMinutes;
                    break;
                case 's':
                    toReturn = fullSeconds;
                    break;

                case 'a':
                    toReturn = date_params.meridiem[ meridiem ];
                    break;
                case 'A':
                    toReturn = date_params.meridiem[ meridiemUp ];
                    break;
                case 'g':
                    if( 0 == hours12 ) {
                        hours12 = 12;
                    }
                    toReturn = hours12;
                    break;
                case 'G':
                    toReturn = hours;
                    break;

            }
            return toReturn;
        } );
        return formattedDate;
    }

    function change_datetime_format( time ) {
        var datetime = time;
        datetime     = datetime + ' UTC';
        datetime     = datetime.replace( /-/g, '/' );

        var current_date = new Date( datetime );

        return format_date( current_date );
    }

    //time format on related section on product page
    $( document ).on( 'yith_infs_added_elem', function () {
        $( '.date_auction' ).each( function ( index ) {

            var timer;
            var product = parseInt( $( this ).data( 'yith-product' ) );

            var utcSeconds     = parseInt( $( this ).data( 'yith-auction-time' ) );
            var b              = new Date();
            c                  = b.getTime() / 1000;
            var date_remaining = utcSeconds - c;

            //Pass Utc seconds to localTime
            var d = new Date( 0 ); // The 0 there is the key, which sets the date to the epoch
            d.setUTCSeconds( utcSeconds );
            string = format_date( d );
            $( this ).text( string );

        } );
    } );
    $( document ).trigger( 'yith_infs_added_elem' );
});
