$(document).ready(function() {
	var task_id = null;
	var other_task_id = null;
	var request_id = null;

	// next three functions just higlight and select a row
	$('.tasks-table td').on('click', function() {
		var tr = $(this).parent()
		$('.tasks-table .selected').removeClass('selected');
		tr.toggleClass('selected');
		task_id = tr.attr('id');
	});

	$('.requests-table td').on('click', function() {
		var tr = $(this).parent()
		$('.requests-table .selected').removeClass('selected');
		tr.toggleClass('selected');
		request_id = tr.attr('id');
	});

	$('.available-tasks-table td').on('click', function() {
		var tr = $(this).parent()
		$('.available-tasks-table .selected').removeClass('selected');
		tr.toggleClass('selected');
		other_task_id = tr.attr('id');
	});

	// corresponding actions when buttons are clicked
	$('#unaccept-button').on('click', function() {
		post('unaccept_task.php', {task_id: task_id});
	});

	$('#view-button').on('click', function() {
		post('view_request.php', {request_id: request_id});
	});

	$('#accept-button').on('click', function() {
		post('accept_task.php', {task_id: other_task_id});
	});

});
