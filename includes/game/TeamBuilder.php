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

    public function UpdateTeams()
    {
        if (isset($_GET["teams"]) && !empty($_GET["teams"])) {
            $teams = json_decode($_GET["teams"]);
            $sanitizedTeams = array();
            foreach ($teams as $teamId => $teamData) {
                $teamId = intval($teamId);
                $teamData = array_map("intval", $teamData);
                $sanitizedTeams[$teamId] = $teamData;
            }

            $playerIdsArray = array();
            foreach ($sanitizedTeams as $team) {
                foreach ($team as $player) {
                    $playerIdsArray[] = $player;
                }
            }
            $playerIds = implode(",", $playerIdsArray);
            $teamIds = implode(",", array_keys($sanitizedTeams));

            $model = new m_TeamBuilder();
            $teamsAreValid = $model->ValidateTeams($_SESSION["gameId"], $teamIds)[0]["teamsAreValid"];
            $playersAreValid = $model->ValidatePlayers($_SESSION["gameId"], $playerIds)[0]["playersAreValid"];

            if ($teamsAreValid && $playersAreValid) {
                foreach ($sanitizedTeams as $teamId => $team) {
                    $playerIdsArray = array();
                    foreach ($team as $player) {
                        $playerIdsArray[] = $player;
                    }
                    $playerIds = implode(",", $playerIdsArray);
                    $model->SetTeamForPlayers($_SESSION["gameId"], $teamId, $playerIds);
                }
                $model->RegisterUpdate($_SESSION["lobbyId"]);
            } else {
                return Errors::INVALID_FIELD_TYPE;
            }
        } else {
            return Errors::EMPTY_FIELDS;
        }
        return Errors::NO_ERROR;
    }
}