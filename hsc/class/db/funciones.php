<?php
require_once('connect.php');

function comprobarSolicitudExiste($codigoCarreraSolicitante,$codigoCarreraDestinatario,$codigoSemestre,$codigoRamo)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "CALL comprobarSolicitudExiste('{$codigoCarreraSolicitante}','{$codigoCarreraDestinatario}','{$codigoSemestre}','{$codigoRamo}')";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($answer);
  $resf->fetch();
  $resf->free_result();
  return $answer;
}

function obtenerTipoDeUsuario($nombreUsuario)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT u.tipo FROM usuario AS u WHERE u.Nombre_Usuario = $nombreUsuario;";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($answer);
  $resf->fetch();
  $resf->free_result();
  return $tipoDeUsuario;
}

function obtenerPeriodoCarrera($codigoCarrera)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT c.Periodo FROM Carrera AS c WHERE c.Codigo = '{$codigoCarrera}'";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($periodo);
  $resf->fetch();
  $resf->free_result();
  return $periodo;
}

function obtenerRegimenCarrera($codigoCarrera)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT c.Regimen FROM Carrera AS c WHERE c.Codigo = '{$codigoCarrera}'";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($regimen);
  $resf->fetch();
  $resf->free_result();
  return $regimen;
}

function obtenerModulos($regimen)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT m.Modulo,m.Inicio,m.Termino FROM Modulo AS m WHERE m.Regimen = '{$regimen}'";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($modulo,$inicio,$termino);
  while($resf->fetch())
  {
    echo '<option value="'.$modulo.'">'.$inicio.' - '.$termino.'</option>';
  }
  $resf->free_result();
}

function obtenerSemestre($periodo)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  if($periodo == 1)
    $sqlf = "SELECT s.Codigo_Semestre FROM Semestre AS s WHERE s.Fecha_Termino = NULL";
  elseif($periodo == 2)
    $sqlf = "SELECT t.Codigo_Trimestre FROM Trimestre AS t WHERE t.Fecha_Termino = NULL";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($codigoSemestre);
  $resf->fetch();
  $resf->free_result();
  return $codigoSemestre;
}

function anhoSemestre($periodoCarrera,$semestreRamo)
{
  if($periodoCarrera == 1)
  {
    if($semestreRamo == 1 || $semestreRamo == 2)
      $semestreRamo = '1 / '.$semestreRamo;
    elseif($semestreRamo == 3 || $semestreRamo == 4)
      $semestreRamo = '2 / '.$semestreRamo;
    elseif($semestreRamo == 5 || $semestreRamo == 6)
      $semestreRamo = '3 / '.$semestreRamo;
    elseif($semestreRamo == 7 || $semestreRamo == 8)
      $semestreRamo = '4 / '.$semestreRamo;
    elseif($semestreRamo == 9 || $semestreRamo == 10)
      $semestreRamo = '5 / '.$semestreRamo;
    elseif($semestreRamo == 11 || $semestreRamo == 12)
      $semestreRamo = '6 / '.$semestreRamo;
    elseif($semestreRamo == 13 || $semestreRamo == 14)
      $semestreRamo = '7 / '.$semestreRamo;
    elseif($semestreRamo == 14 || $semestreRamo == 15)
      $semestreRamo = '8 / '.$semestreRamo;
    elseif($semestreRamo == 16 || $semestreRamo == 17)
      $semestreRamo = '9 / '.$semestreRamo;
  }
  else
  {
    if($semestreRamo == 1 || $semestreRamo == 2 || $semestreRamo == 3)
      $semestreRamo = '1 / '.$semestreRamo;
    elseif($semestreRamo == 4 || $semestreRamo == 5 || $semestreRamo == 6)
      $semestreRamo = '2 / '.$semestreRamo;
    elseif($semestreRamo == 7 || $semestreRamo == 8 || $semestreRamo == 9)
      $semestreRamo = '3 / '.$semestreRamo;
    elseif($semestreRamo == 10 || $semestreRamo == 11 || $semestreRamo == 12)
      $semestreRamo = '4 / '.$semestreRamo;
    elseif($semestreRamo == 13 || $semestreRamo == 14 || $semestreRamo == 15)
      $semestreRamo = '5 / '.$semestreRamo;
    elseif($semestreRamo == 16 || $semestreRamo == 17 || $semestreRamo == 18)
      $semestreRamo = '6 / '.$semestreRamo;
    elseif($semestreRamo == 19 || $semestreRamo == 20 || $semestreRamo == 21)
      $semestreRamo = '7 / '.$semestreRamo;
    elseif($semestreRamo == 22 || $semestreRamo == 23 || $semestreRamo == 24)
      $semestreRamo = '8 / '.$semestreRamo;
    elseif($semestreRamo == 25 || $semestreRamo == 26 || $semestreRamo == 27)
      $semestreRamo = '9 / '.$semestreRamo;
  }
  return $semestreRamo;
}

function obtenerHorarios($codigoCarrera)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "CALL obtenerHorarios('{$codigoCarrera}')";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($modulo,$regimen,$inicio,$termino);
  while($resf->fetch())
  {
    echo '<option value="'.$modulo.'">'.$modulo.'. '.substr($inicio,0,5).' - '.substr($termino,0,5).'</option>';
  }
  $resf->free_result();
}

