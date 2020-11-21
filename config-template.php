<?php
	$site_url = '\/\/'.$_SERVER['SERVER_ADDR'];
	$root_dir = getcwd();
	$ram_fsdir="/dev/shm";
	
	$script_types[1]="PHP";
	$script_types[2]="Python";
	$script_subdir[1]="/scripts/php/";
	$script_subdir[2]="/scripts/python/";
	$script_ext[1]=".php";
	$script_ext[2]=".py";
	
	$max_file_size=(100*1024*1024); //100MB
	
	// Mysql Database Settings
	$db_host = "127.0.0.1";
	$db_user = "bebot";
	$db_name = "bebot";
	$db_pass = "**put_your_mysq_db_password_here**";
  
  $lang="bg";
  if ($lang=="bg") include "lang_bg.php";
 

?>
