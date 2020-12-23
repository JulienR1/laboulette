function addWord(event){
    event.stopPropagation();
    event.preventDefault();

    var input = document.getElementById("newWord"); 
    var word = input.value;
    input.value = "";

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4 && this.status == 200){            
            manageWordError(xhttp.responseText);
        }
    };
    xhttp.open("GET", "game/newWord?w=" + word);
    xhttp.send();
}

function manageWordError(errorCode){
    switch(errorCode){
        case 8:
            break;
    }
    console.log("TODO: gestion des erreurs");
}