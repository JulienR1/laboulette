<?php

class m_LobbyJoiner extends DatabaseHandler
{

    public static function GetLobbyData($lobbyHash)
    {
        $sql = "SELECT * FROM lobbies WHERE hash=?";
        return parent::query($sql, $lobbyHash);
    }

    public static function IsUsernameFree($username, $lobbyId)
    {
        $sql = "SELECT id FROM players WHERE lobbyId = ? AND username = ?";
        return parent::query($sql, $lobbyId, $username) === null;
    }

    public static function AddPlayer($playerName, $lobbyId)
    {
        $sql = "INSERT INTO players (id, username, lobbyId, isHost, connected)
                VALUES (NULL, ?, ?, FALSE, TRUE)";
        parent::query($sql, $playerName, $lobbyId);

        parent::query(Settings::UPDATE_SQL, $lobbyId);

        $playerInfoSql = "SELECT * FROM players WHERE username=? AND lobbyId=?";
        return parent::query($playerInfoSql, $playerName, $lobbyId);
    }

}