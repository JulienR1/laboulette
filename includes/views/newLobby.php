<?php

if (static::$buildError !== Errors::NO_ERROR) {
    echo "there was an error<br>";
    echo "code: " . static::$buildError;
}

?>

<form action="#" method="POST" autocomplete="off">
    <input type="text" name="username" placeholder="Nom">
    <input type="text" name="password" placeholder="Mot de passe">
    <input type="numeric" step="1" name="minWords" value="10">
    <button type="submit" name="create-btn">Créer</button>
</form>