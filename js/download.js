jQuery(document).ready(function($) {
   
   // Hide/reveal the signup options when the CreateAccount checkbox is un/checked.
   $('#Form_CreateAccount').click(function() {
      if ($(this).attr('checked')) {
         $('ul.JoinFields').slideDown('fast');
      } else {
         $('ul.JoinFields').slideUp('fast');
      }
   });
   // Hide onload if unchecked   
   if ($('#Form_CreateAccount').attr('checked')) {
      $('ul.JoinFields').show();
   } else {
      $('ul.JoinFields').hide();
   }
	
   // Check to see if the selected username is valid
   $('#Form_Name').blur(function() {
      var name = $(this).val();
      if (name != '') {
         var checkUrl = gdn.combinePaths(
            gdn.definition('WebRoot', ''),
            'index.php?p=/dashboard/user/usernameavailable/'+encodeURIComponent(name)
         );
         $.ajax({
            type: "GET",
            url: checkUrl,
            dataType: 'text',
            error: function(XMLHttpRequest, textStatus, errorThrown) {
               $.popup({}, XMLHttpRequest.responseText);
            },
            success: function(text) {
               if (text == 'TRUE')
                  $('#NameUnavailable').hide();
               else
                  $('#NameUnavailable').show();
            }
         });
      }
   });
   
   // Check to see if passwords match
   $('#Form_PasswordMatch').blur(function() {
      if ($('#Form_Password').val() == $(this).val())
         $('#PasswordsDontMatch').hide();
      else
         $('#PasswordsDontMatch').show();
   });
   
});