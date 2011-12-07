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

function obtenerModulosSugerencia($regimen,$idClase,$codigoCarrera,$codigoSemestre)
{
  global $mysqli,$db_host,$db_user,$db_pass,$db_database;
  $i = 0;
  $ramos = array();

  $mysqlif2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf2 = "SELECT c.Id,c.Dia,s.Id,r.Codigo,ctr.Semestre,carr.Numero
             FROM Clase AS c
             INNER JOIN Seccion AS s ON s.Id = c.Seccion_Id
             INNER JOIN Ramo AS r ON r.Codigo = s.Codigo_Ramo
             INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Carrera = '{$codigoCarrera}' AND ctr.Codigo_Ramo = r.Codigo
             INNER JOIN Carrera AS carr ON carr.Codigo = '{$codigoCarrera}'
            WHERE c.Id = '{$claseId}'";
  $resf2 = $mysqlif2->prepare($sqlf2);
  $resf2->execute();
  $resf2->bind_result($idClass,$dia,$idSeccion,$ramoCodigo,$semestreRamo,$semestres);
  $resf2->fetch();
  $resf2->free_result();
  if($semestreRamo == 1)
  {
    $semestreAntes = 0;
  }
  else
  {
    $semestreAntes = $semestreRamo - 1;
  }

  if($semestreRamo == $semestres)
  {
    $semestreDespues = 0;
  }
  else
  {
    $semestreDespues = $semestreRamo + 1;
  }

  $mysqlif3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf3 = "SELECT ri.Codigo_Ramo
             FROM Carrera_Tiene_Ramos AS ctr
             INNER JOIN Ramos_Impartidos AS ri ON ri.Codigo_Ramo = ctr.Codigo_Ramo AND ri.Impartido = 1
            WHERE ctr.Codigo_Carrera = '{$codigoCarrera}' AND ctr.Semestre = '{$semestreRamo}'";
  $resf3 = $mysqlif3->prepare($sqlf3);
  $resf3->execute();
  $resf3->bind_result($codigoRamo);
  while($resf3->fetch())
  {
    $ramos[$i] = $codigoRamo;
    $i++;
  }
  $resf3->free_result();

  if($semestreAntes > 0)
  {
    $mysqlif3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sqlf3 = "SELECT ri.Codigo_Ramo
               FROM Carrera_Tiene_Ramos AS ctr
               INNER JOIN Ramos_Impartidos AS ri ON ri.Codigo_Ramo = ctr.Codigo_Ramo AND ri.Impartido = 1
              WHERE ctr.Codigo_Carrera = '{$codigoCarrera}' AND ctr.Semestre = '{$semestreAntes}'";
    $resf3 = $mysqlif3->prepare($sqlf3);
    $resf3->execute();
    $resf3->bind_result($codigoRamo);
    while($resf3->fetch())
    {
      $ramos[$i] = $codigoRamo;
      $i++;
    }
    $resf3->free_result();
  }
  if($semestreDespues > 0)
  {
    $mysqlif3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sqlf3 = "SELECT ri.Codigo_Ramo
               FROM Carrera_Tiene_Ramos AS ctr
               INNER JOIN Ramos_Impartidos AS ri ON ri.Codigo_Ramo = ctr.Codigo_Ramo AND ri.Impartido = 1
              WHERE ctr.Codigo_Carrera = '{$codigoCarrera}' AND ctr.Semestre = '{$semestreDespues}'";
    $resf3 = $mysqlif3->prepare($sqlf3);
    $resf3->execute();
    $resf3->bind_result($codigoRamo);
    while($resf3->fetch())
    {
      $ramos[$i] = $codigoRamo;
      $i++;
    }
    $resf3->free_result();
  }

  $mysqlif = @new mysqli($db_host, $db_user, $db_pass, $db_database);
  $sqlf = "SELECT m.Modulo,m.Inicio,m.Termino FROM Modulo AS m WHERE m.Regimen = '{$regimen}'";
  $resf = $mysqlif->prepare($sqlf);
  $resf->execute();
  $resf->bind_result($modulo,$inicio,$termino);
  while($resf->fetch())
  {
    $mysqlif3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sqlf3 = "SELECT c.Clase_Tipo
               FROM Seccion AS s
               INNER JOIN Clase AS c ON c.Seccion_Id = s.Id AND c.Dia = '{$dia}' AND c.Modulo_Inicio = '{$modulo}' OR c.Modulo_Termino = '{$modulo}'
              WHERE s.Codigo_Ramo = '{$codigoRamo}' AND s.Codigo_Semestre = '{$codigoSemestre}' AND s.Codigo_Carrera = '{$codigoCarrera}'";
    $resf3 = $mysqlif3->prepare($sqlf3);
    $resf3->execute();
    $resf3->bind_result($codigoRamo);
    if($resf3->fetch())
    {
      echo '<option value="0" style="background-color: red;">'.$modulo.'. '.$inicio.' - '.$termino.'</option>';
    }
    else
    {
      echo '<option value="'.$modulo.'">'.$modulo.'. '.$inicio.' - '.$termino.'</option>';
    }
    $resf3->free_result();
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
        $seccionesCreadasNumero = '<a href="clases.php?codigoRamo='.$codigoRamo.'">'.$seccionesCreadasNumero.'</a>';
      $res2->free_result();

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
        echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td class="mid"><form method="post" name="crearSeccion" target="_self"><input type="hidden" name="hiddenCodigoRamo" value="'.$codigoRamo.'"></input><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoSemestre.'"></input><input type="hidden" name="hiddenCodigoCarrera" value="'.$codigoCarrera.'"></input><input type="submit" name="submit" value="Crear"></input></form></td><td class="mid">'.$seccionesCreadasNumero.'</td><td class="mid">0</td><td class="mid">'.$seccionesCreadasOtroNumero.'</td></tr>';
      }
      else
      {
        echo '<tr><td class="mid">'.$semestreRamo.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td><td class="mid"></td><td class="mid">'.$seccionesCreadasNumero.'</td><td class="mid">0</td><td class="mid">'.$seccionesCreadasOtroNumero.'</td></tr>';
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

function verClases($codigoRamo,$codigoCarrera,$codigoSemestre) {
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
    $flag2 = 0;
    echo '<tr><td>'.$numeroSeccion.'</td><td>'.$NRC.'</td><td>'.$nombre.'</td><td>'.$codigoSemestre.'</td></tr>';
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
    echo '<option value="'.$rutProfesor.'">'.$nombre.'</option>';
  }
  $res->free_result();
}
?>
