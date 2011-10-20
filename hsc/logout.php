<?php
  foreach (glob("class/*.php") as $filename) {
   include_once($filename);
  }
  session_start();
  if(isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $usuario->cerrarSesion();
  }
  header("Location: index.php");
  exit;
?>
