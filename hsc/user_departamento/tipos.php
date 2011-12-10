<?php
foreach (glob("../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if(get_class($usuario) == 'jefeDeCarrera') {
    $usuario = new departamento($usuario->getNombre(),$usuario->getNombreUsuario(),$usuario->getRut());
    $_SESSION['usuario'] = serialize($usuario);
    $_SESSION['carrera'] = NULL;
    $_SESSION['codigoSemestre'] = NULL;
  }
  if($_SESSION['tipoUsuario'] == 4)
  {
    if(isset($_POST['submit']) && $_POST['submit'] == 'Agregar tipo')
    {
      if($_POST['tipo'] != '' && $_POST['abreviacion'] != '')
      {
        $usuario->crearTipoDeRamo($_POST['tipo'],$_POST['abreviacion']);
      }
      else
      {
        if($_POST['tipo'] == '')
          $tipoerror = '*Debe ingresar un tipo.';
        if($_POST['abreviacion'] == '')
          $abreverror = '*Debe ingresar una abreviación.';      
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
          <li><a href="depto.php">Ramos</a></li>
          <li><a href="seccion.php">Secciones</a></li>
          <li class="selected"><a href="tipos.php">Tipos</a></li>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h1>Tipos de ramos</h1>

        <table>
        <tr><td>Tipo</td><td>Abreviación</td></tr> 
        <form method="post" name="agregar" target="_self">
            <tr><td><input type="text" name="tipo" value="<?php if(isset($tipoold)) echo $tipoold;?>" maxlength="50"></input></td>
            <td><input type="text" name="abreviacion" value="<?php if(isset($abrevold)) echo $abrevold;?>" maxlength="3" class="xs" onkeyup="buscarAbreviacion(this.value)"></input></td>
            <td><?php if(isset($codigoold))echo '<input type="submit" name="agrega" value="Agregar tipo" id="btt">'; else echo '<input type="submit" name="agrega" value="Agregar tipo" id="btt" disabled>';?></input></td></tr>
            <tr><td><div id="existe"><td><?php if(isset($tipoerror)) echo '<span class="error">'.$tipoerror.'</span>';?></td></div>
                <td><?php if(isset($abreverror)) echo '<span class="error">'.$abreverror.'</span>';?></td>
                </tr>
          </form>
        </table>

        <h2>Lista de tipos de ramos:</h2><ul>
        <?php
          echo '<table>';
          echo '<tr><td>Id</td><td>Tipo</td><td>Abreviación</td></tr>';     
          verTiposDeRamos();
          echo '</table>';
        ?>
        </ul>

      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
    </div>
  </div>

  <script type='text/javascript' src='../js/jquery.js'></script> 
  <script type='text/javascript' src='../js/jquery.simplemodal.js'></script> 
  <script type='text/javascript' src='../js/bsc.js'></script>
  <script type='text/javascript' src='../js/js.js'></script>
</body>
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
