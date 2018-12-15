<!DOCTYPE html>
<html>
<body>
<?php
	include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";
	include_once $_SERVER['DOCUMENT_ROOT']."user_info.php";
	include_once $_SERVER['DOCUMENT_ROOT']."user_add_edit.php";

	//validate form
	if (!isset($_POST['name'], $_POST['email'], $_POST['password'],
		$_POST['confirmPassword'])) {
		die ('Please input required information.
			   	<a href="index.php">Go back.</a>
			');
	}
   	if (empty($_POST['name']) || empty($_POST['email']) ||
		empty($_POST['password']) || empty( $_POST['confirmPassword'])) {
		die ('Please input required information.
			   	<a href="index.php">Go back.</a>
			');
	}	
	if ($_POST['password'] !== $_POST['confirmPassword']) {
		die ('Passwords must match
			   	<a href="index.php">Go back.</a>
			');
	}

	if (user_exists($_POST['email'])) {
		die ('Email already registered into database
			<a href="index.php">Go back.</a>');
	} else {
		if (user_add($_POST['name'], $_POST['email'], $_POST['password'])){
			echo 'Register successful. <br>
				<a href="index.php">Proceed with login</a>';
		} else {
			echo 'Register failed. <br>
				<a href="index.php">Go back.</a>';
		}
	}

?>
</body>
</html>
