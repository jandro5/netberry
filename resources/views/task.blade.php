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
                <button class="btn btn-primary mb-3" onclick="add_task('{{ route('task.store') }}')">@lang('Add')</button>
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
                <!-- Otra forma de hacerlo -->
                @php
                $aux = 0;
                $i = 0;
                @endphp
                @foreach ($tasks as $t)
                    @if($t->id != $aux && $i > 0)
                        </td>
                        <td><a class="btn btn-danger" onclick="delete_task({{ $aux }}, '{{route('task.destroy')}}', '{{ csrf_token() }}')"><i class="fa-solid fa-trash-can"></i></a></td>
                        </tr>
                    @endif
                    @if($t->id != $aux)
                    <tr id="task-{{ $t->id }}">
                        <td>{{ $t->description }}</td>
                        <td>
                    @endif
                        
                    <span class="badge bg-secondary">{{ $t->category }}</span>

                    @php
                    $aux = $t->id;
                    $i++;
                    @endphp
                @endforeach
                </td>
                <td><a class="btn btn-danger" onclick="delete_task({{ $aux }}, '{{route('task.destroy')}}', '{{ csrf_token() }}')"><i class="fa-solid fa-trash-can"></i></a></td>
                </tr>

                {{-- 
                <!-- By objects and subobjects -->
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
                --}}
            </tbody>
        </table>

    </div>
        
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
  </body>
</html>