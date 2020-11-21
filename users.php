<?php
    include_once 'login.php';
		include_once 'head.php'; 
	
	
		
		$create_link="<a class=\"cmds\" href=\"".$_SERVER['PHP_SELF']."?cmd=create\"><i class=\"fa fa-plus\"></i> $LCreateNewUser</a>";				
		
		if($cmd == "delete" and $ID>0 and $ID!=$logged_userID){
			
			$sql = "DELETE FROM users WHERE ID='$ID' LIMIT 1";
			$res = mysqli_query($conn, $sql);
			
			if($res) {
				$msg_ok=$LUserDeleted;
				$cmd = "";
			}
		}
		
	if($cmd == "save") {
    $names = mysqli_real_escape_string($conn,htmlentities($_POST['names']));
    $email = mysqli_real_escape_string($conn,htmlentities( $_POST['email']));
    $pass1= mysqli_real_escape_string($conn,$_POST['pass1']);
	  $pass2= mysqli_real_escape_string($conn,$_POST['pass2']); 	
  
    unset($msg_ok);
    unset($msg_err);
    $cmd = "edit";
    if ($names and (mb_strlen($names)<3 OR mb_strlen($names)>50))        $msg_err = $LPleaseEnterValidName;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $msg_err = $LPleaseEnterValidEmail;
    $mail_exists=mysqli_num_rows(mysqli_query($conn,"SELECT email FROM users WHERE email='$email' AND ID!='$ID'"));
    if ($mail_exists>0) $msg_err = $LUserWithThisEmailExists;
    if (mb_strlen($pass1)>1)
      { 
	      if (mb_strlen($pass1)<5 and mb_strlen($pass2)<5) $msg_err = $LEnterPasswordAtLeastFive;
	      if ($pass1!=$pass2) $msg_err =$LPasswordsDoNotMatch;
	  	  if (!$msg_err)
             { 	  	   
             	 $chpass=" ,passw='$pass1' ";
	  	         $msg_ok.="<br><b>$LPasswordIs $pass1</b><br><br>";
	  	       };
	    };
	  	
   
    if (!$msg_err) 
    {    
      if($ID>0)
       {
           $sql = "UPDATE users SET names='$names',phone='$phone',access_level='$access_level',email='$email' $chpass WHERE ID='$ID' LIMIT 1";
           $res = mysqli_query($conn, $sql);
           echo mysqli_error($conn);
           $msg_ok.= $LUserDataSaved;
           $cmd = "";  
       } else
       {
    	     $sql = "INSERT INTO users SET names='$names',phone='$phone',access_level='$access_level',email='$email' $chpass ";
           $res = mysqli_query($conn, $sql);
           echo mysqli_error($conn);
           $msg_ok= $LUserCreated;
           $cmd = "";
       };
   };   
}

?>

	
					<div class="content-header">
						<header>
							<div class="header"><h4><?=$LUsers?></h4></div>
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
		  $word=$LCreateNewUser;
			if($ID > 0){ 
				$sql = "SELECT * FROM users WHERE ID='$ID' ";
				$res = mysqli_query($conn, $sql);
				$row = mysqli_fetch_assoc($res);
			  extract($row);
			  $word=$LEditUser;
			} else $s_name="";
		
	  ?>
	   <div class="inside-header">
					<div id="header-title">
						 <h4><?=$word?></h4>
					</div>
			    <div id="scriptSign">
					   
					</div>
		  </div>
	  
	    <form class="form-horizontal group-border hover-stripped" role="form" ENCTYPE="multipart/form-data" METHOD="POST">

   	              <div class="row">
						  		 	  <div class="col-lg-4"><label for="names"><?=$LFirstNLastName?>:</label></div>
                      <div class="col-lg-6"><input type="text" size=36 name="names" id="names"  value="<?=$names?>" required="" ></div>
                   </div>
                  
                   <div class="row">
                      <div class="col-lg-4"><label for="email"><?=$LEmail?>:</label></div>
                      <div class="col-lg-6"><input type="text"  size=36 name="email" id="email"  value="<?=$email?>"  required="" ></div>
                   </div>
                   <div class="row">
                      <div class="col-lg-4"><label for="pass1"><?=$LPassword?>:</label></div>
                      <div class="col-lg-6"><input type="password"  size=18 name="pass1" id="pass1"  required="" ></div>
                   </div>
                   <div class="row">
                      <div class="col-lg-4"><label for="pass2"><?=$LPassword2?>:</label></div>
                      <div class="col-lg-6"><input type="password"  size=18 name="pass2" id="pass2"  required="" ></div>
                   </div>
          
	  					       <div class="row">	  					       
						    	     <div class="col-lg-2"><input type="submit" class="btn btn-info btn-lg" name="saveBtn" id="saveBtn" style="margin-right: 10px;" value="<?=$LSave?>"></div>
						    	   </div>
						    	   <input type="hidden" name="ID" value="<?=$ID?>">
						         <input type="hidden" name="cmd" value="save">		
						    </div>
						  </div>				  		
			</form>
	  <?php	
	}; // END CMD CREATE
	
	if($cmd == "list" or !$cmd) // CMD LIST BEGIN
	{ 
		  ?>
		  <div class="inside-header">
					<div id="header-title"><br><br>
					</div>
			    <div id="scriptSign">
					   <?=$create_link?>
					</div>
		  </div>
	    <?php					
	    // cdate - create date, edate - expiration data, ndate - today (date only format)
			$sql = "SELECT * FROM users";
			if ($res = mysqli_query($conn, $sql)) 
			   if (mysqli_num_rows($res)>0)	
			    { 
	        ?>    
	         <table class="table table-bordered" cellspacing="2" cellpadding="2" border="1">
	      	 <tr>
	      		  <td class="tbheader"><?=$LFirstNLastName?></td>
	      		  <td class="tbheader"><?=$LEmail?></td>
	      		  <td class="tbheader"></td>
	      	 </tr>
	         <?php		
				   while ($row = mysqli_fetch_assoc($res))
			    	{
			       extract($row);
			      
			       ?>
			        <tr>
			    	    <td class="tbcontent"><?=$names?></td>  
			    	    <td class="tbcontent"><?=$email?></td>  			    
			          <td class="tbcontent">
			          &nbsp;&nbsp;<a class="cmds" href="<?=$_SERVER['PHP_SELF']?>?cmd=edit&ID=<?=$ID?>"><i class="fa fa-file" ></i> <?=$LEdit?></a>
			         <?php
			          if ($ID!=$logged_userID) { ?>
			          	
			          	 &nbsp;&nbsp;<a class="cmds" onclick="return confirm('<?=$LConfirmDeleteUser?>');" href="<?=$_SERVER['PHP_SELF']?>?cmd=delete&ID=<?=$ID?>" ><i class="fa fa-trash"></i> <?=$LDelete?></a>
			            <?php 
			            } else echo "<span style=\"padding-right:110px;\"></span>"; ?>
			         </td>
			       </tr>
             <?php
			      } // END while
	   		  ?>
  			  </table>   
          <?php	
          } //END IF ANY results
          else 
          {
          	echo $LNoUsers;
          } 		  
  } // LIST CMD END		  
  ?>
				</div>
				</article>
			</div>
		</div>


<?php include_once "foot.php"; ?>