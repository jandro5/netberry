<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo jQuery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="container mt-5">
        <h1>@lang('task_manager')</h1>

        <form class="row g-3" id="form-task">
            @csrf
            <div class="col">
                <label for="description" class="visually-hidden">{{ __('New_task') }}</label>
                <input type="text" class="form-control" id="description" name="description" aria-describedby="task" placeholder="{{ __('New_task') }}">
            </div>
            <div class="col-auto">
                @foreach ($categories as $c)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="categories-{{ $c->id }}" name="categories[]" data-name="{{ $c->name }}" value="{{ $c->id }}">
                    <label class="form-check-label" for="categories-{{ $c->id }}">{{ $c->name }}</label>
                </div>
            @endforeach
            </div>
            <div class="col-auto">
                <button class="btn btn-primary mb-3" onclick="add_task()">@lang('Add')</button>
            </div>
        </form>
        
        <table class="table mt-3">
            <thead class="table-dark">
                <tr>
                    <th scope="col">{{ __('Task') }}</th>
                    <th scope="col-auto">{{ trans_choice('Category', 2) }}</th>
                    <th scope="col-auto">{{ trans_choice('Action', 2) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $t)
                    <tr id="task-{{ $t->id }}">
                        <td>{{ $t->description }}</td>
                        <td>
                            @foreach ($t->categories as $c)
                                <span class="badge bg-secondary">{{ $c->name }}</span>
                            @endforeach
                        </td>
                        <td><a class="btn btn-danger" onclick="delete_task({{ $t->id }})"><i class="fa-solid fa-trash-can"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <script>
        function add_task(){
            event.preventDefault();
            var form = $("#form-task");
            $.ajax({
                type: "POST",
                url: "{{ route('task.store') }}",
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
                    let markup = '<tr id="task-'+ data.id +'"><td>' + $('#description').val() + '</td>';
                    let categories = $('input[name="categories[]"]:checked');
                    markup += '<td>';
                    categories.each(function() {
                        markup += '<span class="badge bg-secondary">'+ this.getAttribute('data-name') +'</span>\n';
                    });
                    markup += '</td>';
                    markup += '<td><a class="btn btn-danger" onclick="delete_task('+ data.id +')"><i class="fa-solid fa-trash-can"></i></a></td>';
                    markup += '</tr>';
                    
                    $("table tbody").append(markup);
                    form.trigger("reset");

                    Swal.fire({
                        title: '@lang("Task added!")',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                },
                error: function(data) {
                    Swal.fire(
                        'Error!',
                        data.responseJSON.message,
                        'warning'
                    );
                },
                    
            });
        }

        function delete_task(id) {
            event.preventDefault();
            console.log(this);
            Swal.fire({
                title: '@lang("Are you sure?")',
                text: '@lang("You will not be able to revert this!")',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: '@lang("Cancel")',
                confirmButtonText: '@lang("Yes, delete it!")'
            }).then((result) => {
                if (result.isConfirmed) {
                    let data = {
                        "_token": "{{ csrf_token() }}",
                        "id": id
                    }
                    $.ajax({
                        type: "POST",
                        url: "/task/destroy",
                        data: data, // serializes the form's elements.
                        success: function(data) {
                            $("#task-"+ id).remove();
                            Swal.fire({
                                title: '@lang("Deleted")!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        },
                        error: function(data) {
                            Swal.fire(
                                '@lang("Not Deleted")',
                                '',
                                'error'
                            );
                        },
                    });
                }
            });
        }
    </script>
        
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  </body>
</html>