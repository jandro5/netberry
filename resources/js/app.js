import './bootstrap';

window.add_task = function(url){
    event.preventDefault();
    var form = $("#form-task");
    $.ajax({
        type: "POST",
        url: url,
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
                title: 'Tarea añadida!',
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

window.delete_task = function(id, url, token) {
    event.preventDefault();
    Swal.fire({
        title: 'Estás seguro?',
        text: 'No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, eliminado!'
    }).then((result) => {
        if (result.isConfirmed) {
            let data = {
                "_token": token,
                "id": id
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data, // serializes the form's elements.
                success: function(data) {
                    $("#task-"+ id).remove();
                    Swal.fire({
                        title: 'Eliminado!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1000
                    });
                },
                error: function(data) {
                    Swal.fire(
                        'No Eliminado',
                        '',
                        'error'
                    );
                },
            });
        }
    });
}