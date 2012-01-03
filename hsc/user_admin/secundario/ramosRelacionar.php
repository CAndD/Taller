<?php
foreach (glob("../../class/*.php") as $filename) {
   include_once($filename);
}
session_start();
if(isset($_SESSION['usuario']))
{
  $usuario = unserialize($_SESSION['usuario']);
  if($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario'] == 3)
  {
    if(isset($_POST['submit']) && $_POST['submit'] == 'Relacionar')
    {
      if(isset($_POST['hiddenCodigoRamo']) && isset($_POST['codigoCarrera']) && isset($_POST['semestre']))
      {
        if($_POST['codigoCarrera'] != '' && $_POST['semestre'] != '')
        {
          $answer2 = $usuario->relacionarRamoConCarrera($_POST['hiddenCodigoRamo'],$_POST['codigoCarrera'],$_POST['semestre']);
        }
        else
        {
          if($_POST['codigoCarrera'] == '' && $_POST['semestre'] == '')
          {
            $answer2 = '*Debe ingresar código de la carrera y el semestre o trimestre.';
          }
          else
          {
            if($_POST['codigoCarrera'] == ''){
              $codigocarreraerror = '*Debe escoger la carrera.';}
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
  <link rel="stylesheet" type="text/css" href="../../style/style.css" title="style" />
</head>

<body>

  <h2>Relacionar ramo con carrera</h2>
      <table>
      <form method="post" name="relacionar" target="_self">
       <tr><td>Ramo: 
                                        <?php 
                                          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
                                          $sql = "SELECT r.Codigo,r.Nombre
                                                   FROM Ramo AS r
                                                  WHERE r.Codigo = '{$_GET['codigoRamo']}';";
                                          $res = $mysqli->prepare($sql);
                                          $res->execute();
                                          $res->bind_result($codigoRamo,$nombreRamo);
                                          while($res->fetch())
                                          {
                                            echo '<td>'.$nombreRamo.'</td><input type="hidden" name="hiddenCodigoRamo" value="'.$codigoRamo.'"></input>';
                                          }
                                          $res->free_result();
                                        ?>
                                       <?php if(isset($codigoramoerror)) echo '<td><span class="error">'.$codigoramoerror.'</span></td>';?></tr>
       <tr><td>Carrera: </td><td><select name="codigoCarrera"><option value="">Seleccionar carrera</option>
                                        <?php 
                                          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
                                          $sql = "SELECT c.Codigo,c.Nombre_Carrera
                                                   FROM Carrera AS c
                                                   INNER JOIN Ramo AS r ON r.Codigo = '{$_GET['codigoRamo']}'
                                                  WHERE c.Periodo = r.Periodo AND c.Codigo NOT IN (SELECT Codigo_Carrera FROM Carrera_Tiene_Ramos WHERE Codigo_Ramo = '{$_GET['codigoRamo']}');";
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

      <h3>Carreras relacionadas a este ramo</h3>
      <table><tr><td>Código</td><td>Nombre</td></tr>
      <?php 
                                          $mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_database);
                                          $sql = "CALL select_ramoCarrera('{$_GET['codigoRamo']}')";
                                          $res = $mysqli->prepare($sql);
                                          $res->execute();
                                          $res->bind_result($codigoCarrera,$nombreCarrera);
                                          while($res->fetch())
                                          {
                                            echo '<tr><td>'.$codigoCarrera.'</td><td>'.$nombreCarrera.'</td></tr>';
                                          }
                                          $res->free_result();
                                        ?>
      </table>
  <a href="../ramos.php" target="_parent">Cerrar</a>
</body>
</html>
<?php
  }
}
else
{
  header("Location: ../../index.php");
  exit();
}
