<?php

class m_Game extends DatabaseHandler
{
    public function GetLastUpdateTime($lobbyId)
    {
        $sql = "SELECT lastModification FROM lobbies WHERE id=?";
        return parent::query($sql, $lobbyId);
    }

    public function GetCurrentGame($lobbyId)
    {
        $sql = "SELECT * FROM games WHERE lobbyId=? AND gameOver=FALSE";
        return parent::query($sql, $lobbyId);
    }

    public function GetConnectedPlayers($lobbyId)
    {
        $sql = "SELECT id, username, isHost FROM players WHERE lobbyId=? AND connected=TRUE";
        return parent::query($sql, $lobbyId);
    }

    public function GetWordStats($lobbyId)
    {
        $sql = "SELECT minWords, MAX(wordCount) AS wordCount FROM
                (SELECT id AS lobbyId, minWords, 0 AS wordCount FROM lobbies
                UNION ALL
                SELECT lobbyId, 0 AS minWords, COUNT(word) AS wordCount FROM words
                GROUP BY lobbyId) AS A
                WHERE lobbyId = ?
                GROUP BY lobbyId";
        return parent::query($sql, $lobbyId);
    }

    public function GetGameSettings($lobbyId)
    {
        return null;
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