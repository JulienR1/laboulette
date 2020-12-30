<div id="touchControl" disabled>
    <h3>Choisir la nouvelle équipe pour <span>NOM DU JOUEUR</span></h3>
    <ul>
        <?php
$lastTeamId = -1;
foreach ($teamData as $teamPlayer) {
    if ($teamPlayer["teamId"] !== $lastTeamId) {
        echo '<li teamid="' . $teamPlayer["teamId"] . '">' . $teamPlayer["name"] . "</li>";
        $lastTeamId = $teamPlayer["teamId"];
    }
}
?>
    </ul>
    <button onclick="cancelTeamChange()">Annuler</button>
</div>