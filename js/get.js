jQuery(document).ready(function($) {
   var loc = location.href;
   if (loc.substring(0,-1) != '/')
      loc += '/';

   if (loc.substring(0, -5) != '/1/1/') {
      loc += '1';
      document.location = loc;
   }
});