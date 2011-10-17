<?php
include('../class/class_lib.php');
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
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
  <link rel="stylesheet" type="text/css" href="../style/bsc.css" title="style" />
</head>

<body>

  <?php 
    $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
    $sql = "CALL selectCarrera('{$_GET['codigoCarrera']}')";
    $res = $mysqli->prepare($sql);
    $res->execute();
    $res->bind_result($codigoCarrera,$nombreCarrera,$periodoCarrera);
    $res->fetch();
    $res->free_result();
  ?>

  <h2>Malla <?php echo $nombreCarrera.' ('.$codigoCarrera.')';?></h2>
      <?php
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
             if($periodoCarrera == 1)
               $perod = 'Semestre';
             else
               $perod = 'Trimestre';
             echo '<table><tr><td>'.$perod.'</td><td>Codigo Ramo</td><td>Nombre</td></tr>';}
           echo '<tr><td>'.$semestre.'</td><td>'.$codigoRamo.'</td><td>'.$nombreRamo.'</td></tr>';
         }
         if($ram == 0)
           echo 'No tiene ramos asociados.<br><br>';
         else
           echo '</table>';
         $res2->free_result();
     ?>
  <a href="carreras.php" target="_parent">Cerrar</a></body>
</html><?php
  }
}
else
{
  header("Location: ../index.php");
  exit();
}
