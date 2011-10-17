<?php
include('db/connect.php');
include('funciones.php');

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
    $sql = "CALL ver_malla('{$codigoCarrera}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$semestreRamo);
    $flag = 0;
    $periodo = obtenerPeriodoCarrera($codigoCarrera);
    if($periodo == 1)
      echo '<table><tr><td>Año / Semestre</td><td>Código</td><td>Nombre</td><td>Dictar</td><td>No dictar</td></tr>';
    elseif($periodo == 2)
      echo '<table><tr><td>Año / Trimestre</td><td>Código</td><td>Nombre</td><td>Dictar</td><td>No dictar</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "CALL ramoDictado('{$codigoCarrera}','{$codigoRamo}','{$codigoSemestre}')";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($codigoRamoRes);
      $res2->fetch();
      $semestreRamo = anhoSemestre($periodo,$semestreRamo);
      if(isset($codigoRamoRes))
        echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>Si</td><td><form method="post" name="impartir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigoCarrera.'"></input><input type="hidden" name="codigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="No dictar"></input></form></td></tr>';
      else
        echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td><form method="post" name="impartir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigoCarrera.'"></input><input type="hidden" name="codigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Dictar"></input></form></td><td></td></tr>';
      $res2->free_result();
    }
    if($flag == 0)
      echo '<tr><td>No hay ramos asociados a la carrera.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function impartirRamo($codigoCarrera,$codigoRamo,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL impartirRamo('{$codigoCarrera}','{$codigoRamo}','{$codigoSemestre}')";
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

  public function noImpartirRamo($codigoCarrera,$codigoRamo,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL noImpartirRamo('{$codigoCarrera}','{$codigoRamo}','{$codigoSemestre}')";
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

  public function verRamosImpartidos($codigoCarrera,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL verRamosImpartidos('{$codigoCarrera}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$semestreRamo,$periodo);
    $flag = 0;
    if($periodo == 1)
      echo '<table><tr><td>Año / Semestre</td><td>Código</td><td>Nombre</td><td>Crear sección</td><td>Secciones creadas</td><td>Secciones pedidas</td><td>Secciones creadas por otros</td></tr>';
    else
      echo '<table><tr><td>Año / Trimestre</td><td>Código</td><td>Nombre</td><td>Crear sección</td><td>Secciones creadas</td><td>Secciones pedidas</td><td>Secciones creadas por otros</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      $semestreRamo = anhoSemestre($periodo,$semestreRamo);
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "CALL seccionesCreadasNumero('{$codigoRamo}','{$codigoCarrera}','{$codigoSemestre}')";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($seccionesCreadasNumero);
      $res2->fetch();
      if($seccionesCreadasNumero > 0)
        $seccionesCreadasNumero = '<a id="'.$codigoRamo.'" class="seccionesCreadas" href="">'.$seccionesCreadasNumero.'</a>';
      $res2->free_result();

      $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql3 = "CALL seccionesCreadasOtroNumero('{$codigoRamo}','{$codigoCarrera}','{$codigoSemestre}')";
      $res3 = $mysqli3->prepare($sql3);
      $res3->execute();
      $res3->bind_result($seccionesCreadasOtroNumero);
      $res3->fetch();
      if($seccionesCreadasOtroNumero > 0)
        $seccionesCreadasOtroNumero = '<a id="'.$codigoRamo.'" class="seccionesCreadasOtros" href="">'.$seccionesCreadasOtroNumero.'</a>';
      $res3->free_result();

      echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td class="mid"><form method="post" name="crearSeccion" target="_self"><input type="hidden" name="hiddenCodigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoSemestre.'"></input><input type="hidden" name="hiddenCodigoCarrera" value="'.$codigoCarrera.'"></input><input type="submit" name="submit" value="Crear"></input></form></td><td class="mid">'.$seccionesCreadasNumero.'</td><td class="mid">0</td><td class="mid">'.$seccionesCreadasOtroNumero.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay ramos asociados a la carrera.</td><td></td><td></td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function crearSeccion($codigoRamo,$codigoSemestre,$codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL crearSeccion('{$codigoRamo}','{$codigoCarrera}','{$codigoSemestre}')";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Sección creada.';
    }
    else
    {
      $answer = '*Sección no creada.';
    }
    return $answer;
  }

  public function verSeccionesCreadas($codigoRamo,$codigoSemestre,$codigoCarrera) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL verSeccionesCreadas('{$codigoRamo}','{$codigoCarrera}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($NRC,$codigoRamo,$nombreRamo,$codigoCarrera,$rutProfesor,$codigoSemestre);
    $flag = 0;
    echo '<table><tr><td>NRC</td><td>Nombre</td><td>Profesor</td><td>Semestre</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      if($rutProfesor == NULL)
        $rutProfesor = 'S/Profesor';
      echo '<tr><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$rutProfesor.'</td><td>'.$codigoSemestre.'</td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay secciones para este ramo.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

  public function verSeccionesCreadasOtros($codigoRamo,$codigoSemestre,$codigoCarreraMia) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL verSeccionesCreadasOtro('{$codigoRamo}','{$codigoCarreraMia}','{$codigoSemestre}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($NRC,$codigoRamo,$nombreRamo,$codigoCarrera,$rutProfesor,$codigoSemestre);
    $flag = 0;
    $codigoCarreraAnterior = 'carrera';
    echo '<table><tr><td>NRC</td><td>Nombre</td><td>Carrera</td><td>Profesor</td><td>Semestre</td><td>Solicitar vacantes</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      if($rutProfesor == NULL)
        $rutProfesor = 'S/Profesor';
      if($codigoCarreraAnterior != $codigoCarrera)
      {
        $codigoCarreraAnterior = $codigoCarrera;
        $lol = comprobarSolicitudExiste($codigoCarreraMia,$codigoCarrera,$codigoSemestre,$codigoRamo);
        if(!$lol)
          echo '<tr><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$codigoCarrera.'</td><td>'.$rutProfesor.'</td><td>'.$codigoSemestre.'</td><td><form method="post" name="solicitar" target="_self"><input type="hidden" name="hiddenCarreraDuenha" value="'.$codigoCarrera.'"></input><input type="hidden" name="hiddenCodigoRamo" value="'.$codigoRamo.'"></input><input type="text" class="xs" name="numeroVacantes" maxlength="2"></input> <input type="submit" name="submit" value="Solicitar"></input></form></td></tr>';
        else
          echo '<tr><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$codigoCarrera.'</td><td>'.$rutProfesor.'</td><td>'.$codigoSemestre.'</td><td>Solicitud enviada: ID '.$lol.'</td></tr>';
      }
      elseif($codigoCarreraAnterior == $codigoCarrera)
        echo '<tr><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$codigoCarrera.'</td><td>'.$rutProfesor.'</td><td>'.$codigoSemestre.'</td><td></td></tr>';
    }
    if($flag == 0)
      echo '<tr><td>No hay secciones de otras carreras para este ramo.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
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
    $res->bind_result($idSolicitud,$codigoRamo,$nombreRamo,$carreraSolicitante,$vacantes,$fecha_envio,$estado);
    echo '<table><tr><td>Id Solicitud</td><td>Código ramo</td><td>Nombre ramo</td><td>Carrera solicitante</td><td># vacantes</td><td>Fecha envio</td><td>Estado</td><td>Responder</td></tr>';
    $flag = 0;
    $aceptadas = 0;
    $denegadas = 0;
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      if($estado == 1)
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraSolicitante.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Esperando</td><td><a id="'.$idSolicitud.'" class="responderSolicitud" href="">Responder</a></td></tr>';
      elseif($estado == 2)
      {
        if($aceptadas == 0)
        {
          $aceptadas = 1;
          echo '<tr><td>Aceptadas</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraSolicitante.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Aceptada</td><td></td></tr>';
      }
      elseif($estado == 3)
      {
        if($denegadas == 0)
        {
          $denegadas = 1;
          echo '<tr><td>Denegadas</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraSolicitante.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Denegada</td><td></td></tr>';
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
    $res->bind_result($idSolicitud,$codigoRamo,$nombreRamo,$carreraDestinataria,$vacantes,$fecha_envio,$estado);
    echo '<table><tr><td>Id Solicitud</td><td>Código ramo</td><td>Nombre ramo</td><td>Carrera destinataria</td><td># vacantes</td><td>Fecha envio</td><td>Estado</td></tr>';
    $flag = 0;
    $aceptadas = 0;
    $denegadas = 0;
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      if($estado == 1)
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraDestinataria.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Esperando</td></tr>';
      elseif($estado == 2)
      {
        if($aceptadas == 0)
        {
          $aceptadas = 1;
          echo '<tr><td>Aceptadas</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraDestinataria.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Aceptada</td></tr>';
      }
      elseif($estado == 3)
      {
        if($denegadas == 0)
        {
          $denegadas = 1;
          echo '<tr><td>Denegadas</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        echo '<tr><td>'.$idSolicitud.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td>'.$carreraDestinataria.'</td><td class="mid">'.$vacantes.'</td><td>'.$fecha_envio.'</td><td>Denegada</td></tr>';
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
    $res->free_result();
  }

  public function responderSolicitud($idSolicitud,$respuesta)
  {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL responderSolicitud('{$idSolicitud}','{$respuesta}')";
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
    $sql = "CALL select_ramos()";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigo,$nombre,$teoria,$ayudantia,$laboratorio,$taller,$creditos);
    $car = 0;
    while($res->fetch())
    {
      echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$teoria.'</td><td>'.$ayudantia.'</td><td>'.$laboratorio.'</td><td>'.$taller.'</td><td>'.$creditos.'</td><td class="mid"><a id="'.$codigo.'" class="relacionar" href="">Relacionar</a></td><td class="mid"><a href="">X</a></td></tr>';
    }
    if(!isset($codigo))
      echo '<tr><td>No hay carreras.</td></tr>';
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
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL cerrarSemestre('{$codigoSemestre}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Semestre cerrado.';
    }
    else
    {
      $answer = '*Semestre no cerrado.';
    }
    return $answer;
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
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL cerrarTrimestre('{$codigoTrimestre}',NOW())";
    if(($mysqli->query($sql)) == true)
    {
      $answer = '*Trimestre cerrado.';
    }
    else
    {
      $answer = '*Trimestre no cerrado.';
    }
    return $answer;
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

