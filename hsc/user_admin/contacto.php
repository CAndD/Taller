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
          <li class="selected"><a href="contacto.php">Contacto</a></li>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div class="sidebar">
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      </div>
      <div id="content">
        <!-- insert the page content here -->
        <h1>Contacto</h1>
        <h2>Lista de problemas, sugerencias, etc:</h2><ul>
        <?php
          echo '<table>';
          echo '<tr><td>Nombre Usuario</td><td>Categoría</td><td>Fecha</td><td>Revisado?</td></tr>';
          echo '</table>';
        ?>
        </ul>
 
        <br><a href="">Revisar solucionados</a>

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
  </div></body>
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
