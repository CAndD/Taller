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
    $inicio = 0;
    $fin = 0;
    $largo = strlen($_GET['horario']); 
    $flag = 0;
    $i = 0;
    for($i = 0;$i<2;$i++)
    {
      $flag = 0;
      while($flag == 0)
      {
        if(substr($_GET['horario'],$fin,1) == '.')
        {
          if($i == 0) {
            $dia = substr($_GET['horario'],0,$fin);
            $inicio = $fin+1;
            $flag = 1;
          }
          elseif($i == 1) {
            $moduloInicio = substr($_GET['horario'],$inicio,$fin-$inicio);
            $moduloTermino = substr($_GET['horario'],$fin+1,$largo-$fin);
            $flag = 1;    
          }
        } 
        $fin++;
      }
    }
    //echo $_GET['idClase'].'-'.$_GET['horario'].'<br>';
    //echo 'dia: '.$dia.' inicio: '.$moduloInicio.' termino: '.$moduloTermino.'<br>';
    $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql2 = "SELECT ctr.Codigo_Ramo,ctr.semestre,c.Clase_Tipo
             FROM Clase AS c
             INNER JOIN Seccion AS s ON s.Id = c.Seccion_Id AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'
             INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Ramo = s.Codigo_Ramo AND ctr.Codigo_Carrera = '{$_SESSION['carrera']}'
            WHERE c.Id = '{$_GET['idClase']}';";
    $res2 = $mysqli2->prepare($sql2);
    $res2->execute();
    $res2->bind_result($codigoRamo,$semestreRamo,$claseTipo);
    $res2->fetch();
    $res2->free_result();

    //echo 'codigoRamo: '.$codigoRamo.' semestreRamo: '.$semestreRamo.' claseTipo: '.$claseTipo.'<br>';
    //echo 'carrera : '.$_SESSION['carrera'].' semestre: '.$_SESSION['codigoSemestre'].'<br>';

    $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql3 = "SELECT c.Id
             FROM Carrera_Tiene_Ramos AS ctr
             INNER JOIN Seccion AS s ON s.Codigo_Ramo = ctr.Codigo_Ramo AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'
             INNER JOIN Clase AS c ON c.Seccion_Id = s.Id AND c.Dia = '{$dia}' AND c.Modulo_Inicio = '{$moduloInicio}' AND c.Modulo_Termino = '{$moduloTermino}'
            WHERE ctr.Codigo_Carrera = '{$_SESSION['carrera']}' AND ctr.Semestre = '{$semestreRamo}';";
    $res3 = $mysqli3->prepare($sql3);
    $res3->execute();
    $res3->bind_result($idClase);
    $res3->fetch();
    $res3->free_result();
    if($res3->fetch())
    {
      //echo '0 idClase: '.$idClase;
      echo '0';
    }
    else
    {
      $mysqli4 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql4 = "UPDATE Clase SET Dia = '{$dia}',Modulo_Inicio = '{$moduloInicio}',Modulo_Termino = '{$moduloTermino}' WHERE Id = '{$_GET['idClase']}';";
      //$sql4 = "UPDATE Clase SET Dia = '{$dia}' WHERE Id = '{$_GET['idClase']}';";
      if(($mysqli4->query($sql4)) == true)
      {
        //echo '1 dia: '.$dia.' inicio: '.$moduloInicio.' termino: '.$moduloTermino;
        echo '1';
      }
      else
      {
        echo '2';
      }
    }
    $res3->free_result();
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
?>
