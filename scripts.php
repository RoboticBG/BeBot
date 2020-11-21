<?php
    include_once 'login.php';	
	
    if ($script_type<1 or $script_type>2) $script_type=1;
	
		$create_link="<a class=\"cmds\" href=\"".$_SERVER['PHP_SELF']."?cmd=create\"><i class=\"fa fa-plus\"></i> $LCreateProgram</a>";				
		if (!$cmd) $cmd=$_GET['cmd'];
		if (!$cmd) $cmd=$_POST['cmd'];
	  //echo "$cmd cmd";
		if($cmd == "delete" and $ID>0){
			
			$sql = "DELETE FROM scripts WHERE ID='$ID' ";
			$res = mysqli_query($conn, $sql);
			unlink($root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type]);
			if($res) {
				$msg_ok=$LProgramDeleted;
				$cmd = "";
			}
		}
		
	
		
	  if($cmd == "save" or ($cmd == "exec" and $ID>0)){	
	  	$script_title = mysqli_real_escape_string($conn,htmlentities( $_POST['script_title']));
	  	$code=$_POST['code'];
			if (mb_strlen($script_title)<2 OR mb_strlen($script_title)>50) 
			{
			  $msg_err ="Please enter a valid script name. $script_title";
			  $cmd = "edit";
		  }
		  else 
		  {
			  if ($ID>0)
			  {
			  	
			    $sql = "UPDATE scripts SET script_title='$script_title' WHERE ID='$ID'";
		    	$res = mysqli_query($conn, $sql);
		    	file_put_contents($root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type],$code,0);
			    $msg_ok= $LProgramSaved; //.$root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type];
			    if ($cmd!="exec") $cmd = "";
		    } 
		    else
		    {
		    
		    	$sql = "INSERT INTO scripts SET script_title='$script_title', create_date=now(),last_modified=now(), script_type=1";
		    	$res = mysqli_query($conn, $sql);
		    	$ID = mysqli_insert_id($conn);
		    	file_put_contents($root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type],$code,0);
		    	//echo mysqli_error($conn);
			    $msg_ok= $LProgramCreated;
			    $cmd = "";
		    }
			}
		}

    if($cmd == "exec" and $ID>0)
		  {
		  	//$code=$_POST['code'];
				$sql = "SELECT script_title FROM scripts WHERE ID='$ID'";
				$res = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($res);
				$code = file_get_contents($root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type]);
				
				$sql = "UPDATE scripts SET last_executed=NOW() WHERE ID='$ID'";
				$res = mysqli_query($conn, $sql);
				header("Location: task_manager.php?cmd=exec&ID=$ID");
				exit;
				//echo("/usr/local/bin/php ".$root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type]);
	/*
				$out = exec("/usr/local/bin/php ".$root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type],$rzz);
				$msg=implode(" ",$rzz);
				//echo "/usr/bin/php ".$root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type];
				$cmd="edit";*/
			}

if ($msg_ok or $msg_err) header ("Location: ".$_SERVER['PHP_SELF']."?cmd=$cmd&ID=$ID&msg_ok=".urlencode($msg_ok)."&msg_err=".urlencode($msg_err));
include_once 'head.php'; 
	
