<?php

class m_GameSettings extends DatabaseHandler
{

    public function UpdateGameSettings($timer, $teamCount, $gameId, $lobbyId)
    {
        $sql = "UPDATE games SET roundTimer=?, teamCount=? WHERE id=?";
        parent::query($sql, $timer, $teamCount, $gameId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }
}