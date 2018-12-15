$(document).ready(function() {
var task_id = null;
var request_id = $('.requests-table tr:last').attr('id');

$('.tasks-table td').on('click', function() {
	var tr = $(this).parent()
	$('.tasks-table .selected').removeClass('selected');
	tr.toggleClass('selected');
	task_id = tr.attr('id');
});

$('#fulfill-button').on('click', function() {
	post('fulfill_task.php', {task_id: task_id});
});

$('#delete-task-button').on('click', function() {
	post('delete_task.php', {task_id: task_id});
});

$('#delete-button').on('click', function() {
	post('delete_request.php', {request_id: request_id});
});
});
