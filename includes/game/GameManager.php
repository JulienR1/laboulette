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
                $model->SetFirstTeamToPlay($_SESSION["gameId"]);
                $model->UpdateGameState($_SESSION["gameId"], $_SESSION["lobbyId"]);
            } else {
                Errors::INVALID_LOBBY;
            }
        } else {
            return Errors::UNAUTHORIZED_ACCESS;
        }

        return Errors::NO_ERROR;
    }

    public function StartTimer()
    {
        $model = new m_GameManager();
        $currentPlayer = $model->GetCurrentPlayer($_SESSION["gameId"])[0];

        if ($_SESSION["userId"] === $currentPlayer["id"]) {
            $_SESSION["currentGameMode"] = $model->GetGameMode($_SESSION["gameId"])[0]["gameMode"];
            self::GetWord(false);
            $model->StartTimer($_SESSION["gameId"], $_SESSION["lobbyId"]);
        } else {
            return Errors::UNAUTHORIZED_ACCESS;
        }
        return Errors::NO_ERROR;
    }

    public function RequestWord()
    {
        if (isset($_GET["found"]) && !empty($_GET["found"])) {
            $foundWord = $_GET["found"];
            if ($foundWord === "false" || $foundWord === "true") {
                $foundWord = $foundWord === "true" ? true : false;
                return self::GetWord($foundWord);
            } else {
                return Errors::INVALID_FIELD_TYPE;
            }
        } else {
            return Errors::EMPTY_FIELDS;
        }
        return Errors::NO_ERROR;
    }

    public function GetWord($found)
    {
        $model = new m_GameManager();
        $currentPlayer = $model->GetCurrentPlayer($_SESSION["gameId"])[0];
        if ($_SESSION["userId"] === $currentPlayer["id"]) {
            if ($found) {
                if (isset($_SESSION["randomWord"]) && $_SESSION["randomWord"] !== null) {
                    $model->SetWordAsPicked($_SESSION["gameId"], $_SESSION["randomWord"]["id"], $currentPlayer["id"], $_SESSION["lobbyId"]);
                    $_SESSION["randomWord"] = null;
                }
            }

            $randomWord = $model->GetRandomAvailableWord($_SESSION["gameId"], $_SESSION["currentGameMode"]);

            if ($randomWord !== null) {
                $_SESSION["randomWord"] = $randomWord[0];
            } else {
                // FIN DU ROUND
            }
            $model->SendUpdate($_SESSION["lobbyId"]);
        } else {
            return Errors::UNAUTHORIZED_ACCESS;
        }
        return Errors::NO_ERROR;
    }

}