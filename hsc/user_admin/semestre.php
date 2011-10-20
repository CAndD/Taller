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
          <li class="selected"><a href="admin.php">Home</a></li>
          <li><a href="carreras.php">Carreras</a></li>
          <li><a href="jdc.php">Jefes de carrera</a></li>
          <li><a href="ramos.php">Ramos</a></li>
          <li><a href="contacto.php">Contacto</a></li>
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
        <h1>Bienvenido administrador</h1>
        <h2>Semestre</h2>
        <?php
          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "CALL obtenerSemestre()";
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($Codigo_Semestre,$numero,$anho,$fechaInicio,$fechaTermino);
          $res->fetch();
          $res->free_result();

          if($fechaTermino == NULL)
          {
            echo '<p>Actualmente el sistema está en el semestre número '.$numero.' del año '.$anho.'.';
            echo '<h3>Cerrar semestre</h3>La programación de este semestre aún no ha terminado. <br>Si quiere cerrar el semestre puede presionar <a href="">aquí</a>.';
          }
          else
          {
            if($numero == 2)
            {
              $anno = $anho+1;
              echo '<p>Actualmente el sistema está esperando el comienzo de un nuevo semestre número 1 para el año '.$anno.'.</p>';
              echo 'Para comenzar la programación presione <a href="">aquí.</a>';
            }
            else
            {
              echo '<p>Actualmente el sistema se encuentra en la espera para la programación del semestre número 2 del año '.$anho.'.</p>';
              echo 'Para comenzar la programación presione <a href="">aquí.</a>';
            }
          }
        ?>
        <h2>Trimestre</h2>
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
