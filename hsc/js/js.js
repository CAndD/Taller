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
  var xmlhttp;
  var ans;
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("GET","../user_admin/secundario/ajax.php?idClase="+idClase+"&horario="+horario,false);
  xmlhttp.send();
  return xmlhttp.responseText;
  /*xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.status==200 && xmlhttp.readyState==4)
    {
      if(xmlhttp.responseText)
      {
        alert("ajax: "+xmlhttp.responseText);
        ans = xmlhttp.responseText;
        alert("answer: "+ans);
        if(xmlhttp.responseText == '-2'){
          alert("devolvemos menos dos.");
          return xmlhttp.responseText;}
        else if(xmlhttp.responseText == '-1')
          return '-1';
        else if(xmlhttp.responseText == '0')
          return '0';
        else if(xmlhttp.responseText == '1')
          return '1';
        else if(xmlhttp.responseText == '2')
          return '2';
      }
    }
  }*/
}
