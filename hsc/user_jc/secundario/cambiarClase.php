<?php
foreach (glob("../../class/*.php") as $filename) {
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

  if(isset($_POST['submit']) && $_POST['submit'] == 'Cambiar profesor')
  {
    if(isset($_POST['hiddenIdClase']) && $_POST['profesor'] != 0)
    {
      $msg = $usuario->asignarSeccion($_POST['hiddenIdClase'],$_POST['profesor']);
      if($msg == 'Profesor asignado.') {
        $_GET['idClase'] = NULL; 
      }
      else {
        $_GET['idClase'] = $_POST['hiddenIdClase'];
        $_GET['func'] = 'rutProfesor';}
    }
    else
    {
      if($_POST['profesor'] == 0)
      {
        $profesorerror = '*Debe elegir un profesor para la clase.';
        $_GET['func'] = 'rutProfesor';
        $_GET['idClase'] = $_POST['hiddenIdClase'];
      }
    }
  }

  if(isset($_POST['submit']) && $_POST['submit'] == 'Cambiar día')
  {
    if($_POST['cambiarDia'] == 'No')
    {
      $diaerror = 'Debe elegir un día.';
      $_GET['idClase'] = $_POST['hiddenIdClase'];
    }
    else
    {
      $msg2 = $usuario->asignarHorario($_POST['hiddenIdClase'],$_POST['cambiarDia'],1);
    }
  }

  if(isset($_POST['submit']) && $_POST['submit'] == 'Cambiar inicio')
  {
    if($_POST['moduloInicio'] == 0)
    {
      $moduloerror = 'Debe elegir un módulo de inicio o debe elegir uno que no se encuentre en rojo.';
      $_GET['idClase'] = $_POST['hiddenIdClase'];
      $_GET['func'] = 'moduloInicio';
    }
    else
    {
      $msg3 = $usuario->asignarHorario($_POST['hiddenIdClase'],$_POST['moduloInicio'],2);
      $_GET['func'] = 'moduloInicio';
    }
  }  

  if(isset($_POST['submit']) && $_POST['submit'] == 'Cambiar termino')
  {
    if($_POST['moduloTermino'] == 0)
    {
      $moduloerror = 'Debe elegir un módulo de término o debe elegir uno que no se encuentre en rojo.';
      $_GET['idClase'] = $_POST['hiddenIdClase'];
      $_GET['func'] = 'moduloTermino';
    }
    else
    {
      $msg4 = $usuario->asignarHorario($_POST['hiddenIdClase'],$_POST['moduloTermino'],3);
      $_GET['func'] = 'moduloTermino';
    }
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
  <link rel="stylesheet" type="text/css" href="../../style/style.css" title="style" />
  <link rel="stylesheet" type="text/css" href="../../style/bsc.css" title="style" />
</head>

<body>
  <?php
    global $mysqli,$db_host,$db_user,$db_pass,$db_database;
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "SELECT s.Codigo_Ramo
             FROM Seccion AS s
             INNER JOIN Clase AS c ON c.Id = '{$_GET['idClase']}'
            WHERE s.Id = c.Seccion_Id;";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoRamo);
    $res->fetch();
    $res->free_result();
  if(isset($_GET['idClase']) && !isset($msg))
  {
    if(isset($_GET['func']) && $_GET['func'] == 'rutProfesor')
    {
      echo '<h2>Cambiar profesor</h2>';
      echo '<form method="post" name="cambiarProfesor" target="_self"><select name="profesor"><option value="0">Elegir profesor</option>';
      verProfesores();
      echo '</select><input type="hidden" name="hiddenIdClase" value="'.$_GET['idClase'].'"></input><input type="submit" name="submit" value="Cambiar profesor"></input></form>';
      if(isset($profesorerror))
        echo '<span class="error">'.$profesorerror.'</span>';
    }
    if(isset($_GET['func']) && $_GET['func'] == 'moduloInicio')
    {
      echo '<h2>Cambiar módulo de inicio</h2>';
      if(isset($msg3))
      {
        echo $msg3;
      }
      else
      {
        if(isset($moduloerror))
        {
          echo '<span class="error">'.$moduloerror.'</span><br><br>';
        }
        $regimen = obtenerRegimenCarrera($_SESSION['carrera']);
        echo '<form method="post" name="cambiarHoraInicio" target="_self"><select name="moduloInicio"><option value="0">Elegir módulo de inicio</option>';
        obtenerModulosSugerencia($regimen,$_GET['idClase'],$_SESSION['carrera'],$_SESSION['codigoSemestre']);
        echo '</select><input type="hidden" name="hiddenIdClase" value="'.$_GET['idClase'].'"></input><input type="submit" name="submit" value="Cambiar inicio"></input></form>';
      }
    }
    if(isset($_GET['func']) && $_GET['func'] == 'moduloTermino')
    {
      echo '<h2>Cambiar módulo de término</h2>';
      if(isset($msg4))
      {
        echo $msg4;
      }
      else
      {
        if(isset($moduloerror))
        {
          echo '<span class="error">'.$moduloerror.'</span><br><br>';
        }
        $regimen = obtenerRegimenCarrera($_SESSION['carrera']);
        echo '<form method="post" name="cambiarHoraTermino" target="_self"><select name="moduloTermino"><option value="0">Elegir módulo de término</option>';
        obtenerModulosSugerencia($regimen,$_GET['idClase'],$_SESSION['carrera'],$_SESSION['codigoSemestre']);
        echo '</select><input type="hidden" name="hiddenIdClase" value="'.$_GET['idClase'].'"></input><input type="submit" name="submit" value="Cambiar termino"></input></form>';
      }
    }
    if(isset($_GET['func']) && $_GET['func'] == 'diaClase')
    {
      echo '<h2>Cambiar día de clase</h2>';
      if(isset($msg2))
      {
        echo $msg2;
      }
      else
      {
        if(isset($diaerror))
        {
          echo '<span class="error">'.$diaerror.'</span><br><br>';
        }
        $regimen = obtenerRegimenCarrera($_SESSION['carrera']);
        if($regimen == 'D')
        {
          echo '<form method="post" name="cambiarDia" target="_self"><select name="cambiarDia"><option value="No">Elegir día</option>';
          echo '<option value="Lunes">Lunes</option><option value="Martes">Martes</option><option value="Miercoles">Miércoles</option><option value="Jueves">Jueves</option><option value="Viernes">Viernes</option>';
          echo '</select><input type="hidden" name="hiddenIdClase" value="'.$_GET['idClase'].'"></input>';
          echo '<input type="submit" name="submit" value="Cambiar día"></input></form>';
        }
        else
        {
          echo '<form method="post" name="cambiarDia" target="_self"><select name="cambiarDia"><option value="0">Elegir día</option>';
          echo '<option value="1">Lunes</option><option value="2">Martes</option><option value="3">Miércoles</option><option value="4">Jueves</option><option value="5">Viernes</option><option value="6">Sábado</option>';
          echo '</select><input type="hidden" name="hiddenIdClase" value="'.$_GET['idClase'].'"></input>';
          echo '<input type="submit" name="submit" value="Cambiar día"></input></form>';
        }
      }
    }
  ?>
  <br><br><a href="../clases.php?codigoRamo=<?php echo $codigoRamo; ?>" target="_parent">Salir</a>
  <?php
  }
  elseif(isset($msg))
  {
    echo $msg.'<br><a href="../clases.php?codigoRamo='.$codigoRamo.'" target="_parent">Salir</a>';
  }
  else
    echo 'No existen los parametros.<br><a href="../secciones.php" target="_parent">Salir</a>';
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
    header("Location: ../../index.php");
    exit();
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
