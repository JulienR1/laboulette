<h3>Paramètres de partie</h3>

<form method="POST" autocomplete="off" onsubmit="modifySettings(event);">
    <input type="numeric" name="roundTimer" id="roundTimer" value="<?php echo static::$gameData["roundTimer"]; ?>" step="5" onchange="modifySettings(event);">
    <input type="numeric" name="teamCount" id="teamCount" value="<?php echo static::$gameData["teamCount"]; ?>" min="2" step="1" onchange="modifySettings(event);">
    <button type="submit" name="start-btn">Sauvegarder</button>
</form>