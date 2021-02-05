DROP PROCEDURE IF EXISTS AssignRandomlyToTeams;
DELIMITER //

CREATE PROCEDURE AssignRandomlyToTeams(
IN currentLobbyId INT
)
BEGIN

    DECLARE teamCount INT DEFAULT 0;
    DECLARE gameId INT DEFAULT 0;
DECLARE playerCount INT DEFAULT 0;
DECLARE assignedCount INT DEFAULT 0;

DECLARE needToCreateTeams BOOLEAN DEFAULT TRUE;

DECLARE i INT DEFAULT 0;
DECLARE currentTeamId INT DEFAULT 0;
DECLARE currentTeamIndex INT DEFAULT 0;

SELECT games.teamCount, id
INTO teamCount
, gameId FROM games WHERE lobbyId = currentLobbyId AND gameState = 0;
SELECT NOT EXISTS
(SELECT *
FROM teams
WHERE teams.gameId = gameId)
AS teamsAreCreated INTO needToCreateTeams;
IF needToCreateTeams = 1 THEN
SET i
= 0;
WHILE i < teamCount DO
INSERT INTO teams
    (id, name, gameId, priority)
VALUES
    (NULL, CONCAT("Équipe ", i+1), gameId, i);
SET i
= i + 1;
END
WHILE;
END
IF;

DROP TEMPORARY TABLE
IF EXISTS teamIds;
CREATE TEMPORARY TABLE teamIds AS
(
	SELECT id
FROM teams
WHERE teams.gameId = gameId
);

SELECT COUNT(id)
INTO playerCount
FROM players
WHERE lobbyId = currentLobbyId AND connected = TRUE;
WHILE assignedCount < playerCount DO
SET currentTeamIndex
= MOD
(assignedCount, teamCount);
SELECT id
INTO currentTeamId
FROM teamIds LIMIT
currentTeamIndex, 1;
INSERT INTO playerstoteams
    (playerId, teamId)
VALUES
    (GetRandomAvailablePlayer(currentLobbyId), currentTeamId);
SET assignedCount
= assignedCount + 1;
END
WHILE;

DROP TEMPORARY TABLE
IF EXISTS teamIds;

END;
//
DELIMITER ;