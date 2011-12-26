function buscarCodigoCarrera(codigoCarrera)
{
  if(codigoCarrera.length <= 8)
  {
    document.getElementById("btt").disabled=true;
    return;
  }
  var err = '<span class="error">*Código ya existe.</span>';
  var xmlhttp;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?codigoCarrera="+codigoCarrera,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        document.getElementById("btt").disabled=true;
        document.getElementById("existe").innerHTML=err;
      }
      else
      {
        document.getElementById("btt").disabled=false;
        document.getElementById("existe").innerHTML="";
      }
    }
  }
}

function buscarNombreUsuario(nombreUsuario)
{
  if(nombreUsuario.length <= 2)
  {
    document.getElementById("btt").disabled=true;
    return;
  }
  var err = '<span class="error">*Nombre de usuario ya existe.</span>';
  var xmlhttp;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?nombreUsuario="+nombreUsuario,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        document.getElementById("btt").disabled=true;
        document.getElementById("existe").innerHTML=err;
      }
      else
      {
        document.getElementById("btt").disabled=false;
        document.getElementById("existe").innerHTML="";
      }
    }
  }
}

function buscarCodigoRamo(codigoRamo)
{
  if(codigoRamo.length <= 5)
  {
    document.getElementById("btt").disabled=true;
    return;
  }
  var err = '<span class="error">*Código ya existe.</span>';
  var xmlhttp;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?codigoRamo="+codigoRamo,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        document.getElementById("btt").disabled=true;
        document.getElementById("existe").innerHTML=err;
      }
      else
      {
        document.getElementById("btt").disabled=false;
        document.getElementById("existe").innerHTML="";
      }
    }
  }
}

function buscarRutProfesor(rutProfesor)
{
  if(rutProfesor.length <= 7)
  {
    document.getElementById("btt").disabled=true;
    return;
  }
  var err = '<span class="error">*Código ya existe.</span>';
  var xmlhttp;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?rutProfesor="+rutProfesor,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        document.getElementById("btt").disabled=true;
        document.getElementById("existe").innerHTML=err;
      }
      else
      {
        document.getElementById("btt").disabled=false;
        document.getElementById("existe").innerHTML="";
      }
    }
  }
}

function buscarAbreviacion(abrev)
{
  var err = '<span class="error">*Abreviación ya existe.</span>';
  var xmlhttp;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?abrev="+abrev,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        document.getElementById("btt").disabled=true;
        document.getElementById("existe").innerHTML=err;
      }
      else
      {
        document.getElementById("btt").disabled=false;
        document.getElementById("existe").innerHTML="";
      }
    }
  }
}

function asignarHorario(idClase,horario)
{
  var err = '<span class="error">*Tope de horario.</span>';
  var acc = '<span class="error">*Horario asignado.</span>';
  var xmlhttp;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?idClase="+idClase+"&horario="+horario,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        document.getElementById("existe").innerHTML=err;
      }
      else
      {
        document.getElementById("existe").innerHTML="";
      }
    }
  }
}
