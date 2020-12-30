<?php
$lastTeamId = -1;
$teamData = static::$lobbyData["teams"];
for ($i = 0; $i < sizeof($teamData); $i++) {
    if ($lastTeamId !== $teamData[$i]["teamId"]) {
        startTeamHtml($teamData[$i]["name"]);
        $lastTeamId = $teamData[$i]["teamId"];
    }
    addTeamPlayerToHtml($teamData[$i]["username"]);

    if ($i + 1 === sizeof($teamData) || $teamData[$i + 1]["teamId"] !== $lastTeamId) {
        endTeamHtml();
    }
}

function startTeamHtml($teamName)
{
    echo '<table class="team"">';
    echo '<tr><th>' . $teamName . '</th></tr>';
}

function addTeamPlayerToHtml($playerName)
{
    if ($playerName !== null) {
        echo '<tr class="player"><td>' . $playerName . "</td></tr>";
    }
}

function endTeamHtml()
{
    echo "</table>";
}