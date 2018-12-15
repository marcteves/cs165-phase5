<?php
	// if user is not logged in, redirect to index.php (Log-in page)
	session_start();
	if (isset($_SESSION['logged_in'])){
		if ($_SESSION['logged_in'] == false){
			session_destroy();
			header('Location: index.php');
		}
	}
	else {
		header('Location: index.php');
	}
?>
