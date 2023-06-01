@extends('layouts.app')
@section('title','All task')
@section('content')
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Email</th>
      <th scope="col">Contact</th>
      <th scope="col">Gender</th>
      <th scope="col">Hobbies</th>
      <th scope="col">State</th>
      <th scope="col">City</th>
      <th scope="col">Profile</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id="task-table-body"></tbody>
</table>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Define the CSRF token value as a global JavaScript variable
  var csrfToken = "{{ csrf_token() }}";

  $(document).ready(function() {
    // Make an AJAX request to retrieve task data
    $.ajax({
      url: '/api/tasks',
      type: 'GET',
      dataType: 'json',
      success: function(response) {
        // Iterate through the tasks and append rows to the table
        var tasks = response;
        var tableBody = $('#task-table-body');
        tasks.forEach(function(task) {
          var row = '<tr>' +
            '<th scope="row">' + task.id + '</th>' +
            '<td>' + task.name + '</td>' +
            '<td>' + task.email + '</td>' +
            '<td>' + task.contact_number + '</td>' +
            '<td>' + task.gender + '</td>' +
            '<td>';

          // Iterate over the hobbies array and concatenate the names
          for (var i = 0; i < task.hobbies.length; i++) {
            row += task.hobbies[i].name;
            if (i < task.hobbies.length - 1) {
              row += ', ';
            }
          }

          var profilePic = "{{ asset('storage/') }}/" + task.profile_pic;
          var imageElement = $('<img>').attr('src', profilePic).addClass('img-fluid').attr('width', '100').attr('height', '100').prop('outerHTML');
          var tableCell = $('<td>').append(imageElement);

          row += '</td>' +
            '<td>' + task.state.name + '</td>' +
            '<td>' + task.city.name + '</td>' +
            '<td>' + imageElement + '</td>' +
            '<td>' +
            '<a href="{{ url("/task/") }}/' + task.id + '/show" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;' +
            '<a href="{{ url("/task/") }}/' + task.id + '/edit" class="btn btn-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>&nbsp;' +
            '<a href="#" class="btn btn-danger delete" data-taskId="' + task.id + '"><i class="fa fa-trash" aria-hidden="true"></i></a>&nbsp;' +
            '</td>' +
            '</tr>';

          tableBody.append(row);
        });
      },
      error: function(xhr, status, error) {
        console.log(error); // Handle error if necessary
      }
    });

    // Event delegation for the delete button
    $('#task-table-body').on('click', '.delete', function(event) {
      event.preventDefault();
      var taskId = $(this).data('taskid');
      if(taskId !== ""){
        deleteTask(taskId);
      }
    });

    function deleteTask(taskId) {
      if (confirm("Are you sure you want to delete this task?")) {
        $.ajax({
          url: '/api/tasks/' + taskId,
          type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          success: function(response) {
            alert('Task deleted successfully');
            location.reload();
          },
          error: function(xhr, status, error) {
            alert('Failed to delete task');
            location.reload();
          }
        });
      }
    }
  });
</script>
@endsection
