<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

$delete_query = '
	DELETE FROM Task
	WHERE Task.id=?
	';

$prep_stmt = $dbo->prepare($delete_query);
$prep_stmt->execute([$_POST['task_id']]);

header('Location: view_request.php');
?>
