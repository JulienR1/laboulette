<?php

class GameWindow extends Controller
{
    private static $model = null;
    public static $lobbyData = array();

    private static $gameData = array();

    public static function Build()
    {
        parent::$jsFiles[] = "gameloop.js";
        parent::$jsFiles[] = "wordForm.js";
        parent::$jsFiles[] = "settingsForm.js";
        parent::$jsFiles[] = "disconnect.js";
        parent::$jsFiles[] = "teamBuilder.js";
        parent::CreateView("gameWindow");

        $_SESSION["lastUpdateTime"] = null;
        $_SESSION["lobbyData"] = array();
        $_SESSION["wasHost"] = false;
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

        $lobbyId = $_SESSION["lobbyId"];

        if (self::UpdatesWereMade()) {
            self::DownloadPlayerData();
            self::DownloadCurrentGameData($lobbyId);

            if (self::IsInGame()) {
                // -- AVANT (HOST)
                // ---- generation des equipes
                // ---- ecran pour faire les modifications des equipes
                // ---- bouton pour go final
                // -- APRES
                // ---- JEU!
            } else {
                if (self::$gameData === null) {
                    self::CreateGame($lobbyId);
                }
                $updatePlayers = self::DetermineIfNeedsUpdate("connectedPlayers", self::$model->GetConnectedPlayers($lobbyId));
                $updateWordStats = self::DetermineIfNeedsUpdate("wordStats", self::$model->GetWordStats($lobbyId)[0]);
                $updateGameSettings = self::DetermineIfNeedsUpdate("gameSettings", self::$model->GetGameSettings($lobbyId)[0]);
                $updateStartButton = true;
            }
        }

        $updatedSections = array();
        if (sizeof(self::$lobbyData) > 0) {
            if ($updatePlayers) {
                $updatedSections["connectedPlayers"] = self::GetViewHTML("includes/gameviews/v_connectedPlayers.php");
            }
            if ($updateWordStats) {
                $updatedSections["wordStats"] = self::GetViewHTML("includes/gameviews/v_wordStats.php");
            }

            if ($updateGameSettings) {
                if ($_SESSION["isHost"]) {
                    if (!$_SESSION["wasHost"]) {
                        $updatedSections["gameSettings"] = self::GetViewHTML("includes/gameviews/v_settingsFormHost.php");
                    }
                } else {
                    $updatedSections["gameSettings"] = self::GetViewHTML("includes/gameviews/v_settingsFormUser.php");
                }
            }

            if ($updateStartButton) {
                if ($_SESSION["isHost"]) {
                    if (!$_SESSION["wasHost"]) {
                        $updatedSections["startButton"] = self::GetViewHTML("includes/gameviews/v_startGameButton.php");
                    }
                } else {
                    $updatedSections["startButton"] = self::GetViewHTML("includes/gameviews/v_waitingForStart.php");
                }
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

    private static function IsInGame()
    {
        if (self::$gameData !== null) {
            if (self::$gameData["lobbyId"] == $_SESSION["lobbyId"]) {
                if (self::$gameData["gameState"] > 0) {
                    return true;
                }
            }
        }
        return false;
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

}