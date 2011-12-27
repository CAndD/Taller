<?php
foreach (glob("../class/*.php") as $filename) {
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

  if(isset($_POST['cambiarCarrera']) && $_POST['cambiarCarrera'] == 'CAMBIAR CARRERA') {
    $_SESSION['carrera'] = null;
    $_SESSION['codigoSemestre'] = null;
    header("Location: ../home.php");
    exit();
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
  <link rel="stylesheet" type="text/css" href="../style/style.css" title="style" />
  <link rel="stylesheet" type="text/css" href="../style/bsc.css" title="style" />
  <script type="text/javascript" src="../js/jquery-1.6.1.min.js"></script>
  <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
  <script type="text/javascript" src="../js/js.js"></script>

  <script>
		$(function(){
			$('.up .item').draggable({
				revert:true
			});
			$('.down td.drop').droppable({
				onDragEnter:function(){
					$(this).addClass('over');
				},
				onDragLeave:function(){
					$(this).removeClass('over');
				},
				onDrop:function(e,source){
					$(this).removeClass('over');
					if ($(source).hasClass('assigned')){
						$(this).append(source);
                                                //alert($(this).attr('id') + $(source).attr('id'));
					} else {
						var c = $(source).clone().addClass('assigned');
                                                //alert($(this).attr('id'));
                                                var resp = asignarHorario($(source).attr('id'),$(this).attr('id'));
                                                alert(resp);
                                                //$('.up').empty();
						$(this).append(c);
						c.draggable({
							revert:true
						});
					}
				}
			});
		});
	</script>

</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.php">Universidad<span class="logo_colour"> Andrés Bello</span></a></h1>
          <h2>Herramienta de programación de horarios.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="../home.php">Home</a></li>
          <?php
          if(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && (is_string($_SESSION['carrera']) == true)) {
            echo '<li><a href="ramos.php">Ramos</a></li>';
            echo '<li><a href="secciones.php">Secciones y Vacantes</a></li>';
            echo '<li class="selected"><a href="horario.php">Horario</a></li>';
            echo '<li><a href="solicitudes.php">Solicitudes</a></li>';
          }
          ?>
          <li><a href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <!-- insert the page content here -->
        <h2>Horario</h2>
        <div>
         <div id="horario"></div>
        <?php 
          if(isset($_GET['idClase']))
          {
            
            echo '<div class="up">';
            echo '<div class="item" id="'.$_GET['idClase'].'">'.$_GET['codigoRamo'].'<br>'.$_GET['numeroSeccion'].'. '.$_GET['tipoClase'].'</div>';
            echo '</div>';
          }

          $numeroSemestres = numeroSemestres($_SESSION['carrera']);
          if(isset($_GET['numeroSemestre']) || (isset($_POST['submit']) && $_POST['submit'] == 'Cambiar' && isset($_POST['numeroSemestre'])))
          {
            if(isset($_GET['numeroSemestre'])) 
            {
              verHorario($_SESSION['carrera'],$_SESSION['codigoSemestre'],$_GET['numeroSemestre']);
              echo '</div>';
              echo '<table class="centerTable"><tr><td>';
              if($_GET['numeroSemestre'] - 1 > 0)
                echo '<a href="horario.php?numeroSemestre='.($_GET['numeroSemestre']-1).'">atrás</a>';
              echo ' '.$_GET['numeroSemestre'].' ';
              if($_GET['numeroSemestre'] + 1 <= $numeroSemestres)
                echo '<a href="horario.php?numeroSemestre='.($_GET['numeroSemestre']+1).'">adelante</a>';
              echo '</td><td><form method="post" target="_self" name="cambiarSemestre"><input type="text" name="numeroSemestre" value="" maxlength="2" class="xs"></input> <input type="submit" name="submit" value="Cambiar"></input></form></td></tr></table>';
            }
            elseif(isset($_POST['submit']) && $_POST['submit'] == 'Cambiar' && isset($_POST['numeroSemestre']))
            {
              if($_POST['numeroSemestre'] <= 0 || $_POST['numeroSemestre'] > $numeroSemestres)
              {
                $_POST['numeroSemestre'] = 1;
                echo '<span class="error">*Debe escoger un semestre entre 1 y '.$numeroSemestres.'.</span>';
              }
              verHorario($_SESSION['carrera'],$_SESSION['codigoSemestre'],$_POST['numeroSemestre']);
              echo '</div>';
              echo '<table class="centerTable"><tr><td>';
              if($_POST['numeroSemestre'] - 1 > 0)
                echo '<a href="horario.php?numeroSemestre='.($_POST['numeroSemestre']-1).'">atrás</a>';
              echo ' '.$_POST['numeroSemestre'].' ';
              if($_POST['numeroSemestre'] + 1 <= $numeroSemestres)
                echo '<a href="horario.php?numeroSemestre='.($_POST['numeroSemestre']+1).'">adelante</a>';
              echo '</td><td><form method="post" target="_self" name="cambiarSemestre"><input type="text" name="numeroSemestre" value="" maxlength="2" class="xs"></input> <input type="submit" name="submit" value="Cambiar"></input></form></td></tr></table>';
            }
          }
          else
          {
            verHorario($_SESSION['carrera'],$_SESSION['codigoSemestre'],1);
            echo '</div>';
            echo '<table class="centerTable"><tr><td>1 <a href="horario.php?numeroSemestre=2">adelante</a></td><td><form method="post" target="_self" name="cambiarSemestre"><input type="text" name="numeroSemestre" value="" maxlength="2" class="xs"></input> <input type="submit" name="submit" value="Cambiar"></input></form></td></tr></table>';
          }   
        ?>
        <br><br>
      </div>
    </div>
    <div id="content_footer"></div>
    <div id="footer">
    <?php
      if(($_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 3) && !is_null($_SESSION['carrera']) &&$_SESSION['nroCarrera'] > 1) {
        echo '<form method="post" name="cambiarCarrera" target="_self"><input type="submit" name="cambiarCarrera" value="CAMBIAR CARRERA" class="inp"></input></form>';
        $j = 1;
      }
      if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3) {
        if(isset($j) && $j == 1)
          echo ' / ';
        echo '<a href="../user_admin/admin.php">Modo administrador</a>';
      }
    ?>
    </div>
  </div>
  <!--<script type='text/javascript' src='../js/jquery.js'></script>-->
  <!--<script type='text/javascript' src='../js/jquery.simplemodal.js'></script>-->
  <!--<script type='text/javascript' src='../js/horario.js'></script>-->
  <script type='text/javascript' src='../js/bsc.js'></script>
</body>
</html>
<?php
  }
  else
  {
    header("Location: index.php");
    exit();
  }
}
else
{
  header("Location: index.php");
  exit();
}
