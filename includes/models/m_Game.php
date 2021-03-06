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
        $sql = "SELECT * FROM games WHERE lobbyId=? ORDER BY startTime DESC LIMIT 1";
        return parent::query($sql, $lobbyId);
    }

    public function CreateGame($lobbyId)
    {
        $sql = "INSERT INTO games (id, lobbyId) VALUES (NULL, ?)";
        parent::query($sql, $lobbyId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
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
        $sql = "SELECT roundTimer, teamCount FROM games WHERE lobbyId = ?";
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

    public function GetPlayerData($playerId)
    {
        $sql = "SELECT username, isHost FROM players WHERE id=?";
        return parent::query($sql, $playerId);
    }

    public function GetTeams($gameId)
    {
        $sql = "SELECT A.id AS teamId, A.name, playerId, username
                FROM (
                    SELECT id, teams.name
                    FROM teams
                    WHERE gameId = ?) AS A
                LEFT JOIN playerstoteams ON teamId = A.id
                LEFT JOIN players ON players.id = playerId AND connected = TRUE";
        return parent::query($sql, $gameId);
    }

    public function GetPlayerTeam($playerId, $gameId)
    {
        $sql = "SELECT teamId FROM players
                JOIN playerstoteams ON players.id = playerId AND playerId = ?
                JOIN teams ON teams.id = teamId AND gameId = ?";
        return parent::query($sql, $playerId, $gameId);
    }
}