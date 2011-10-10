<?php
include('db/connect.php');

class usuario {
  public $nombre;
  public $nombreUsuario;
  public $tipo;
  public $rut;
  private $password;
  private $login;

  function __construct($nombreUsuario,$password) {
    $this->nombreUsuario = $nombreUsuario;
    $this->password = $password;
    $this->login = false;
  }

  function __destruct() {
    unset($this->nombreUsuario);
    unset($this->password);
    unset($this->login);
    unset($this->tipo);
    unset($this);
  }

  public function ingresarAlSistema() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $nombreUsuario = $this->getNombreUsuario();
    $pass = $this->getPassword();
    $sql = "CALL user_login('{$nombreUsuario}','{$pass}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($rut,$nombre,$tipo);
    if($res->fetch())
    {
      $this->setLogin(true);
      $this->setTipo($tipo);
      if($this->getTipo() == 1 || $this->getTipo() == 3) {
        $jdc = new jefeDeCarrera($nombre,$this->getNombreUsuario(),$rut,$this->getTipo());
        $_SESSION['usuario'] = serialize($jdc);
        $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sql2 = "CALL jdc_carreras('{$jdc->getNombreUsuario()}')";
        $res2 = $mysqli2->prepare($sql2);
        $res2->execute();
        $res2->bind_result($codigo,$nombre);
        $i = 0;
        while($res2->fetch()) {
          $_SESSION['carrera'] = $codigo;
          $i++;
        }
        if($i == 0)
          $_SESSION['carrera'] = 0;
        elseif($i>1)
          $_SESSION['carrera'] = null;
          $_SESSION['nroCarrera'] = $i;
        $res2->free_result();
      }
      elseif($this->getTipo() == 2) {
        $admin = new administrador($nombre,$this->getNombreUsuario(),$rut,$this->getTipo());
        $_SESSION['usuario'] = serialize($admin);
      }
    }
    $res->free_result();
  }

  public function visualizarPanelDeControl($nombreUsuario) {
  }

  public function getNombre() {
    return $this->nombre;
  }

  public function getNombreUsuario() {
    return $this->nombreUsuario;
  }

  public function getPassword() {
    return $this->password;
  }
  
  public function getTipo() {
    return $this->tipo;
  }

  public function setTipo($nuevoTipo) {
    $this->tipo = $nuevoTipo;
  }

  public function getRut() {
    return $this->rut;
  }

  public function getLogin() {
    return $this->login;
  }

  public function setLogin($nuevoLogin) {
    $this->login = $nuevoLogin;
  }

  public function cerrarSesion() {
    $_SESSION = array();
    $session_name = session_name();
    session_destroy();
  }
}

class jefeDeCarrera extends usuario {

  function __construct($nombre,$nombreUsuario,$rut,$tipo) {
    $this->nombre = $nombre;
    $this->nombreUsuario = $nombreUsuario;
    $this->rut = $rut;
    $this->tipo = $tipo;
  }

  function __destruct() {
    unset($this->nombre);
    unset($this->nombreUsuario);
    unset($this->rut);
    unset($this->tipo);
    unset($this);
  }

