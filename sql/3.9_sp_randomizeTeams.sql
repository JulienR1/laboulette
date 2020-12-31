DROP PROCEDURE IF EXISTS RandomizeTeamOrder;

DELIMITER //
CREATE PROCEDURE RandomizeTeamOrder(IN currentGameId INT)
BEGIN

    DECLARE teamCount INT DEFAULT 2;
    DECLARE currentTeamIndex INT DEFAULT 0;

DROP TEMPORARY TABLE
IF EXISTS randomTeams;
CREATE TEMPORARY TABLE randomTeams
SELECT id
FROM teams
WHERE gameId = currentGameId
ORDER BY RAND();

SELECT COUNT(id)
INTO teamCount
FROM randomTeams;
WHILE currentTeamIndex < teamCount DO
UPDATE teams
        SET priority = currentTeamIndex
        WHERE id = (SELECT *
FROM (SELECT id
    FROM randomTeams LIMIT currentTeamIndex, 1)
AS A);

SET currentTeamIndex
= currentTeamIndex + 1;
END
WHILE;
    
    DROP TEMPORARY TABLE randomTeams;

END;
//
DELIMITER ;