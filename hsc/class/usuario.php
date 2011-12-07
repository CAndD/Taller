<?php
include_once('db/connect.php');
include_once('db/funciones.php');

class usuario {
  public $nombre;
  public $nombreUsuario;
  public $rut;
  private $password;

  function __construct($nombreUsuario,$password) {
    $this->nombreUsuario = $nombreUsuario;
    $this->password = $password;
  }

  function __destruct() {
    unset($this->nombreUsuario);
    unset($this->password);
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
      if($tipo == 1 || $tipo == 3) 
      {
        $jdc = new jefeDeCarrera($nombre,$nombreUsuario,$rut);
        $_SESSION['usuario'] = serialize($jdc);
        $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sql2 = "CALL jdc_carreras('{$nombreUsuario}')";
        $res2 = $mysqli2->prepare($sql2);
        $res2->execute();
        $res2->bind_result($codigo,$nombre,$periodo);
        $i = 0;
        while($res2->fetch()) 
        {
          $_SESSION['carrera'] = $codigo;
          $i++;
        }
        if($i == 0)
          $_SESSION['carrera'] = 0;
        elseif($i == 1) {
          $semestre = obtenerSemestre($periodo);
          $_SESSION['codigoSemestre'] = $semestre;
        }
        elseif($i>1)
          $_SESSION['carrera'] = null;
        $_SESSION['nroCarrera'] = $i;
        $res2->free_result();
        $_SESSION['tipoUsuario'] = $tipo;
        $login = true;
      }
      elseif($tipo == 2) 
      {
        $admin = new administrador($nombre,$this->getNombreUsuario(),$rut,$tipo);
        $_SESSION['usuario'] = serialize($admin);
        $_SESSION['tipoUsuario'] = $tipo;
        $login = true;       
      }
    }
    $res->free_result();
    if(!isset($login))
      $login = false;
    return $login;
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

  public function getRut() {
    return $this->rut;
  }

  public function cerrarSesion() {
    $_SESSION = array();
    $session_name = session_name();
    session_destroy();
  }
}

class administrador extends usuario {
  
  function __construct($nombre,$nombreUsuario,$rut) {
    $this->nombre = $nombre;
    $this->nombreUsuario = $nombreUsuario;
    $this->rut = $rut;
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
    $sql = "SELECT r.Codigo,r.Nombre,r.Teoria,rt.Abreviacion,r.Ayudantia,r.Laboratorio,r.Taller,r.Creditos
             FROM Ramo AS r 
             INNER JOIN Ramo_Tipo AS rt ON rt.Id = r.Tipo
            ORDER by r.Codigo;";
    //$sql = "CALL select_ramos()";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigo,$nombre,$teoria,$tipo,$ayudantia,$laboratorio,$taller,$creditos);
    $car = 0;
    while($res->fetch())
    {
      echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$tipo.'</td><td>'.$teoria.'</td><td>'.$ayudantia.'</td><td>'.$laboratorio.'</td><td>'.$taller.'</td><td>'.$creditos.'</td><td class="mid"><a id="'.$codigo.'" class="relacionar" href="">Relacionar</a></td><td class="mid"><a href="">X</a></td></tr>';
    }
    if(!isset($codigo))
      echo '<tr><td>No hay carreras.</td></tr>';
    $res->free_result();
  }

