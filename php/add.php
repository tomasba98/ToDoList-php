<?php
require_once 'auth.php';
session_start();
$Conn = new Auth();

if (isset($_POST['task'])) {
  $info = array(
    'task_name' => $_POST['task'],
    'state_id' => 2,
    'user_id' => $_SESSION['user_id']
  );
  error_log('Por agregar');
  error_log(json_encode($info));
  $result = $Conn->agregarTarea($info);
  if ($result !== false) {
    http_response_code(200);
    echo json_encode(array('message' => 'Tarea creada exitosamente'));
    exit();
  } else {
    http_response_code(500); // Error interno del servidor
    echo json_encode(array('message' => json_encode($result)));
  }
} else {
  http_response_code(400); // Solicitud incorrecta
  echo json_encode(array('message' => 'Falta el parámetro task_id en la solicitud'));
}