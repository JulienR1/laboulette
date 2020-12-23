function modifySettings(event){
    event.stopPropagation();
    event.preventDefault();

    var roundTimer = document.getElementById("roundTimer").value;
    var teamCount = document.getElementById("teamCount").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){            
            ManageSettingsError(xhttp.responseText);
        }
    };
    xhttp.open("GET", `game/updateSettings?timer=${roundTimer}&teamCount=${teamCount}`);
    xhttp.send();
}

function ManageSettingsError(errorCode){
    switch(errorCode){
        
    }
    console.log("TODO: Manage errors from settings");
}