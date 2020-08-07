jQuery(document).ready(function($) {

    $('.time_widget_product').each(function(index){

        var timer_widget;
        var product = parseInt($(this).data('product-id'));
        var date_now = Date.now()/1000;
        var date_finnish = parseInt($(this).data('finish-time-'+product+''));
        var result_widget = date_finnish - date_now;

        timer_widget = setInterval(function() {
            timeBetweenDatesWidget(result_widget,product);
            result_widget--
        }, 1000);
    });
    
    function timeBetweenDatesWidget(result,product) {
        if (result <= 0) {

            // Timer done

            clearInterval(timer_widget);
            //window.location.reload(true);

        } else {

            var seconds = Math.floor(result);
            var minutes = Math.floor(seconds / 60);
            var hours = Math.floor(minutes / 60);
            var days = Math.floor(hours / 24);

            hours %= 24;
            minutes %= 60;
            seconds %= 60;

            $( 'span[id="days_widget_'+product+'"]' ).text(days);
            $( 'span[id="hours_widget_'+product+'"]' ).text(hours);
            $( 'span[id="minutes_widget_'+product+'"]' ).text(minutes);
            $( 'span[id="seconds_widget_'+product+'"]' ).text(seconds);

        }
    }

});