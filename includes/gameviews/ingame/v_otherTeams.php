<h3>Votre équipe ne joue pas</h3>
<?php echo static::$currentPlayer["username"] ?> est en train de jouer. Ne soufflez pas les réponses; elles sont toutes autant valides! Le mot doit être deviné
<?php
switch (static::$currentGameMode) {
    case 0:
        echo " selon sa <b>description</b>!";
        break;
    case 1:
        echo " en se fiant à <b>un mot</b>!";
        break;
    case 2:
        echo " selon les <b>mimes</b>!";
        break;
    case 3:
        echo " selon <b>une seule pose</b>!";
        break;
}
?>
Portez attention aux mots sortis, ils reviendront dans les prochains tours.