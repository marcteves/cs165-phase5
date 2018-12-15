<?php
	// return to login page after logout
	session_start();
	if (session_destroy()){
		header('Location: index.php');
	}
?>
