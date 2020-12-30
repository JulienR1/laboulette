let teams, players;
let currentSelectedPlayer = null;
let duplicatePlayer = null;

let touchControlMenu;

function loadTeamBuilder(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(xhttp.responseText);
        }
    };
    xhttp.open("GET","game/beginTeamBuilding");
    xhttp.send();
}

function notifyTeamChanges(){
    var encodedTeams = getEncodedTeams();
    if(encodedTeams === false){
        console.log("Error in data format, aborting");
        return;
    }

    encodedTeams = JSON.stringify(encodedTeams);

    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "game/updateTeams?teams=" + encodedTeams);
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){
            console.log(xhttp.responseText);
        }
    };
    xhttp.send();
}

function getEncodedTeams(){
    var encodedData = {};

    var usedTeamIds = [];
    var usedPlayerIds = [];

    teams.forEach(team=>{
        var teamId = -1;
        if(team.hasAttribute("teamId")){
            teamId = team.getAttribute("teamId");
            if(teamId in usedTeamIds === false){
                usedTeamIds.push(teamId);
            } else {
                return false;
            }
        }else{
            return false;
        }

        encodedData[teamId] = [];
        team.querySelectorAll(".player").forEach(player => {
            var playerId = -1;
            if(player.hasAttribute("playerId")){
                playerId = player.getAttribute("playerId");
                if(playerId in usedPlayerIds === false){
                    usedPlayerIds.push(playerId);                    
                    encodedData[teamId].push(playerId);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });
    });
    return encodedData;
}

function initTeamBuilder(){
    teams = document.querySelectorAll(".team");
    players = document.querySelectorAll(".player");
    touchControlMenu = document.getElementById("touchControl");    

    if(touchControlMenu !== null){
        Array.from(players).map(addEventsToPlayer);
        Array.from(teams).map(addEventsToTeams);
        
        document.addEventListener("click", cancelTeamChange);
        touchControlMenu.querySelectorAll("li").forEach((button)=>{
            button.addEventListener("click", onTeamSelection);
        });
    }
}

function addEventsToPlayer(player){
    player.addEventListener("dragstart", dragStart);
    player.addEventListener("dragend", dragEnd);
    player.addEventListener("click", onPlayerClick);
}

function addEventsToTeams(team){
    team.addEventListener("dragenter", dragEnter);
    team.addEventListener("dragleave", dragLeave);
    team.addEventListener("dragover", dragOver);
    team.addEventListener("drop", dragDrop);
}

function dragStart(){
    this.className += " hold";
    currentSelectedPlayer = this;
    setTimeout(() => {
        this.className = "invisible";
    }, 0);

    duplicatePlayer = this.cloneNode(true);
    duplicatePlayer.cloneName += " duplicate";
    currentTeamHoveredId = null;
}

function dragEnd(){
    this.className = "player";
    currentSelectedPlayer = null;

    duplicatePlayer.remove();
    duplicatePlayer = null;
}

function dragOver(e){
    e.preventDefault();    
    this.querySelector("tbody").append(duplicatePlayer);    
}

function dragEnter(e){
    e.preventDefault();
    this.className += " hovered";
}

function dragLeave(){
    this.className = "team";
    duplicatePlayer.remove();
}

function dragDrop(){
    this.className = "team";
    if(currentSelectedPlayer !== null){
        this.querySelector("tbody").append(currentSelectedPlayer);
        notifyTeamChanges();
    }
}

//-------------------------- Touch device interactions ------------------------------

function onPlayerClick(e){
    e.stopPropagation();
    if("ontouchstart" in document.documentElement === true){
        onPlayerTouch(this);
    }
}

function onPlayerTouch(player){
    currentSelectedPlayer = player;
    let team = player.parentNode.parentNode;
    let teamId = team.getAttribute("teamId");
    touchControlMenu.querySelectorAll("li").forEach(teamSelector => {
        if(teamId === teamSelector.getAttribute("teamId")){
            teamSelector.setAttribute("inactive", "");
        }else{
            teamSelector.removeAttribute("inactive");
        }
    });
    touchControlMenu.removeAttribute("disabled");
}

function onTeamSelection(e){
    if(this.hasAttribute("inactive") || touchControlMenu.hasAttribute("disabled") || currentSelectedPlayer === null){
        e.stopPropagation();
        return;
    }

    teams.forEach(team => {
        if(team.getAttribute("teamid") === this.getAttribute("teamid")){
            if(currentSelectedPlayer in team.querySelectorAll(".player") === false){
                team.append(currentSelectedPlayer);
                notifyTeamChanges();
            }
            return;            
        }
    });
}

function cancelTeamChange(){
    touchControlMenu.setAttribute("disabled","");
    currentSelectedPlayer = null;
}