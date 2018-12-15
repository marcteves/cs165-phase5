<?php
	include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";
	$_SESSION['location_id'] = $_POST['location_id'];
	$_SESSION['short_name'] = $_POST['short_name'];
	if ($_POST['referrer'] == 'home.php'){
		header('Location: user_location.php');
	} else {
		header('Location: ' . $_POST['referrer']);
	}
?>
