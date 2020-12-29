<?php

class Game extends Controller
{
    public static function Dispatch()
    {
        session_start();
        if (isset($_GET["cmd"])) {
            $cmd = $_GET["cmd"];

            switch ($cmd) {
                case "disconnect":
                    self::Disconnect();
                    break;
                case "build":
                    $sectionsHTML = GameWindow::BuildGame();
                    echo json_encode($sectionsHTML);
                    break;
                case "newWord":
                    $saveError = WordManager::RecordWord();
                    echo $saveError;
                    break;
                case "updateSettings":
                    $saveError = SettingsManager::RecordSettings();
                    echo $saveError;
                    break;
                case "beginTeamBuilding":
                    $processError = TeamBuilder::LoadBuilder();
                    echo $processError;
                    break;
            }
        }
    }

    private static function Disconnect()
    {
        $userId = $_SESSION["userId"];
        $lobbyId = $_SESSION["lobbyId"];
        $isHost = $_SESSION["isHost"];
        session_destroy();

        $model = new m_Game();
        $model->DisconnectPlayer($userId, $lobbyId);
        if ($isHost) {
            $model->AssignHost($lobbyId);
        }
    }
}