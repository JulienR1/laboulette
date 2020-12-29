game window<br>
localhost/join?id=<?php echo $_GET["id"]; ?><br>
lobby id: <?php echo $_SESSION["lobbyId"]; ?><br>

<div id="connectedPlayers"></div>

<div id="wordStats"></div>

<div id="wordForm">
    <?php require "includes/gameviews/all/v_wordForm.php";?>
</div>

<div id="gameSettings"></div>

<div id="startButton"></div>

<div id="teams"></div>