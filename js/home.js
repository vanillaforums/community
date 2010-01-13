// This file contains javascript that is specific to the garden/routes controller.
jQuery(document).ready(function($) {
   
   $('div.Splash img').click(function() {
      cycleImage(1);
      return false;
   });
   function cycleImage(math) {
      var images = new Array('discussions','discussion','dashboard','profile','themes','users');
      var count = images.length - 1;
      var img = $('div.Splash img');
      var current = 0;
      for (var i = 0; i < images.length; i++) {
         if ($(img).attr('src').indexOf(images[i]+'.jpg') > 0)
            current = i;
      }
      var next = current + 1;
      if (next > count)
         next = 0;
         
      $(img).attr('src', $(img).attr('src').replace('screen_'+images[current]+'.', 'screen_'+images[next]+'.'));
   }
});