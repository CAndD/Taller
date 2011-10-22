<?php
foreach (glob("../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'jefeDeCarrera') {
    $usuario = new administrador($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut());
    $_SESSION['usuario'] = serialize($usuario);
    $_SESSION['carrera'] = NULL;
    $_SESSION['codigoSemestre'] = NULL;
  }
  if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3)
  {
    if(isset($_POST['submit']) && $_POST['submit'] == 'Cerrar semestre')
    {
      if(isset($_POST['hiddenCodigo']))
      {
        $msg = $usuario->cerrarSemestre($_POST['hiddenCodigo']);
      }
    }
    if(isset($_POST['submit']) && $_POST['submit'] == 'Comenzar semestre')
    {
      if(isset($_POST['hiddenAnno']) && $_POST['hiddenSemestre'])
      {
        if($_POST['hiddenSemestre'] == 1)
          $codigoSemes = $_POST['hiddenAnno'].'10';
        elseif($_POST['hiddenSemestre'] == 2)
          $codigoSemes = $_POST['hiddenAnno'].'20';
        $msg = $usuario->comenzarSemestre($codigoSemes,$_POST['hiddenAnno'],$_POST['hiddenSemestre']);
      }
    }
    if(isset($_POST['submit']) && $_POST['submit'] == 'Abrir semestre')
    {
      if(isset($_POST['hiddenCodigoSemestre']))
      {
        $msg = $usuario->abrirSemestreAnterior($_POST['hiddenCodigoSemestre']);
      }
    }

    if(isset($_POST['submit']) && $_POST['submit'] == 'Cerrar trimestre')
    {
      if(isset($_POST['hiddenCodigoTrimestre']))
      {
        $msg2 = $usuario->cerrarTrimestre($_POST['hiddenCodigoTrimestre']);
      }
    }
    if(isset($_POST['submit']) && $_POST['submit'] == 'Comenzar trimestre')
    {
      if(isset($_POST['hiddenAnno']) && $_POST['hiddenTrimestre'])
      {
        if($_POST['hiddenTrimestre'] == 1)
          $codigoTrimes = $_POST['hiddenAnno'].'05';
        elseif($_POST['hiddenTrimestre'] == 2)
          $codigoTrimes = $_POST['hiddenAnno'].'15';
        elseif($_POST['hiddenTrimestre'] == 3)
          $codigoTrimes = $_POST['hiddenAnno'].'25';
        $msg2 = $usuario->comenzarTrimestre($codigoTrimes,$_POST['hiddenAnno'],$_POST['hiddenTrimestre']);
      }
    }
    if(isset($_POST['submit']) && $_POST['submit'] == 'Abrir trimestre')
    {
      if(isset($_POST['hiddenCodigoTrimestre']))
      {
        $msg2 = $usuario->abrirTrimestreAnterior($_POST['hiddenCodigoTrimestre']);
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
          if(isset($msg))
            echo '<span class="error">'.$msg.'</span><br>';
          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "CALL obtenerSemestre()";
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($codigoSemestre,$numero,$anho,$fechaInicio,$fechaTermino);
          $res->fetch();
          $res->free_result();

          if($fechaTermino == NULL)
          {
            echo 'Actualmente el sistema está en el semestre número '.$numero.' del año '.$anho.'.<br>';
            echo '<br>La programación de este semestre aún no ha terminado. <br>Si quiere cerrar el semestre puede presionar <form method="post" name="Cerrar" target="_self"><input type="hidden" name="hiddenCodigo" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Cerrar semestre"></input></form>';
          }
          else
          {
            if($numero == 2)
            {
              $anno = $anho+1;
              echo 'Actualmente el sistema está esperando el comienzo de un nuevo semestre número 1 para el año '.$anno.'.</p>';
              echo 'Para comenzar la programación presione <form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenAnno" value="'.$anno.'"></input><input type="hidden" name="hiddenSemestre" value="1"></input><input type="submit" name="submit" value="Comenzar semestre"></input></form>';
              $anno = $anno-1;
              echo '<br>También puede abrir el semestre anterior, '.$anno.'-2.';
              echo '<form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Abrir semestre"></input></form>';
            }
            else
            {
              echo 'Actualmente el sistema se encuentra en la espera para la programación del semestre número 2 del año '.$anho.'.</p>';
              echo 'Para comenzar la programación presione <form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenAnno" value="'.$anho.'"></input><input type="hidden" name="hiddenSemestre" value="2"></input><input type="submit" name="submit" value="Comenzar semestre"></input></form>';
              echo '<br>También puede abrir el semestre anterior, '.$anho.'-1.';
              echo '<form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenCodigoSemestre" value="'.$codigoSemestre.'"></input><input type="submit" name="submit" value="Abrir semestre"></input></form>';
            }
          }
        ?>
        <br><hr>
        <h2>Trimestre</h2>
        <?php
          if(isset($msg2))
            echo '<span class="error">'.$msg2.'</span><br>';
          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
          $sql = "CALL obtenerTrimestre()";
          $res = $mysqli->prepare($sql);
          $res->execute();
          $res->bind_result($codigoTrimestre,$numero,$anho,$fechaInicio,$fechaTermino);
          $res->fetch();
          $res->free_result();
          if($fechaTermino == NULL)
          {
            echo 'Actualmente el sistema está en el trimestre número '.$numero.' del año '.$anho.'.<br>';
            echo '<br>La programación de este trimestre aún no ha terminado.<br>Si quiere cerrar el trimestre puede presionar <form method="post" name="Cerrar" target="_self"><input type="hidden" name="hiddenCodigoTrimestre" value="'.$codigoTrimestre.'"></input><input type="submit" name="submit" value="Cerrar trimestre"></input></form>';
          }
          else
          {
            if($numero == 3)
            {
              $anno = $anho+1;
              echo 'Actualmente el sistema está esperando el comienzo de un nuevo trimestre número 1 para el año '.$anno.'.<br>';
              echo 'Para comenzar la programación presione <form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenAnno" value="'.$anno.'"></input><input type="hidden" name="hiddenTrimestre" value="1"></input><input type="submit" name="submit" value="Comenzar trimestre"></input></form>';
              $anno = $anno-1;
              echo '<br>También puede abrir el trimestre anterior, '.$anno.'-3.';
              echo '<form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenCodigoTrimestre" value="'.$codigoTrimestre.'"></input><input type="submit" name="submit" value="Abrir trimestre"></input></form>';
            }
            elseif($numero == 2)
            {
              echo 'Actualmente el sistema se encuentra en la espera para la programación del trimestre número 3 del año '.$anho.'.<br>';
              echo 'Para comenzar la programación presione <form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenAnno" value="'.$anho.'"></input><input type="hidden" name="hiddenTrimestre" value="3"></input><input type="submit" name="submit" value="Comenzar trimestre"></input></form>';
              echo '<br>También puede abrir el trimestre anterior, '.$anho.'-2.';
              echo '<form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenCodigoTrimestre" value="'.$codigoTrimestre.'"></input><input type="submit" name="submit" value="Abrir trimestre"></input></form>';
            }
            else
            {
              echo 'Actualmente el sistema se encuentra en la espera para la programación del trimestre número 2 del año '.$anho.'.<br>';
              echo 'Para comenzar la programación presione <form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenAnno" value="'.$anho.'"></input><input type="hidden" name="hiddenTrimestre" value="2"></input><input type="submit" name="submit" value="Comenzar trimestre"></input></form>';
              echo '<br>También puede abrir el trimestre anterior, '.$anho.'-1.';
              echo '<form method="post" name="Comenzar" target="_self"><input type="hidden" name="hiddenCodigoTrimestre" value="'.$codigoTrimestre.'"></input><input type="submit" name="submit" value="Abrir trimestre"></input></form>';
            }
          }
        ?>

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
