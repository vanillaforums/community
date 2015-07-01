jQuery(document).ready(function($) {

    $('textarea.TextBox').livequery(function() {
        $(this).autogrow();
    });

});