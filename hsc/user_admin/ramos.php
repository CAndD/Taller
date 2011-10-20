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
    if(isset($_POST['agrega']) && $_POST['agrega'] == 'Agregar')
    {
      if(isset($_POST['codigo']) && isset($_POST['nombre']))
      {
        if($_POST['codigo'] != '' && $_POST['nombre'] != '' && $_POST['teo'] != '' && $_POST['ayu'] != '' && $_POST['lab'] != '' && $_POST['tall'] != '' && $_POST['cre'] != '')
        {
          $answer = $usuario->agregarRamo($_POST['codigo'],$_POST['nombre'],$_POST['teo'],$_POST['ayu'],$_POST['lab'],$_POST['tall'],$_POST['cre']);
        }
        else
        {
          if($_POST['codigo'] == '' && $_POST['nombre'] == '' && $_POST['teo'] == '' && $_POST['ayu'] == '' && $_POST['lab'] == '' && $_POST['tall'] == '' && $_POST['cre'] == '')
          {
            $answer = '*Debe ingresar los campos requeridos.';
          }
          else
          {
            if($_POST['codigo'] == ''){
              $codigoerror = '*Debe ingresar el código del ramo.';}
            else{
              $codigoold = $_POST['codigo'];}
            if($_POST['nombre'] == ''){
              $nombreerror = '*Debe ingresar el nombre del ramo.';}
            else{
              $nombreold = $_POST['nombre'];}
            if($_POST['teo'] == ''){
              $teoerror = '*Debe ingresar las horas teóricas del ramo.';}
            else{
              $teoold = $_POST['teo'];}
            if($_POST['ayu'] == ''){
              $ayuerror = '*Debe ingresar las horas de ayudantía del ramo.';}
            else{
              $ayuold = $_POST['ayu'];}
            if($_POST['lab'] == ''){
              $laberror = '*Debe ingresar las horas de laboratorio del ramo.';}
            else{
              $labold = $_POST['lab'];}
            if($_POST['tall'] == ''){
              $tallerror = '*Debe ingresar las horas de taller del ramo.';}
            else{
              $tallold = $_POST['tall'];}
            if($_POST['cre'] == ''){
              $creerror = '*Debe ingresar los créditos del ramo.';}
            else{
              $creold = $_POST['cre'];}
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
  <link rel="stylesheet" type="text/css" href="../style/bsc.css" title="style" />
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
          <li><a href="carreras.php">Carreras</a></li>
          <li><a href="jdc.php">Jefes de carrera</a></li>
          <li class="selected"><a href="ramos.php">Ramos</a></li>
          <li><a href="contacto.php">Contacto</a></li>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">

      <div id="content">
        <!-- insert the page content here -->
        <h1>Ramos</h1>
 
        <h2>Agregar Ramo:</h2>
        <?php if(isset($answer)) echo '<span class="error">'.$answer.'</span>';?>
        <table>
        <tr><td>Codigo</td><td>Nombre</td><td>Teó.</td><td>Ayu.</td><td>Lab.</td><td>Tall.</td><td>Créd.</td><td></td></tr> 
        <form method="post" name="agregar" target="_self">
            <tr><td><input type="text" name="codigo" value="<?php if(isset($codigoold)) echo $codigoold;?>" maxlength="6" class="m" onkeyup="buscarCodigoRamo(this.value)"></input></td> 
            <td><input type="text" name="nombre" value="<?php if(isset($nombreold)) echo $nombreold;?>" maxlength="50"></input></td>
            <td><input type="text" name="teo" value="<?php if(isset($teoold)) echo $teoold;?>" maxlength="2" class="xs"></input></td>
            <td><input type="text" name="ayu" value="<?php if(isset($ayuold)) echo $ayuold;?>" maxlength="2" class="xs"></input></td>
            <td><input type="text" name="lab" value="<?php if(isset($labold)) echo $labold;?>" maxlength="2" class="xs"></input></td>
            <td><input type="text" name="tall" value="<?php if(isset($tallold)) echo $tallold;?>" maxlength="2" class="xs"></input></td>
            <td><input type="text" name="cre" value="<?php if(isset($creold)) echo $creold;?>" maxlength="2" class="xs"></input></td>
            <td><?php if(isset($codigoold))echo '<input type="submit" name="agrega" value="Agregar" id="btt">'; else echo '<input type="submit" name="agrega" value="Agregar" id="btt" disabled>';?></input></td></tr>
            <tr><td><div id="existe"><?php if(isset($codigoerror)) echo '<td><span class="error">'.$codigoerror.'</span></td>';?></div></td>
                <td><?php if(isset($nombreerror)) echo '<span class="error">'.$nombreerror.'</span>';?></td>
                <td><?php if(isset($teoerror)) echo '<span class="error">'.$teoerror.'</span>';?></td>
                <td><?php if(isset($ayuerror)) echo '<span class="error">'.$ayuerror.'</span>';?></td>
                <td><?php if(isset($laberror)) echo '<span class="error">'.$laberror.'</span>';?></td>
                <td><?php if(isset($tallerror)) echo '<span class="error">'.$tallerror.'</span>';?></td>
                <td><?php if(isset($creerror)) echo '<span class="error">'.$creerror.'</span>';?></td>
                <td></td></tr>
          </form>
        </table>

        <h2>Lista de ramos:</h2><ul>
        <?php
          echo '<table>';
          echo '<tr><td>Codigo</td><td>Nombre</td><td>Teó.</td><td>Ayu.</td><td>Lab.</td><td>Tall.</td><td>Créd.</td><td>Relacionar</td><td>Eliminar</td></tr>';     
          $usuario->verRamos();
          echo '</table>';
        ?>
        </ul>
     
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
  <script type='text/javascript' src='../js/bsc.js'></script>
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
