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
    $numeroSemestree = $_POST['hiddenNumeroSemestre'];
    if(isset($_POST['hiddenIdClase']) && $_POST['profesor'] != 0)
    {
      $msg = $usuario->asignarSeccion($_POST['hiddenIdClase'],$_POST['profesor']);
      if($msg == 'Profesor asignado.') {
        $_GET['idClase'] = NULL; 
      }
      else {
        $_GET['idClase'] = $_POST['hiddenIdClaseOriginal'];
        $_GET['func'] = 'rutProfesor';}
    }
    else
    {
      if($_POST['profesor'] == 0)
      {
        $profesorerror = '*Debe elegir un profesor para la clase.';
        $_GET['func'] = 'rutProfesor';
        $_GET['idClase'] = $_POST['hiddenIdClaseOriginal'];
      }
    }
  }

  if(isset($_POST['submit']) && $_POST['submit'] == 'Eliminar profesor')
  {
    if(isset($_POST['hiddenIdClase']))
    {
      $numeroSemestree = $_POST['hiddenNumeroSemestre'];
      $msg = $usuario->eliminarProfesorDeSeccion($_POST['hiddenIdClase']);
      if($msg == 'Profesor eliminado.') {
        $_GET['idClase'] = NULL; 
      }
    }
    else
    {
      $_GET['idClase'] = $_POST['hiddenIdClaseOriginal'];
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
      $inicio = 0;
      $fin = 0;
      $largo = strlen($_GET['idClase']); 
      $flag = 0;
      $i = 0;
      for($i = 0;$i<1;$i++)
      {
        $flag = 0;
        while($flag == 0)
        {
          if(substr($_GET['idClase'],$fin,1) == '.')
          {
            if($i == 0) {
              $idClasee = substr($_GET['idClase'],0,$fin);
              $inicio = $fin+1;
              $numeroSemestree = substr($_GET['idClase'],$fin+1,$largo-$fin);
              $flag = 1;
            }
          } 
          $fin++;
        }
      }
      echo '<h2>Cambiar profesor</h2>';
      echo '<form method="post" name="cambiarProfesor" target="_self"><select name="profesor"><option value="0">Elegir profesor</option>';
      verProfesores();
      echo '</select><input type="hidden" name="hiddenIdClase" value="'.$idClasee.'"></input><input type="hidden" name="hiddenIdClaseOriginal" value="'.$_GET['idClase'].'"></input><input type="hidden" name="hiddenNumeroSemestre" value="'.$numeroSemestree.'"></input><input type="submit" name="submit" value="Cambiar profesor"></input></form>';
      if(isset($profesorerror))
        echo '<span class="error">'.$profesorerror.'</span>';
      echo 'O<br><form method="post" name="eliminarProfesor" target="_self"><input type="hidden" name="hiddenIdClase" value="'.$idClasee.'"></input><input type="hidden" name="hiddenIdClaseOriginal" value="'.$_GET['idClase'].'"></input><input type="hidden" name="hiddenNumeroSemestre" value="'.$numeroSemestree.'"></input><input type="submit" name="submit" value="Eliminar profesor"></input></form>';
    }
  ?>
  <br><br><a href="../horario.php?numeroSemestre=<?php echo $numeroSemestree;?>" target="_parent">Salir</a>
  <?php
  }
  elseif(isset($msg))
  {
    echo $msg.'<br><a href="../horario.php?numeroSemestre='.$numeroSemestree.'" target="_parent">Salir</a>';
  }
  else
    echo 'No existen los parametros.<br><a href="../horario.php" target="_parent">Salir</a>';
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
