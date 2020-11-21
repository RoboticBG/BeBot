$( document ).ready(function() {
	 	
	 	var toggleButton = $('#toggle-nav-btn');
		toggleButton.on('click', function(){
		if(toggleButton.hasClass('visible_pre')){
			//$("#navbarNav2").css("display", "block");
			$("#navbarNav2").slideDown();
			$("button").removeClass('visible_pre');
		}
		else {
			$("#navbarNav2").slideUp();
			//$("#navbarNav2").css("display", "none");
			$("button").addClass('visible_pre');
		}
	});
		
	if($( window ).width() <= 505){
		$('#connected').html('<i class="fas fa-wifi system-icons"></i>');
	}else{
		$('#connected').html('<i class="fas fa-wifi system-icons"></i> connected'); 
	}
	
	$( window ).resize(function() {
		if($( window ).width() <= 505){
			$('#connected').html('<i class="fas fa-wifi system-icons"></i>');
		}
		else{
			$('#connected').html('<i class="fas fa-wifi system-icons"></i> connected');
		}
	});
	
	$('a.clickable').on("click", function () {
    	console.log("cuknato e");
       	if ($(this).hasClass('panel-collapsed')) {
       		$(this).parents('li').next().slideDown();
          	// $(this).parents('.active').find('.collapsein').slideDown();
       		$(this).removeClass('panel-collapsed');
       	}
       	else {
       		$(this).parents('li').next().slideUp();
       		$(this).addClass('panel-collapsed');
	   	}
    });
	 	
	 	console.log("running");
	
;
});
    
    function subName(name){
    	if(name.length > 10){
    		return name.substr(0, 10) + '.' + name.split('.').pop();
    	}
    }
    
    function cancel(){
			window.history.go(-1);	
		}
		
		
		function browse() {
      $("#fileInput").click();
      $("#fileInput").change(function(){
      	$("#upload_file").submit();
      });
   }

