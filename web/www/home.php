<!DOCTYPE html>
<html>
<head>
<style>
.header {
	display: flex;
	flex-direction: row;
}

.page-body {
	display: flex;
	flex-direction: row;
	justify-content: space-between;
}

.page-body > div {
	margin: 10px;
}

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
<script src='home.js'></script>
</head>
<body>
<?php
	include_once $_SERVER['DOCUMENT_ROOT']."user_info.php";
	$user_data = get_user_data($_SESSION['email']);
	echo 'Welcome, ' . $user_data["name"] . '<br>';
	echo 'You are located at: ' . $user_data["short_name"] . '<br>';
	if ($user_data['short_name'] === NULL) {
		header('Location:  select_location.php');
	}
	# unset($_SESSION['request_id']);
?>
	<br>
	<div class='header'>
		<form action=logout.php method=post>
			<input type="submit" value="Logout">
		</form>
		<form action=select_location.php method=post>
			<input type="hidden" name="referrer" value="home.php">
			<input type="submit" value="Set location">
		</form>
	</div>
	<br>
	<div class='page-body'>
		<div class='tasks-panel'>
			<div class='accepted-tasks'>
			Tasks you've accepted:
			<table class='tasks-table'>
				<tr>
					<th>Poster</th>
					<th>Description</th>
					<th>Bounty</th>
					<th>Deadline</th>
					<th>Location</th>
					<th>Deliver to</th>
				</tr>
			<?php
	if($accepted_tasks = get_user_accepted_tasks()){
		foreach ($accepted_tasks as $row){
			echo '<tr id="'. $row['id'].'">';
			echo '<td>'. $row['name']. '</td>';
			echo '<td>'. $row['info']. '</td>';
			echo '<td>'. $row['bounty']. '</td>';
			echo '<td>'. $row['deadline']. '</td>';
			echo '<td>'. $row['source']. '</td>';
			echo '<td>'. $row['target']. '</td>';
			echo '</tr>';
		}
	} else {
		echo 'Trouble retrieving accepted tasks.';
	}
			?>
			</table>
			<input type="button" id='unaccept-button' value="Unaccept Task">
			</div>
			<div class='posted-requests'>
			Requests you've posted:
			<table class='requests-table'>
				<tr>
					<th>Description</th>
					<th>Status</th>
					<th>Deadline</th>
					<th>Deliver to</th>
				</tr>
			<?php
	if($posted_requests = get_user_posted_requests()){
		foreach ($posted_requests as $row){
			echo '<tr id="'. $row['id'].'">';
			echo '<td>'. $row['info']. '</td>';
			echo '<td>'. $row['status']. '</td>';
			echo '<td>'. $row['deadline']. '</td>';
			echo '<td>'. $row['location']. '</td>';
			echo '</tr>';
		}
	} else {
		echo 'Trouble retrieving accepted tasks.';
	}
			?>
			</table>
			<input type="button" id='view-button' value="View Request">
			<br>
			<form action=add_request.php method=post>
			<input type="text" name="info" placeholder="Description">
			<input type="text" name="deadline"
				 placeholder="Deadline" value="2018-12-24" readonly='readonly'>
				<input type="hidden" name="referrer" value="home.php">
				<input type="submit" value="Add Request">
			</form>
			</div>
		</div>
		<div class='available-panel'>
			Available Tasks:
			<table class='available-tasks-table'>
				<tr>
					<th>Poster</th>
					<th>Description</th>
					<th>Bounty</th>
					<th>Deadline</th>
					<th>Location</th>
					<th>Deliver to</th>
				</tr>
			<?php
	if($available_tasks = get_closest_tasks()){
		foreach ($available_tasks as $row){
			echo '<tr id="'. $row['id'].'">';
			echo '<td>'. $row['name']. '</td>';
			echo '<td>'. $row['info']. '</td>';
			echo '<td>'. $row['bounty']. '</td>';
			echo '<td>'. $row['deadline']. '</td>';
			echo '<td>'. $row['source']. '</td>';
			echo '<td>'. $row['target']. '</td>';
			echo '</tr>';
		}
	} else {
		echo 'Trouble retrieving available tasks.';
	}
			?>
		</table>
		<input type="button" id='accept-button' value="Accept Task">
		</div>
	</form>
	</div>
</body>
