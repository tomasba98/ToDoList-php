<?php
require_once 'auth.php';
require_once 'tagService.php';
session_start();
$Conn = new Auth();
$tag = TagService::getInstance();


$result = $Conn->lastTask([$_SESSION['user_id']]);
if ($result !== false) {
    error_log(json_encode($result));
    $row = $tag->getRow($result); 
    http_response_code(200);
    echo $row;
    exit();
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Error al Crear la tarea'));
}