function obtenerHorariosSegunRamo($codigoCarrera,$codigoRamo)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database,$flag;
  $mysqli1 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql1 = "CALL semestreRamo('{$codigoCarrera}','{$codigoRamo}')";
  $res1 = $mysqli1->prepare($sql1);
  $res1->execute();
  $res1->bind_result($semestreRamo);
  $res1->fetch();
  $res1->free_result();
  
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "CALL obtenerHorarios('{$codigoCarrera}')";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($modulo,$regimen,$inicio,$termino);
  while($resf->fetch())
  {
    $flag = 0;
    $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql2 = "CALL ramosSemestre('{$codigoCarrera}','{$codigoRamo}','{$_SESSION['codigoSemestre']}','{$semestreRamo}')";
    $res2 = $mysqli2->prepare($sql2);
    $res2->execute();
    $res2->bind_result($codigoRamo2);
    while($res2->fetch())
    {
      $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql3 = "CALL horarioSeccion('{$codigoCarrera}','{$codigoRamo2}','{$_SESSION['codigoSemestre']}')";
      $res3 = $mysqli3->prepare($sql3);
      $res3->execute();
      $res3->bind_result($horarioInicio,$horarioTermino);
      $res3->fetch();
      if($modulo == $horarioInicio || $modulo == $horarioTermino)
      {
        $flag = 1;
        break;
      }
      $res3->free_result();
    }  
    $res2->free_result();
    if($flag == 0)
      echo '<option value="'.$modulo.'">'.$modulo.'. '.substr($inicio,0,5).' - '.substr($termino,0,5).'</option>';
    else
      echo '<option value="'.$modulo.'" style="background-color: red;">'.$modulo.'. '.substr($inicio,0,5).' - '.substr($termino,0,5).'</option>';
  }
  $resf->free_result();
}

function obtenerHorarioActual($codigoSeccion)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "CALL obtenerHorarioActual('{$codigoSeccion}')";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($horarioInicio,$horarioTermino);
  $resf->fetch();
  $resf->free_result();
  return $horarioInicio.$horarioTermino;
}

function obtenerGrados()
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT gp.id,gp.grado FROM Profesor_Grado AS gp;";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($id,$grado);
  while($resf->fetch())
  {
    echo '<option value="'.$id.'">'.$grado.'</option>';
  }
  $resf->free_result();
}

function obtenerTiposRamo($tipoUsuario)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  if($tipoUsuario == 2 || $tipoUsuario == 3)
  {
    $sqlf = "SELECT rt.id,rt.tipo 
              FROM Ramo_Tipo AS rt
             WHERE rt.Abreviacion = 'C' OR rt.Abreviacion = 'O' OR rt.Abreviacion = 'P';";
  }
  elseif($tipoUsuario == 4)
  {
    $sqlf = "SELECT rt.id,rt.tipo 
              FROM Ramo_Tipo AS rt
             WHERE rt.Abreviacion = 'F' OR rt.Abreviacion = 'I' OR rt.Abreviacion = 'M' OR rt.Abreviacion = 'Q';";
  }
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($id,$tipo);
  while($resf->fetch())
  {
    echo '<option value="'.$id.'">'.$tipo.'</option>';
  }
  $resf->free_result();
}

function verTiposDeRamos()
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT rt.id,rt.tipo,rt.abreviacion 
            FROM Ramo_Tipo AS rt;";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($id,$tipo,$abreviacion);
  while($resf->fetch())
  {
    echo '<tr><td>'.$id.'</td><td>'.$tipo.'</td><td>'.$abreviacion.'</td></tr>';
  }
  $resf->free_result();
}

//Funciones de usuario Jefe de carrera
function verRamosImpartidos($codigoCarrera,$codigoSemestre) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT r.Codigo,r.Nombre,r.Tipo,rt.Abreviacion,ctr.Semestre,c.Periodo
             FROM Ramos_Impartidos AS ri
             INNER JOIN Ramo AS r ON r.Codigo = ri.Codigo_Ramo
             INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Carrera = ri.Codigo_Carrera AND ctr.Codigo_Ramo = ri.Codigo_Ramo
             INNER JOIN Carrera AS c ON c.Codigo = ctr.Codigo_Carrera
             INNER JOIN Ramo_Tipo AS rt ON rt.Id = r.Tipo
            WHERE ri.Codigo_Carrera = '{$codigoCarrera}' AND ri.Codigo_Semestre = '{$codigoSemestre}' AND ri.Impartido = 1 ORDER BY ctr.Semestre,r.Codigo;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$nombreRamo,$tipo,$tipoAbreviacion,$semestreRamo,$periodo);
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
        $seccionesCreadasNumero = '<a href="clases.php?codigoRamo='.$codigoRamo.'&mod=0">'.$seccionesCreadasNumero.'</a>';
      $res2->free_result();

      $mysqli22 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql22 = "SELECT DISTINCT COUNT(s.Id)
                 FROM Solicitud AS s
                WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Carrera_Solicitante = '{$codigoCarrera}' AND s.Codigo_Semestre = '{$codigoSemestre}' AND s.Estado = 2;";
      $res22 = $mysqli22->prepare($sql22);
      $res22->execute();
      $res22->bind_result($seccionesPedidasNumero);
      $res22->fetch();
      if($seccionesPedidasNumero > 0)
        $seccionesPedidasNumero = '<a href="clases.php?codigoRamo='.$codigoRamo.'&mod=1">'.$seccionesPedidasNumero.'</a>';
      $res22->free_result();

      $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql3 = "CALL seccionesCreadasOtroNumero('{$codigoRamo}','{$codigoCarrera}','{$codigoSemestre}')";
      $res3 = $mysqli3->prepare($sql3);
      $res3->execute();
      $res3->bind_result($seccionesCreadasOtroNumero);
      $res3->fetch();
      if($seccionesCreadasOtroNumero > 0)
        $seccionesCreadasOtroNumero = $seccionesCreadasOtroNumero.'<br><a id="'.$codigoRamo.'" class="seccionesCreadasOtros" href="">Pedir vacantes</a>';
      $res3->free_result();

      if($tipo == '1')
      {
        echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td class="mid"><form method="post" name="crearSeccion" target="_self"><input type="hidden" name="hiddenCodigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoSemestre.'"></input><input type="hidden" name="hiddenCodigoCarrera" value="'.$codigoCarrera.'"></input><input type="submit" name="submit" value="Crear"></input></form></td><td class="mid">'.$seccionesCreadasNumero.'</td><td class="mid">'.$seccionesPedidasNumero.'</td><td class="mid">'.$seccionesCreadasOtroNumero.'</td></tr>';
      }
      else
      {
        echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td class="mid"></td><td class="mid">'.$seccionesCreadasNumero.'</td><td class="mid">'.$seccionesPedidasNumero.'</td><td class="mid">'.$seccionesCreadasOtroNumero.'</td></tr>';
      }
    }
    if($flag == 0)
      echo '<tr><td>No hay ramos asociados a la carrera.</td><td></td><td></td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }

