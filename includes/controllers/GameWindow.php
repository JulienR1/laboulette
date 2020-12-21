<?php

class GameWindow extends Controller
{
    private static $lastUpdateTime = null;
    private static $model = null;

    public static function Build()
    {
        parent::$jsFiles[] = "gameloop.js";
        parent::$jsFiles[] = "disconnect.js";
        parent::CreateView("gameWindow");
    }

    public static function BuildGame()
    {
        self::$model = new m_Game();

        if (self::UpdatesWereMade()) {
            // si game pas commencee:
            // -- pour TOUS:
            // ---- afficher joueurs connectes
            // ---- afficher nb de mots ajoutes
            // ---- afficher formulaire pour ajouter des mots
            // ---- voir settings de la partie
            // ---- message qui dit quil faut attendre apres le host
            // -- pour HOST:
            // ---- menu pour changer les settings de la partie
            // ---- bouton pour commencer la partie

            // si game commencee
            // -- AVANT (HOST)
            // ---- generation des equipes
            // ---- ecran pour faire les modifications des equipes
            // ---- bouton pour go final
            // -- APRES
            // ---- JEU!
        }

        require_once "includes/views/gameWindow.php";
    }

    private static function UpdatesWereMade()
    {
        $updateTimeTable = self::$model->GetLastUpdateTime($_SESSION["lobbyId"]);
        if ($updateTimeTable !== null) {
            $newUpdateTime = strtotime($updateTimeTable[0]["lastModification"]);
            if (self::$lastUpdateTime < $newUpdateTime) {
                self::$lastUpdateTime = $newUpdateTime;
                return true;
            }
        }
        return false;
    }

}