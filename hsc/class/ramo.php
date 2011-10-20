<?php
include_once('db/connect.php');
include_once('db/funciones.php');

class ramo {
  public $codigo;
  public $nombre;

  function __construct($codigo,$nombre) {
    $this->codigo = $codigo;
    $this->nombre = $nombre;
  }

  function agregarSeccion() {
  }
}
?>