function verSeccionesCreadas($codigoRamo,$codigoSemestre,$codigoCarrera) {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT s.Id,s.Numero_Seccion,s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.Codigo_Semestre,s.Vacantes
           FROM Seccion AS s
           INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
          WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Carrera = '{$codigoCarrera}' AND s.Codigo_Semestre = '{$codigoSemestre}' ORDER BY s.Numero_Seccion;";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($id,$numeroSeccion,$NRC,$codigoRamo,$nombre,$codigoCarrera,$codigoSemestre,$vacantes);
  $flag = 0;
  echo '<table><tr><td>Sección</td><td>NRC</td><td>Nombre</td><td>Semestre</td></tr>';
  while($res->fetch())
  {
    if($flag == 0)
      $flag = 1;
    echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombre.'</td><td>'.$codigoSemestre.'</td></tr>';
  }
  if($flag == 0)
    echo '<tr><td>No hay secciones para este ramo.</td><td></td></tr>';
  echo '</table>';
  $res->free_result();
}

function verSeccionesPedidas() {
  
}

function verSeccionesCreadasOtros($codigoRamo,$codigoSemestre,$codigoCarreraMia) {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT s.Id,s.Numero_Seccion,s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.Codigo_Semestre,s.Vacantes
           FROM Seccion AS s
           INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
          WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Carrera != '{$codigoCarreraMia}' AND s.Codigo_Semestre = '{$codigoSemestre}' ORDER BY s.Codigo_Carrera,s.NRC;";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($id,$numeroSeccion,$NRC,$codigoRamo,$nombreRamo,$codigoCarrera,$codigoSemestre,$vacantes);
  $flag = 0;
  $codigoCarreraAnterior = 'carrera';
  echo '<table><tr><td>Sección</td><td>NRC</td><td>Nombre</td><td>Carrera</td><td>Semestre</td><td>Solicitar vacantes</td></tr>';
  while($res->fetch())
  {
    if($flag == 0)
      $flag = 1;
    if($codigoCarreraAnterior != $codigoCarrera)
    {
      $codigoCarreraAnterior = $codigoCarrera;
      $lol = comprobarSolicitudExiste($codigoCarreraMia,$codigoCarrera,$codigoSemestre,$codigoRamo);
      if(!$lol)
        echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$codigoCarrera.'</td><td>'.$codigoSemestre.'</td><td><form method="post" name="solicitar" target="_self"><input type="hidden" name="hiddenCarreraDuenha" value="'.$codigoCarrera.'"></input><input type="hidden" name="hiddenCodigoRamo" value="'.$codigoRamo.'"></input><input type="text" class="xs" name="numeroVacantes" maxlength="2"></input> <input type="submit" name="submit" value="Solicitar"></input></form></td></tr>';
      else
        echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$codigoCarrera.'</td><td>'.$codigoSemestre.'</td><td>Solicitud enviada: ID '.$lol.'</td></tr>';
    }
    elseif($codigoCarreraAnterior == $codigoCarrera)
      echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombreRamo.'</td><td>'.$codigoCarrera.'</td><td>'.$codigoSemestre.'</td><td></td></tr>';
  }
  if($flag == 0)
    echo '<tr><td>No hay secciones de otras carreras para este ramo.</td><td></td></tr>';
  echo '</table>';
  $res->free_result();
}

