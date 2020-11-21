<script src="/libs/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="/libs/bootstrap-4.5.0-dist/css/bootstrap.css?t=<?php echo time(); ?>">

<style>
	#output_ajax{
		
		overflow:scroll;
		height:700px;
		width:1200px;
		border:1px solid #343434;
		display:block;
		padding-bottom:10px;
		
	}
	
	#output_ajax .message {
		margin-top: auto;
    margin-bottom: auto;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 10px;
    border-radius: 25px;
    padding: 10px;
    position: relative;
    display: table-row;
    max-width: 80%;
    word-break:break-all;
    font-size: 18px;
    font-weight: 500;
	}
	
	#output_ajax .ajax_response, #output_ajax .user_response{
	
    display: table;
	}
	
	#output_ajax .ajax_response .message{
	
    color: #1d9ab7;
		float: left;
		
	}
	
	#output_ajax .user_response .message{
		
    color: #cc2323;
    float: left;
		
	}
	
	.user_response, .ajax_response{
		width: 100%;
    display: table;
	}
</style>
	
	<form id="ajax_send_form">
		<label>Command:</label>
		<input class="form-control" type="text" id="command_ajax">
		
		<br><br>
		
		<button type="submit" class="btn btn-primary" id="submit_ajax">Send Command</button>
	</form>
	<br>
	<button class="btn btn-info" id="start_ajax_interval">Start Interval</button>
	<button class="btn btn-danger" id="stop_ajax_interval">Stop Interval</button>
	
	<br><br>
	
	<label>Output:</label>
	<div id="output_ajax"></div>
	
<script>
	
	var command_ajax, submit_ajax, output_ajax, user_data, ajax_interval;
	
	// How often to make requests
	var ajax_interval_time = 2000;
	
	function send_ajax(user_command=""){
		
		$.post( "read_pipe.php", { cmd: user_command })
			  .done(function( response_data ) {
			  
			  // Check if any data is returned
			  if(response_data){
				  
				  	// Parse the JSON
				  	response_data = JSON.parse(response_data);
				  	
				  	// check if a message is returned
				  	if(response_data.message){
				  		
				  		 output_ajax.html("<div class='ajax_response'><div class='message'> # "+ response_data.message +"</div></div>");
				  		 output_ajax.scrollTop(output_ajax[0].scrollHeight);
				  	}
				  	
			  }
			  // Debug Here
			  else{
			  	 output_ajax.html("<div class='ajax_response'><div class='message'>No Data Returned</div></div>");
				   output_ajax.scrollTop(output_ajax[0].scrollHeight);
			  }
			  
			  });
		
	}
	
	
	function send_user_data(){
			user_data = command_ajax.val();
			
			if(user_data){
				output_ajax.html("<div class='user_response'><div class='message'> # "+ user_data +"</div></div>");
				output_ajax.scrollTop(output_ajax[0].scrollHeight);
				send_ajax(user_data);
			}
	}
	
	
	$(document).ready(function(){
		
		// Start the interval/loop of calling the ajax function
		ajax_interval = setInterval(send_ajax, ajax_interval_time);
	
		$(document).on("click","#stop_ajax_interval",function(){
			 clearInterval(ajax_interval);
		});
		
		
		$(document).on("click","#start_ajax_interval",function(){
			 clearInterval(ajax_interval);
			 ajax_interval = setInterval(send_ajax, ajax_interval_time);
		});
		
		
		command_ajax = $("#command_ajax");
		submit_ajax = $("#submit_ajax");
		output_ajax = $("#output_ajax");
		
		
		$(document).on("submit","#ajax_send_form",function(e){
			
			e.preventDefault();
			send_user_data();
			
		});
		
		
	});
	
</script>
