window.addEventListener("beforeunload", disconnect);

function disconnect(){
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET","game/disconnect", true);
  //  xhttp.send();
}