function verClases($codigoRamo,$codigoCarrera,$codigoSemestre,$mod) {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  if($mod == 0)
  {
    $sql = "SELECT s.Id,s.Numero_Seccion,s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.Codigo_Semestre,s.Vacantes,s.Vacantes_Utilizadas
             FROM Seccion AS s
             INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
            WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Carrera = '{$codigoCarrera}' AND s.Codigo_Semestre = '{$codigoSemestre}' ORDER BY s.Numero_Seccion;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($id,$numeroSeccion,$NRC,$codigoRamo,$nombre,$codigoCarrera,$codigoSemestre,$vacantes,$vacantesUtilizadas);
    $flag = 0;
    echo '<table><tr><td>Sección</td><td>NRC</td><td>Nombre</td><td>Semestre</td><td>Vacantes</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      $flag2 = 0;
      $vacantesFinal = calcularVacantesRestantes($id);
      global $mysqli,$db_host,$db_user,$db_pass,$db_database;
      $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sqlo = "SELECT SUM(s.Vacantes_Asignadas)
                FROM Solicitud AS s
               WHERE s.Seccion_Asignada = '{$id}';";
      $reso = $mysqlio->prepare($sqlo);
      $reso->execute();
      $reso->bind_result($vacantesSolicitud);
      $reso->fetch();
      $reso->free_result();

      if($vacantesSolicitud == NULL)
        $vacantesSolicitud = 0;    

        $form = '<form method="post" target="_self" name="vacantes"><input type="text" name="vacantes" value="'.$vacantesUtilizadas.'" class="xs" maxlength="2"></input><input type="hidden" name="hiddenSolicitud" value="'.$vacantesSolicitud.'"></input><input type="hidden" name="hiddenTotal" value="'.$vacantes.'"></input><input type="hidden" name="hiddenIdSeccion" value="'.$id.'"></input> <input type="submit" name="submit" value="Reservar"></input></form>';
      echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombre.'</td><td>'.$codigoSemestre.'</td><td>Disponibles: '.$vacantes.'<br>Solicitud: '.$vacantesSolicitud.'<br>Utilizadas: '.$vacantesUtilizadas.' '.$form.'<br>Total: '.$vacantesFinal.'</td></tr>';
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "SELECT c.Id,c.Clase_Tipo,c.RUT_Profesor,c.Modulo_Inicio,c.Modulo_Termino,c.Dia,c.Codigo_Semestre
                FROM Clase AS c
               WHERE c.Seccion_Id = '{$id}';";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($idClase,$claseTipo,$rutProfesor,$moduloInicio,$moduloTermino,$diaClase,$codigoSemestreClase);
      while($res2->fetch())
      {
        if($flag2 == 0)
          $flag2 = 1;
        if($diaClase == NULL) {
          $diaClase = 'Día de la clase no asignado.<br><a id="'.$idClase.'" class="cambiarDiaClase" href="">Asignar</a>';
          $asdf = false;  
        }
        else
          $diaClase = $diaClase.'<br><a id="'.$idClase.'" class="cambiarDiaClase" href="">Cambiar</a>';
        if($rutProfesor == NULL)
          $rutProfesor = 'Profesor no asignado.<br><a id="'.$idClase.'" class="cambiarProfesor" href="">Asignar</a>';
        else
          $rutProfesor = $rutProfesor.'<br><a id="'.$idClase.'" class="cambiarProfesor" href="">Cambiar</a>';
        if(isset($asdf) && $asdf == false) {
            $moduloInicio = 'No se puede asignar módulo de inicio sin asignar antes el día.';
            $moduloTermino = 'No se puede asignar módulo de término sin asignar antes el día.';
        }
        else
        {
          if($moduloInicio == NULL)
            $moduloInicio = 'Hora de inicio no asignada.<br><a id="'.$idClase.'" class="cambiarModuloInicio" href="">Asignar</a>';
          else {
            $hora = obtenerHoraModulo($moduloInicio,$idClase);
            $moduloInicio = ' '.$moduloInicio.'.'.$hora.' <br><a id="'.$idClase.'" class="cambiarModuloInicio" href="">Cambiar</a>';
          }
          if($moduloTermino == NULL)
            $moduloTermino = 'Hora de termino no asignada.<br><a id="'.$idClase.'" class="cambiarModuloTermino" href="">Asignar</a>';
          else {
          
            $hora2 = obtenerHoraModulo($moduloTermino,$idClase);
            $moduloTermino = ' '.$moduloTermino.'.'.$hora2.' <br><a id="'.$idClase.'" class="cambiarModuloTermino" href="">Cambiar</a>';
          }
        }
        echo '<tr><td class="dc">'.$claseTipo.'</td><td class="dc">'.$rutProfesor.'</td><td class="dc">'.$diaClase.'</td><td class="dc">'.$moduloInicio.'</td><td class="dc">'.$moduloTermino.'</td></tr>';
      }
      if($flag2 == 0)
        echo '<tr><td class="dc">No existen clases para esta sección.</td></tr>';
      $res2->free_result();
    }
    if($flag == 0)
      echo '<tr><td>No hay secciones para este ramo.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }
  elseif($mod == 1)
  {
    $sql = "SELECT DISTINCT s.Id,sc.Numero_Seccion,sc.NRC,s.Codigo_Ramo,r.Nombre,s.Carrera,s.Codigo_Semestre,s.Vacantes_Asignadas
             FROM Solicitud AS s
             INNER JOIN Seccion AS sc ON sc.Id = s.Seccion_Asignada
             INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
            WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Carrera_Solicitante = '{$codigoCarrera}' AND s.Codigo_Semestre = '{$codigoSemestre}' AND s.Estado = 2;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($id,$numeroSeccion,$NRC,$codigoRamo,$nombre,$codigoCarrera,$codigoSemestre,$vacantesAsignadas);
    $flag = 0;
    echo '<table><tr><td>Sección</td><td>NRC</td><td>Nombre</td><td>Semestre</td><td>Carrera dueña</td><td>Vacantes asignadas</td></tr>';
    while($res->fetch())
    {
      if($flag == 0)
        $flag = 1;
      $flag2 = 0;
     
      echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombre.'</td><td>'.$codigoSemestre.'</td><td>'.$codigoCarrera.'</td><td>'.$vacantesAsignadas.'</td></tr>';
      $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql2 = "SELECT c.Id,c.Clase_Tipo,c.RUT_Profesor,c.Modulo_Inicio,c.Modulo_Termino,c.Dia,c.Codigo_Semestre
                FROM Clase AS c
               WHERE c.Seccion_Id = '{$id}';";
      $res2 = $mysqli2->prepare($sql2);
      $res2->execute();
      $res2->bind_result($idClase,$claseTipo,$rutProfesor,$moduloInicio,$moduloTermino,$diaClase,$codigoSemestreClase);
      while($res2->fetch())
      {
        if($flag2 == 0)
          $flag2 = 1;
        if($diaClase == NULL)
          $diaClase = 'Día de la clase no asignado.';
        if($rutProfesor == NULL)
          $rutProfesor = 'Profesor no asignado.';
        if($moduloInicio == NULL)
          $moduloInicio = 'Hora de inicio no asignada.';
        else {
          $hora = obtenerHoraModulo($moduloInicio,$idClase);
          $moduloInicio = ' '.$moduloInicio.'.'.$hora;
        }
        if($moduloTermino == NULL)
          $moduloTermino = 'Hora de termino no asignada.';
        else {   
          $hora2 = obtenerHoraModulo($moduloTermino,$idClase);
          $moduloTermino = ' '.$moduloTermino.'.'.$hora2;
        }
        echo '<tr><td class="dc">'.$claseTipo.'</td><td class="dc">'.$rutProfesor.'</td><td class="dc">'.$diaClase.'</td><td class="dc">'.$moduloInicio.'</td><td class="dc">'.$moduloTermino.'</td></tr>';
      }
      if($flag2 == 0)
        echo '<tr><td class="dc">No existen clases para esta sección.</td></tr>';
      $res2->free_result();
    }
    if($flag == 0)
      echo '<tr><td>No hay secciones para este ramo.</td><td></td></tr>';
    echo '</table>';
    $res->free_result();
  }
}

