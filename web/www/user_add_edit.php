<?php
	include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";
	include_once $_SERVER['DOCUMENT_ROOT']."user_info.php";

//returns true on successful user insert
function user_add($name, $email, $password){
		$reg_query = 'INSERT INTO User (name, upmail, password) VALUES (?,
			?, ?)';
		if ($prep_stmt = $GLOBALS['dbo']->prepare($reg_query)) {
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);

			return $prep_stmt->execute([$name, $email, $hashed_password]);
		}
}
?>
