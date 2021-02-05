<?php

if (static::$joinError !== Errors::NO_ERROR) {
    echo "there was an error<br>";
    echo "code: " . static::$joinError;
}

?>

<form action="#" method="POST" autocomplete="off">
    <input type="text" name="username" placeholder="Nom">
    <input type="text" name="lobbyHash" placeholder="Code de salon" maxlength="6" value="<?php echo isset($_GET["id"]) ? $_GET["id"] : ""; ?>">
    <input type="text" name="password" placeholder="Mot de passe">
    <button type="submit" name="join-btn">Joindre le salon</button>
</form>