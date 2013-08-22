// This file contains javascript that is specific to the dashboard/routes controller.
jQuery(document).ready(function($) {
	//Get size of images, how many there are, then determin the size of the image reel.
	var imageWidth = $("div.Reel img:first").width();
   var reelWidth = imageWidth * $('div.Reel img').length;
	
	//Adjust the image reel to its new size
	$('div.Window').css({'width' : imageWidth});
   $('div.Reel').css({'width' : reelWidth});
	
	//Paging + Slider Function
	rotate = function(){	
		var num = $active.attr('rel');
		var triggerID = num - 1; //Get number of times to slide
		var image_reelPosition = triggerID * imageWidth; //Determines the distance the image reel needs to slide

		$('.ScreenNav a').removeClass('Active');
		$active.addClass('Active');
		$(".ScreenNav a i").removeClass('SpriteDotOn');
		$('.ScreenNav a i').removeClass('SpriteDotOff');
		$(".ScreenNav a i").addClass('SpriteDotOff');
		$('.ScreenNav .Sprite'+num).removeClass('SpriteDotOff');
		$('.ScreenNav .Sprite'+num).addClass('SpriteDotOn');
		
		//Slider Animation
		$("div.Reel").animate({ 
			left: -image_reelPosition
		}, 500 );
		
	}; 
	
	//Rotation + Timing Event
	rotateSwitch = function(){		
		play = setInterval(function(){ //Set timer - this will repeat itself every 3 seconds
			$active = $('.ScreenNav a.Active').next();
			if ($active.length === 0) 
				$active = $('.ScreenNav a:first'); //go back to first

			rotate(); //Trigger the paging and slider function
		}, 5000); //Timer speed in milliseconds (3 seconds)
	};
	
	rotateSwitch(); //Run function on launch
	
	//On Hover
	$("div.Window").hover(function() {
		clearInterval(play); //Stop the rotation
	}, function() {
		rotateSwitch(); //Resume rotation
	});	
	
	//On Click
	$(".ScreenNav a").click(function() {	
		$active = $(this); //Activate the clicked paging
		//Reset Timer
		clearInterval(play); //Stop the rotation
		rotate(); //Trigger rotation immediately
		rotateSwitch(); // Resume rotation
		return false; //Prevent browser jump to link anchor
	});	
});