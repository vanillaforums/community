jQuery(document).ready(function($) {
   setTimeout(function() {
      var Location = location.href;
      var StubLength = Location.length;
      var StubExtension = Location.substring(StubLength - 4,StubLength);
      if (StubExtension != '.zip') {
         Location += '.zip';
         document.location.replace(Location);
      }
   }, 1000);
});