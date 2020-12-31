Le mot à faire deviner en

<?php
switch (static::$currentGameMode) {
    case 0:
        echo " donnant une <b>description</b>";
        break;
    case 1:
        echo " en ne donnant qu'<b>un seul mot</b>!";
        break;
    case 2:
        echo " <b>mimant</b>!";
        break;
    case 3:
        echo " prenant <b>une seule pose</b>!";
        break;
}
?>
est : <span style="display: none;"><?php echo $_SESSION["randomWord"]["word"]; ?> </span>

<button onclick="renderWord()">Afficher</button>