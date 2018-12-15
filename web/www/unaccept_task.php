<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

try {
	$dbo -> beginTransaction();

	$task_ls_query = '
		UPDATE Task
		SET Task.last_assigned=NULL
		WHERE Task.id=?
		';

	$prep_stmt = $dbo->prepare($task_ls_query);
	$prep_stmt->execute([$_POST['task_id']]);

	$task_status_query = '
		UPDATE Task
		SET Task.status_code=0
		WHERE Task.id=?
		';

	$prep_stmt = $dbo->prepare($task_status_query);
	$prep_stmt->execute([$_POST['task_id']]);

	$dbo->commit();
	header('Location: home.php');

} catch (Exception $e) {
	$dbo -> rollback();
	throw $e;
}
?>
