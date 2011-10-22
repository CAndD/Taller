<?php
foreach (glob("../../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3)
  {
    if(isset($_POST['eliminarJDC']) && $_POST['eliminarJDC'] == 'Si')
    {
      $msg = $usuario->eliminarJefeDeCarrera($_POST['hidden_jdc']);
    }
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
</head>

<body>
  <?php
  if(isset($msg))
  {
    echo $msg.'<a href="../jdc.php" target="_parent">Cerrar</a>';
  }
  else {
?>
  <h1>Desea eliminar jefe de carrera?</h1>
  
  
  
  <form method="post" name="eliminar_jdc" target="_self">
   <input type="hidden" name="hidden_jdc" value="<?php if(isset($_GET['hidden_jdc'])){echo$_GET['hidden_jdc'];}elseif(isset($_POST['hidden_jdc'])){echo$_POST['hidden_jdc'];}?>"></input>
   <input type="submit" name="eliminarJDC" value="Si"></input>
  </form>
  <a href="../jdc.php" target="_parent">No eliminar</a></body>
</html><?php
    }
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
