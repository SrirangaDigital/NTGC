
$(document).ready(function(){
		
	$(function(){ // document ready
	
	  if (!!$('.sticky').offset()) { // make sure ".sticky" element exists
		var stickyTop = $('.sticky').offset().top; // returns number
		$(window).scroll(function(){ // scroll event
	 
		  var windowTop = $(window).scrollTop(); // returns number
		 	  if (stickyTop < windowTop){
				$('.sticky').css({ position: 'fixed', top: "30px", left: $('.sticky').offset().left+"px" });
			  }
			  else {
				$('.sticky').css('position','static');
			  }
		 
		});
	 
	  }
	 
	});
});

