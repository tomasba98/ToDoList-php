<?php
require_once 'tagService.php';
require_once 'auth.php';

function getTasks()
{
    $conn = new Auth();

    $result = $conn->getAllTasks([$_SESSION['user_id']]);
    $tag = TagService::getInstance();
    if ($result !== false) {
        echo '<div id="container_table" class="--bs-body-bg">';
        if ($result->num_rows > 0) {
            echo $tag->getTable($result);
        } else {
            echo $tag->getTableInit();
        }
        echo '</div>';
    } else {    
        echo "An error occurred while fetching tasks.";
    }
}

getTasks();

?>

<script>
$(document).ready(function() {
    const form = $('#form-Tarea');
    const table = $('#table').DataTable();
    form.on('submit', function(event) {
        event.preventDefault();
        const taskValue = $('[name="task"]').val();
        $.ajax({
            url: "../php/add.php",
            method: 'POST',
            data: {
                task: taskValue
            },
        }).done(function(data) {
            $.ajax({
                url: '../php/getLastTask.php',
                method: 'POST',
            }).done(function(datosActualizados) {

                var nuevaFila = $(
                    datosActualizados); // Convierte el HTML en un elemento jQuery
                    console.log(datosActualizados);
                table.row.add(nuevaFila).draw();
            });
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error al agregar tarea:', errorThrown);
        });
    });
});
$(document).ready(function() {
    $('#table').on('change', function() {
        cargarEventListeners();
    });

    cargarEventListeners();

    function cargarEventListeners() {
        $('#table').off('click', '.modify_btn').on('click', '.modify_btn', function(event) {
            const button = $(this);
            button.prop('disabled', true);
            const row = button.closest('tr');
            event.preventDefault();
            const task_id = button.data('element-id');
            const user_id = '<?php echo $_SESSION['user_id']; ?>';

            $.ajax({
                url: '../php/modify.php',
                method: 'POST',
                data: {
                    task_id: task_id,
                    user_id: user_id
                },
            });

            const thirdTd = row.find('td:eq(2)');
            thirdTd.html('Completada');
            $.notify("Se modificó correctamente", "success");
        });

        $('#table').off('click', '.delete_btn').on('click', '.delete_btn', function(event) {
            const button = $(this);
            const row = button.closest('tr');
            event.preventDefault();
            const task_id = button.data('element-id');
            const user_id = '<?php echo $_SESSION['user_id']; ?>';

            $.ajax({
                url: '../php/delete.php',
                method: 'POST',
                data: {
                    task_id: task_id,
                    user_id: user_id
                },
            }).done(function() {
                $.notify("Se eliminó correctamente", "success");
                row.remove();
                $.ajax({
                    url: '../php/getTasks.php', // Cambia a la URL que obtiene las tareas actualizadas
                    method: 'GET',
                }).done(function(nuevaTabla) {
                    $('#container_table').html(nuevaTabla);
                    cargarEventListeners(); // Vuelve a cargar los event listeners
                });
            });
        });
    }

});
</script>