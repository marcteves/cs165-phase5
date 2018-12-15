<?php
	include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

// return associated array of user info when user exists
// otherwise, return false
function get_user_data($email){
	$user_query = 'SELECT User.*, short_name FROM User JOIN Location ON User.location_id=Location.id WHERE upmail=?';
	if ($prep_stmt = $GLOBALS['dbo']->prepare($user_query)){
			$prep_stmt->execute([$email]);
			if ($prep_stmt->rowCount() > 0) {
				return $prep_stmt->fetch();
			} else {
				return false;
			}
	} else {
		echo "fatal_error";
	}
}

// return true if user exists
// otherwise, return false
function user_exists($email){
	if (get_user_data($email)){
		return true;
	} else {
		return false;
	}
}

// true if password matches
function verify_password($email, $password){
	$user_data = get_user_data($email);
	if ($user_data){
		$hashed_password = $user_data['password'];
		return password_verify($password, $hashed_password);
	} else {
		return false;
	}
}

// returns PDOStatement containing accepted tasks
function get_user_accepted_tasks(){
	$id = $_SESSION['id'];
	// selects all accepted tasks by a user
	$tasks_query = '
		SELECT * FROM NearbyTasks WHERE last_assigned = ?';
	if ($prep_stmt = $GLOBALS['dbo']->prepare($tasks_query)){
		$prep_stmt->execute([$id]);
	} else {
		return false;
	}
	return $prep_stmt;
}

// returns PDOStatement containing posted requests
function get_user_posted_requests(){
	$id = $_SESSION['id'];
	// selects all accepted tasks by a user
	$requests_query = '
		SELECT Request.id, Request.info, Status.info AS status,
			Request.deadline, Location.short_name AS location
		FROM Request
		JOIN Status ON Request.status_code=Status.id
		JOIN Location ON Request.location_id=Location.id
		WHERE Request.posted_by = ?
		';
	if ($prep_stmt = $GLOBALS['dbo']->prepare($requests_query)){
		$prep_stmt->execute([$id]);
	} else {
		return false;
	}
	return $prep_stmt;
}

// returns PDOStatement containing tasks closest to user
function get_closest_tasks(){
	$id = $_SESSION['id'];
	$limit = 5;
	$closest_tasks_query = '
		SELECT *, ST_Distance(userloc, point) as distance
		FROM NearbyTasks,
		(SELECT point AS userloc FROM User
		JOIN Location ON Location.id=User.location_id WHERE User.id=?) AS
		UserLoc 
		WHERE poster <> ? AND (last_assigned <> ? OR last_assigned IS NULL)
		ORDER BY distance LIMIT ' . $limit . '';
	if ($prep_stmt = $GLOBALS['dbo']->prepare($closest_tasks_query)){
		$prep_stmt->execute([$id, $id, $id]);
	} else {
		return false;
	}
	return $prep_stmt;
}

?>
