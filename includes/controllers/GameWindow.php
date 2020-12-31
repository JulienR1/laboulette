<?php

class GameWindow extends Controller
{
    private static $model = null;
    public static $lobbyData = array();

    private static $gameData = array();
    private static $currentPlayer = null;
    private static $currentGameMode = null;

    public static function Build()
    {
        parent::$jsFiles[] = "gameloop.js";
        parent::$jsFiles[] = "wordForm.js";
        parent::$jsFiles[] = "settingsForm.js";
        parent::$jsFiles[] = "disconnect.js";
        parent::$jsFiles[] = "teamBuilder.js";
        parent::$jsFiles[] = "game.js";
        parent::CreateView("gameWindow");

        $_SESSION["lastUpdateTime"] = null;
        $_SESSION["lobbyData"] = array();
        $_SESSION["wasHost"] = false;
        $_SESSION["lastGameState"] = GameStates::LOBBY;
        unset($_SESSION["gameId"]);
    }

    public static function BuildGame()
    {
        self::$lobbyData = isset($_SESSION["lobbyData"]) ? $_SESSION["lobbyData"] : array();
        self::$model = new m_Game();

        $updatePlayers = false;
        $updateWordStats = false;
        $updateGameSettings = false;
        $updateStartButton = false;
        $updateTeams = false;
        $updatePlayButton = false;

        $updatePlayerLayout = false;
        $updateGuessLayout = false;
        $updateWaitLayout = false;

        $updateTimer = false;

        $lobbyId = $_SESSION["lobbyId"];
        $updatedSections = array();

        $game = new m_GameManager();
        $tempGameId = $game->GetGameId($lobbyId)[0]["id"];
        $roundIsComplete = $game->GetRoundComplete($tempGameId)[0]["roundComplete"];

        if (self::UpdatesWereMade() || $roundIsComplete) {
            self::DownloadPlayerData();
            self::DownloadCurrentGameData($lobbyId);

            $gameState = self::GetGameState();

            switch ($gameState) {
                case GameStates::LOBBY:
                    if (self::$gameData === null) {
                        self::CreateGame($lobbyId);
                    }
                    $updatePlayers = self::DetermineIfNeedsUpdate("connectedPlayers", self::$model->GetConnectedPlayers($lobbyId));
                    $updateWordStats = self::DetermineIfNeedsUpdate("wordStats", self::$model->GetWordStats($lobbyId)[0]);
                    $updateGameSettings = self::DetermineIfNeedsUpdate("gameSettings", self::$model->GetGameSettings($lobbyId)[0]);
                    $updateStartButton = true;
                    break;
                case GameStates::TEAM_BUILDING:
                    $updateTeams = self::DetermineIfNeedsUpdate("teams", self::$model->GetTeams(self::$gameData["id"]));
                    $_SESSION["teamId"] = self::$model->GetPlayerTeam($_SESSION["userId"], $_SESSION["gameId"])[0]["teamId"];
                    $updatePlayButton = true;
                    break;
                case GameStates::IN_GAME:
                    $gameId = $_SESSION["gameId"];
                    self::$currentPlayer = $game->GetCurrentPlayer($gameId)[0];
                    self::$currentGameMode = $game->GetGameMode($gameId)[0]["gameMode"];

                    if (self::$currentGameMode === 5) {
                        // game over
                    }

                    if (self::$currentPlayer["id"] === $_SESSION["userId"]) {
                        $updatePlayerLayout = true;
                        if ($roundIsComplete) {
                            $game->SetNextTeamToPlay($gameId);
                            $game->NextRound($gameId, $lobbyId);
                        }
                    } else if (self::$currentPlayer["teamId"] == $_SESSION["teamId"]) {
                        $updateGuessLayout = true; // check if new word to guess (scored animation) (retaltime score update?)
                    } else {
                        $updateWaitLayout = true; // check if new word to not guess (scored animation) (realtime score update?)
                    }

                    $updateTimer = self::DetermineIfNeedsUpdate("timer", self::$gameData["roundEndTime"]);
                    break;
                case GameStates::ERROR:
                    return;
            }

            if ($gameState != $_SESSION["lastGameState"]) {
                $updatedSections = self::ClearAllSections($updatedSections);
            }
            $_SESSION["lastGameState"] = $gameState;
        }

        if (sizeof(self::$lobbyData) > 0) {
            if ($updatePlayers) {
                $updatedSections["connectedPlayers"] = self::GetViewHTML("includes/gameviews/all/v_connectedPlayers.php");
            }
            if ($updateWordStats) {
                $updatedSections["wordStats"] = self::GetViewHTML("includes/gameviews/all/v_wordStats.php");
            }

            if ($updateGameSettings) {
                if ($_SESSION["isHost"]) {
                    if (!$_SESSION["wasHost"]) {
                        $updatedSections["gameSettings"] = self::GetViewHTML("includes/gameviews/host/v_settingsEdition.php");
                    }
                } else {
                    $updatedSections["gameSettings"] = self::GetViewHTML("includes/gameviews/guests/v_settings.php");
                }
            }

            if ($updateStartButton) {
                if ($_SESSION["isHost"]) {
                    if (!$_SESSION["wasHost"]) {
                        $updatedSections["startButton"] = self::GetViewHTML("includes/gameviews/host/v_startGameButton.php");
                    }
                } else {
                    $updatedSections["startButton"] = self::GetViewHTML("includes/gameviews/guests/v_waitingForStart.php");
                }
            }

            if ($updateTeams) {
                if ($_SESSION["isHost"]) {
                    if (!$_SESSION["wasHost"] || true) {
                        $updatedSections["teams"] = self::GetViewHTML("includes/gameviews/host/v_teamEdition.php");
                    }
                } else {
                    $updatedSections["teams"] = self::GetViewHTML("includes/gameviews/guests/v_teams.php");
                }
            }

            if ($updatePlayButton) {
                if ($_SESSION["isHost"]) {
                    if (!$_SESSION["wasHost"]) {
                        $updatedSections["startButton"] = self::GetViewHTML("includes/gameviews/host/v_playGameButton.php");
                    }
                } else {
                    $updatedSections["startButton"] = self::GetViewHTML("includes/gameviews/guests/v_waitingForStart.php");
                }
            }

            if ($updatePlayerLayout) {
                $updatedSections["extraInfos"] = "";
                $updatedSections["controls"] = self::GetViewHTML("includes/gameviews/ingame/v_player.php");
                if (isset($_SESSION["randomWord"]) && $_SESSION["randomWord"] !== null) {
                    $updatedSections["word"] = self::GetViewHTML("includes/gameviews/ingame/v_word.php");
                }
            }

            if ($updateGuessLayout) {
                $updatedSections["extraInfos"] = self::GetViewHTML("includes/gameviews/ingame/v_guessers.php");
                $updatedSections["controls"] = "";
                $updatedSections["word"] = "";
            }

            if ($updateWaitLayout) {
                $updatedSections["extraInfos"] = self::GetViewHTML("includes/gameviews/ingame/v_otherTeams.php");
                $updatedSections["controls"] = "";
                $updatedSections["word"] = "";
            }

            if ($updateTimer) {
                $updatedSections["timer"] = self::GetViewHTML("includes/gameviews/ingame/v_timer.php");
            }

            $_SESSION["lobbyData"] = self::$lobbyData;

            if ($_SESSION["isHost"]) {
                $_SESSION["wasHost"] = true;
            }
        }
        return $updatedSections;
    }

