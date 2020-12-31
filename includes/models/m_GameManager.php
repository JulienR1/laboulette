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

    public function UpdateGameState($gameId, $lobbyId)
    {
        $sql = "UPDATE games SET gameState=? WHERE id=?";
        parent::query($sql, GameStates::IN_GAME, $gameId);
        parent::query(Settings::UPDATE_SQL, $lobbyId);
    }

}