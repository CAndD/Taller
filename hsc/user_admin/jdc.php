<?php
foreach (glob("../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'jefeDeCarrera') {
    $usuario = new administrador($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut(),$usuario->getTipo());
    $_SESSION['usuario'] = serialize($usuario);
  }
  if($usuario->getTipo() == 2 || $usuario->getTipo() == 3)
  {
    if(isset($_POST['rut']) && isset($_POST['nombre']) && isset($_POST['nusuario']) && isset($_POST['pass']))
    {
      if($_POST['rut'] != '' && $_POST['nombre'] != '' && $_POST['nusuario'] != '' && $_POST['pass'] != '')
      {
        $answer = $usuario->agregarJefeDeCarrera($_POST['rut'],$_POST['nombre'],$_POST['nusuario'],$_POST['pass']);
      }
      else
      {
        if($_POST['rut'] == '' && $_POST['nombre'] == '' && $_POST['nusuario'] == '' && $_POST['pass'] == '')
        {
          $answer = '*Debe ingresar rut, nombre, nombre usuario y contraseña del jefe de carrera.';
        }
        else
        {
          if($_POST['rut'] == '')
          {
            $ruterror = '*Debe ingresar el rut del jefe de carrera.';
          }
          else
          {
            $rutold = $_POST['rut'];
          }  
 
          if($_POST['nombre'] == '')
          {
            $nombreerror = '*Debe ingresar el nombre del jefe de carrera.';
          }
          else
          {
            $nombreold = $_POST['nombre'];
          }

          if($_POST['nusuario'] == '')
          {
            $nusuarioerror = '*Debe ingresar el nombre de usuario del jefe carrera.';
          }
          else
          {
            $nusuarioold = $_POST['nusuario'];
          }
 
          if($_POST['pass'] == '')
          {
            $passerror = '*Debe ingresar la contraseña del jefe de carrera.';
          }
          else
          {
            $passold = $_POST['pass'];
          }
        }
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
  <link rel="stylesheet" type="text/css" href="../style/bsc.css" title="style" />
  <script type="text/javascript" src="../js/js.js"></script>
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="../index.php">Universidad<span class="logo_colour"> Andrés Bello</span></a></h1>
          <h2>Herramienta de programación de horarios.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="admin.php">Home</a></li>
          <li><a href="carreras.php">Carreras</a></li>
          <li class="selected"><a href="jdc.php">Jefes de carrera</a></li>
          <li><a href="ramos.php">Ramos</a></li>
          <li><a href="contacto.php">Contacto</a></li>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <!--<div class="sidebar">
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      </div>-->
      <div id="content">
        <!-- insert the page content here -->
        <h1>Jefes de Carreras</h1>

        <h2>Agregar Jefe de Carrera:</h2>
        <?php if(isset($answer)) echo '<td><span class="error">'.$answer.'</span></td>';?>
        <table>
        <form method="post" name="agregar" target="_self">
          <tr><td>RUT</td><td>Nombre</td><td>Nombre usuario</td><td>Contraseña</td></tr>
          <tr><td><input type="text" name="rut" value="<?php if(isset($rutold)) echo $rutold;?>" maxlength="10" class="l"></input></td>
          <td><input type="text" name="nombre" value="<?php if(isset($nombreold)) echo $nombreold;?>" maxlength="40"></input></td>
          <td><input type="text" name="nusuario" value="<?php if(isset($nusuarioold)) echo $nusuarioold;?>" maxlength="40" onkeyup="buscarNombreUsuario(this.value)"></input></td>
          <td><input type="text" name="pass" value="" maxlength="32"></input></td>
          <td><?php if(isset($nusuarioold)) echo '<input id="btt" type="submit" name="agrega" value="Agregar"></input>'; else echo '<input id="btt" type="submit" name="agrega" value="Agregar" disabled></input>';?></td></tr>
          <tr>
           <td><?php if(isset($ruterror)) echo '<span class="error">'.$ruterror.'</span>';?></td>
           <td><?php if(isset($nombreerror)) echo '<span class="error">'.$nombreerror.'</span>';?></td>
           <td><div id="existe"><?php if(isset($nusuarioerror)) echo '<span class="error">'.$nusuarioerror.'</span>';?></div></td>
           <td><?php if(isset($passerror)) echo '<span class="error">'.$passerror.'</span>';?></td>
          </tr>
        </form>
        </table>

        <h2>Lista de jefes carrera:</h2><ul>
        <?php
          $usuario->verJefesDeCarrera();
        ?>
        </ul>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      <?php
      if($usuario->getTipo() == 3) {
        echo '<a href="../home.php">Modo Jefe de Carrera</a>';
      }
      ?>
    </div>
  </div>
  <script type='text/javascript' src='../js/jquery.js'></script> 
  <script type='text/javascript' src='../js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='../js/bsc.js'></script></body>
</html><?php
  }
  else
  {
    header("Location: ../index.php");
    exit();
  }
}
else
{
  header("Location: ../index.php");
  exit();
}
