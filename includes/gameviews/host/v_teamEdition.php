<?php
$lastTeamId = -1;
$teamData = static::$lobbyData["teams"];
for ($i = 0; $i < sizeof($teamData); $i++) {
    if ($lastTeamId !== $teamData[$i]["teamId"]) {
        startTeamHtml($teamData[$i]["teamId"], $teamData[$i]["name"]);
        $lastTeamId = $teamData[$i]["teamId"];
    }
    addTeamPlayerToHtml($teamData[$i]["playerId"], $teamData[$i]["username"]);

    if ($i + 1 === sizeof($teamData) || $teamData[$i + 1]["teamId"] !== $lastTeamId) {
        endTeamHtml();
    }
}

include "v_teamEdition_touchControl.php";

function startTeamHtml($teamId, $teamName)
{
    echo '<table class="team" teamId="' . $teamId . '">';
    echo '<tr><th>' . $teamName . '</th></tr>';
}

function addTeamPlayerToHtml($playerId, $playerName)
{
    if ($playerId !== null) {
        echo '<tr draggable="true" class="player" playerid="' . $playerId . '"><td>' . $playerName . "</td></tr>";
    }
}

function endTeamHtml()
{
    echo "</table>";
}