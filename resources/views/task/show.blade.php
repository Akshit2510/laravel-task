@extends('layouts.app')
@section('title', 'Edit task')
@section('content')
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endsection
<form enctype="multipart/form-data">
    @include('task.partials._form', compact('states', 'hobbies', 'task'))
</form>
@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('form').submit(function(event) {
            event.preventDefault();
            var id = @if(isset($task) && !empty($task->id)) {{ $task->id }} @else null @endif;
            var form = $(this);
            var formData = new FormData(form[0]);

            $.ajax({
                url: '/api/tasks/'+id,
                type: 'PUT',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Task updated successfully');
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $('.error-message').remove();

                        $.each(errors, function(field, messages) {
                            var input = form.find('[name="' + field + '"]');
                            input.addClass('is-invalid');

                            if (field === 'hobbies[]') {
                                var select2Container = input.next('.select2-container');
                                select2Container.addClass('is-invalid');
                                select2Container.after('<div class="invalid-feedback error-message">Please select at least one hobby.</div>');
                            }

                            // Handle profile pic validation error
                            if (field === 'profile_pic') {
                                var inputGroup = input.closest('.input-group');
                                inputGroup.addClass('is-invalid');
                                inputGroup.after('<div class="invalid-feedback error-message">' + messages[0] + '</div>');
                            }

                            $.each(messages, function(index, message) {
                                input.after('<div class="invalid-feedback error-message">' + message + '</div>');
                            });
                        });
                    } else {
                        alert('Failed to create task. Please try again later.');
                    }
                }
            });
        });

        var stateId = $("#state").val();
        if(stateId != "")
        {
            getCity(stateId);
        }       
        $('#state').change(function() {
        var stateId = $(this).val();
        getCity(stateId);
    });

        function getCity(stateId){
            $.ajax({
                url: '/task/cities/' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#city').empty();
                    $.each(response, function(index, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
});
</script>
@endsection
@endsection