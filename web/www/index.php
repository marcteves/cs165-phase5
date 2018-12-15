<!DOCTYPE html>
<html>
<body>
<?php
	//verify is user is logged-in
	//if yes, redirect to homepage
	session_start();
	if (isset($_SESSION['logged_in'])){
		if ($_SESSION['logged_in'] == true){
			session_destroy();
			header('Location: home.php');
		}
	} else {
		echo 'Logout successful.';
	}
?>
	<h1>Log-in</h1>
	<form action="auth.php" method="post">
		<input type="text" name="email" placeholder="E-mail">
		<input type="password" name="password" placeholder="Password">
		<input type="submit" value="Submit">
	</form>

	<h1>Register:</h1>
	<form action="register.php" method="post">
		<input type="text" name="name" placeholder="Name">
		<input type="text" name="email" placeholder="E-mail">
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="confirmPassword" placeholder="Confirm Password">
		<input type="submit" value="Submit">
	</form>

</body>
</html>
