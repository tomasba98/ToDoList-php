<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
echo $_SESSION['username'];
$username = $_SESSION['username'];
?>

<?php require_once './layout/header.php'; ?>

<div class="container" id="buscador">
    <form id="form-Tarea">
        <div class="form-row align-items-center">
            <div class="col-auto">
                <div class="form-group">
                    <input type="text" class="form-control" required id="tarea-nueva" name="task" placeholder="Nombre de tarea">
                </div>
            </div>
            <div class="col-auto align-self-start">
                <button type="submit" class="btn btn-primary" id="btn_main">Agregar</button>
            </div>
        </div>
    </form>
</div>

<?php require_once 'php/taskTable.php' ?>

<?php require_once './layout/footer.php'; ?>