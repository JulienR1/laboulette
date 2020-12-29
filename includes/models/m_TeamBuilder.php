<?php

class m_TeamBuilder extends DatabaseHandler
{

    public function GetGameSettingsValidation($lobbyId)
    {
        $sql = "SELECT ValidateGameSettings(?) AS validGame";
        return parent::query($sql, $lobbyId);
    }

    public function GenerateRandomTeams($lobbyId)
    {
        $sql = "CALL AssignRandomlyToTeams(?)";
        parent::query($sql, $lobbyId);
    }

    public function SetGameToTeamBuilding($lobbyId)
    {
        $sql = "UPDATE games SET gameState = " . GameStates::TEAM_BUILDING . " WHERE lobbyId = ? AND gameState = " . GameStates::LOBBY;
        parent::query($sql, $lobbyId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

}