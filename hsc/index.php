<?php
foreach (glob("class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(!isset($_SESSION['usuario']))
{
  if(isset($_POST['user']) && isset($_POST['pass']))
  {
    if($_POST['user'] != '' && $_POST['pass'] != '')
    {
      if(strlen($_POST['pass'])>4)
      {
        $password = md5($_POST['pass']);
        $usuario = new usuario($_POST['user'],$password);
        $login = $usuario->ingresarAlSistema();
        if($login == true)
        {
          $usuario->__destruct();
          //setcookie('tzRemember',$_POST['rememberMe'],time() + 86400);
          $usuario = unserialize($_SESSION['usuario']);
          if($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) 
          {
            header("Location: home.php");
            exit();
          }
          elseif($_SESSION['tipoUsuario'] == 2) 
          {
            header("Location: user_admin/admin.php");
            exit();
          }
        }
        else
        {
          $loginerror = '*Usuario o contraseña incorrectos.';
          $userold = $_POST['user'];
        }
      }
      else
      {
        $userold = $_POST['user'];
        $passerror = '*Debe ingresar una contraseña con largo mayor a 4.';
      }
    }
    else
    {
      if($_POST['user'] == '')
      {
        $usererror = '*Debe ingresar un nombre de usuario.';
      }
      else
      {
        $userold = $_POST['user'];
      }
 
      if($_POST['pass'] == '')
      {
        $passerror = '*Debe ingresar una contraseña.';
      }
    }
  }
}
else
{
  header("Location: home.php");
  exit();
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
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
  <link rel="stylesheet" type="text/css" href="style/bsc.css" />
</head>

<body>
  <div id="main">

    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Escuela de <span class="logo_colour">Ingeniería</span></a></h1>
          <h2>Herramienta de software para la comunicación en el proceso de Programación de Horarios</h2>
        </div>
      </div>
    </div>

    <div id="site_content">
      <div id="content">
        <h1>Bienvenido</h1>
        <p>A la herramienta de ayuda de programación de horarios para la escuela de ingeniería de la Universidad Andrés Bello sede república.</p>
        
        <h2>Login Jefe de Carrera:</h2>
        <table>
        <tr><td>
        <form method="post" name="login" target="_self">
         Nombre de usuario: </td><td><input type="text" name="user" maxlength="40" value="<?php if(isset($userold)){echo $userold;}?>"></input></td><?php if(isset($usererror)){echo '<td><span class="error">'.$usererror.'</span></td>';}?></tr>
         <tr><td>
         Contraseña: </td><td><input type="password" name="pass" maxlength="20"></input></td><?php if(isset($passerror)){echo '<td><span class="error">'.$passerror.'</span></td>';}?></tr>
         <tr><td></td><td>
         <input type="submit" name="Ingresar" value="Ingresar"></input></td><?php if(isset($loginerror)){echo '<td><span class="error">'.$loginerror.'</span></td>';}?></tr>
        </form>
        </table>
      </div>
    </div>

    <div id="content_footer"></div>
    <div id="footer"></div>

  </div>
<div style="text-align: center; font-size: 0.75em;"></div>  
  <script type='text/javascript' src='js/bsc.js'></script>
</body>
</html>
