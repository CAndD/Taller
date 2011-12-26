<?php
include('../../class/db/connect.php');
session_start();
if(isset($_SESSION['usuario']))
{
  if(isset($_GET['codigoRamo']))
  {
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT Codigo
             FROM Ramo
            WHERE Codigo = '{$_GET['codigoRamo']}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo);
    if($res->fetch())
    {
      echo $codigoRamo;
    }
    $res->free_result();
  }
  elseif(isset($_GET['codigoCarrera']))
  {
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT Codigo
             FROM Carrera
            WHERE Codigo = '{$_GET['codigoCarrera']}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoCarrera);
    if($res->fetch())
    {
      echo $codigoCarrera;
    }
    $res->free_result();
  }
  elseif(isset($_GET['nombreUsuario']))
  {
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT Nombre_Usuario
             FROM Usuario
            WHERE Nombre_Usuario = '{$_GET['nombreUsuario']}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($nombreUsuario);
    if($res->fetch())
    {
      echo $nombreUsuario;
    }
    $res->free_result();
  }
  elseif(isset($_GET['rutProfesor']))
  {
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT p.Rut_Profesor
             FROM Profesor AS p
            WHERE p.Rut_Profesor = '{$_GET['rutProfesor']}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($rutProfesor);
    if($res->fetch())
    {
      echo $rutProfesor;
    }
    $res->free_result();
  }
  elseif(isset($_GET['abrev']))
  {
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT rt.Abreviacion
             FROM Ramo_Tipo AS rt
            WHERE rt.Abreviacion = '{$_GET['abrev']}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($abreviacion);
    if($res->fetch())
    {
      echo $abreviacion;
    }
    $res->free_result();
  }
  elseif(isset($_GET['idClase']) && isset($_GET['horario']))
  {
    $list($dia,$moduloInicio,$moduloTermino) = explode(".",$_GET['horario']);
    //echo $dia.$moduloInicio.$moduloTermino;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT ctr.Codigo_Ramo,ctr.semestre,c.Clase_Tipo
             FROM Clase AS c
             INNER JOIN Seccion AS s ON s.Id = c.Seccion_Id AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'
             INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Ramo = s.Codigo_Ramo AND ctr.Codigo_Carrera = '{$_SESSION['carrera']}'
            WHERE c.Id = '{$_GET['idClase']}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo,$semestreRamo,$claseTipo);
    $res->fetch();
    $res->free_result();

    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT c.Id
             FROM Carrera_Tiene_Ramos AS ctr
             INNER JOIN Seccion AS s ON s.Codigo_Ramo = ctr.Codigo_Ramo AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'
             INNER JOIN Clase AS c ON c.Seccion_Id = s.Id AND c.Dia = '{$dia}' AND c.Modulo_Inicio = '{$moduloInicio}' AND c.Modulo_Termino = '{$moduloTermino}'
            WHERE ctr.Codigo_Carrera = '{$_SESSION['carrera']}' AND ctr.Semestre = '{$semestreRamo}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($semestreRamo);
    $res->fetch();
    $res->free_result();
    if($res->fetch())
    {
      echo $abreviacion;
    }
    else
    {
      
    }
    $res->free_result();
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
?>
