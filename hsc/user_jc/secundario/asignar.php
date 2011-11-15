<?php
foreach (glob("../../class/*.php") as $filename) {
   include_once($filename);
}
include_once("../../class/db/funciones.php");
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'administrador') {
    $usuario = new jefeDeCarrera($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut(),$usuario->getTipo());
    $_SESSION['usuario'] = serialize($usuario);
  }


  if(isset($_POST['submit']) && $_POST['submit'] == 'Asignar')
  {
    if(isset($_POST['hiddenNRC']) && isset($_POST['inicio']) && isset($_POST['termino']))
    {
      if($_POST['inicio'] != 0 && $_POST['termino'] != 0) {
        if($_POST['termino'] >= $_POST['inicio'])
        {
          $answer2 = asignarHorarioASeccion($_POST['hiddenNRC'],$_POST['inicio'],$_POST['termino']);
        }
        else
        {
          $error2 = '*El horario de termino de la sección debe ser mayor o igual que el de inicio.';
        }
      }
      else {
        $error2 = '*Debe elegir los horarios de inicio y termino.';
      }
    }
  }

  if($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3)
  {
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>colour_blue</title>
  <meta charset="utf-8" />
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="../../style/style.css" title="style" />
  <link rel="stylesheet" type="text/css" href="../../style/bsc.css" title="style" />
</head>

<body>
  <?php
    if(isset($_GET['codigoRamo']) && isset($_GET['id']))
    {
      if($_GET['id'] == 'profe')
      {
        echo '<h1>Elegir profesor:</h1>';
        echo '<form name="cambiarProfesor" method="post" target="_self"><select><option value="0">Elegir profesor</option>';
        
      }
      elseif($_GET['id'] == 'horario')
      {
        echo '<h1>Cambiar horario</h1>';
        $horario = obtenerHorarioActual($_GET['codigoRamo']);
        if($horario == NULL)
          $horario = 'S/Horario';
        echo '<table><tr><td>Horario actual</td><td>'.$horario.'</td></tr>';
        echo '<tr><form name="cambiarHorario" method="post" target="_self"><td>Inicio sección</td><td><select name="inicio"><option value="">Elegir horario</option>';
        obtenerHorariosSegunRamo($_SESSION['carrera'],$_GET['codigoRamo']);
        echo '</select></td></tr>';
        echo '<tr><td>Termino sección</td><td><select name="termino"><option value="">Elegir horario</option>';
        obtenerHorariosSegunRamo($_SESSION['carrera'],$_GET['codigoRamo']);
        echo '</select></td><td><input type="submit" name="submit" value="Asignar"></input></td></tr>';
        echo '<input type="hidden" name="hiddenNRC" value="'.$_GET['codigoRamo'].'"></input>';
        echo '</form></table>';
        if(isset($error2))
          echo '<span class="error">'.$error2.'</span>';
      }
    }
    else
      echo 'Malo';
  ?>
  <br><a href="../secciones.php" target="_parent">Cerrar</a>
  <script type='text/javascript' src='../../js/jquery.js'></script> 
  <script type='text/javascript' src='../../js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='../../js/bsc.js'></script></body>
</html><?php
  }
  else
  {
    header("Location: index.php");
    exit();
  }
}
else
{
  header("Location: index.php");
  exit();
}
