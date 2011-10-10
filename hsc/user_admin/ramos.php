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

    if(isset($_POST['submit']) && $_POST['submit'] == 'Relacionar'){
      if(isset($_POST['codigoramo']) && isset($_POST['codigocarrera']) && isset($_POST['semestre']))
      {
        if($_POST['codigoramo'] != '' && $_POST['codigocarrera'] != '' && $_POST['semestre'] != '')
        {
          $answer2 = $usuario->relacionarRamoConCarrera($_POST['codigoramo'],$_POST['codigocarrera'],$_POST['semestre']);
        }
        else
        {
          if($_POST['codigoramo'] == '' && $_POST['codigocarrera'] == '' && $_POST['semestre'])
          {
            $answer2 = '*Debe ingresar código del ramo y código de la carrera.';
          }
          else
          {
            if($_POST['codigoramo'] == ''){
              $codigoramoerror = '*Debe ingresar el código del ramo.';}
            if($_POST['codigocarrera'] == ''){
              $codigocarreraerror = '*Debe ingresar el nombre del ramo.';}
            if($_POST['semestre'] == ''){
              $semestreerror = '*Debe ingresar el semestre.';}
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
      <div class="sidebar">
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      </div>
      <div id="content">
        <!-- insert the page content here -->
        <h1>Ramos</h1>
        <h2>Lista de ramos:</h2><ul>
        <?php
          $usuario->verRamos();
        ?>
        </ul>

        <h2>Agregar Ramo:</h2>
        <table>
        <form method="post" name="agregar" target="_self">
          <tr><td>Código: </td><td><input type="text" name="codigo" value="<?php if(isset($codigoold)) echo $codigoold;?>" maxlength="6"></input></td><?php if(isset($codigoerror)) echo '<td><span class="error">'.$codigoerror.'</span></td>';?></tr> 
          <tr><td>Nombre Ramo: </td><td><input type="text" name="nombre" value="<?php if(isset($nombreold)) echo $nombreold;?>" maxlength="50"></input></td><?php if(isset($nombreerror)) echo '<td><span class="error">'.$nombreerror.'</span></td>';?></tr>
          <tr><td>Teoría: </td><td><input type="text" name="teo" value="<?php if(isset($teoold)) echo $teoold;?>" maxlength="2"></input></td><?php if(isset($teoerror)) echo '<td><span class="error">'.$teoerror.'</span></td>';?></tr>
          <tr><td>Ayudantía: </td><td><input type="text" name="ayu" value="<?php if(isset($ayuold)) echo $ayuold;?>" maxlength="2"></input></td><?php if(isset($ayuerror)) echo '<td><span class="error">'.$ayuerror.'</span></td>';?></tr>
          <tr><td>Laboratorio: </td><td><input type="text" name="lab" value="<?php if(isset($labold)) echo $labold;?>" maxlength="2"></input></td><?php if(isset($laberror)) echo '<td><span class="error">'.$laberror.'</span></td>';?></tr>
          <tr><td>Taller: </td><td><input type="text" name="tall" value="<?php if(isset($tallold)) echo $tallold;?>" maxlength="2"></input></td><?php if(isset($tallerror)) echo '<td><span class="error">'.$tallerror.'</span></td>';?></tr>
          <tr><td>Créditos: </td><td><input type="text" name="cre" value="<?php if(isset($creold)) echo $creold;?>" maxlength="2"></input></td><?php if(isset($creerror)) echo '<td><span class="error">'.$creerror.'</span></td>';?></tr>
          <tr><td></td><td><input type="submit" name="agrega" value="Agregar"></input></td><?php if(isset($answer)) echo '<td><span class="error">'.$answer.'</span></td>';?></tr>
        </form>
        </table>
     
      <h2>Relacionar ramo con carrera</h2>
      <table>
      <form method="post" name="relacionar" target="_self">
       <tr><td>Ramo: </td><td><select name="codigoramo"><option value="">Seleccionar ramo</option>
                                        <?php 
                                          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
                                          $sql = "CALL select_cramos()";
                                          $res = $mysqli->prepare($sql);
                                          $res->execute();
                                          $res->bind_result($codigoRamo,$nombreRamo);
                                          while($res->fetch())
                                          {
                                            echo '<option value="'.$codigoRamo.'">'.$nombreRamo.'</option>';
                                          }
                                          $res->free_result();
                                        ?>
                                        </select></td><?php if(isset($codigoramoerror)) echo '<td><span class="error">'.$codigoramoerror.'</span></td>';?></tr>
       <tr><td>Carrera: </td><td><select name="codigocarrera"><option value="">Seleccionar carrera</option>
                                        <?php 
                                          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
                                          $sql = "CALL select_ccarreras()";
                                          $res = $mysqli->prepare($sql);
                                          $res->execute();
                                          $res->bind_result($codigoCarrera,$nombreCarrera);
                                          while($res->fetch())
                                          {
                                            echo '<option value="'.$codigoCarrera.'">'.$nombreCarrera.'</option>';
                                          }
                                          $res->free_result();
                                        ?>
                                        </select></td><?php if(isset($codigocarreraerror)) echo '<td><span class="error">'.$codigocarreraerror.'</span></td>';?></tr>
      <tr><td>Semestre/Trimestre: </td><td><input type="text" name="semestre" value="" maxlength="2"></input></td><?php if(isset($semestreerror)) echo '<td><span class="error">'.$semestreerror.'</span></td>';?></tr>
      <tr><td></td><td><input type="submit" name="submit" value="Relacionar"></input></td><?php if(isset($answer2)) echo '<td><span class="error">'.$answer2.'</span></td>';?></tr>
      </table>

      <h2>Carreras con ramos relacionados</h2>
      <?php
       $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
       $sql = "CALL select_ccarreras()";
       $res = $mysqli->prepare($sql);
       $res->execute();
       $res->bind_result($codigoCarrera,$nombreCarrera);
       while($res->fetch())
       {
         echo '<h4>'.$codigoCarrera.' - '.$nombreCarrera.'</h4>';
         $mysqli2 = @new mysqli($db_host, $db_user, $db_pass, $db_database);
         $sql2 = "CALL select_ramoscarreras('{$codigoCarrera}')";
         $res2 = $mysqli2->prepare($sql2);
         $res2->execute();
         $res2->bind_result($codigoRamo,$nombreRamo,$semestre);
         $ram = 0;
         while($res2->fetch())
         {
           if($ram == 0){
             $ram = 1;
             echo '<table><tr><td>Semestre</td><td>Codigo Ramo</td><td>Nombre</td></tr>';}
           echo '<tr><td>'.$semestre.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td></tr>';
         }
         if($ram == 0)
           echo 'No tiene ramos asociados.<br><br>';
         else
           echo '</table>';
         $res2->free_result();
       }
       $res->free_result();
     ?>
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
