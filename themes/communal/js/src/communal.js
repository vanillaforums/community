// bower:js
//= require ../../bower_components/hoverintent/jquery.hoverIntent.js
//= require ../../bower_components/jquery-icheck/icheck.min.js
// endbower

;(function ($, window, document, undefined) {

  $(function () {

    $('input').iCheck();

    $('.js-sidebar').hoverIntent(
      function (e) {
        $(e.currentTarget).addClass('is-open');
      },
      function (e) {
        $(e.currentTarget).removeClass('is-open');
      }
    )

  });

})(jQuery, window, document);
