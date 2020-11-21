<?php
  include_once 'config.php';
	include_once 'func.php';
	$conn=dbcon();
	$cmd = $_GET['cmd'];
	unset($user,$userID,$setRandCookie);
	$flag = false;
	function user_logged(){
		global $conn,$logged_user,$logged_userID;
		if($_COOKIE['userID'] != ""){
			$curr = $_COOKIE['userID'];
			$sql = "SELECT ID,names FROM users WHERE cookie='$curr'";
			$res = mysqli_query($conn, $sql);
			if(mysqli_num_rows($res) > 0) {
				$row = mysqli_fetch_assoc($res);
				$logged_user = $row['names'];
				$logged_userID = $row['ID'];
				return true;
			}
			return false;
		}
		
		return false;
	}
	
	if($cmd == "login"){ //&& user_logged() == false
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
		
		if(empty($username) || empty($password)){
			header("Location: " . "signin.php?error=emptyfields");
		}
		else{
		
			$sql = "SELECT * FROM users WHERE email='$username' AND passw='$password' LIMIT 1";
			
			$res = mysqli_query($conn, $sql);
			$resultRow = mysqli_fetch_assoc($res);
			if(mysqli_num_rows($res) > 0){
				$setRandCookie = md5(rand(1500, 100000000)); 
				$sql = "UPDATE users SET cookie='$setRandCookie', last_login_date=NOW()  WHERE ID='$resultRow[ID]'";
				
				mysqli_query($conn, $sql);
				setcookie("userID", $setRandCookie);
				header("Location: $_SERVER[PHP_SELF]");
				exit;
			}
			else {
				mysqli_close($conn);
				header("Location: " . "signin.php?error=invalidCredentials");
				exit;
			}
		}
	}
	else if($cmd == "logout"){
		$sql = "UPDATE users SET cookie='' WHERE cookie='$_COOKIE[userID]'";
		mysqli_query($conn, $sql);
		mysqli_close($conn);
		setcookie("userID", '');
		header("Location: " . "signin.php");
		exit;
	}
							
	if(!user_logged()) {
		mysqli_close($conn);
		header("Location: " . "signin.php");
		exit;
	}
	if ($_POST['cmd']) $cmd = $_POST['cmd'];      else $cmd = $_GET['cmd'];
	if ($_POST['ID'] ) $ID  = (int) $_POST['ID']; else $ID  = $_GET['ID'];

?>
