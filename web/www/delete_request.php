<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

# Very simple given that there is a foreign key constraint on Tasks
# Just delete the Request and all will follow.

$delete_query = '
	DELETE FROM Request
	WHERE Request.id=?
	';

$prep_stmt = $dbo->prepare($delete_query);
$prep_stmt->execute([$_POST['request_id']]);

header('Location: home.php');
?>
