jQuery(document).ready(function($) {
    var loc = location.href;
    if (loc.substring(0,-1) != '/')
        loc += '/';

    loc += '1';
    document.location = loc;
});