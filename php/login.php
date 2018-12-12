<?php
	require_once "admin.php";
	
	$username = null;
	$password = null;

	if (isset($_POST['username'])){
		$username = $_POST['username'];
	}
	if (isset($_POST['password'])){
		$password = $_POST['password'];
	}
	
	$authenticated = check_login($username,$password);
	if ($authenticated == true){
		//if (get_active()){
			// do something here when the login is sucess (e.g. visit homepage?)
			header("Location:/INFS3202/");	
		//}
		//else{
		//	session_destroy();
		//	echo "<script>alert('Please activate your account'); window.location.href='register.php';</script>";
			
		//}
	}
	else {
		// do something here when the login is fail. (e.g. prompt the user login again? or anything)
		header("Location: /INFS3202/login.php?");
	}
?>