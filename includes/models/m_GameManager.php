<?php

class m_GameManager extends DatabaseHandler
{

    public function ValidateTeamsForGame($gameId)
    {
        $sql = "SELECT teamId, COUNT(playerId) >= 2 AS enoughPlayers
                FROM (
                    SELECT A.id AS teamId, playerId
                    FROM (
                        SELECT id, teams.name
                        FROM teams
                        WHERE gameId = ?) AS A
                    LEFT JOIN playerstoteams ON teamId = A.id
                    LEFT JOIN players ON players.id = playerId AND connected = TRUE) AS B
                GROUP BY teamId";
        return parent::query($sql, $gameId);
    }

    public function Randomize($gameId)
    {
        $teamSql = "CALL RandomizeTeamOrder(?)";
        $playerSql = "CALL RandomizePlayerOrder(?)";
        parent::query($teamSql, $gameId);
        parent::query($playerSql, $gameId);
    }

    public function SetFirstTeamToPlay($gameId)
    {
        $sql = "UPDATE games
                SET roundNo = 0,
                teamIdToPlay = (
                    SELECT * FROM (
                        SELECT id
                        FROM teams
                        WHERE gameId = ?
                        ORDER BY priority
                        LIMIT 1)
                    AS A)
                WHERE id = ?";
        parent::query($sql, $gameId, $gameId);
    }

    public function UpdateGameState($gameId, $lobbyId)
    {
        $sql = "UPDATE games SET gameState=? WHERE id=?";
        parent::query($sql, GameStates::IN_GAME, $gameId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

    public function GetCurrentPlayer($gameId)
    {
        $sql = "SELECT id, username, teamId FROM players, playerstoteams WHERE id = GetCurrentPlayer(?) AND playerId = id";
        return parent::query($sql, $gameId);
    }

    public function GetGameMode($gameId)
    {
        $sql = "SELECT GetCurrentGameMode(?) AS gameMode";
        return parent::query($sql, $gameId);
    }

    public function StartTimer($gameId, $lobbyId)
    {
        $sql = "UPDATE games SET roundEndTime = DATE_ADD(NOW(), INTERVAL roundTimer SECOND), roundStarted = TRUE WHERE id = ?";
        parent::query($sql, $gameId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

    public function GetRoundComplete($gameId)
    {
        $sql = "SELECT NOW() > roundEndTime AND roundStarted = TRUE AS roundComplete FROM games WHERE id = ?";
        return parent::query($sql, $gameId);
    }

    public function SetNextTeamToPlay($gameId)
    {
        $sql = "CALL SetNextTeam(?)";
        parent::query($sql, $gameId);
    }

    public function NextRound($gameId, $lobbyId)
    {
        $sql = "UPDATE games SET roundNo = roundNo + 1, roundStarted = FALSE WHERE id = ?";
        parent::query($sql, $gameId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

    public function GetRandomAvailableWord($gameId, $currentGameMode)
    {
        $sql = "SELECT id, word FROM words, (SELECT GetRandomWordId(?, ?) AS randId) AS A WHERE words.id = randId";
        return parent::query($sql, $gameId, $currentGameMode);
    }

    public function SetWordAsPicked($gameId, $wordId, $playerId, $lobbyId)
    {
        $sql = "INSERT INTO pickedwords (id, wordId, gameId, playerId) VALUES (NULL, ?, ?, ?)";
        parent::query($sql, $wordId, $gameId, $playerId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

    public function GetGameId($lobbyId)
    {
        $sql = "SELECT id FROM games WHERE games.lobbyId = ?";
        return parent::query($sql, $lobbyId);
    }

    public function SendUpdate($lobbyId)
    {
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

}