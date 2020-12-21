document.addEventListener("DOMContentLoaded", start);

let gameover = false;
let refreshTime = 1000;

let mainContainer = null;

function start(){
    mainContainer = document.querySelector("main");
    loop();
}

function loop(){
    console.log("loop");

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            mainContainer.innerHTML = xhttp.responseText;            
        }
    };
    xhttp.open("GET","game/build", true);
    xhttp.send();

    if(gameover){
        return;
    }

    setTimeout(() => {
        loop();
    }, refreshTime);
}