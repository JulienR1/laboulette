function startGame(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(xhttp.responseText);
        }
    };
    xhttp.open("GET", "game/start");
    xhttp.send();
}

function startTimer(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(xhttp.responseText);
        }
    };
    xhttp.open("GET", "game/startTimer");
    xhttp.send();
}

var previousDelta = null;
var timerloop = setInterval(function(){
    var timerWrapper = document.querySelector("#timer .wrapper");
    if(timerWrapper !== null){
        var target = new Date(timerWrapper.getAttribute("targettime")).getTime();
        var now = new Date().getTime();
        var delta = target - now;
        
        var minutes = Math.max(Math.floor((delta % 3600000) / 60000), 0);
        var seconds = Math.max(Math.floor((delta % 60000) / 1000), 0);
        var milliseconds = Math.max(Math.floor(delta % 1000), 0);

        if(previousDelta === null || previousDelta > 0 || delta > 0){
            timerWrapper.querySelector(".min").innerHTML = minutes;
            timerWrapper.querySelector(".sec").innerHTML = seconds;
            timerWrapper.querySelector(".msec").innerHTML = milliseconds;
            previousDelta = delta;
        }
    }
}, 113);

function registerFound(){
    requestNextWord(true);
}

function skipWord(){
    requestNextWord(false);
}

function requestNextWord(foundWord){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(xhttp.responseText);
        }
    }
    xhttp.open("GET", "game/requestWord?found=" + foundWord);
    xhttp.send();
}

function renderWord(){
    document.querySelector("#word span").style.display = "block";
    document.querySelector("#word button").style.display = "none";
}