function verClase($codigoRamo,$codigoCarrera,$codigoSemestre,$idSeccion) {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT s.Id,s.Numero_Seccion,s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.Codigo_Semestre,s.Vacantes,s.Vacantes_Utilizadas
           FROM Seccion AS s
           INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
          WHERE s.Id = '{$idSeccion}' AND s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Carrera = '{$codigoCarrera}' AND s.Codigo_Semestre = '{$codigoSemestre}' ORDER BY s.Numero_Seccion;";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($id,$numeroSeccion,$NRC,$codigoRamo,$nombre,$codigoCarrera,$codigoSemestre,$vacantes,$vacantesUtilizadas);
  $flag2 = 0;
  echo '<table><tr><td>Sección</td><td>NRC</td><td>Nombre</td><td>Semestre</td><td>Vacantes</td></tr>';
  $res->fetch();  $vacantesFinal = calcularVacantesRestantes($id);
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlo = "SELECT SUM(s.Vacantes_Asignadas)
            FROM Solicitud AS s
           WHERE s.Seccion_Asignada = '{$id}';";
  $reso = $mysqlio->prepare($sqlo);
  $reso->execute();
  $reso->bind_result($vacantesSolicitud);
  $reso->fetch();
  $reso->free_result();

  if($vacantesSolicitud == NULL)
    $vacantesSolicitud = 0;    

  $form = '<form method="post" target="_self" name="vacantes"><input type="text" name="vacantes" value="'.$vacantesUtilizadas.'" class="xs" maxlength="2"></input><input type="hidden" name="hiddenSolicitud" value="'.$vacantesSolicitud.'"></input><input type="hidden" name="hiddenTotal" value="'.$vacantes.'"></input><input type="hidden" name="hiddenIdSeccion" value="'.$id.'"></input> <input type="submit" name="submit" value="Cambiar"></input></form>';
  echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombre.'</td><td>'.$codigoSemestre.'</td><td>Disponibles: '.$vacantes.'<br>Solicitud: '.$vacantesSolicitud.'<br>Utilizadas: '.$vacantesUtilizadas.' '.$form.'<br>Total: '.$vacantesFinal.'</td></tr>';
  $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql2 = "SELECT c.Id,c.Clase_Tipo,c.RUT_Profesor,c.Modulo_Inicio,c.Modulo_Termino,c.Dia,c.Codigo_Semestre
            FROM Clase AS c
           WHERE c.Seccion_Id = '{$id}';";
  $res2 = $mysqli2->prepare($sql2);
  $res2->execute();
  $res2->bind_result($idClase,$claseTipo,$rutProfesor,$moduloInicio,$moduloTermino,$diaClase,$codigoSemestreClase);
  while($res2->fetch())
  {
    if($flag2 == 0)
      $flag2 = 1;
    if($diaClase == NULL) {
      $diaClase = 'Día de la clase no asignado.<br><a id="'.$idClase.'" class="cambiarDiaClase" href="">Asignar</a>';
      $asdf = false;  
    }
    else
      $diaClase = $diaClase.'<br><a id="'.$idClase.'" class="cambiarDiaClase" href="">Cambiar</a>';
    if($rutProfesor == NULL)
      $rutProfesor = 'Profesor no asignado.<br><a id="'.$idClase.'" class="cambiarProfesor" href="">Asignar</a>';
    else
      $rutProfesor = $rutProfesor.'<br><a id="'.$idClase.'" class="cambiarProfesor" href="">Cambiar</a>';
    if(isset($asdf) && $asdf == false) {
        $moduloInicio = 'No se puede asignar módulo de inicio sin asignar antes el día.';
        $moduloTermino = 'No se puede asignar módulo de término sin asignar antes el día.';
    }
    else
    {
      if($moduloInicio == NULL)
        $moduloInicio = 'Hora de inicio no asignada.<br><a id="'.$idClase.'" class="cambiarModuloInicio" href="">Asignar</a>';
      else {
        $hora = obtenerHoraModulo($moduloInicio,$idClase);
        $moduloInicio = ' '.$moduloInicio.'.'.$hora.' <br><a id="'.$idClase.'" class="cambiarModuloInicio" href="">Cambiar</a>';
      }
      if($moduloTermino == NULL)
        $moduloTermino = 'Hora de termino no asignada.<br><a id="'.$idClase.'" class="cambiarModuloTermino" href="">Asignar</a>';
      else {  
        $hora2 = obtenerHoraModulo($moduloTermino,$idClase);
        $moduloTermino = ' '.$moduloTermino.'.'.$hora2.' <br><a id="'.$idClase.'" class="cambiarModuloTermino" href="">Cambiar</a>';
      }
    }
    echo '<tr><td class="dc">'.$claseTipo.'</td><td class="dc">'.$rutProfesor.'</td><td class="dc">'.$diaClase.'</td><td class="dc">'.$moduloInicio.'</td><td class="dc">'.$moduloTermino.'</td></tr>';
  }
  if($flag2 == 0)
    echo '<tr><td class="dc">No existen clases para esta sección.</td></tr>';
  $res2->free_result();
  echo '</table>';
  $res->free_result();
}

function obtenerHoraModulo($modulo,$idClase)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT m.Inicio,m.Termino
           FROM Modulo AS m
           INNER JOIN Clase AS c ON c.Id = '{$idClase}'
           INNER JOIN Seccion AS s ON s.Id = c.Seccion_Id
          WHERE m.Regimen = s.Regimen AND M.Modulo = '{$modulo}';";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($inicio,$termino);
  $res->fetch();
  $res->free_result();
  return substr($inicio,0,5).' - '.substr($termino,0,5);
}

function verProfesores() {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT p.RUT_Profesor,p.Nombre,p.Profesor_Grado
           FROM Profesor AS p
          ORDER BY p.Nombre;";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($rutProfesor,$nombre,$profesorGrado);
  while($res->fetch())
  {
    if($profesorGrado == 1)
      $profesorGrado = 'Egresado o estudiante';
    elseif($profesorGrado == 2)
      $profesorGrado = 'Titulado';
    elseif($profesorGrado == 3)
      $profesorGrado = 'Magister';
    elseif($profesorGrado == 4)
      $profesorGrado = 'Doctorado';
    echo '<option value="'.$rutProfesor.'">'.$nombre.' ('.$rutProfesor.') - '.$profesorGrado.'</option>';
  }
  $res->free_result();
}

