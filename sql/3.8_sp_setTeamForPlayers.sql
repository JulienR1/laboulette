SET SQL_SAFE_UPDATES
= 0;

DROP PROCEDURE IF EXISTS SetTeamForPlayers;

DELIMITER //
CREATE PROCEDURE SetTeamForPlayers(IN currentGameId INT, IN newTeamId INT, IN playerIds TINYTEXT)
BEGIN
  UPDATE playerstoteams
    SET teamId = newTeamId
    WHERE playerId IN
		(SELECT *
  FROM
    (SELECT playerId
    FROM playerstoteams
    WHERE teamId IN (SELECT teams.id
      FROM teams
      WHERE teams.gameId = currentGameId) AND
      FIND_IN_SET(playerId, playerIds) > 0) AS A);
END;
//
DELIMITER ;