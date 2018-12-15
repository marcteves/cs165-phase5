<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

$insert_task_query='
	INSERT INTO Task (info, bounty, task_in, location_id)
	VALUES (?, ?, ?, ?)
';

$request_id = $_POST['request_id'];
$info = $_POST['info'];
$bounty = $_POST['bounty'];
$location_id = $_POST['location_id'];

if ($prep_stmt = $GLOBALS['dbo']->prepare($insert_task_query)) {
	$prep_stmt->execute([$info, $bounty, $request_id, $location_id]);
}

header('Location: view_request.php');
?>
