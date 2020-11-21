<?php
    $kpid=(int)$_GET['kpid'];
    include_once 'login.php';	
    $run_pid=@file_get_contents("$ram_fsdir/bebotphpexec.pid");
    $ps=exec("ps cax | grep $run_pid | grep -o '^[ ]*[0-9]*'",$out);
    $ps=(int)$ps;
    //echo "$run_pid ".$ps;
    if ($ps==0) @unlink("$ram_fsdir/bebotphpexec.pid");
    
    $desc = array (
                    0 => array('pipe', 'r'), // 0 is STDIN for process
                    1 => array('file', '/dev/shm/prog-output.txt', 'a'), // 1 is STDOUT for process
                    2 => array('file', '/dev/shm/prog-err.txt', 'a'), // 2 is STDERR for process
                  );	
    if($cmd == "exec" and $ID>0)
		  {
		  	$run_pid=@file_get_contents ("$ram_fsdir/bebotphpexec.pid");
		  	@exec("$root_dir/robo-libs/kill_recur.sh $run_pid");
		    @unlink("$ram_fsdir/bebotphpexec.pid");
		  	@unlink("$ram_fsdir/prog-output.txt");
		    @unlink("$ram_fsdir/prog-err.txt");
				$sql = "SELECT * FROM scripts WHERE ID='$ID'";
				$res = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($res);
				extract($row);
				$code_file=$root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type];
				$prepend_code_file=$root_dir."/robo-libs/begin_code.php"; ;
				$append_code_file=$root_dir."/robo-libs/end_code.php"; ;

				$to_exec="cat $prepend_code_file $code_file $append_code_file | /usr/bin/php 2>&1 ";//& echo $! ";
		
				$proc = proc_open($to_exec, $desc, $pipes);
				$sts=proc_get_status($proc);
				$pid=$sts['pid'];
				$cmd="";
			
				$msg_ok="Програмата е изпълнена: PID $pid";;
				if ($rzz[1]) $msg_err="Грешка: ".$rzz[1];
				file_put_contents ("$ram_fsdir/bebotphpexec.pid",$pid."\n");
				//echo $msg_ok;
				header("Location: task_manager.php");
			}

include_once 'head.php'; 
	
?>

	
					<div class="content-header">
						<header>
							<div class="header"><h4><?=$LProcessViewer?></h4></div>
						</header>
					</div>
					<div class="content-container" style="overflow: scroll;">
					
	<?php
	
	
	
	if ($cmd=="stop" and $kpid>0)
	{
		@exec("$root_dir/robo-libs/kill_recur.sh $kpid");
		@unlink("$ram_fsdir/bebotphpexec.pid");
		$msg_ok="Изпълнението на програмата е прекратено";
	}
	
	if($msg_err) 
	  { ?>					
		  <div id="alerterror" class="alert alert-danger" role="alert">
		  	  <?=$msg_err?>
		  </div>				
      <?php						  
	  }
	  
	if($msg_ok) 
	  { ?>					
		  <div id="alertsuccess" class="alert alert-success" role="alert">
		  	  <?=$msg_ok?>
		  </div>				
      <?php						  
	  }
		

	if($cmd == "list" or !$cmd) // CMD LIST BEGIN
	{ 
		if ($pid==0) $pid=@file_get_contents ("$ram_fsdir/bebotphpexec.pid");
		$pid=(int)$pid;
		  ?>
		  <div class="inside-header">
					<div id="header-title">
				<?php
				if ($pid>0) echo "<a href=\"$PHP_SELF?cmd=stop&kpid=$pid\"><img src=\"/img/stop.jpg\" width=150></a>";
				else echo "<h6>Няма работеща програма в паметта</h6>" ;
			
					
				if ($pid>0) { 
					?>
					
			
<style>
	#output_ajax{
		
		overflow:scroll;
		height:200px;
		width:500px;
		border:1px solid #343434;
		display:block;
		padding-bottom:10px;
		background-color: #1d1d1d;
		white-space: pre;
		
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
	
    color: #1db79a;
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
				  		
				  		 output_ajax.html("<div class='ajax_response'><div class='message'> "+ response_data.message +"</div></div>");
				  		 output_ajax.scrollTop(output_ajax[0].scrollHeight);
				  	}
				  	
			  }
			  // Debug Here
			  else{
			  	// output_ajax.html("<div class='ajax_response'><div class='message'>No Data Returned</div></div>");
				  // output_ajax.scrollTop(output_ajax[0].scrollHeight);
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

				<?php } ?>	
					</div>
			    
		  </div>
  
          <?php	
        	  
  } // LIST CMD END		  
  ?>
				</div>
				</article>
			</div>
		</div>

<?php include_once "foot.php"; ?>