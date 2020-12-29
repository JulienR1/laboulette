document.addEventListener("DOMContentLoaded", start);

let gameover = false;
let refreshTime = 1000;

const CONTAINERS = ["connectedPlayers", "wordStats", "gameSettings", "startButton"];

function start(){    
    loop();
}

function loop(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            // console.log(xhttp.responseText);
            buildPage(JSON.parse(xhttp.responseText));
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

function buildPage(content){
    CONTAINERS.forEach((container)=>{
        if(content[container] !== undefined){
            document.getElementById(container).innerHTML = content[container];            
        }
    });
}