$msg_ok=$_GET['msg_ok'];
$msg_err=$_GET['msg_err'];
?>

	
					<div class="content-header">
						<header>
							<div class="header"><h4><?=$LRobotPrograms?></h4></div>
						</header>
					</div>
					<div class="content-container" style="overflow: scroll;">
					
	<?php
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
		

	
	if ($cmd=="create" or $cmd=="edit") 
	{  
		  $word=$LCreateProgram;
			if($ID > 0){ 
				$sql = "SELECT * FROM scripts WHERE ID='$ID' ";
				$res = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($res);
			  extract($row);
			  //echo $root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type];
			  $code = file_get_contents($root_dir.$script_subdir[$script_type].$ID.$script_ext[$script_type]);
			  $word=$LEditProgram;
			} else $script_title="";
		
	  ?>
	   <div class="inside-header">
					<div id="header-title">
						 <h4><?=$word?></h4>
					</div>
			    <div id="scriptSign">
					   
					</div>
		  </div>
		
	  	<form id="mainForm" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
							
						  <div class="form-group">
						  	<div class="container">
						  		<div class="row">
						    		<label for="script_title"><?=$LProgramName?>:</label>
							  	</div>
						    	<div class="row">
						    		<div class="col-lg-8">
						    			 <input type="text" class="form-control" id="script_title" placeholder="<?=$LProgramName?>" name="script_title" value="<?=$script_title?>"> 
						    		</div>
						    	</div>
						    	<div class="row">
						    		<div class="col-lg-8"><br>
						    			<textarea class="form-control" id="editor" name="code"><?=$code;?></textarea>
						    			<br>
						    		</div>
						    	</div>
						    		
						    		
						    	<div class="row">
						    		<div class="col-lg-8">	
						    	<?php 
								if($cmd == "form" || $cmd == "save"){ 
									if($ID == 0) {
										$option_text = "Enter Edit Mode after save";
										$option_name = "editmode";
									}
									else if($ID > 0) {
										$option_text = "Stay on page after save";
										$option_name = "stayOnPage";
									}
									?>
									<div class="form-check custom-checkbox">
							      <label class="form-check-label" for="check1">
							        <input type="checkbox" class="form-check-input" id="check1" name="<?=$option_name?>" checked> <?=$option_text?>
							      </label>
							    </div>
									<?php
								}
								if($ID > 0)
								{ 
									echo "<button type=\"submit\" class=\"btn btn-info btn-lg\" name=\"cmd\" value=\"save\" id=\"saveBtn\" style=\"margin-right: 10px;\">$LSaveProgram</button>";
									echo "<button type=\"submit\" class=\"btn btn-warning btn-lg\" name=\"cmd\" value=\"exec\" id=\"executeSaveBtn\" style=\"margin-right: 10px;\">$LSaveExecProgram</button>";
									echo "<a onclick=\"return confirm('Are you sure you want to delete?');\" href=\"scripts.php?cmd=delete&ID=$ID\">
									<button type=\"button\" class=\"btn btn-danger btn-lg\">$LDeleteProgram</button></a>";
								}
								else 
								{
									echo "<button type=\"submit\" class=\"btn btn-info btn-lg\" name=\"cmd\" value=\"save\" id=\"saveBtn\" style=\"margin-right: 10px;\">$LSaveProgram</button>";
								}
									echo "<button type=\"submit\" class=\"btn btn-info btn-lg\" name=\"cmd\" value=\"\" id=\"cancelBtn\" style=\"margin-left: 10px;\">$LReturnToList</button>";
								
							?>
						<input type="hidden" name="ID" value="<?=$ID?>">
					
						   </div>
						    	</div>
						    		 		
						    		
						    		
						    		
						    </div>
						  </div>
									
			</form>
			<script>
		  	
	       function initCodeMirror()
	         {
		          editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
			        mode: "text/x-php",
			        theme: "rubyblue",
			        lineNumbers: true,
			        matchBrackets: true,
		          scrollbarStyle: "simple"
		         });
	         }
//				var editor;
				initCodeMirror();
	    </script>
			
	  <?php	
	}; // END CMD CREATE
	
	if($cmd == "list" or !$cmd) // CMD LIST BEGIN
	{ 
		  ?>
		  <div class="inside-header">
					<div id="header-title">
						
					</div>
			    <div id="scriptSign">
					   <?=$create_link?>
					</div>
		  </div>
	    <?php					
			$sql = "SELECT * FROM scripts WHERE 1 ORDER BY ID desc";
			if ($res = mysqli_query($conn, $sql)) 
			   if (mysqli_num_rows($res)>0)	
			    { 
				   while ($row = mysqli_fetch_assoc($res))
			    	{
			       extract($row);
			       ?>
			      <a href="scripts.php?cmd=edit&ID=<?php echo $row['ID'];?>" class="script-view-link"><img src="/img/script_image.png" class="script-image" name="<?=$row['script_title']?>"/>
						<div class="script-name">
							<?php
								if(strlen($file) - 3 > 10){
									$subFileName = substr($row['script_title'], 0, 10);
									echo $subFileName . ".php";
								} 
							  else {
							  	echo $row['script_title'];
							  }
							?>
				  	</div>
			      <?php
			     
			      } // END while
	   		  ?>
  			  </table>   
          <?php	
          } //END IF ANY results
          else 
          {
          	echo $LNoPrograms;
          } 		  
  } // LIST CMD END		  
  ?>
				</div>
				</article>
			</div>
		</div>

<?php include_once "foot.php"; ?>