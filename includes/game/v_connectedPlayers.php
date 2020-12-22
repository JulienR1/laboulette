<h3>Les joueurs:</h3>
<ul>
    <?php
foreach (static::$lobbyData["connectedPlayers"] as $player) {
    if ($player["id"] == $_SESSION["userId"]) {
        echo "<li><b>" . $player["username"] . "</b>" . ($player["isHost"] ? " [host]" : "") . "</li>";
    } else {
        echo "<li>" . $player["username"] . ($player["isHost"] ? " [host]" : "") . "</li>";
    }
}
?>
</ul>