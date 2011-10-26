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

  public function verCarreras() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL select_carreras()";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigo,$nombre_carrera,$nombreUsuarioJC,$periodo,$numero,$nombreJC,$rutJC);
    $car = 0;
    echo '<table>';
    while($res->fetch())
    {
      if($car == 0){
        echo '<tr><td>Nombre Carrera</td><td>Código</td><td>Nombre Jefe Carrera</td><td>RUT Jefe Carrera</td><td>Periodo</td><td>#Sem/Trim</td><td>Malla</td><td>Eliminar</td></tr>';
        $car = 1;}
      if($nombreJC == 'No asignado'){
        $nombreJC = '<a id="'.$codigo.'" class="asigna" href="">Asignar Jefe Carrera</a>';
        $rutJC = '';}
      else
      {
        $nombreJC = $nombreJC.'<br><a id="'.$codigo.'" class="cambia" href="">Cambiar</a>';
      }
      if($periodo == 1) $periodo = 'Semestral'; else $periodo = 'Trimestral';
      echo '<tr><td>'.$nombre_carrera.'</td><td>'.$codigo.'</td><td>'.$nombreJC.'</td><td>'.$rutJC.'</td><td>'.$periodo.'</td><td class="mid">'.$numero.'</td><td class="mid"><a id="'.$codigo.'" class="verMalla" href="">Ver malla</td><td class="mid"><a href="">X</a></td></tr>';
    }
    if(!isset($codigo))
      echo '<tr><td>No hay carreras.</td></tr>';
    echo '</table>';
    $res->free_result();
  }

  function mostrarSemestre() {
    return $this->semestre;
  }
}
?>