function verRamos($tipoUsuario) {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    if($tipoUsuario == 2 || $tipoUsuario == 3)
    { 
      $sql = "SELECT r.Codigo,r.Nombre,r.Periodo,r.Teoria,rt.Abreviacion,r.Ayudantia,r.Laboratorio,r.Taller,r.Creditos
               FROM Ramo AS r 
               INNER JOIN Ramo_Tipo AS rt ON rt.Id = r.Tipo
              ORDER by r.Codigo;";
    }
    elseif($tipoUsuario == 4)
    {
      $sql = "SELECT r.Codigo,r.Nombre,r.Periodo,r.Teoria,rt.Abreviacion,r.Ayudantia,r.Laboratorio,r.Taller,r.Creditos
               FROM Ramo AS r 
               INNER JOIN Ramo_Tipo AS rt ON rt.Id = r.Tipo
              WHERE rt.Abreviacion = 'F' OR rt.Abreviacion = 'I' OR rt.Abreviacion = 'M' OR rt.Abreviacion = 'Q'
              ORDER by r.Codigo;";
    }
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigo,$nombre,$periodo,$teoria,$tipo,$ayudantia,$laboratorio,$taller,$creditos);
    $car = 0;
    while($res->fetch())
    {
      if($periodo == 1)
        $periodo = 'S';
      elseif($periodo == 2)
        $periodo = 'T';
      if($tipoUsuario == 2 || $tipoUsuario == 3)
      {
        if($tipo == 'C' || $tipo == 'O' || $tipo == 'P')
        {
          echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$tipo.'</td><td class="mid">'.$periodo.'</td><td>'.$teoria.'</td><td>'.$ayudantia.'</td><td>'.$laboratorio.'</td><td>'.$taller.'</td><td>'.$creditos.'</td><td class="mid"><a id="'.$codigo.'" class="relacionar" href="">Relacionar</a></td><td class="mid"><a href="">X</a></td></tr>';
        }
        else
        {
          echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$tipo.'</td><td class="mid">'.$periodo.'</td><td>'.$teoria.'</td><td>'.$ayudantia.'</td><td>'.$laboratorio.'</td><td>'.$taller.'</td><td>'.$creditos.'</td><td class="mid"><a id="'.$codigo.'" class="relacionar" href="">Relacionar</a></td><td></td></tr>';
        }
      }
      elseif($tipoUsuario == 4)
      {
        echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$tipo.'</td><td class="mid">'.$periodo.'</td><td>'.$teoria.'</td><td>'.$ayudantia.'</td><td>'.$laboratorio.'</td><td>'.$taller.'</td><td>'.$creditos.'</td><td class="mid"><a href="">X</a></td></tr>';
      }
    }
    if(!isset($codigo))
      echo '<tr><td>No hay ramos.</td></tr>';
    $res->free_result();
  }

function revisarPresupuesto($codigoCarrera,$codigoSemestre)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT p.presupuesto
           FROM Presupuesto AS p 
          WHERE p.Codigo_Carrera = '{$codigoCarrera}' AND p.Codigo_Semestre = '{$codigoSemestre}';";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($presupuesto);
  if($res->fetch())
  {
    $res->free_result();
    return $presupuesto;
  }
  else
  {
    $res->free_result();
    return NULL;
  }
}

function verHorario($codigoCarrera,$codigoSemestre,$numeroSemestre)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT c.Periodo,c.Regimen,c.Numero
           FROM Carrera AS c 
          WHERE c.Codigo = '{$codigoCarrera}';"; 
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($periodo,$regimen,$numero);
  $res->fetch();
  $res->free_result();  
  
  $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql2 = "SELECT m.Modulo,m.Inicio,m.Termino
           FROM Modulo AS m 
          WHERE m.Regimen = '{$regimen}';"; 
  $res2 = $mysqli2->prepare($sql2);
  $res2->execute();
  $res2->bind_result($modulo,$inicio,$termino);
  if($regimen == 'D')
    echo '<div class="down"><table><tr><td class="dc">Módulo / Días</td><td class="dc">Lunes</td><td class="dc">Martes</td><td class="dc">Miércoles</td><td class="dc">Jueves</td><td class="dc">Viernes</td></tr>';
  else
    echo '<div class="down"><table><tr><td class="dc">Módulo / Días</td><td class="dc">Lunes</td><td class="dc">Martes</td><td class="dc">Miércoles</td><td class="dc">Jueves</td><td class="dc">Viernes</td><td class="hor">Sábado</td></tr>';
  while($res2->fetch())
  {
    if($modulo % 2 != 0) 
    {
        echo '<tr><td class="dc">'.$modulo.'. '.substr($inicio,0,5).'-'.substr($termino,0,5).'<br>';
        $moduloAnterior = $modulo;
    }
    else 
    {
      if($regimen == 'D') 
      {
        echo $modulo.'. '.substr($inicio,0,5).'-'.substr($termino,0,5).'</td>';
        $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $i = 0;
        for($i = 0;$i<5;$i++)
        {
          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "SELECT s.Codigo_Ramo,s.Numero_Seccion,c.Clase_Tipo,c.Dia,c.Modulo_Inicio,c.Modulo_Termino
                   FROM Carrera_Tiene_Ramos AS ctr 
                   INNER JOIN Ramos_Impartidos AS ri ON ri.Codigo_Carrera = ctr.Codigo_Carrera AND ri.Codigo_Ramo = ctr.Codigo_Ramo AND ri.Codigo_Semestre = '{$codigoSemestre}' AND ri.Impartido = 1
                   INNER JOIN Seccion AS s ON s.Codigo_Ramo = ri.Codigo_Ramo AND s.Codigo_Carrera = ri.Codigo_Carrera AND s.Codigo_Semestre = ri.Codigo_Semestre
                   INNER JOIN Clase AS c ON c.Seccion_Id = s.Id AND c.Modulo_Inicio = '{$moduloAnterior}' AND c.Modulo_Termino = '{$modulo}' AND c.Dia = '{$dias[$i]}'
                  WHERE ctr.Codigo_Carrera = '{$codigoCarrera}' AND ctr.Semestre = '{$numeroSemestre}';"; 
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($codigoRamo,$numeroSeccion,$claseTipo,$dia,$moduloInicio,$moduloTermino);
          $flag = 0;
          while($res->fetch())
          {
            if($flag == 0) {
              $flag = 1;
              echo '<td class="drop">';
            }
            echo '<div class="item assigned" style="position: static; left: 649px; top: 450px;">'.$codigoRamo.'<br>'.$numeroSeccion.'.'.$claseTipo.'</div>';
          }
          if($flag == 0)
            echo '<td class="drop"></td>';
          else
            echo '</td>';
          $res->free_result();  
        }
        echo '</tr>';
      }
      else {
        echo $modulo.'. '.substr($inicio,0,5).'-'.substr($termino,0,5).'</td><td class="hor"></td><td class="hor"></td><td class="hor"></td><td class="hor"></td><td class="hor"></td><td class="hor"></td></tr>';
      }
    }
  }
  $res2->free_result();  
  echo '</table></div>';
}

