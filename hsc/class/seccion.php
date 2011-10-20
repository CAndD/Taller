<?php
include_once('db/connect.php');
include_once('db/funciones.php');

class seccion {
  public $nrc;
  public $numeroSeccion;
  public $vacantes;
  public $estado;

  function __construct($nrc,$numeroSeccion,$vacantes,$estado) {
    $this->nrc = $nrc;
    $this->numeroSeccion = $numeroSeccion;
    $this->vacantes = $vacantes;
    $this->estado = $estado;
  }

  public function mostrarVacantesIniciales() {
    return $this->vacantes;
  }

  private function descontarVacantes($vacantesPedidas) {
    if(($this->vacantes - $vacantesPedidas) >= 0)
    {
      $this->vacantes = $this->vacantes - $vacantesPedidas;
    }
  }

  private function asignarHorario() {
  }

  private function asignarProfesor() {
  }
}
?>
