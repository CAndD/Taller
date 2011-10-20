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

  if(isset($_POST['submit']) && $_POST['submit'] == 'Aceptar')
  {
    if(isset($_POST['vacantes']) && isset($_POST['hiddenIdSolicitud']) && isset($_POST['hiddenVacantes']))
    {
      if($_POST['vacantes'] == 0 || $_POST['vacantes'] == '')
      {
        $msg2 = "Debe elegir al menos 1 vacantes para aceptar.";
        $_GET['idSolicitud'] = $_POST['hiddenIdSolicitud'];
        $vacantes = $_POST['hiddenVacantes'];
      }
      elseif($_POST['vacantes'] >= 1 && $_POST['vacantes'] > $_POST['hiddenVacantes'])
      {
        $msg2 = "Debe elegir aceptar vacantes hasta ".$_POST['hiddenVacantes'];
        $_GET['idSolicitud'] = $_POST['hiddenIdSolicitud'];
        $vacantes = $_POST['hiddenVacantes'];
      }
      elseif($_POST['vacantes'] >= 1 && $_POST['vacantes'] <= $_POST['hiddenVacantes'])
      {
        $msg = $usuario->responderSolicitud($_POST['hiddenIdSolicitud'],2,$_POST['vacantes']);
        $_GET['idSolicitud'] = null;
        $vacantes = null;
      }
    }
  }

  if(isset($_POST['submit']) && $_POST['submit'] == 'Denegar')
  {
    if(isset($_POST['hiddenIdSolicitud']))
    {
      $msg = $usuario->responderSolicitud($_POST['hiddenIdSolicitud'],3,0);
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
    $vacantes = $usuario->revisarSolicitud($_GET['idSolicitud']);
  ?>
  <table><tr>
    <form method="post" name="aceptarSolicitud" target="_self">
      <td><input type="text" name="vacantes" value="<?php echo $vacantes;?>" class="xs"></input></td><td><?php if(isset($msg2)) echo '<span class="error">'.$msg2.'</span>';?></td>
          <input type="hidden" name="hiddenIdSolicitud" value="<?php echo $_GET['idSolicitud'];?>"></input>
          <input type="hidden" name="hiddenVacantes" value="<?php echo $vacantes;?>"></input>
      <td><input type="submit" name="submit" value="Aceptar"></input></td></tr>
    </form>
  </table>
    <form method="post" name="denegarSolicitud" target="_self">
      <input type="hidden" name="hiddenIdSolicitud" value="<?php echo $_GET['idSolicitud'];?>"></input>
      <input type="submit" name="submit" value="Denegar"></input>
    </form>
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
