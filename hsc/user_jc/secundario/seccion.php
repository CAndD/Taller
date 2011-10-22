<?php
foreach (glob("../../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'administrador') {
    $usuario = new jefeDeCarrera($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut(),$usuario->getTipo());
    $_SESSION['usuario'] = serialize($usuario);
  }


  if(isset($_POST['submit']) && $_POST['submit'] == 'Solicitar')
  {
    if(isset($_POST['hiddenCarreraDuenha']) && isset($_POST['hiddenCodigoRamo']) && isset($_POST['numeroVacantes']))
    {
      if(ctype_digit($_POST['numeroVacantes']) && $_POST['numeroVacantes'] > 0) {
        $msg2 = $usuario->solicitarVacantes($_POST['hiddenCodigoRamo'],$_POST['hiddenCarreraDuenha'],$_SESSION['carrera'],$_POST['numeroVacantes'],$_SESSION['codigoSemestre']);
        $_GET['otros'] = 'si';
        $_GET['codigoRamo'] = $_POST['hiddenCodigoRamo'];
      }
      else {
        $msg2 = '*Debe al menos pedir 1 vacante.';
        $_GET['otros'] = 'si';
        $_GET['codigoRamo'] = $_POST['hiddenCodigoRamo'];
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
    if(isset($_GET['otros']) && $_GET['otros'] == 'no')
    {
      echo '<h2>Secciones</h2>';
      $usuario->verSeccionesCreadas($_GET['codigoRamo'],$_SESSION['codigoSemestre'],$_SESSION['carrera']);
    }
    elseif(isset($_GET['otros']) && $_GET['otros'] == 'si')
    {
      echo '<h2>Secciones de otras carreras</h2>';
      if(isset($msg2))
        echo '<span class="error">'.$msg2.'</span>';
      $usuario->verSeccionesCreadasOtros($_GET['codigoRamo'],$_SESSION['codigoSemestre'],$_SESSION['carrera']);
    }
    else
      echo 'Malo';
  ?>
  <a href="../secciones.php" target="_parent">Cerrar</a>
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
