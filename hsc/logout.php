<?php
  include('class/class_lib.php');
  session_start();
  if(isset($_SESSION['usuario'])) {
    $usuario = unserialize($_SESSION['usuario']);
    $usuario->cerrarSesion();
  }
  header("Location: index.php");
  exit;
?>