  public function agregarRamo($codigo,$nombre,$tipo,$teo,$ayu,$lab,$tall,$cre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "INSERT INTO Ramo(Codigo,Nombre,Teoria,Tipo,Ayudantia,Laboratorio,Taller,Creditos) VALUES('{$codigo}','{$nombre}','{$teo}','{$tipo}','{$ayu}','{$lab}','{$tall}','{$cre}')";
    //$sql = "CALL agregar_ramo('{$codigo}','{$nombre}','{$teo}','{$ayu}','{$lab}','{$tall}','{$cre}')";
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

  public function comenzarSemestre($codigoSemestre,$anno,$semestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL comenzarSemestre('{$codigoSemestre}','{$semestre}','{$anno}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Semestre comenzado.';
    }
    else
    {
      $answer = '*Semestre no comenzado.';
    }
    return $answer;
  }

  public function cerrarSemestre($codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $array = array();
    $i = 0;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT Codigo FROM carrera WHERE periodo = 1;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoCarrera);
    while($res->fetch())
    {
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "SELECT Codigo_Ramo FROM carrera_tiene_ramos WHERE Codigo_Carrera = '{$codigoCarrera}';";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($codigoRamo);
      while($res2->fetch())
      {
        $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sql3 = "SELECT Codigo_Ramo FROM ramos_impartidos WHERE Codigo_Carrera = '{$codigoCarrera}' AND Codigo_Semestre = '{$codigoSemestre}' AND Codigo_Ramo = '{$codigoRamo}';";
        $res3 = $mysqli3->prepare($sql3);
        $res3->execute();
        $res3->bind_result($codigoRamo2);
        if($res3->fetch())
        {
        }
        else
        {
          $array[$i] = '<span class="error">*La carrera '.$codigoCarrera.' no tiene impartido su ramo '.$codigoRamo.'.</span>';
          $i++;
        }  
        $res3->free_result();
      }
      $res2->free_result();
    }
    $res->free_result();

    if($i <= 0)
    {  
      $mysqli4 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql4 = "CALL cerrarTrimestre('{$codigoSemestre}',NOW())";
      if(($mysqli4->query($sql4)) == true)
      {
        $answer = '*Semestre cerrado.';
      }
      else
      {
        $answer = '*Semestre no cerrado.';
      }
      return $answer;
    }
    return $array;
  }

  public function abrirSemestreAnterior($codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL abrirSemestreAnterior('{$codigoSemestre}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Semestre abierto nuevamente.';
    }
    else
    {
      $answer = '*Semestre no se puede abrir.';
    }
    return $answer;
  }

  public function comenzarTrimestre($codigoTrimestre,$anno,$trimestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL comenzarTrimestre('{$codigoTrimestre}','{$trimestre}','{$anno}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Trimestre comenzado.';
    }
    else
    {
      $answer = '*Trimestre no comenzado.';
    }
    return $answer;
  }

  public function cerrarTrimestre($codigoTrimestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $array = array();
    $i = 0;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT Codigo FROM carrera WHERE periodo = 2;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoCarrera);
    while($res->fetch())
    {
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "SELECT Codigo_Ramo FROM carrera_tiene_ramos WHERE Codigo_Carrera = '{$codigoCarrera}';";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($codigoRamo);
      while($res2->fetch())
      {
        $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sql3 = "SELECT Codigo_Ramo FROM ramos_impartidos WHERE Codigo_Carrera = '{$codigoCarrera}' AND Codigo_Semestre = '{$codigoTrimestre}' AND Codigo_Ramo = '{$codigoRamo}';";
        $res3 = $mysqli3->prepare($sql3);
        $res3->execute();
        $res3->bind_result($codigoRamo2);
        if($res3->fetch())
        {
        }
        else
        {
          $array[$i] = '<span class="error">*La carrera '.$codigoCarrera.' no tiene impartido su ramo '.$codigoRamo.'.</span>';
          $i++;
        }  
        $res3->free_result();
      }
      $res2->free_result();
    }
    $res->free_result();

    if($i <= 0)
    {  
      $mysqli4 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql4 = "CALL cerrarTrimestre('{$codigoTrimestre}',NOW())";
      if(($mysqli4->query($sql4)) == true)
      {
        $answer = '*Trimestre cerrado.';
      }
      else
      {
        $answer = '*Trimestre no cerrado.';
      }
      return $answer;
    }
    return $array;
  }

  public function abrirTrimestreAnterior($codigoTrimestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL abrirTrimestreAnterior('{$codigoTrimestre}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Trimestre abierto nuevamente.';
    }
    else
    {
      $answer = '*Trimestre no se puede abrir.';
    }
    return $answer;
  }

  public function agregarProfesor($rutProfesor,$nombreProfesor,$gradoProfesor) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "INSERT INTO Profesor(Rut_Profesor,Nombre,Profesor_Grado) VALUES ('{$rutProfesor}','{$nombreProfesor}','{$gradoProfesor}');";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Profesor agregado.';
    }
    else
    {
      $answer = '*Profesor no agregado.';
    }
    return $answer;
  }

  public function verProfesores() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT p.Rut_Profesor,p.Nombre FROM Profesor AS p";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($rut,$nombre);
    echo '<table><tr><td>Rut</td><td>Nombre</td><td>Relacionar</td><td>Eliminar</td></tr>';
    $flag = 0;
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      echo '<tr><td>'.$rut.'</td><td>'.$nombre.'</td><td><a href="">Relacionar</a></td><td><a href="">Eliminar</a></td>';
    }
    if($flag == 0)
      echo '<tr><td>No hay profesores.</td><td></td><td></td></tr></table>';
    else
      echo '</table>';
    $res->free_result();
  }

}

