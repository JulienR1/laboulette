<h3>C'est au tour de votre équipe</h3>
<?php echo static::$currentPlayer["username"] ?> est en train de jouer. Devinez le mot
<?php
switch (static::$currentGameMode) {
    case 0:
        echo " selon sa <b>description</b>!";
        break;
    case 1:
        echo " en vous fiant à <b>un mot</b>!";
        break;
    case 2:
        echo " selon les <b>mimes</b>!";
        break;
    case 3:
        echo " selon <b>une seule pose</b>!";
        break;
}
?>