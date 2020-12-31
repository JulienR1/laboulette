DROP PROCEDURE IF EXISTS SetNextTeam;

DELIMITER //
CREATE PROCEDURE SetNextTeam(IN currentGameId INT)
BEGIN

    DECLARE newTeamId INT DEFAULT 0;

    SELECT id
    INTO newTeamId
    FROM teams,
        (SELECT MOD(priority + 1, teamCount) AS targetPriority, games.id AS gameId
        FROM teams, games
        WHERE teams.id = teamIdToPlay AND games.id = currentGameId)
	AS A
    WHERE teams.gameId = A.gameId AND priority = targetPriority;

    UPDATE games
    SET teamIdToPlay = newTeamId
    WHERE id = currentGameId;

END;
//
DELIMITER ;