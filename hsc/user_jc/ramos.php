<?php
foreach (glob("../class/*.php") as $filename) {
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

  if((isset($_POST['submit']) && $_POST['submit'] == 'Elegir') && isset($_POST['codigoCarrera'])) {
    $_SESSION['carrera'] = $_POST['codigoCarrera'];
  }

  if(isset($_POST['cambiarCarrera']) && $_POST['cambiarCarrera'] == 'CAMBIAR CARRERA') {
    $_SESSION['carrera'] = null;
    $_SESSION['codigoSemestre'] = null;
    header("Location: ../home.php");
    exit();
  }
 
  if(isset($_POST['submit']) && $_POST['submit'] == 'Dictar')
  {
    if(isset($_POST['codigoCarrera']) && isset($_POST['codigoRamo']) && isset($_POST['codigoSemestre']))
    {
      $msg = $usuario->impartirRamo($_POST['codigoCarrera'],$_POST['codigoRamo'],$_POST['codigoSemestre']);
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
  <link rel="stylesheet" type="text/css" href="../style/style.css" title="style" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Universidad<span class="logo_colour"> Andrés Bello</span></a></h1>
          <h2>Herramienta de programación de horarios.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="../home.php">Home</a></li>
          <?php
          if(($usuario->getTipo() == 1 || $usuario->getTipo() == 3) && (is_string($_SESSION['carrera']) == true)) {
            echo '<li class="selected"><a href="ramos.php">Ramos</a></li>';
            echo '<li><a href="secciones.php">Secciones y Vacantes</a></li>';
            echo '<li><a href="solicitudes.php">Solicitudes</a></li>';
          }
          ?>
          <li><a href="">Contacto</a></li>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h2>Ramos a impartir</h2>
        <?php
          if(isset($msg))
            echo '<span class="error">'.$msg.'</span>';
          $usuario->verRamosDeCarrera($_SESSION['carrera'],$_SESSION['codigoSemestre']);
        ?>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
    <?php
      if(($usuario->getTipo() == 1 || $usuario->getTipo() == 3) && !is_null($_SESSION['carrera']) && $_SESSION['nroCarrera'] > 1) {
        echo '<form method="post" name="cambiarCarrera" target="_self"><input type="submit" name="cambiarCarrera" value="CAMBIAR CARRERA" class="inp"></input></form>';
        $j = 1;
      }
      if($usuario->getTipo() == 2 || $usuario->getTipo() == 3) {
        if(isset($j) && $j == 1)
          echo ' / ';
        echo '<a href="../user_admin/admin.php">Modo administrador</a>';
      }
    ?>
    </div>
  </div>
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
