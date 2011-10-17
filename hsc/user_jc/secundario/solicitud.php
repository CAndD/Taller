<?php
include('../../class/class_lib.php');
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'administrador') {
    $usuario = new jefeDeCarrera($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut(),$usuario->getTipo());
    $_SESSION['usuario'] = serialize($usuario);
  }

  if(isset($_POST['submit']) && $_POST['submit'] == 'Responder')
  {
    if(isset($_POST['respuesta']))
    {
      $msg = $usuario->responderSolicitud($_POST['hiddenIdSolicitud'],$_POST['respuesta']);
      $_GET['idSolicitud'] = null;
    }
  }

  if($usuario->getTipo() == 1 || $usuario->getTipo() == 3)
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
  <h2>Responder solicitud</h2>
  <?php
  if(isset($_GET['idSolicitud']) && $_GET['idSolicitud'] != null)
  {
    $usuario->revisarSolicitud($_GET['idSolicitud']);
  ?>
  <table><tr>
    <form method="post" name="responderSolicitud" target="_self">
      <td><input type="radio" name="respuesta" value="2">Aceptar</input></td></tr>
      <tr><td><input type="radio" name="respuesta" value="3">Denegar</input></td>
      <input type="hidden" name="hiddenIdSolicitud" value="<?php echo $_GET['idSolicitud'];?>"></input>
      <td><input type="submit" name="submit" value="Responder"></input></td></tr>
    </form>
  </table>
  <br>
  <a href="../solicitudes.php" target="_parent">Cancelar</a>
  <?php
  }
  elseif(isset($msg))
  {
    echo $msg.'<br><a href="../solicitudes.php" target="_parent">Salir</a>';
  }
  else
    echo 'No existen los parametros.<br><a href="../solicitudes.php" target="_parent">Salir</a>';
  ?>
  <script type='text/javascript' src='../../js/jquery.js'></script> 
  <script type='text/javascript' src='../../js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='../../js/bsc.js'></script>
</body>
</html>
<?php
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