  public function verMalla($codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL ver_malla('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$semestreRamo);
    $flag = 0;
    echo '<table><tr><td>Semestre</td><td>Código</td><td>Nombre</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      echo '<tr><td>'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay ramos asociados a la carrera.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verRamosQuePiden($codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL sol_pidieron('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($nro_sol,$nombreUsuarioJC,$NrcSeccion,$vacantes);
    $flag = 0;
    echo '<table><tr><td>#</td><td>Remitente</td><td>NRC</td><td>Vacantes</td></tr>';
    while($res->fetch())
    {
      $flag = 1;
      echo '<tr><td>'.$nro_sol.'</td><td>'.$nombreUsuarioJC.'</td><td>'.$NrcSeccion.'</td><td>'.$vacantes.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No existen solicitudes.</td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verRamosQuePido($nombreUsuario) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL sol_pedidas('{$nombreUsuario}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($nro_sol,$codigoCarrera,$NrcSeccion,$vacantes);
    $flag = 0;
    echo '<table><tr><td>#</td><td>Solicitante</td><td>NRC</td><td>Vacantes</td></tr>';
    while($res->fetch())
    {
      $flag = 1;
      echo '<tr><td>'.$nro_sol.'</td><td>'.$codigoCarrera.'</td><td>'.$NrcSeccion.'</td><td>'.$vacantes.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No existen solicitudes.</td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verProgramacionVsPresupuesto() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL presupuesto('INF1200')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($presupuesto);
    echo '<table>';
    if($res->fetch())
    {
      echo '<tr><td>'.$rut.'</td><td>'.$nombre.'</td></tr>';
    }
    else
    {
      echo '<tr><td><a href="">Ingresar presupuesto del semestre.</a></td></tr>';
    }
    echo '</table>';
    $res->free_result();
  }

  public function verProfesoresAsignados($codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL prof_asignados('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($rut,$nombre);
    $flag = 0;
    echo '<table><tr><td>RUT</td><td>Nombre</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      echo '<tr><td>'.$rut.'</td><td>'.$nombre.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay profesores asignados.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verProfesoresSinCargaAcademica($codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL prof_asignados_sc('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($rut,$nombre);
    $flag = 0;
    echo '<table><tr><td>RUT</td><td>Nombre</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      echo '<tr><td>'.$rut.'</td><td>'.$nombre.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay profesores sin carga.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verSeccionesSinProfesor($codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL seccion_sprofe('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$NRCSeccion);
    $flag = 0;
    echo '<table><tr><td>Nombre</td><td>Código</td><td>NRC</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      echo '<tr><td>'.$nombreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$NRCSeccion.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay secciones sin profesor.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  private function programarHorario() {
  }
}

class administrador extends usuario {
  
  function __construct($nombre,$nombreUsuario,$rut,$tipo) {
    $this->nombre = $nombre;
    $this->nombreUsuario = $nombreUsuario;
    $this->rut = $rut;
    $this->tipo = $tipo;
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
        echo '<tr><td>Nombre Carrera</td><td>Código</td><td>Nombre Jefe Carrera</td><td>RUT Jefe Carrera</td><td>Periodo</td><td># Sem/Trim</td></tr>';
        $car = 1;}
      if($nombreJC == 'No asignado'){
        $nombreJC = '<a id="'.$codigo.'" class="asigna" href="">Asignar Jefe Carrera</a>';
        $rutJC = '';}
      else
      {
        $nombreJC = $nombreJC.'<br><a id="'.$codigo.'" class="cambia" href="">Cambiar</a>';
      }
      if($periodo == 1) $periodo = 'Semestral'; else $periodo = 'Trimestral';
      echo '<tr><td>'.$nombre_carrera.'</td><td>'.$codigo.'</td><td>'.$nombreJC.'</td><td>'.$rutJC.'</td><td>'.$periodo.'</td><td>'.$numero.'</td></tr>';
    }
    if(!isset($codigo))
      echo '<tr><td>No hay carreras.</td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function agregarCarrera($codigo,$nombre,$periodo,$nro) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL agregar_carrera('{$codigo}','{$nombre}','{$periodo}','{$nro}')";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Carrera agregada con éxito.';
    }
    else
    {
      $answer = '*Carrera ya existe.';
    }
    return $answer;
  }

  private function modificarCarrera() {
  }

  private function eliminarCarrera() {
  }

  public function verJefesDeCarrera() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL select_jefe_carrera()";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($nombreUsuarioJC,$rutJC,$nombreJC);
    $flag = 0;
    echo '<table>';
    while($res->fetch())
    {
      if($flag == 0)
      {
        echo '<tr><td>Nombre</td><td>RUT</td><td>Nombre de usuario</td><td>Eliminar</td></tr>';
        $flag = 1;
      }
      echo '<tr><td>'.$nombreJC.'</td><td>'.$rutJC.'</td><td>'.$nombreUsuarioJC.'</td><td><a id="'.$nombreUsuarioJC.'" class="eliminar" href="">X</a></td></tr>';
    }
    if(!isset($rutJC))
      echo 'No hay carreras.';
    echo '</table>';
    $res->free_result();
  }

  public function agregarJefeDeCarrera($rut,$nombre,$nusuario,$pass) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $pass = md5($pass);
    $sql = "CALL agregar_jefe_carrera('{$rut}','{$nombre}','{$nusuario}','{$pass}')";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Jefe de Carrera agregado con éxito.';
    }
    else
    {
      $answer = '*Jefe de carrera ya existe.';
    }
    return $answer;
  }

  public function modificarJefeDeCarrera() {
  }
 
  public function eliminarJefeDeCarrera($nombreUsuario) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL eliminar_jdc('{$nombreUsuario}')";
    if(($mysqli->query($sql)) == true)
    {
      $msg = 'Jefe de carrera eliminado.';
      return $msg;
    }
    else
    {
      $msg = 'Jefe de carrera no eliminado.';
      return $msg;
    } 
  }

  private function agregarUsuario() {
  }

  private function modificarUsuario() {
  }

  private function eliminarUsuario() {
  }

  public function verRamos() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL select_ramos()";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigo,$nombre,$teoria,$ayudantia,$laboratorio,$taller,$creditos);
    $car = 0;
    echo '<table>';
    while($res->fetch())
    {
      if($car == 0){
        echo '<tr><td>Codigo</td><td>Nombre</td><td>Teó.</td><td>Ayu.</td><td>Lab.</td><td>Tall.</td><td>Créd.</td></tr>';
        $car = 1;}
      echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$teoria.'</td><td>'.$ayudantia.'</td><td>'.$laboratorio.'</td><td>'.$taller.'</td><td>'.$creditos.'</td></tr>';
    }
    if(!isset($codigo))
      echo '<tr><td>No hay carreras.</td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function agregarRamo($codigo,$nombre,$teo,$ayu,$lab,$tall,$cre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL agregar_ramo('{$codigo}','{$nombre}','{$teo}','{$ayu}','{$lab}','{$tall}','{$cre}')";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Ramo agregado con éxito.';
    }
    else
    {
      $answer = '*Ramo ya existe.';
    }
    return $answer;
  }

  private function modificarRamo() {
  }

  private function eliminarRamo() {
  }

  public function relacionarRamoConCarrera($codigoRamo,$codigoCarrera,$semestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL relacionar_cramos('{$codigoRamo}','{$codigoCarrera}','{$semestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($answer2);
    $res->fetch();
    if($answer2 == 1)
    {
      $answer2 = '*Carrera y ramo relacionados con éxito.';
    }
    else
    {
      $answer2 = '*Esta relación ya existe.';
    }
    return $answer2;
    $res->free_result();
  }
}

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

class profesor {
  public $rut;
  public $nombre;
  public $email;
}

class horario {
  public $codigo;
  public $modulo;
  public $dia;
}

