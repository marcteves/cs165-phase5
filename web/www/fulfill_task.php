<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

try {
	$dbo -> beginTransaction();

	$update_task_query = '
		UPDATE Task
		SET status_code=2
		WHERE Task.id=?
		';
	$prep_stmt = $dbo->prepare($update_task_query);
	$prep_stmt->execute([$_POST['task_id']]);

	# Count remaining pending tasks.
	$count_remaining_tasks = '
		SELECT COUNT(*) AS count FROM
		Task JOIN Request ON Task.task_in=Request.id
		WHERE Task.status_code<2
		AND Request.id IN(SELECT task_in FROM Task WHERE Task.id=?)
	';
	$prep_stmt = $dbo->prepare($count_remaining_tasks);
	$prep_stmt->execute([$_POST['task_id']]);

	$count=$prep_stmt->fetch()['count'];
	# If all tasks under query done, then set status to 0
	if ($count == 0){
		$update_request_query = '
			UPDATE Request
			SET status_code=2
			WHERE Request.id
			IN(SELECT task_in FROM Task WHERE Task.id=?)
		';
		$prep_stmt = $dbo->prepare($update_request_query);
		$prep_stmt->execute([$_POST['task_id']]);
	}

	$dbo->commit();
	echo "Task marked as fulfilled..";
	header('Location: view_request.php');

} catch (Exception $e) {
	$dbo -> rollback();
	throw $e;
}
?>
