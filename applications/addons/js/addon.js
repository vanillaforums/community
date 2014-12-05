jQuery(document).ready(function($) {

	$("a[rel^='popable']").fancyZoom({closeOnClick: true});

   // Hide comment deletes and hijack their clicks to confirm
   $('a.DeleteComment').popup({
      confirm: true,
      followConfirm: false,
      afterConfirm: function(json, sender) {
      var row = $(sender).parents('li:first');
         $(row).slideUp('fast', function() {
            $(row).remove();
         });
      }
   });
   
   // Reveal comment deletes on hover
   $('li.Comment').livequery(function() {
      $(this).find('a.DeleteComment').hide();
      $(this).hover(function() {
         $(this).find('a.DeleteComment').show();
      }, function() {
         $(this).find('a.DeleteComment').hide();
      });
   });

   // hijack addon deletes
   $('li.DeleteAddon a, li.ApproveAddon a').popup({
      confirm: true,
      followConfirm: true
   });

   // Set up paging
   if ($.morepager)
      $('.MorePager').morepager({
         pageContainerSelector: '#Discussion:last'
      });

});