function numeroSemestres($codigoCarrera)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT c.Numero
           FROM Carrera AS c 
          WHERE c.Codigo = '{$codigoCarrera}';"; 
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($numeroSemestres);
  $res->fetch();
  $res->free_result();  
  return $numeroSemestres;
}

// Funciones usuario departamento
function verRamosDepartamento() {
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT r.Codigo,r.Nombre,r.Periodo,r.Teoria,rt.Abreviacion,r.Ayudantia,r.Laboratorio,r.Taller,r.Creditos
             FROM Ramo AS r 
             INNER JOIN Ramo_Tipo AS rt ON rt.Id = r.Tipo
            WHERE rt.Abreviacion = 'F' OR rt.Abreviacion = 'I' OR rt.Abreviacion = 'M' OR rt.Abreviacion = 'Q'
            ORDER by r.Codigo;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigo,$nombre,$periodo,$teoria,$tipo,$ayudantia,$laboratorio,$taller,$creditos);

    $codigoSemestre = obtenerSemestreDepartamento();
    $codigoTrimestre = obtenerTrimestreDepartamento();  

    while($res->fetch())
    {
      $mysqliw = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $numero = 0;
      if($codigoSemestre != 0 && $codigoTrimestre != 0)
      {
        $sqlw = "SELECT COUNT(s.Id)
                  FROM Seccion AS s
                 WHERE s.Codigo_Ramo = '{$codigo}' AND (s.Codigo_Semestre = '{$codigoSemestre}' OR s.Codigo_Semestre = '{$codigoTrimestre}');";
      }
      elseif($codigoSemestre != 0 && $codigoTrimestre == 0)
      {
        $sqlw = "SELECT COUNT(s.Id)
                  FROM Seccion AS s
                 WHERE s.Codigo_Ramo = '{$codigo}' AND s.Codigo_Semestre = '{$codigoSemestre}';";
      }
      elseif($codigoSemestre == 0 && $codigoTrimestre != 0)
      {
        $sqlw = "SELECT COUNT(s.Id)
                  FROM Seccion AS s
                 WHERE s.Codigo_Ramo = '{$codigo}' AND s.Codigo_Semestre = '{$codigoTrimestre}';";
      }
      $resw = $mysqliw->prepare($sqlw);
      $resw->execute();
      $resw->bind_result($numero);
      $resw->fetch();

      if($periodo == 1 && $codigoSemestre != 0)
      {
        $form = '<form method="post" name="crearSeccion" target="_self"><input type="radio" name="regimen" value="D">D</input> <input type="radio" name="regimen" value="V">V</input><br><input type="hidden" name="hiddenCodigoRamo" value="'.$codigo.'"></input><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Crear"></input></form>';
      }
      elseif($periodo == 1 && $codigoSemestre == 0)
      {
        $form = 'Semestre cerrado.';
      }
      elseif($periodo == 2 && $codigoTrimestre != 0)
      {
        $form = '<form method="post" name="crearSeccion" target="_self"><input type="radio" name="regimen" value="D">D</input> <input type="radio" name="regimen" value="V">V</input><br><input type="hidden" name="hiddenCodigoRamo" value="'.$codigo.'"></input><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoTrimestre.'"></input><input type="submit" name="submit" value="Crear"></input></form>';
      }
      elseif($periodo == 2 && $codigoTrimestre == 0)
      {
        $form = 'Trimestre cerrado.';
      }

      echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td class="mid">'.$form.'</td><td class="mid"><a href="clases.php?codigoRamo='.$codigo.'">'.$numero.'</a></td></tr>';
      $resw->free_result();
    }
    if(!isset($codigo))
      echo '<tr><td>No hay ramos.</td></tr>';
    $res->free_result();
  }

function obtenerSemestreDepartamento() {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlo = "SELECT s.Codigo_Semestre
            FROM Semestre AS s
           WHERE s.Fecha_Termino IS NULL;";
  $reso = $mysqlio->prepare($sqlo);
  $reso->execute();
  $reso->bind_result($codigoSemestre);
  if($reso->fetch())
  {
    $reso->free_result();
    return $codigoSemestre;
  }
  else
  {
    $reso->free_result();
    return 0;
  }
}

function obtenerTrimestreDepartamento() {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlo = "SELECT t.Codigo_Trimestre
            FROM Trimestre AS t
           WHERE t.Fecha_Termino IS NULL;";
  $reso = $mysqlio->prepare($sqlo);
  $reso->execute();
  $reso->bind_result($codigoTrimestre);
  if($reso->fetch())
  {
    $reso->free_result();
    return $codigoTrimestre;
  }
  else
  {
    $reso->free_result();
    return 0;
  }
}

