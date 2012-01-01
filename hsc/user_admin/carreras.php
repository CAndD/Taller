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
  if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3)
  {
    if(isset($_POST['agrega']) && $_POST['agrega'] == 'Agregar')
    {
      if(isset($_POST['codigo']) && isset($_POST['nombre']))
      {
        if(!isset($_POST['periodo']))
          $_POST['periodo'] = 0;
        if($_POST['regimen'] == 1)
          $_POST['regimen'] = 'D';
        elseif($_POST['regimen'] == 2)
          $_POST['regimen'] = 'V';
        if($_POST['codigo'] != '' && $_POST['nombre'] != '' && $_POST['periodo'] != 0 && $_POST['regimen'] != '' && $_POST['numero'] != '')
        {
          $answer = $usuario->agregarCarrera($_POST['codigo'],$_POST['nombre'],$_POST['periodo'],$_POST['regimen'],$_POST['numero']);
        }
        else
        {
          if($_POST['codigo'] == '' && $_POST['nombre'] == '' && $_POST['periodo'] == 0 && $_POST['regimen'] == 0 && $_POST['numero'] == '')
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
            if($_POST['periodo'] == 0){
              $periodoerror = '*Debe ingresar el tipo de período de la carrera.';}
            if($_POST['regimen'] == 0) {
              $regimenerror = '*Debe escoger el regimen de la carrera.';}
            if($_POST['numero'] == ''){
              $numeroerror = '*Debe ingresar el número de semestre o trimestres de la carrera.';}
            else{
              $numeroold = $_POST['numero'];}
          }
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
          <li class="selected"><a href="carreras.php">Carreras</a></li>
          <li><a href="jdc.php">Jefes de carrera</a></li>
          <li><a href="ramos.php">Ramos</a></li>
          <li><a href="profesores.php">Profesores</a></li>
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
        <h1>Carreras</h1>

        <h2>Agregar Carrera:</h2>
        <?php if(isset($answer)) echo '<span class="error">'.$answer.'</span>';?>
        <table>
        <form method="post" name="agregar" target="_self">
          <tr><td>Código</td><td>Nombre</td><td>Período</td><td>Regimen</td><td>Número Sem/Trim</td></tr>
          <tr><td><input type="text" name="codigo" value="<?php if(isset($codigoold)) echo $codigoold;?>" maxlength="9" onkeyup="buscarCodigoCarrera(this.value)" class="xl"></input></td>
          <td><input type="text" name="nombre" value="<?php if(isset($nombreold)) echo $nombreold;?>" maxlength="100"></input></td>
          <td><input type="radio" name="periodo" value="1">Semestral<br></input><input type="radio" name="periodo" value="2">Trimestral</input></td>
          <td><select name="regimen"><option value="0">Elegir regimen</option><option value="1">Diurno</option><option value="2">Vespertino</option></select></td>
          <td><input type="text" name="numero" value="<?php if(isset($numeroold)) echo $numeroold;?>" maxlength="2" class="xs"></input></td>
          <td><?php if(isset($codigoold)) echo '<input id="btt" type="submit" name="agrega" value="Agregar">'; else echo '<input id="btt" type="submit" name="agrega" value="Agregar" disabled>';?></input></td></tr>
          <tr><td><div id="existe"><?php if(isset($codigoerror)) echo '<td><span class="error">'.$codigoerror.'</span></td>';?></div></td>
              <td><?php if(isset($nombreerror)) echo '<span class="error">'.$nombreerror.'</span>';?></td>
              <td><?php if(isset($periodoerror)) echo '<span class="error">'.$periodoerror.'</span>';?></td>
              <td><?php if(isset($regimenerror)) echo '<span class="error">'.$regimenerror.'</span>';?></td>
              <td><?php if(isset($numeroerror)) echo '<span class="error">'.$numeroerror.'</span>';?></td>
              <td></td>
          </tr>
        </form>
        </table>

        <h2>Lista de carreras y sus jefes:</h2><ul>
        <?php
          $usuario->verCarreras();
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
