<?php

class TeamBuilder
{
    public function LoadBuilder()
    {
        if ($_SESSION["isHost"]) {
            $model = new m_Game();
            $gameData = $model->GetCurrentGame($_SESSION["lobbyId"]);
            if ($gameData !== null) {
                if ($gameData[0]["gameState"] !== GameStates::LOBBY) {
                    $model = new m_TeamBuilder();
                    $gameIsValid = $model->GetGameSettingsValidation($_SESSION["lobbyId"])[0]["validGame"];
                    if ($gameIsValid) {
                        $model->GenerateRandomTeams($_SESSION["lobbyId"]);
                        $model->SetGameToTeamBuilding($_SESSION["lobbyId"]);
                    } else {
                        return Errors::GAME_NOT_PROPERLY_SET;
                    }
                } else {
                    return Errors::GAME_STARTED;
                }
            } else {
                return Errors::INVALID_LOBBY;
            }
        } else {
            return Errors::UNAUTHORIZED_ACCESS;
        }
        return Errors::NO_ERROR;
    }
}