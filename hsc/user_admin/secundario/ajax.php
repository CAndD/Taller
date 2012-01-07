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
            $moduloInicioF = substr($_GET['horario'],$inicio,$fin-$inicio);
            $moduloTerminoF = substr($_GET['horario'],$fin+1,$largo-$fin);
            $flag = 1;    
          }
        } 
        $fin++;
      }
    }

    if($moduloInicioF == 0 || $moduloTerminoF == 0)
      echo 'false inicio: '.$moduloInicioF.' termino: '.$moduloTerminoF;
    else{
    $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql2 = "SELECT ctr.Codigo_Ramo,ctr.semestre,s.Numero_Seccion,c.Clase_Tipo,s.Id
             FROM Clase AS c
             INNER JOIN Seccion AS s ON s.Id = c.Seccion_Id AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'
             INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Ramo = s.Codigo_Ramo AND ctr.Codigo_Carrera = '{$_SESSION['carrera']}'
            WHERE c.Id = '{$_GET['idClase']}';";
    $res2 = $mysqli2->prepare($sql2);
    $res2->execute();
    $res2->bind_result($codigoRamo,$semestreRamo,$numeroSeccion,$claseTipo,$idSeccion);
    $res2->fetch();
    $res2->free_result();

    $flag2 = 0;

    //1. Buscar clase de sección de diferente ramo.
    $mysqli3 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql3 = "SELECT c.Id
             FROM Carrera_Tiene_Ramos AS ctr
             INNER JOIN Seccion AS s ON s.Codigo_Ramo = ctr.Codigo_Ramo AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'
             INNER JOIN Clase AS c ON c.Seccion_Id = s.Id AND c.Dia = '{$dia}' AND c.Modulo_Inicio = '{$moduloInicioF}' AND c.Modulo_Termino = '{$moduloTerminoF}'
            WHERE ctr.Codigo_Carrera = '{$_SESSION['carrera']}' AND ctr.Semestre = '{$semestreRamo}' AND ctr.Codigo_Ramo != '{$codigoRamo}';";
    $res3 = $mysqli3->prepare($sql3);
    $res3->execute();
    $res3->bind_result($idClaseSql3);
    while($res3->fetch())
    {
      $flag2 = 1;
    }
    if($flag2 == 1)
    {
      echo '(-2) Existe una clase de un ramo distinto en este horario.';
    }
    else
    {
      //2. Buscar clase de la misma sección.
      $mysqli4 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql4 = "SELECT c.Id
                FROM Carrera_Tiene_Ramos AS ctr
                INNER JOIN Seccion AS s ON s.Codigo_Ramo = ctr.Codigo_Ramo AND s.Codigo_Carrera = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}' AND s.Codigo_Ramo = '{$codigoRamo}' AND s.Numero_Seccion = '{$numeroSeccion}'
                INNER JOIN Clase AS c ON c.Seccion_Id = s.Id AND c.Dia = '{$dia}' AND c.Modulo_Inicio = '{$moduloInicioF}' AND c.Modulo_Termino = '{$moduloTerminoF}'
               WHERE ctr.Codigo_Carrera = '{$_SESSION['carrera']}' AND ctr.Semestre = '{$semestreRamo}';";
      $res4 = $mysqli4->prepare($sql4);
      $res4->execute();
      $res4->bind_result($idClase);
      if($res4->fetch())
      {
        echo '(-1) Ya se ha asignado una clase de la misma sección en este horario.';
      }
      else
      {
        //3. Buscar clase de sección obtenida por solicitud que sea de otro ramo.
        $mysqli5 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
        $sql5 = "SELECT c.Id
                  FROM Solicitud AS s
                  INNER JOIN Carrera_Tiene_Ramos AS ctr ON ctr.Codigo_Carrera = '{$_SESSION['carrera']}' AND ctr.Codigo_Ramo = s.Codigo_Ramo AND ctr.Semestre = '{$semestreRamo}'
                  INNER JOIN Clase AS c ON c.Seccion_Id = s.Seccion_Asignada AND c.Modulo_Inicio = '{$moduloInicioF}' AND c.Modulo_Termino = '{$moduloTerminoF}' AND c.Dia = '{$dia}'
                 WHERE s.Carrera_Solicitante = '{$_SESSION['carrera']}' AND s.Codigo_Semestre = '{$_SESSION['codigoSemestre']}'AND s.Codigo_Ramo != '{$codigoRamo}' AND s.Estado = 2;";
        $res5 = $mysqli5->prepare($sql5);
        $res5->execute();
        $res5->bind_result($idClase);
        if($res5->fetch())
        {
          echo '(0)Existe una clase de un ramo pedido por solicitud.';
        }
        else
        {
          $mysqli6 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql6 = "UPDATE Clase SET Dia = '{$dia}',Modulo_Inicio = '{$moduloInicioF}',Modulo_Termino = '{$moduloTerminoF}' WHERE Id = '{$_GET['idClase']}';";
          if(($mysqli6->query($sql6)) == true)
          {
            echo '(1)Horario asignado.';
          }
          else
          {
            echo '(2)Horario no asignado, intente en un rato más.';
          }
        }
      }
      $res4->free_result();
    }
    $res3->free_result();
  }//else
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
?>
