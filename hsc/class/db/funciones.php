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

function obtenerTiposRamo()
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT rt.id,rt.tipo FROM Ramo_Tipo AS rt;";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($id,$tipo);
  while($resf->fetch())
  {
    echo '<option value="'.$id.'">'.$tipo.'</option>';
  }
  $resf->free_result();
}

//Funciones de usuario Jefe de carrera
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
?>
