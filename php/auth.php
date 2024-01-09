<?php
class Auth
{
  private $mysqli;
  function __construct()
  {
    $this->mysqli = new mysqli('127.0.0.1', 'root', '', 'todolist', 3306);
    if ($this->mysqli->connect_errno != null) {
      echo "Error número " . $this->mysqli->connect_errno . " conectando a la base de datos.<br>Mensaje: " . $this->mysqli->connect_error;
      exit();
    }
  }

  function __destruct()
  {
    $this->mysqli->close();
  }

  public function authenticateUser($username, $password)
  {
    $username = $this->mysqli->real_escape_string($username);
    $password = $this->mysqli->real_escape_string($password);
    $sql = "SELECT id, password FROM users WHERE username = '$username'";
    $result = $this->mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      var_dump($row); // Agrega esta línea para verificar el contenido de $row
      $dbPasswordHash = $row['password'];

      if (password_verify($password, $dbPasswordHash)) {
        $_SESSION['user_id'] = $row['id'];
        return true;
      }
    }
    return false;
  }


  public function registerUser($username, $password)
  {
    $username = $this->mysqli->real_escape_string($username);
    $password = $this->mysqli->real_escape_string($password);

    $sql = "SELECT COUNT(*) as userCount FROM users WHERE username = '$username'";
    $result = $this->mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $userCount = $row['userCount'];

      if ($userCount > 0) {
        return false;
      }
    }

    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password_hash')";
    return $this->mysqli->query($sql);
  }

  function agregarTarea($args)
  {
    if (!isset($_SESSION['user_id'])) {
      echo '<script>$.notify("Error: Usuario no autenticado", "error");</script>';
      return false;
    }
    $query = "INSERT INTO tasks (name, state_id, user_id) VALUES (?, ?, ?)";
    $result = $this->executeQuery($query,$args);
    echo $result;
  }

  public function getAllTasks($args)
  {
    try {
      $query = "SELECT T.id AS 'ID TAREA', T.name AS 'TAREA', S.description AS 'ESTADO' FROM tasks T INNER JOIN states S ON S.id = T.state_id WHERE T.user_id = ?";
      return $this->executeQuery($query, $args);
    } catch (Exception $e) {
      return false;
    }
  }
  public function lastTask($args)
  {
    try {
      $query = "SELECT T.id AS 'ID TAREA', T.name AS 'TAREA', S.description AS 'ESTADO'
          FROM tasks T
          INNER JOIN states S ON S.id = T.state_id
          WHERE T.user_id = ?
          ORDER BY T.create_date DESC
          LIMIT 1";
          $result = $this->executeQuery($query, $args);
        return $result;
    } catch (Exception $e) {  
      return false;
    }
  }


  public function deleteTask($args)
  {
    $query = 'DELETE FROM tasks WHERE id = ? AND user_id = ?';
    try {
      return $this->executeQuery($query, $args);
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  public function modifyTask($args)
  {
    $query = 'UPDATE tasks
    SET state_id = 1
    WHERE id = ? AND user_id = ?';

    try {
      return $this->executeQuery($query, $args);
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  private function executeQuery($query, $args)
  {
    try {
      $cleanedArgs = $this->cleanParam($args); // Debe pasarse un array de valores
      $values = $this->transformArray($cleanedArgs);
      $statement = $this->mysqli->prepare($query);
      $types = $this->mapTypes($cleanedArgs);
      array_unshift($values, $types);
      error_log(json_encode($values));
      $statement->bind_param(...$values);
      $statement->execute();
      return $statement->get_result();
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }

  private function mapTypes($args)
  {
    $types = '';
    foreach ($args as $arg => $key) {
      switch ($key) {
        case 'task_id':
          $types .= 'i';
          break;
        case 'user_id':
          $types .= 'i';
          break;
        case 'state_id':
          $types .= 'i';
          break;
        default:
          $types .= 's';
          break;
      }
    }
    return $types;
  }

  private function cleanParam($args)
  {
    $newArgs = array();
    foreach ($args as $arg) {
      $newArgs[] = mysqli_real_escape_string($this->mysqli, $arg);
    }
    return $newArgs;
  }

  private function transformArray($args)
  {
    $newArray = array();
    foreach ($args as $arg) {
      $newArray[] = $arg;
    }
    return $newArray;
  }

  // function agregarTarea($args)
  // {
  //   if (!isset($_SESSION['user_id'])) {
  //     echo '<script>$.notify("Error: Usuario no autenticado", "error");</script>';
  //     return false;
  //   }

  //   $user = $_SESSION['user_id'];

  //   $tarea = trim($args);
  //   $tarea = $this->mysqli->real_escape_string($tarea);

  //   // Agregar la nueva tarea a la base de datos
  //   $query = "INSERT INTO tasks (name, state_id, user_id) VALUES ('$tarea', 2, $user)";
  //   if ($this->mysqli->query($query) === TRUE) {
  //     // Obtener el ID de la tarea recién creada
  //     $nuevaTareaId = $this->mysqli->insert_id;

  //     // Realizar una segunda consulta para obtener los detalles de la tarea recién creada
  //     $query = "SELECT * FROM tasks WHERE id = $nuevaTareaId";
  //     $result = $this->mysqli->query($query);

  //     if ($result && $result->num_rows > 0) {
  //       return $result->fetch_assoc();
  //     } else {
  //       // Hubo un problema al obtener los datos de la tarea
  //       return false;
  //     }
  //   } else {
  //     return false;
  //   }
  // }
}