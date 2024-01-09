<?php
require_once 'auth.php';

$Conn = new Auth();

if (isset($_POST['task_id'])) {
  $info = array(
    'task_id' => $_POST['task_id'],
    'user_id' => $_POST['user_id']
  );
  $result = $Conn->modifyTask($info);
  if ($result === false) {
    http_response_code(200);
    echo json_encode(array('message' => 'Tarea modificada exitosamente'));
  } else {
    http_response_code(500); // Error interno del servidor
    echo json_encode(array('message' => 'Error al modificar la tarea'));
  }
} else {
  http_response_code(400); // Solicitud incorrecta
  echo json_encode(array('message' => 'Falta el parámetro task_id en la solicitud'));
}
