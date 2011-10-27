<?php
require_once('db/connect.php');
require_once('db/funciones.php');

class carrera {
  public $codigo;
  public $nombre;
  public $escuela;
  public $semestre;

  function __construct($codigo,$nombre,$escuela,$semestre) {
    $this->codigo = $codigo;
    $this->nombre = $nombre;
    $this->escuela = $escuela;
    $this->semestre = $semestre;
  }

  function asignarProfesor() {
  }

  function asignarRamo() {
  }  


  function mostrarSemestre() {
    return $this->semestre;
  }
}
?>

