<?php
foreach (glob("class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'administrador') {
    $usuario = new jefeDeCarrera($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut());
    $_SESSION['usuario'] = serialize($usuario);
  }

  if((isset($_POST['submit']) && $_POST['submit'] == 'Elegir') && isset($_POST['codigoCarrera']) && isset($_POST['codigoSemestre'])) {
    $_SESSION['carrera'] = $_POST['codigoCarrera'];
    $_SESSION['codigoSemestre'] = $_POST['codigoSemestre'];
  }

  if((isset($_POST['submit']) && $_POST['submit'] == 'Elegir') && isset($_POST['codigoCarrera']) && isset($_POST['codigoTrimestre'])) {
    $_SESSION['carrera'] = $_POST['codigoCarrera'];
    $_SESSION['codigoSemestre'] = $_POST['codigoTrimestre'];
  }

  if(isset($_POST['cambiarCarrera']) && $_POST['cambiarCarrera'] == 'CAMBIAR CARRERA') {
    $_SESSION['carrera'] = null;
    $_SESSION['codigoSemestre'] = null;
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
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
  <link rel="stylesheet" type="text/css" href="style/bsc.css" title="style" />
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
          <li class="selected"><a href="home.php">Home</a></li>
          <?php
          if(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && (is_string($_SESSION['carrera']) == true)) {
            echo '<li><a href="user_jc/ramos.php">Ramos</a></li>';
            echo '<li><a href="user_jc/secciones.php">Secciones y Vacantes</a></li>';
            echo '<li><a href="user_jc/solicitudes.php">Solicitudes</a></li>';
          }
          ?>
          <li><a href="">Contacto</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h1>Bienvenido <?php echo $usuario->getNombre();?></h1>
        <?php
        if(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && (is_string($_SESSION['carrera']) == true)) {?>
        <table><tr>
        <td><div class="ramos_malla" style="overflow: scroll;"><a href="user_jc/ramos.php" class="title">Ramos de malla</a>
          <?php
            $usuario->verMalla($_SESSION['carrera']);
          ?>
        </div></td>
        <td><div class="ramos_piden" style="overflow: scroll;"><a href="user_jc/solicitudes.php" class="title">Ramos que piden</a><br>
          <?php
            $usuario->verRamosQuePiden($_SESSION['carrera'],$_SESSION['codigoSemestre']);
          ?>
        </div></td>
        <td><div class="ramos_pido" style="overflow: scroll;"><a href="user_jc/solicitudes.php" class="title">Ramos que pido</a><br>
          <?php
            $usuario->verRamosQuePido($_SESSION['carrera'],$_SESSION['codigoSemestre']);
          ?>
        </div></td></tr>
        <tr>
        <td><div class="prog_presu"><span class="title">Programación versus Presupuesto</span><br>
          <?php
            $usuario->verProgramacionVsPresupuesto($_SESSION['carrera'],$_SESSION['codigoSemestre']);
          ?>
        </div></td></tr>
        <tr>
        <td><div class="prof_asig"><span class="title">Profesores asignados</span><br>
          <?php
            $usuario->verProfesoresAsignados($_SESSION['carrera'],$_SESSION['codigoSemestre']);
          ?>
        </div></td>
        <td><div class="prof_asig_scarga"><span class="title">Profesores sin carga académica</span><br>
          <?php 
            //$usuario->verProfesoresSinCargaAcademica($_SESSION['carrera']);
          ?>
        </div></td>
        <td><div class="seccion_sprof" style="overflow: scroll;"><span class="title">Secciones sin profesor</span><br>
          <?php 
            $usuario->verSeccionesSinProfesor($_SESSION['carrera'],$_SESSION['codigoSemestre']);
          ?>
        </div></td>
        </tr></table>
        <?php
        }
        elseif(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && is_null($_SESSION['carrera'])) {
          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "CALL obtenerSemestre()";
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($codigoSemestre,$numeroSemestre,$anhoSemestre,$fechaInicioSemestre,$fechaTerminoSemestre);
          $res->fetch();
          $res->free_result();

          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "CALL obtenerTrimestre()";
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($codigoTrimestre,$numeroTrimestre,$anhoTrimestre,$fechaInicioTrimestre,$fechaTerminoTrimestre);
          $res->fetch();
          $res->free_result();
     
          echo 'Elija la carrera: ';
          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "CALL jdc_carreras('{$usuario->getNombreUsuario()}')";
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($codigo,$nombre,$periodo);
          echo '<table><tr><th>Código</th><th>Nombre</th><th>Estado</th><th>Elegir</th></tr>';
          while($res->fetch()) {
           if($periodo == 1)
           {
             if($fechaTerminoSemestre == NULL)
               echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$codigoSemestre.'</td><td><form method="post" name="elegir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigo.'"></input><input type="hidden" name="codigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Elegir"></input></form></td></tr>';
             else
               echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>Cerrado</td><td><form method="post" name="elegir" target="_self"><input type="submit" name="submit" value="Elegir" disabled></input></form></td></tr>';
           }
           else
           {
             if($fechaTerminoTrimestre == NULL)
               echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>'.$codigoTrimestre.'</td><td><form method="post" name="elegir" target="_self"><input type="hidden" name="codigoCarrera" value="'.$codigo.'"></input><input type="hidden" name="codigoTrimestre" value="'.$codigoTrimestre.'"></input><input type="submit" name="submit" value="Elegir"></input></form></td></tr>';
             else
               echo '<tr><td>'.$codigo.'</td><td>'.$nombre.'</td><td>Cerrado</td><td><form method="post" name="elegir" target="_self"><input type="submit" name="submit" value="Elegir" disabled></input></form></td></tr>';
           }
          }
          echo '</table>';
          $res->free_result();
        }
        elseif(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && $_SESSION['carrera'] == 0) {
          echo 'Aún no se le ha asignado una carrera.';
        }
        ?>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
    <?php
      if(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && !is_null($_SESSION['carrera']) && $_SESSION['nroCarrera'] > 1) {
        echo '<form method="post" name="cambiarCarrera" target="_self"><input type="submit" name="cambiarCarrera" value="CAMBIAR CARRERA" class="inp"></input></form>';
        $j = 1;
      }
      if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3) {
        if(isset($j) && $j == 1)
          echo ' / ';
        echo '<a href="user_admin/admin.php">Modo administrador</a>';
      }
    ?>
    </div>
  </div>

  <script type='text/javascript' src='js/jquery.js'></script> 
  <script type='text/javascript' src='js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='js/bsc.js'></script>
  
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
