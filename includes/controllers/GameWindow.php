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
        parent::$jsFiles[] = "disconnect.js";
        parent::CreateView("gameWindow");
    }

    public static function BuildGame()
    {
        self::$lobbyData = isset($_SESSION["lobbyData"]) ? $_SESSION["lobbyData"] : array();
        self::$model = new m_Game();

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
                self::$lobbyData["connectedPlayers"] = self::$model->GetConnectedPlayers($_SESSION["lobbyId"]);
                // ---- afficher nb de mots ajoutes
                self::$lobbyData["wordStats"] = self::$model->GetWordStats($_SESSION["lobbyId"])[0];

                // ---- afficher formulaire pour ajouter des mots
                // ---- voir settings de la partie
                // ---- message qui dit quil faut attendre apres le host
                // -- pour HOST:
                // ---- menu pour changer les settings de la partie
                // ---- bouton pour commencer la partie
            }
        }

        if (sizeof(self::$lobbyData) > 0) {

            $updatedSections = array();
            $updatedSections["connectedPlayers"] = self::GetViewHTML("includes/game/v_connectedPlayers.php");
            $updatedSections["wordStats"] = self::GetViewHTML("includes/game/v_wordStats.php");
            echo json_encode($updatedSections);

            $_SESSION["lobbyData"] = self::$lobbyData;
        }
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

    private static function GetViewHTML($file)
    {
        ob_start();
        include $file;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}