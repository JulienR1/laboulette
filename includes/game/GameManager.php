<?php

class GameManager
{

    public function Start()
    {
        if ($_SESSION["isHost"]) {
            if ($_SESSION["lastGameState"] == GameStates::TEAM_BUILDING) {
                $model = new m_GameManager();
                $enoughPlayers = $model->ValidateTeamsForGame($_SESSION["gameId"]);
                foreach ($enoughPlayers as $specificTeamIsValid) {
                    if (!$specificTeamIsValid["enoughPlayers"]) {
                        return Errors::INVALID_TEAMS;
                    }
                }
                $model->Randomize($_SESSION["gameId"]);
                $model->UpdateGameState($_SESSION["gameId"], $_SESSION["lobbyId"]);
            } else {
                Errors::INVALID_LOBBY;
            }
        } else {
            return Errors::UNAUTHORIZED_ACCESS;
        }

        return Errors::NO_ERROR;
    }

}