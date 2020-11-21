<?php
  function dbcon()
  {   
  	  global $db_host, $db_user, $db_pass, $db_name;
     	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
     	if(!$conn) die("Connection failed: " . mysqli_connect_error());
     	else return($conn);
  
  }	   
   
?>
