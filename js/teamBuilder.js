function loadTeamBuilder(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){            
            console.log(xhttp.responseText);
        }
    };
    xhttp.open("GET",`game/beginTeamBuilding`);
    xhttp.send();
}