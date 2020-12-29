document.addEventListener("DOMContentLoaded", start);

let gameover = false;
let refreshTime = 1000;

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
    for(container in content){     
        if(content[container] !== undefined){
            document.getElementById(container).innerHTML = content[container];            
        }
    }
}