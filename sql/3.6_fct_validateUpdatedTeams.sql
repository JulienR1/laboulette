DROP FUNCTION IF EXISTS ValidateUpdatedTeams;

DELIMITER //
CREATE FUNCTION ValidateUpdatedTeams(currentGameId INT, teamsToUpdate TINYTEXT)
RETURNS BOOLEAN
DETERMINISTIC
BEGIN

    DECLARE targetTeamCount INT DEFAULT 2;
    DECLARE actualTeamCount INT DEFAULT -1;
DECLARE passedTeamCount INT DEFAULT -1;


SELECT teamCount
INTO targetTeamCount
FROM games
WHERE id=currentGameId;

SELECT LENGTH(teamsToUpdate) - LENGTH(REPLACE(teamsToUpdate, ",", "")) + 1
INTO passedTeamCount;

IF NOT passedTeamCount = targetTeamCount THEN
RETURN FALSE;
END
IF;
    
SELECT MAX(FIND_IN_SET(id, teamsToUpdate))
INTO actualTeamCount
FROM teams
WHERE gameId=currentGameId;

RETURN actualTeamCount
= targetTeamCount;

END;
//
DELIMITER ;