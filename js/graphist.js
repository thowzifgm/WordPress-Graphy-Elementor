"use strict";

var ready = ( callback ) => {
    if ( 'loading' !== document.readyState ) {
        callback();
    } else {
        document.addEventListener( 'DOMContentLoaded', callback );
    }
};