    private static function UpdatesWereMade()
    {
        $updateTimeTable = self::$model->GetLastUpdateTime($_SESSION["lobbyId"]);
        if ($updateTimeTable !== null) {
            $newUpdateTime = strtotime($updateTimeTable[0]["lastModification"]);
            if (!isset($_SESSION["lastUpdateTime"]) || $_SESSION["lastUpdateTime"] < $newUpdateTime) {
                $_SESSION["lastUpdateTime"] = $newUpdateTime;
                return true;
            }
        }
        return false;
    }

    private static function DownloadCurrentGameData($lobbyId)
    {
        self::$gameData = self::$model->GetCurrentGame($lobbyId);
        if (self::$gameData !== null) {
            self::$gameData = self::$gameData[0];
            $_SESSION["gameId"] = self::$gameData["id"];
        }
    }

    private static function DownloadPlayerData()
    {
        $playerInfo = self::$model->GetPlayerData($_SESSION["userId"]);
        if ($playerInfo !== null) {
            $playerInfo = $playerInfo[0];
            $_SESSION["username"] = $playerInfo["username"];
            $_SESSION["isHost"] = $playerInfo["isHost"];
        }
    }

    private static function GetGameState()
    {
        if (self::$gameData !== null) {
            if (self::$gameData["lobbyId"] == $_SESSION["lobbyId"]) {
                return self::$gameData["gameState"];
            }
        } else {
            return GameStates::LOBBY;
        }
        return GameStates::ERROR;
    }

    private static function CreateGame($lobbyId)
    {
        self::$model->CreateGame($lobbyId);
        self::DownloadCurrentGameData($lobbyId);
    }

    private static function DetermineIfNeedsUpdate($sectionName, $latestData)
    {
        $previous = isset(self::$lobbyData[$sectionName]) ? self::$lobbyData[$sectionName] : array();
        self::$lobbyData[$sectionName] = $latestData;
        return self::$lobbyData[$sectionName] != $previous;
    }

    private static function GetViewHTML($file)
    {
        ob_start();
        include $file;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    private static function ClearAllSections($sectionsHtml)
    {
        $sectionsHtml["connectedPlayers"] = "";
        $sectionsHtml["wordStats"] = "";
        $sectionsHtml["wordForm"] = "";
        $sectionsHtml["gameSettings"] = "";
        $sectionsHtml["startButton"] = "";
        $sectionsHtml["teams"] = "";
        return $sectionsHtml;
    }

}