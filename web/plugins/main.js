$(document).ready(function(){
	
	function run() {
	    var prev = $("#activities ul li:first-child");
	    $.unique(prev).each(function(i) {
	      $(this).delay(i*600).slideUp(1000,function() {
	        $(this).appendTo(this.parentNode).fadeIn(500);
	      });
	    });
	}

	window.setInterval(run,1000);

});