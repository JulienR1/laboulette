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
        parent::CreateView("gameWindow");

        $_SESSION["lastUpdateTime"] = null;
        $_SESSION["lobbyData"] = array();
    }

    public static function BuildGame()
    {
        self::$lobbyData = isset($_SESSION["lobbyData"]) ? $_SESSION["lobbyData"] : array();
        self::$model = new m_Game();

        $updatePlayers = false;
        $updateWordStats = false;
        $updateGameSettings = false;

        $lobbyId = $_SESSION["lobbyId"];

        if (self::UpdatesWereMade()) {
            if (self::IsInGame()) {
                // -- AVANT (HOST)
                // ---- generation des equipes
                // ---- ecran pour faire les modifications des equipes
                // ---- bouton pour go final
                // -- APRES
                // ---- JEU!
            } else {
                // -- pour TOUS:
                // ---- afficher joueurs connectes
                $updatePlayers = self::DetermineIfNeedsUpdate("connectedPlayers", self::$model->GetConnectedPlayers($lobbyId));
                // ---- afficher nb de mots ajoutes
                $updateWordStats = self::DetermineIfNeedsUpdate("wordStats", self::$model->GetWordStats($lobbyId)[0]);

                // ---- afficher formulaire pour ajouter des mots
                // ---- voir settings de la partie
                $updateGameSettings = true; //self::DetermineIfNeedsUpdate("gameSettings", self::$model->GetGameSettings($lobbyId)[0]);

                // ---- message qui dit quil faut attendre apres le host
                // -- pour HOST:
                // ---- menu pour changer les settings de la partie
                // ---- bouton pour commencer la partie
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
                    // TODO: check if new host
                    $updatedSections["gameSettings"] = self::GetViewHTML("includes/gameviews/v_settingsFormHost.php");
                } else {
                    $updatedSections["gameSettings"] = self::GetViewHTML("includes/gameviews/v_settingsFormUser.php");
                }
            }

            $_SESSION["lobbyData"] = self::$lobbyData;
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

    private static function IsInGame()
    {
        self::$gameData = self::$model->GetCurrentGame($_SESSION["lobbyId"]);
        return self::$gameData !== null;
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