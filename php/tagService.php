<?php

class TagService
{
  private static $instance;

  private function __construct()
  {
  }

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new TagService();
    }
    return self::$instance;
  }

  private static function transform($result)
  {
    $table = [
      'title' => [],  // Arreglo para las columnas
      'body' => []    // Arreglo para los registros
    ];
    // Verifica que $result sea un objeto válido
    if ($result && $result->num_rows > 0) {
      $firstRow = $result->fetch_assoc();
      $table['title'] = array_keys($firstRow);

      // Reinicia el puntero del resultado
      $result->data_seek(0);
      while ($row = $result->fetch_assoc()) {
        $table['body'][] = array_values($row);
      }
    }

    return $table;
  }

  public static function getRow($data)
  {
    $count = 0;
    $disabled = false;
    $trow = '<tr>';
    while ($row = $data->fetch_assoc()) {
      foreach ($row as $registerValue) {
        if ($count === 0) {
          $idTask = $registerValue;
        }
        if ($count === 2 && $registerValue === 'Completada') {
          $disabled = true;
        }
        $count++;
        $trow .= '<td>' . $registerValue . '</td>';
      }
      $trow .= '<td>
      <div>
          <button class="btn btn-success mx-1 modify_btn" data-element-id="' . $idTask . '"' . ($disabled ? 'disabled' : '') . '>Completar tarea</button>
          <button class="btn btn-danger mx-1 delete_btn" data-element-id="' . $idTask . '">Eliminar tarea</button>
      </div>
      </td></tr>';
    }
    return $trow;
  }

  public static function getTableInit(){
    error_log("tuvieja");
    $args = '<table class="table-dark table-striped" id="table">
      <thead>
        <tr>
          <td>ID TAREA</td>
          <td>TAREA</td>
          <td>ESTADO</td>
          <td>ACCIONES</td>
        </tr>
      </thead>
    </table>';
    return $args;
  }

  public static function getTable($data)
  {
    $field = self::transform($data);
    $args = '<table class="table-dark table-striped" id="table">' .
      self::getTitle($field['title']) .
      self::getBody($field['body']) .
      '</table>';
    return $args;
  }

  private static function getTitle($titles)
  {
    $thead = '<thead>
  <tr>';
    foreach ($titles as $title) {
      $thead .= '<th>' . $title . '</th>';
    }
    $thead .= '<th>Acciones</th>';
    $thead .= '</tr></thead>';
    return $thead;
  }

  private static function getBody($body)
  {
    $tbody = '<tbody id=tbody>';
    foreach ($body as $arg) {
      $tbody .= '<tr>';
      $count = 0;
      $disabled = false;
      $idTask = ''; // Declarar $idTask fuera del bucle
      foreach ($arg as $registerValue) {
        if ($count === 0) {
          $idTask = $registerValue;
        }
        if ($count === 2 && $registerValue === 'Completada') {
          $disabled = true;
        }
        $count++;
        $tbody .= '<td>' . $registerValue . '</td>';
      }
      $tbody .= '<td>
      <div>
          <button class="btn btn-success mx-1 modify_btn" data-element-id="' . $idTask . '"' . ($disabled ? 'disabled' : '') . '>Completar tarea</button>
          <button class="btn btn-danger mx-1 delete_btn" data-element-id="' . $idTask . '">Eliminar tarea</button>
      </div>
      </td></tr>';
    }
    $tbody .= '</tbody>';
    return $tbody;
  }
}