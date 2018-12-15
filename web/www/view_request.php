<html>
<head>
<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th
{
	font-weight:bold;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

tr.selected {
	background-color: #ffffdd;
}	

</style>
<script
			  src="https://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous"></script>
<script src='post.js'></script>
<script src='view_request.js'></script>
</head>
<body>
<a href='home.php'>Go back</a>
<br>
<?php
	include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

	if (isset($_POST['request_id'])){
		$_SESSION['request_id'] = $_POST['request_id'];
	}

	function request_details(){
		$request_query = '
		SELECT Request.id, Request.info, Status.info AS status,
			Request.deadline, Location.short_name AS location
		FROM Request
		JOIN Status ON Request.status_code=Status.id
		JOIN User ON Request.posted_by=User.id
		JOIN Location ON User.location_id=Location.id
		WHERE Request.id=? AND Request.posted_by = ?';
		if ($prep_stmt = $GLOBALS['dbo']->prepare($request_query)){
				$prep_stmt->execute([$_SESSION['request_id'],
					$_SESSION['id']]);
				if ($prep_stmt->rowCount() > 0) {
					return $prep_stmt->fetch();
				} else {
					return false;
				}
		}
	}

	function tasks_in_request(){
		$tasks_query = '
		SELECT Task.id, User.name, Task.info, Status.info AS status,
			Task.bounty, Location.short_name
		FROM
			Task 
			JOIN Status ON Task.status_code=Status.id
			LEFT OUTER JOIN User ON Task.last_assigned=User.id
			JOIN Location ON Task.location_id=Location.id
			JOIN Request ON Task.task_in=Request.id
		WHERE
			Task.task_in=? AND Request.posted_by=?;
		';
		if ($prep_stmt = $GLOBALS['dbo']->prepare($tasks_query)){
			$prep_stmt->execute([$_SESSION['request_id'], $_SESSION['id']]);
		} else {
			return false;
		}
		return $prep_stmt;
	}
?>
	<table class='requests-table'>
		<tr>
			<th>Description</th>
			<th>Status</th>
			<th>Deadline</th>
			<th>Deliver to</th>
		</tr>
<?php
		$row = request_details();
		echo '<tr id="'. $row['id'].'">';
		echo '<td>'. $row['info']. '</td>';
		echo '<td>'. $row['status']. '</td>';
		echo '<td>'. $row['deadline']. '</td>';
		echo '<td>'. $row['location']. '</td>';
		echo '</tr>';
?>
	</table>
	<input type="button" id='delete-button' value="Delete Request">
	<table class='tasks-table'>
		<tr>
			<th>Fulfiller</th>
			<th>Description</th>
			<th>Status</th>
			<th>Bounty</th>
			<th>Location</th>
		</tr>
<?php
	if($tasks = tasks_in_request()){
		foreach ($tasks as $row){
			echo '<tr id="'. $row['id'].'">';
			echo '<td>'. $row['name']. '</td>';
			echo '<td>'. $row['info']. '</td>';
			echo '<td>'. $row['status']. '</td>';
			echo '<td>'. $row['bounty']. '</td>';
			echo '<td>'. $row['short_name']. '</td>';
			echo '</tr>';
		}
	}
?>
	</table>
	<input type="button" id='fulfill-button' value="Mark Task Fulfilled">
	<input type="button" id='delete-task-button' value="Delete Task">
	<form action="add_task.php" method="post">
	<input type="hidden" name="request_id" value="<?php echo $_SESSION['request_id'] ?>">
		<input type="text" name="info" placeholder="Description">
		<input type="text" name="bounty" placeholder="Bounty" value="0.00">
<?php
		if (isset($_SESSION['location_id'])){
			echo '<input type="hidden" name="location_id" value="' .
				$_SESSION['location_id'] . '">';
			echo 'Selected Location: ' . $_SESSION['short_name'];
		}
?>
		<input type="submit" value="Add New Task To Request">
	</form>
	<form action="select_location.php" method="post">
		<input type="hidden" name="referrer" value="view_request.php">
		<input type="submit" value="Select a Location">
	</form>
	<br>
<?php
	if (!isset($_SESSION['location_id'])){
		echo "Select a location first before creating a new task.";
	}
?>
</body>
</html>
