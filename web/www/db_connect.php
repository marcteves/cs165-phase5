<?php
	session_start();
	error_reporting(E_ALL);
	$hostname 	= "localhost";
	$username 	= "root";
	$password 	= "";
	$dbname		= "iskolivery";
	$charset 	= 'utf8mb4';
	$dsn	  	= "mysql:host=$hostname;dbname=$dbname;charset=$charset";
	$options	= [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => true,
	];

	$dbo		= null;
	try {
		$dbo = new PDO($dsn, $username, $password);
	}
	catch (PDOException $e){
		echo "Error: " . $e->getMessage();
		die();
	}
?>

