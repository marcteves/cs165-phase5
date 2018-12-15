<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

// Set user location based on selected location.

function set_user_location(){
	$user_location_query = '
		UPDATE User
		SET location_id=?
		WHERE id=?';
	if ($prep_stmt = $GLOBALS['dbo']->prepare($user_location_query)){
		$prep_stmt->execute([$_SESSION['location_id'], $_SESSION['id']]);
	} else {
		return false;
	}
	return $prep_stmt;
}

set_user_location();

unset($_SESSION['location_id']);
header('Location: home.php');
?>
