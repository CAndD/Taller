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

  if(isset($_POST['submit']) && $_POST['submit'] == 'Ingresar')
  {
    if($_POST['presupuesto'] != 0)
    {
      if(!is_int($_POST['presupuesto'])) 
      {
        $msg = $usuario->ingresarPresupuesto($_SESSION['carrera'],$_SESSION['codigoSemestre'],$_POST['presupuesto']);
      }
      else
        $error = '*El presupuesto debe ser ingresado solamente con números.';
    }
    else 
      $error = '*Debe ingresar el presupuesto.';
  }

  if(isset($_POST['submit']) && $_POST['submit'] == 'Cambiar')
  {
    if($_POST['presupuesto'] != 0)
    {
      if(!is_int($_POST['presupuesto'])) 
      {
        $msg = $usuario->cambiarPresupuesto($_SESSION['carrera'],$_SESSION['codigoSemestre'],$_POST['presupuesto']);
      }
      else
        $error = '*El presupuesto debe ser ingresado solamente con números.';
    }
    else 
      $error = '*Debe ingresar el presupuesto.';
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
    echo '<h1>Presupuesto</h1>';

    if(isset($error))
      echo '<span class="error">'.$error.'</span><br>';

    if(isset($msg))
      echo '<span class="error">'.$msg.'</span><br>';

    $res = revisarPresupuesto($_SESSION['carrera'],$_SESSION['codigoSemestre']);
    if($res == NULL)
    {
      echo 'Ingrese el presupuesto sin símbolos ni puntos.';
      echo '<form method="post" name="ingresarPresupuesto" value=""><input type="text" name="presupuesto" value="" maxlength="11"></input><input type="submit" name="submit" value="Ingresar"></input></form>';
    }
    else
    {
      echo 'Presupuesto actual: '.$res.'<br>';
      echo '<form method="post" name="cambiarPresupuesto" value=""><input type="text" name="presupuesto" value="'.$res.'" maxlength="11"></input><input type="submit" name="submit" value="Cambiar"></input></form>';
    }
    echo '<br><br><a href="../../home.php" target="_parent">Salir</a>';
  ?>
  <script type='text/javascript' src='../../js/jquery.js'></script> 
  <script type='text/javascript' src='../../js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='../../js/bsc.js'></script></body>
</html><?php
  }
  else
  {
    header("Location: ../../index.php");
    exit();
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
