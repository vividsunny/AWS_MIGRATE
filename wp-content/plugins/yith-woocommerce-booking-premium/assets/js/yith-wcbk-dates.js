( function ( root, undefined ) {
    "use strict";

    // Create the local library object, to be exported or referenced globally later
    var lib = {};

    lib.add_days_to_date = function ( date_string, days ) {
        var date = new Date( date_string );
        date.setDate( date.getDate() + days );
        return date;
    };

    lib.date_diff = function ( dateA, dateB, type ) {
        //support for iOS devices
        if ( typeof dateA === 'string' ) {
            dateA = dateA.replace( /-/g, '/' );
        }

        if ( typeof dateB === 'string' ) {
            dateB = dateB.replace( /-/g, '/' );
        }

        dateA        = new Date( dateA );
        dateB        = new Date( dateB );
        type         = ( typeof type !== 'undefined' ) ? type : 'seconds';
        var interval = 0,
            t1, t2;

        switch ( type ) {
            case 'days':
                // subtract timezone offset in milliseconds to retrieve the UTC time: to prevent issues with Daylight Saving Time
                t2 = dateA.getTime() - ( dateA.getTimezoneOffset() * 60 * 1000 );
                t1 = dateB.getTime() - ( dateB.getTimezoneOffset() * 60 * 1000 );

                interval = parseInt( ( t2 - t1 ) / ( 24 * 3600 * 1000 ) );
                break;
            case 'months':
                var d1Y = dateA.getFullYear(),
                    d2Y = dateB.getFullYear(),
                    d1M = dateA.getMonth(),
                    d2M = dateB.getMonth();

                interval = ( d2M + 12 * d2Y ) - ( d1M + 12 * d1Y );
                break;
            default:
                // subtract timezone offset in milliseconds to retrieve the UTC time: to prevent issues with Daylight Saving Time
                t2 = dateA.getTime() - ( dateA.getTimezoneOffset() * 60 * 1000 );
                t1 = dateB.getTime() - ( dateB.getTimezoneOffset() * 60 * 1000 );

                interval = parseInt( ( t2 - t1 ) );
                break;
        }

        return isNaN( interval ) ? 0 : interval;
    };

    // Declare `fx` on the root (global/window) object:
    root[ 'yith_wcbk_dates' ] = lib;

    // Root will be `window` in browser or `global` on the server:
}( this ) );