<?php
foreach (glob("../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if($usuario->getTipo() == 2 || $usuario->getTipo() == 3)
  {
    if(isset($_POST['asignarJDC']) && $_POST['asignarJDC'] == 'Ingresar')
    {
      $d01_mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql = "CALL asignar_jdc('{$_POST['hidden_car']}','{$_POST['jdc']}')";
      if(($d01_mysqli->query($sql)) == true)
      {
        $msg = 'Jefe de carrera asignado.';
      }
      else
      {
        $msg = 'Jefe de carrera no asignado.';
      } 
    }
    
    if(isset($_POST['cambiarJDC']) && $_POST['cambiarJDC'] == 'Ingresar')
    {
      $d01_mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql = "CALL cambiar_jdc('{$_POST['hidden_car']}','{$_POST['jdc']}')";
      if(($d01_mysqli->query($sql)) == true)
      {
        $msg = 'Jefe de carrera cambiado.';
      }
      else
      {
        $msg = 'Jefe de carrera no cambiado.';
      } 
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
  <link rel="stylesheet" type="text/css" href="../style/style.css" title="style" />
</head>

<body>
  <?php
  if(isset($msg))
  {
    echo $msg;
  }
  else
  {?>
  <h1>Asignar jefe de carrera</h1>
  <?php
    if(isset($_GET['asigna']) && $_GET['asigna'] == 1) {?>
  <form method="post" name="asignar_jdc" target="_self">
   <input type="hidden" name="hidden_car" value="<?php if(isset($_GET['hidden_car'])){echo$_GET['hidden_car'];}elseif(isset($_POST['hidden_car'])){echo$_POST['hidden_car'];}?>"></input>
   <select name="jdc"><option value="0">Seleccione jefe de carrera</option>
     <?php 
      $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql = "CALL select_jefe_carrera()";
      $res = $mysqli->prepare($sql);
      $res->execute();
      $res->bind_result($nombreUsuarioJC,$rutJC,$nombreJC);
      while($res->fetch())
      {
        echo '<option value="'.$nombreUsuarioJC.'">'.$nombreJC.'</option>';
      }
      if(!isset($rutJC))
        echo '<option value="0">No hay Jefes de Carrera.</option>';
      $res->free_result();
     ?>
   </select>
   <input type="submit" name="asignarJDC" value="Ingresar"></input>
  </form>
  <?php
  }
  elseif(isset($_GET['cambia']) && $_GET['cambia'] == 1) {?>
    <form method="post" name="cambiar_jdc" target="_self">
   <input type="hidden" name="hidden_car" value="<?php if(isset($_GET['hidden_car'])){echo$_GET['hidden_car'];}elseif(isset($_POST['hidden_car'])){echo$_POST['hidden_car'];}?>"></input>
   <select name="jdc"><option value="0">Seleccione jefe de carrera</option>
                      <option value="null">Ninguno</option>
     <?php 
      $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
      $sql = "CALL select_jefe_carrera()";
      $res = $mysqli->prepare($sql);
      $res->execute();
      $res->bind_result($nombreUsuarioJC,$rutJC,$nombreJC);
      while($res->fetch())
      {
        echo '<option value="'.$nombreUsuarioJC.'">'.$nombreJC.'</option>';
      }
      if(!isset($rutJC))
        echo '<option value="0">No hay Jefes de Carrera.</option>';
      $res->free_result();
     ?>
   </select>
   <input type="submit" name="cambiarJDC" value="Ingresar"></input>
  </form>
  <?php
    }
  }?>
  <a href="carreras.php" target="_parent">Cerrar</a></body>
</html><?php
  }
}
else
{
  header("Location: ../index.php");
  exit();
}