function verClasesDepartamento($codigoRamo) {
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;

  $codigoSemestre = obtenerSemestreDepartamento();
  $codigoTrimestre = obtenerTrimestreDepartamento(); 

  $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sql = "SELECT s.Id,s.Numero_Seccion,s.NRC,s.Codigo_Ramo,r.Nombre,s.Codigo_Carrera,s.Codigo_Semestre,s.Vacantes
           FROM Seccion AS s
           INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
          WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Carrera = 'UNABDEPTO' AND (s.Codigo_Semestre = '{$codigoSemestre}' OR s.Codigo_Semestre = '{$codigoTrimestre}') ORDER BY s.Numero_Seccion;";
  $res = $mysqli->prepare($sql);
  $res->execute();
  $res->bind_result($id,$numeroSeccion,$NRC,$codigoRamo,$nombre,$codigoCarrera,$codigoSemestre2,$vacantes);
  $flag = 0;
  echo '<table><tr><td>Sección</td><td>NRC</td><td>Nombre</td><td>Semestre</td></tr>';
  while($res->fetch())
  {
    if($flag == 0)
      $flag = 1;
    $flag2 = 0;
    echo '<tr><td class="dc">'.$numeroSeccion.'</td><td class="dc">'.$NRC.'</td><td class="dc">'.$nombre.'</td><td class="dc">'.$codigoSemestre2.'</td></tr>';
    $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql2 = "SELECT c.Id,c.Clase_Tipo,c.RUT_Profesor,c.Modulo_Inicio,c.Modulo_Termino,c.Dia,c.Codigo_Semestre
              FROM Clase AS c
             WHERE c.Seccion_Id = '{$id}';";
    $res2 = $mysqli2->prepare($sql2);
    $res2->execute();
    $res2->bind_result($idClase,$claseTipo,$rutProfesor,$moduloInicio,$moduloTermino,$diaClase,$codigoSemestreClase);
    while($res2->fetch())
    {
      if($flag2 == 0)
        $flag2 = 1;
      if($diaClase == NULL) {
        $diaClase = 'Día de la clase no asignado.<br><a id="'.$idClase.'" class="cambiarDiaClase" href="">Asignar</a>';
        $asdf = false;  
      }
      else
        $diaClase = $diaClase.'<br><a id="'.$idClase.'" class="cambiarDiaClase" href="">Cambiar</a>';
      if($rutProfesor == NULL)
        $rutProfesor = 'Profesor no asignado.<br><a id="'.$idClase.'" class="cambiarProfesor" href="">Asignar</a>';
      else
        $rutProfesor = $rutProfesor.'<br><a id="'.$idClase.'" class="cambiarProfesor" href="">Cambiar</a>';
      if(isset($asdf) && $asdf == false) {
          $moduloInicio = 'No se puede asignar módulo de inicio sin asignar antes el día.';
          $moduloTermino = 'No se puede asignar módulo de término sin asignar antes el día.';
      }
      else
      {
        if($moduloInicio == NULL)
          $moduloInicio = 'Hora de inicio no asignada.<br><a id="'.$idClase.'" class="cambiarModuloInicio" href="">Asignar</a>';
        else
          $moduloInicio = $moduloInicio.'<br><a id="'.$idClase.'" class="cambiarModuloInicio" href="">Cambiar</a>';
        if($moduloTermino == NULL)
          $moduloTermino = 'Hora de termino no asignada.<br><a id="'.$idClase.'" class="cambiarModuloTermino" href="">Asignar</a>';
        else
          $moduloTermino = $moduloTermino.'<br><a id="'.$idClase.'" class="cambiarModuloTermino" href="">Cambiar</a>';
      }
      echo '<tr><td>'.$claseTipo.'</td><td>'.$rutProfesor.'</td><td>'.$diaClase.'</td><td>'.$moduloInicio.'</td><td>'.$moduloTermino.'</td></tr>';
    }
    if($flag2 == 0)
      echo '<tr><td class="dc">No existen clases para esta sección.</td></tr>';
    $res2->free_result();
  }
  if($flag == 0)
    echo '<tr><td>No hay secciones para este ramo.</td><td></td></tr>';
  echo '</table>';
  $res->free_result();
}

function obtenerSeccionesSolicitud($idSolicitud)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlo = "SELECT sc.id,sc.Numero_Seccion,sc.NRC,sc.Codigo_Ramo,sc.Vacantes,sc.Vacantes_Utilizadas
            FROM Solicitud AS s
            INNER JOIN Seccion AS sc ON sc.Codigo_Ramo = s.Codigo_Ramo AND sc.Codigo_Carrera = s.Carrera AND sc.Codigo_Semestre = s.Codigo_Semestre
           WHERE s.Id = '{$idSolicitud}';";
  $reso = $mysqlio->prepare($sqlo);
  $reso->execute();
  $reso->bind_result($idSeccion,$numeroSeccion,$nrc,$codigoRamo,$vacantes,$vacantesUtilizadas);
  echo '<table><tr><td># Sección</td><td>Ramo</td><td>NRC</td><td>Vacantes utilizadas</td><td></td></tr>';
  while($reso->fetch())
  {
    $vacantesRestantes = calcularVacantesRestantes($idSeccion);
    if($vacantesRestantes == 60)
      echo '<tr><td>'.$numeroSeccion.'</td><td>'.$codigoRamo.'</td><td>'.$nrc.'</td><td>'.$vacantesRestantes.'<td><input type="radio" name="seccion" value="'.$idSeccion.'" disabled></input></td></tr>';
    else
      echo '<tr><td>'.$numeroSeccion.'</td><td>'.$codigoRamo.'</td><td>'.$nrc.'</td><td>'.$vacantesRestantes.'<td><input type="radio" name="seccion" value="'.$idSeccion.'"></input></td></tr>';
  }
  echo '</table>';
  $reso->free_result();
}

function calcularVacantesRestantes($idSeccion)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlo = "SELECT s.Vacantes_Utilizadas
            FROM Seccion AS s
           WHERE s.Id = '{$idSeccion}';";
  $reso = $mysqlio->prepare($sqlo);
  $reso->execute();
  $reso->bind_result($vacantesUtilizadas);
  $reso->fetch();
  $reso->free_result();

  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlio = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlo = "SELECT SUM(s.Vacantes_Asignadas)
            FROM Solicitud AS s
           WHERE s.Seccion_Asignada = '{$idSeccion}';";
  $reso = $mysqlio->prepare($sqlo);
  $reso->execute();
  $reso->bind_result($vacantesSolicitud);
  $reso->fetch();
  $reso->free_result();

  if($vacantesUtilizadas == NULL && $vacantesSolicitud == NULL)
    return 0;
  elseif($vacantesUtilizadas == NULL && $vacantesSolicitud != NULL)
    return $vacantesSolicitud;
  elseif($vacantesUtilizadas != NULL && $vacantesSolicitud == NULL)
    return $vacantesUtilizadas;
  else
    return $vacantesUtilizadas+$vacantesSolicitud;
}

?>
