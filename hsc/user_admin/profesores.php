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

  if(isset($_POST['agrega']) && $_POST['agrega'] == 'Agregar profesor')
  {
    if(isset($_POST['rut']) && ctype_digit($_POST['rut']) && $_POST['nombre'] != '')
    {
      $answer = $usuario->agregarProfesor($_POST['rut'],$_POST['nombre']);
    }
    else
    {
      if(!ctype_digit($_POST['rut']))
        $ruterror = '*Debe ingresar solamente números en<br> el rut y sin guiones ni puntos.';
      if($_POST['nombre'] == '')
        $nombreerror = '*Debe ingresar un nombre.';
      else
        $nombreold = $_POST['nombre'];
    }
  }

  if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3)
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
  <link rel="stylesheet" type="text/css" href="../style/style.css" title="style" />
  <link rel="stylesheet" type="text/css" href="../../style/bsc.css" title="style" />
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
          <li><a href="jdc.php">Jefes de carrera</a></li>
          <li><a href="ramos.php">Ramos</a></li>
          <li class="selected"><a href="profesores.php">Profesores</a></li>
          <!--<li><a href="contacto.php">Contacto</a></li>-->
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
        <h1>Profesores</h1>

        <h2>Agregar Profesor:</h2>
        <?php if(isset($answer)) echo '<span class="error">'.$answer.'</span>';?>
        <table>
        <form method="post" name="agregar" target="_self">
          <tr><td>Rut (sin puntos ni guiones)</td><td>Nombre</td></tr>
          <tr><td><input type="text" name="rut" value="" maxlength="9" onkeyup="buscarRutProfesor(this.value)" class="xl"></input></td>
          <td><input type="text" name="nombre" value="<?php if(isset($nombreold)) echo $nombreold;?>" maxlength="50"></input></td>
          <td><?php if(isset($codigoold)) echo '<input id="btt" type="submit" name="agrega" value="Agregar profesor">'; else echo '<input id="btt" type="submit" name="agrega" value="Agregar profesor" disabled>';?></input></td></tr>
          <tr><td><?php if(isset($ruterror)) echo '<span class="error">'.$ruterror.'</span>';?></td>
              <td><?php if(isset($nombreerror)) echo '<span class="error">'.$nombreerror.'</span>';?></td>
          </tr>
        </form>
        </table>

        <h2>Lista de profesores:</h2><ul>
        <?php
          $usuario->verProfesores();
        ?>
        </ul>

      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
      <?php
      if($_SESSION['tipoUsuario'] == 3) {
        echo '<a href="../home.php">Modo Jefe de Carrera</a>';
      }
      ?>
    </div>
  </div>
  <script type='text/javascript' src='../js/jquery.js'></script> 
  <script type='text/javascript' src='../js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='../js/bsc.js'></script>
</body>
</html>
<?php
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