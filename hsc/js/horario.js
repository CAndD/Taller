$(function(){
  var div = document.getElementById("asdf");
  var i;
  for (i=0;i<div.childNodes.length;i++)
  {
    alert(div.childNodes[i]);
    $(div.childNodes[i]).draggable({
      revert:true });
  }
});


