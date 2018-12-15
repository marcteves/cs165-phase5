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
<script>
$(document).ready(function() {

$('.locations-table td').on('click', function() {
	var tr = $(this).parent()
	$('.locations-table .selected').removeClass('selected');
	tr.toggleClass('selected');
	$('input[name=location_id]').attr("value", tr.attr('id'));
	$('input[name=short_name]').attr("value", tr.find('td').text());
});
});

</script>
</head>
<body>
<?php
include_once $_SERVER['DOCUMENT_ROOT']."db_connect.php";

// PDOStatement of Location table
function get_location_table(){
	$location_query = '
		SELECT id, short_name FROM Location';
	if ($prep_stmt = $GLOBALS['dbo']->prepare($location_query)){
		$prep_stmt->execute();
	} else {
		return false;
	}
	return $prep_stmt;
}

?>
More locations to be added.
	<table class='locations-table'>
		<tr>
			<th>Name</th>
		</tr>
<?php
	if($locations = get_location_table()){
		foreach ($locations as $row){
			echo '<tr id="'. $row['id'].'">';
			echo '<td>'. $row['short_name']. '</td>';
			echo '</tr>';
		}
	} else {
		echo 'Trouble retrieving locations.';
	}
?>
	</table>
	<form action="set_location.php" method="post">
		<input type="hidden" name="referrer" value="<?php
if(array_key_exists('referrer', $_POST)) { echo $_POST['referrer'];}
else {echo 'home.php';}
?>">
		<input type="hidden" name="location_id" value="null">
		<input type="hidden" name="short_name" value="null">
		<input type="submit" value="Set Location">
	</form>

</body>
</html>
