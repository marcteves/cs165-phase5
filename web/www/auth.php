<!DOCTYPE html>
<html>
<body>
<?php
	include_once $_SERVER['DOCUMENT_ROOT']."user_info.php";
	//validate form
	if (!isset($_POST['email'], $_POST['password'])) {
		die ('Please input required information.
			   	<a href="index.php">Go back.</a>
			');
	}
   	if (empty($_POST['email']) || empty($_POST['password'])) {
		die ('Please input required information.
			   	<a href="index.php">Go back.</a>
			');
	}	


	if (user_exists($_POST['email'])) {

		if (verify_password($_POST['email'], $_POST['password'])){
			session_start();
			echo 'Login successful';
			$user_data = get_user_data($_POST['email']);
			$_SESSION['logged_in'] 	= true;
			$_SESSION['id'] 		= $user_data['id'];
			$_SESSION['email'] 		= $user_data['upmail'];
			header('Location: home.php');
		} else {
			die ('Password does not match.
			   	<a href="index.php">Go back.</a>');
			session_destroy();
		}
	}
	else {
		die ('Email not yet registered. Register your account first. <br>
			<a href="index.php">Go back.</a>');
	}

?>
