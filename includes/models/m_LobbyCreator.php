<?php

class m_LobbyCreator extends DatabaseHandler
{

    public static function GetHash()
    {
        $sql = "SELECT GenerateHash() AS 'hash'";
        return parent::query($sql);
    }

    public static function CreateLobby($hash, $password, $minWords)
    {
        $insertSql = "INSERT INTO lobbies (id, hash, password, minWords)
                VALUES (NULL, ?, ?, ?)";
        parent::query($insertSql, $hash, $password, $minWords);

        $selectSql = "SELECT id FROM lobbies WHERE hash=?";
        return parent::query($selectSql, $hash);
    }

    public static function AddHost($hostName, $lobbyId)
    {
        $sql = "INSERT INTO players (id, username, lobbyId, isHost)
                VALUES (NULL, ?, ?, TRUE)";
        parent::query($sql, $hostName, $lobbyId);

        $idSql = "SELECT * FROM players WHERE username=? AND lobbyId=?";
        return parent::query($idSql, $hostName, $lobbyId);
    }

}