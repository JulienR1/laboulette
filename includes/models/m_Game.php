<?php

class m_Game extends DatabaseHandler
{
    public function GetLastUpdateTime($lobbyId)
    {
        $sql = "SELECT lastModification FROM lobbies WHERE id=?";
        return parent::query($sql, $lobbyId);
    }

    public function DisconnectPlayer($playerId, $lobbyId)
    {
        $sql = "UPDATE players SET connected=FALSE, isHost=FALSE WHERE id=?";
        parent::query($sql, $playerId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

    public function AssignHost($lobbyId)
    {
        $sql = "CALL AssignHost(?)";
        parent::query($sql, $lobbyId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }
}