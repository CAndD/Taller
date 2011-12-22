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
  elseif(isset($_GET['idClase']) && isset($_GET['diaClase']) && isset($_GET['moduloInicio']) && isset($_GET['moduloTermino']) && isset($_GET['codigoSemestre']))
  {
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT rt.Abreviacion
             FROM Ramo_Tipo AS rt
            WHERE rt.Abreviacion = '{}';";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($abreviacion);
    if($res->fetch())
    {
      echo $abreviacion;
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
