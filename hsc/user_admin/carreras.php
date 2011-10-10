<?php
include('../class/class_lib.php');
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
    if(isset($_POST['codigo']) && isset($_POST['nombre']) && isset($_POST['periodo']) && isset($_POST['numero']))
    {
      if($_POST['codigo'] != '' && $_POST['nombre'] != '' && $_POST['periodo'] != '' && $_POST['numero'] != '')
      {
        $answer = $usuario->agregarCarrera($_POST['codigo'],$_POST['nombre'],$_POST['periodo'],$_POST['numero']);
      }
      else
      {
        if($_POST['codigo'] == '' && $_POST['nombre'] == '' && $_POST['periodo'] != '' && $_POST['numero'] == '')
        {
          $answer = '*Debe ingresar código, nombre y semestres de duración de carrera.';
        }
        else
        {
          if($_POST['codigo'] == ''){
            $codigoerror = '*Debe ingresar el código de la carrera.';}
          else{
            $codigoold = $_POST['codigo'];}  
          if($_POST['nombre'] == ''){
            $nombreerror = '*Debe ingresar el nombre de la carrera.';}
          else{
            $nombreold = $_POST['nombre'];}
          if($_POST['periodo'] == ''){
            $periodoerror = '*Debe ingresar el tipo de período de la carrera.';}
          if($_POST['numero'] == ''){
            $numeroerror = '*Debe ingresar el número de semestre o trimestres de la carrera.';}
          else{
            $numeroold = $_POST['numero'];}
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
  <link 
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
          <li class="selected"><a href="carreras.php">Carreras</a></li>
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
        <h1>Carreras</h1>
        <h2>Lista de carreras y sus jefes:</h2><ul>
        <?php
          $usuario->verCarreras();
        ?>
        </ul>

        <h2>Agregar Carrera:</h2>
        <table>
        <form method="post" name="agregar" target="_self">
          <tr><td>Código: </td><td><input type="text" name="codigo" value="<?php if(isset($codigoold)) echo $codigoold;?>" maxlength="9"></input></td><?php if(isset($codigoerror)) echo '<td><span class="error">'.$codigoerror.'</span></td>';?></tr> 
          <tr><td>Nombre: </td><td><input type="text" name="nombre" value="<?php if(isset($nombreold)) echo $nombreold;?>" maxlength="100"></input></td><?php if(isset($nombreerror)) echo '<td><span class="error">'.$nombreerror.'</span></td>';?></tr>
          <tr><td>Período: </td><td><input type="radio" name="periodo" value="1"> Semestral </input><input type="radio" name="periodo" value="2"> Trimestral</input></td><?php if(isset($periodoerror)) echo '<td><span class="error">'.$periodoerror.'</span></td>';?></tr>
          <tr><td>Número Sem/Trim: </td><td><input type="text" name="numero" value="<?php if(isset($numeroold)) echo $numeroold;?>" maxlength="2"></input></td><?php if(isset($numeroerror)) echo '<td><span class="error">'.$semestreerror.'</span></td>';?></tr>
          <tr><td></td><td><input type="submit" name="agrega" value="Agregar"></input></td><?php if(isset($answer)) echo '<td><span class="error">'.$answer.'</span></td>';?></tr>
        </form>
        </table>
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