class jefeDeCarrera extends usuario {

  function __construct($nombre,$nombreUsuario,$rut) {
    $this->nombre = $nombre;
    $this->nombreUsuario = $nombreUsuario;
    $this->rut = $rut;
  }

  function __destruct() {
    unset($this->nombre);
    unset($this->nombreUsuario);
    unset($this->rut);
    unset($this);
  }

  public function verMalla($codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL ver_malla('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$tipo,$semestreRamo);
    $flag = 0;
    $periodo = obtenerPeriodoCarrera($codigoCarrera);
    if($periodo == 1)
      echo '<table><tr><td>Semestre</td><td>Código</td><td>Nombre</td></tr>';
    else
      echo '<table><tr><td>Trimestre</td><td>Código</td><td>Nombre</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      $semestreRamo = anhoSemestre($periodo,$semestreRamo);
      echo '<tr><td>'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay ramos asociados a la carrera.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verRamosQuePiden($codigoCarrera,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL solicitudesPedidas('{$codigoCarrera}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($idSolicitud,$codigoRamo,$nombreRamo,$carreraSolicitante,$vacantes);
    $flag = 0;
    echo '<table><tr><td>#</td><td>Remitente</td><td>Código ramo</td><td>Nombre ramo</td><td>Vacantes</td></tr>';
    while($res->fetch())
    {
      $flag = 1;
      echo '<tr><td>'.$idSolicitud.'</td><td>'.$carreraSolicitante.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$vacantes.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No existen solicitudes.</td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verRamosQuePido($codigoCarrera,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL solicitudesSolicitadas('{$codigoCarrera}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($idSolicitud,$codigoRamo,$nombreRamo,$carreraDestino,$vacantes);
    $flag = 0;
    echo '<table><tr><td>#</td><td>Destino</td><td>Código ramo</td><td>Nombre ramo</td><td>Vacantes</td></tr>';
    while($res->fetch())
    {
      $flag = 1;
      echo '<tr><td>'.$idSolicitud.'</td><td>'.$carreraDestino.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$vacantes.'</td></tr>';
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

  public function verSeccionesSinProfesor($codigoCarrera,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL verSeccionesSinProfesor('{$codigoCarrera}','{$codigoSemestre}')";
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

  public function verRamosDeCarrera($codigoCarrera,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT ctr.codigo_Ramo,r.Nombre,r.Tipo,rt.Abreviacion,ctr.Semestre 
             FROM Carrera_Tiene_Ramos AS ctr
             INNER JOIN Ramo AS r ON r.Codigo = ctr.Codigo_Ramo
             INNER JOIN Ramo_Tipo AS rt ON rt.Id = r.Tipo
            WHERE ctr.Codigo_Carrera = '{$codigoCarrera}' ORDER BY ctr.Semestre;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$tipo,$tipoAbreviacion,$semestreRamo);
    $flag = 0;
    $periodo = obtenerPeriodoCarrera($codigoCarrera);
    if($periodo == 1)
      echo '<table><tr><td>Año / Semestre</td><td>Código</td><td>Nombre</td><td>Tipo</td><td>Dictar</td><td>No dictar</td></tr>';
    elseif($periodo == 2)
      echo '<table><tr><td>Año / Trimestre</td><td>Código</td><td>Nombre</td><td>Tipo</td><td>Dictar</td><td>No dictar</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "SELECT ri.Codigo_Ramo,ri.Impartido
                FROM Ramos_Impartidos AS ri
               WHERE ri.Codigo_Carrera = '{$codigoCarrera}' AND ri.Codigo_Ramo = '{$codigoRamo}' AND ri.Codigo_Semestre = '{$codigoSemestre}';";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($codigoRamoRes,$impartido);
      $semestreRamo = anhoSemestre($periodo,$semestreRamo);
      if($res2->fetch())
      {
        if($impartido == 1)
        {
          echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$tipoAbreviacion.'</td><td>Si</td><td><form method="post" name="impartir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigoCarrera.'"></input><input type="hidden" name="codigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="No dictar"></input></form></td></tr>';
        }
        elseif($impartido == 2)
        {
          echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$tipoAbreviacion.'</td><td><form method="post" name="impartir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigoCarrera.'"></input><input type="hidden" name="codigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Dictar"></input></form></td><td></td></tr>';
        }
        else
          echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$tipoAbreviacion.'</td><td><form method="post" name="impartir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigoCarrera.'"></input><input type="hidden" name="codigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="hidden" name="primera" value="primera"></input><input type="submit" name="submit" value="Dictar"></input></form></td><td><form method="post" name="impartir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigoCarrera.'"></input><input type="hidden" name="codigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="hidden" name="primera" value="primera"></input><input type="submit" name="submit" value="No dictar"></input></form></td><td><span class="error">*Debe elegir si impartir o no el ramo.</span></td></tr>';
      }
      $res2->free_result();
    }
    if($flag == 0)
      echo '<tr><td>No hay ramos asociados a la carrera.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function impartirRamo($codigoCarrera,$codigoRamo,$codigoSemestre,$primera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    if($primera == 1)
      $sql = "INSERT INTO Ramos_Impartidos(Codigo_Carrera,Codigo_Ramo,Codigo_Semestre,Impartido) VALUES('{$codigoCarrera}','{$codigoRamo}','{$codigoSemestre}',1);";
    elseif($primera == 0)
      $sql = "UPDATE Ramos_Impartidos SET Impartido = 1 WHERE Codigo_Carrera = '{$codigoCarrera}' AND Codigo_Ramo = '{$codigoRamo}' AND Codigo_Semestre = '{$codigoSemestre}';";
    //$sql = "CALL impartirRamo('{$codigoCarrera}','{$codigoRamo}','{$codigoSemestre}',1)";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Ramo impartido.';
    }
    else
    {
      $answer = '*Ramo no impartido.';
    }
    return $answer;
  }

  public function noImpartirRamo($codigoCarrera,$codigoRamo,$codigoSemestre,$primera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    if($primera == 1)
      $sql = "INSERT INTO Ramos_Impartidos(Codigo_Carrera,Codigo_Ramo,Codigo_Semestre,Impartido) VALUES('{$codigoCarrera}','{$codigoRamo}','{$codigoSemestre}',2);";
    else
      $sql = "UPDATE Ramos_Impartidos SET Impartido = 2 WHERE Codigo_Carrera = '{$codigoCarrera}' AND Codigo_Ramo = '{$codigoRamo}' AND Codigo_Semestre = '{$codigoSemestre}';";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Ramo no impartido.';
    }
    else
    {
      $answer = '*Ramo no se puede no impartir.';
    }
    return $answer;
  }


  public function crearSeccion($codigoRamo,$codigoSemestre,$codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT c.Regimen
             FROM Carrera AS c
            WHERE c.Codigo = '{$codigoCarrera}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($regimen);
    $res->fetch();
    $res->free_result();

    $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql2 = "SELECT MAX(s.Numero_Seccion)
              FROM Seccion AS s
              INNER JOIN Carrera AS c ON c.Codigo = s.Codigo_Carrera AND c.Regimen = '{$regimen}'
             WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Semestre = '{$codigoSemestre}';";
    $res2 = $mysqli2->prepare($sql2);
    $res2->execute();
    $res2->bind_result($numeroSeccion);
    $res2->fetch();
    $res2->free_result();

    $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql3 = "SELECT r.Teoria,r.Ayudantia,r.Laboratorio,r.Taller
              FROM Ramo AS r
             WHERE r.Codigo = '{$codigoRamo}';";
    $res3 = $mysqli3->prepare($sql3);
    $res3->execute();
    $res3->bind_result($teoria,$ayudantia,$laboratorio,$taller);
    $res3->fetch();
    $res3->free_result();

    $mysqli4 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    if($numeroSeccion == 0) {
      if($regimen == 'D')
        $numeroSeccion = 1;
      elseif($regimen == 'V')
        $numeroSeccion = 100;
    }
    else
      $numeroSeccion++;
    $sql4 = "INSERT INTO Seccion(Numero_Seccion,NRC,Codigo_Ramo,Codigo_Carrera,Codigo_Semestre,Vacantes) VALUES('{$numeroSeccion}',1524,'{$codigoRamo}','{$codigoCarrera}','{$codigoSemestre}',60);";
    if(($mysqli4->query($sql4)) == true)
    {
      $answer = '*Sección creada.';
    }
    else
    {
      $answer = '*Sección no creada.';
    }

    $mysqli5 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql5 = "SELECT s.Id
              FROM Seccion AS s
             WHERE s.Numero_Seccion = '{$numeroSeccion}' AND s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Carrera = '{$codigoCarrera}' AND s.Codigo_Semestre = '{$codigoSemestre}';";
    $res5 = $mysqli5->prepare($sql5);
    $res5->execute();
    $res5->bind_result($idSeccion);
    $res5->fetch();
    $res5->free_result();

    $teoria = $teoria/2;
    if($teoria > 0) {
      for($i = 0;$i<$teoria;$i++)
      {
        $mysqliteo = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sqlteo = "INSERT INTO Clase(Clase_Tipo,Seccion_Id,Codigo_Semestre) VALUES('Teoria','{$idSeccion}','{$codigoSemestre}');";
        $mysqliteo->query($sqlteo);
      } 
    }
    $ayudantia = $ayudantia/2;
    if($ayudantia > 0) {
      for($i = 0;$i<$ayudantia;$i++)
      {
        $mysqliteo = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sqlteo = "INSERT INTO Clase(Clase_Tipo,Seccion_Id,Codigo_Semestre) VALUES('Ayudantia','{$idSeccion}','{$codigoSemestre}');";
        $mysqliteo->query($sqlteo);
      }
    }
    $laboratorio = $laboratorio/2;
    if($laboratorio > 0) {
      for($i = 0;$i<$laboratorio;$i++)
      {
        $mysqliteo = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sqlteo = "INSERT INTO Clase(Clase_Tipo,Seccion_Id,Codigo_Semestre) VALUES('Laboratorio','{$idSeccion}','{$codigoSemestre}');";
        $mysqliteo->query($sqlteo);
      }
    }
    $taller = $taller/2;
    if($taller > 0) {
      for($i = 0;$i<$taller;$i++)
      {
        $mysqliteo = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sqlteo = "INSERT INTO Clase(Clase_Tipo,Seccion_Id,Codigo_Semestre) VALUES('Taller','{$idSeccion}','{$codigoSemestre}');";
        $mysqliteo->query($sqlteo);
      }
    }

    return $answer;
  }


  public function asignarHorarioASeccion($NRC,$inicio,$termino)
  {
    
  }

  public function solicitarVacantes($codigoRamo,$codigoCarrera,$codigoCarreraSolicitante,$numeroVacantes,$codigoSemestre)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL solicitarVacantes('{$codigoRamo}','{$codigoCarrera}','{$codigoCarreraSolicitante}','{$numeroVacantes}','{$codigoSemestre}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Solicitud enviada.';
    }
    else
    {
      $answer = '*Solicitud no enviada.';
    }
    return $answer;
  }

  public function verSolicitudes($codigoCarrera,$codigoSemestre) {
    echo '<h4>Solicitudes pedidas a mi</h4>';
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL verSolicitudesOtros('{$codigoCarrera}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($idSolicitud,$codigoRamo,$nombreRamo,$carreraSolicitante,$vacantes,$vacantesAsignadas,$fecha_envio,$fechaRespuesta,$estado);
    echo '<table><tr><td class="dc">Esperando</td></tr>';
    echo '<tr><td class="dc">Id Solicitud</td><td class="dc">Código ramo</td><td class="dc">Nombre ramo</td><td class="dc">Carrera solicitante</td><td class="dc"># vacantes</td><td class="dc">Fecha envio</td><td class="dc">Estado</td><td class="dc">Responder</td></tr>';
    $flag = 0;
    $aceptadas = 0;
    $denegadas = 0;
    while($res->fetch())
    {
      if($flag == 0) {
        $flag = 1;}
      if($estado == 1)
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraSolicitante.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Esperando</td><td><a id="'.$idSolicitud.'" class="responderSolicitud" href="">Responder</a></td></tr>';
      elseif($estado == 2)
      {
        if($aceptadas == 0)
        {
          $aceptadas = 1;
          echo '<tr></tr>';
          echo '<tr><td class="dc">Aceptadas</td></tr>';
          echo '<tr><td class="dc">Id Solicitud</td><td class="dc">Código ramo</td><td class="dc">Nombre ramo</td><td class="dc">Carrera solicitante</td><td class="dc">Vacantes pedidas</td><td class="dc">Vacantes asignadas</td><td class="dc">Fecha envio</td><td class="dc">Fecha respuesta</td><td class="dc">Estado</td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraSolicitante.'</td><td class="mid">'.$vacantes.'</td><td class="mid">'.$vacantesAsignadas.'</td><td>'.$fecha_envio.'</td><td>'.$fechaRespuesta.'</td><td>Aceptada</td></tr>';
      }
      elseif($estado == 3)
      {
        if($denegadas == 0)
        {
          $denegadas = 1;
          echo '<tr></tr>';
          echo '<tr><td class="dc">Denegadas</td></tr>';
          echo '<tr><td class="dc">Id Solicitud</td><td class="dc">Código ramo</td><td class="dc">Nombre ramo</td><td class="dc">Carrera solicitante</td><td class="dc">Vacantes pedidas</td><td class="dc">Vacantes asignadas</td><td class="dc">Fecha envio</td><td class="dc">Fecha respuesta</td><td class="dc">Estado</td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraSolicitante.'</td><td class="mid">'.$vacantes.'</td><td class="mid">'.$vacantesAsignadas.'</td><td>'.$fecha_envio.'</td><td>'.$fechaRespuesta.'</td><td>Denegada</td></tr>';
      }
    }
    if($flag == 0)
      echo 'No hay solicitudes</table>';
    else
      echo '</table>';
    $res->free_result();
  
    echo '<h4>Solicitudes realizadas por mi</h4>';
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL verSolicitudesMias('{$codigoCarrera}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($idSolicitud,$codigoRamo,$nombreRamo,$carreraDestinataria,$vacantes,$vacantesAsignadas,$fecha_envio,$fechaRespuesta,$estado);
    echo '<table><tr><td class="dc">Esperando</td></tr>';
    echo '<tr><td class="dc">Id Solicitud</td><td class="dc">Código ramo</td><td class="dc">Nombre ramo</td><td class="dc">Carrera destinataria</td><td class="dc"># vacantes</td><td class="dc">Fecha envio</td><td class="dc">Estado</td><td class="dc">Modificar</td><td class="dc">Eliminar</td></tr>';
    $flag = 0;
    $aceptadas = 0;
    $denegadas = 0;
    while($res->fetch())
    {
      if($flag == 0) {
        $flag = 1;}
      if($estado == 1)
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraDestinataria.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Esperando</td><td><form method="post" name="modificarSolicitud" target="_self"><input type="text" name="numeroVacantes" value="'.$vacantes.'" class="xs"></input><input type="hidden" name="hiddenSolicitudId" value="'.$idSolicitud.'"></input> <input type="submit" name="submit" value="Modificar"></input></form></td><td><form method="post" name="eliminarSolicitud" target="_self"><input type="hidden" name="hiddenSolicitudId" value="'.$idSolicitud.'"></input><input type="submit" name="submit" value="Eliminar"></input></form></td></tr>';
      elseif($estado == 2)
      {
        if($aceptadas == 0)
        {
          $aceptadas = 1;
          echo '<tr></tr>';
          echo '<tr><td class="dc">Aceptadas</td></tr>';
          echo '<tr><td class="dc">Id Solicitud</td><td class="dc">Código ramo</td><td class="dc">Nombre ramo</td><td class="dc">Carrera solicitante</td><td class="dc">Vacantes pedidas</td><td class="dc">Vacantes asignadas</td><td class="dc">Fecha envio</td><td class="dc">Fecha respuesta</td><td class="dc">Estado</td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraDestinataria.'</td><td class="mid">'.$vacantes.'</td><td class="mid">'.$vacantesAsignadas.'</td><td>'.$fecha_envio.'</td><td>'.$fechaRespuesta.'</td><td>Aceptada</td></tr>';
      }
      elseif($estado == 3)
      {
        if($denegadas == 0)
        {
          $denegadas = 1;
          echo '<tr></tr>';
          echo '<tr><td class="dc">Denegadas</td></tr>';
          echo '<tr><td class="dc">Id Solicitud</td><td class="dc">Código ramo</td><td class="dc">Nombre ramo</td><td class="dc">Carrera solicitante</td><td class="dc">Vacantes pedidas</td><td class="dc">Vacantes asignadas</td><td class="dc">Fecha envio</td><td class="dc">Fecha respuesta</td><td class="dc">Estado</td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraDestinataria.'</td><td class="mid">'.$vacantes.'</td><td class="mid">'.$vacantesAsignadas.'</td><td>'.$fecha_envio.'</td><td>'.$fechaRespuesta.'</td><td>Denegada</td></tr>';
      }
    }
    if($flag == 0)
      echo 'No hay solicitudes</table>';
    else
      echo '</table>';
    $res->free_result();
  }

  public function revisarSolicitud($idSolicitud)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL revisarSolicitud('{$idSolicitud}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($id,$codigoRamo,$carrera,$carreraSolicitante,$vacantes,$codigoSemestre,$fecha_envio,$fecha_termino,$estado);
    $res->fetch();
    echo '<table><h4>Solicitud número '.$id.'</h4></table>';
    echo '<table><tr><td>Carrera solicitante: '.$carreraSolicitante.'</td></tr><tr><td>Número vacantes: '.$vacantes.'</td></tr></table>';
    $res->free_result();
    return $vacantes;
  }

  public function responderSolicitud($idSolicitud,$respuesta,$vacantes)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL responderSolicitud('{$idSolicitud}','{$respuesta}','{$vacantes}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Solicitud respondida.';
    }
    else
    {
      $answer = '*Solicitud no respondida.';
    }
    return $answer;
  }

  public function modificarSolicitud($idSolicitud,$numeroVacantes)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL modificarSolicitud('{$idSolicitud}','{$numeroVacantes}')";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Solicitud modificada.';
    }
    else
    {
      $answer = '*Solicitud no modificada.';
    }
    return $answer;
  }

  public function eliminarSolicitud($idSolicitud)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL eliminarSolicitud('{$idSolicitud}')";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Solicitud eliminada.';
    }
    else
    {
      $answer = '*Solicitud no eliminada.';
    }
    return $answer;
  }

  public function asignarSeccion($idClase,$rutProfesor)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "UPDATE Clase SET RUT_Profesor = '{$rutProfesor}' WHERE Id = '{$idClase}';";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Profesor asignado.';
    }
    else
    {
      $answer = '*Profesor asignado.';
    }
    return $answer;
  }

  public function asignarHorario($idClase,$dos,$tipo)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    if($tipo == 1)
    {
      $sql = "UPDATE Clase SET Dia = '{$dos}' WHERE Id = '{$idClase}';";
      if(($mysqli->query($sql)) == true)
      {
        $answer = '*Día asignado.';
      }
      else
      {
        $answer = '*Día no asignado.';
      }
      return $answer;
    }
    elseif($tipo == 2)
    {
      $sql = "UPDATE Clase SET Modulo_Inicio = '{$dos}' WHERE Id = '{$idClase}';";
      if(($mysqli->query($sql)) == true)
      {
        $answer = '*Módulo de inicio asignado.';
      }
      else
      {
        $answer = '*Módulo de inicio no asignado.';
      }
      return $answer;
    }
    elseif($tipo == 3)
    {
      $sql = "UPDATE Clase SET Modulo_Termino = '{$dos}' WHERE Id = '{$idClase}';";
      if(($mysqli->query($sql)) == true)
      {
        $answer = '*Módulo de término asignado.';
      }
      else
      {
        $answer = '*Módulo de término no asignado.';
      }
      return $answer;
    }
  }

  private function programarHorario() {
  }
}
?>
