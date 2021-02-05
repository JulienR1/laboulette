document.addEventListener("DOMContentLoaded", start);

let gameover = false;
let refreshTime = 1000;
let potentialEvents;

function start(){    
    potentialEvents = {"teams":initTeamBuilder};
    loop();
}

function loop(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            // console.log(xhttp.responseText);
            var responseJSON = JSON.parse(xhttp.responseText);
            buildPage(responseJSON);
            callPotentialEvents(responseJSON);
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

function callPotentialEvents(content){
    for(container in content){
        if(container in potentialEvents){            
            potentialEvents[container]();
        }
    }
}