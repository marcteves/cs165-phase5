<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";
session_start();

$insert_request_query='
	INSERT INTO Request (posted_by, info, deadline)
	VALUES (?, ?, ?)
';

$posted_by = $_SESSION['id'];
$info = $_POST['info'];
$deadline = $_POST['deadline'];

if ($prep_stmt = $GLOBALS['dbo']->prepare($insert_request_query)) {
	$prep_stmt->execute([$posted_by, $info, $deadline]);
}

header('Location: home.php');
?>
