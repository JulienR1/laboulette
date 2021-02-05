DROP PROCEDURE IF EXISTS RandomizePlayerOrder;

DELIMITER //
CREATE PROCEDURE RandomizePlayerOrder(IN currentGameId INT)
BEGIN

    DECLARE playerCount INT DEFAULT 0;
    DECLARE currentPlayerId INT DEFAULT 0;
DECLARE currentPlayerIndex INT DEFAULT 0;
DECLARE recordExist BOOLEAN DEFAULT FALSE;

DROP TEMPORARY TABLE
IF EXISTS randomPlayers;
CREATE TEMPORARY TABLE randomPlayers
SELECT playerId AS id
FROM players
    JOIN playerstoteams ON players.id = playerId
    JOIN teams ON teams.id = teamId AND gameId = currentGameId
ORDER BY RAND();

SELECT COUNT(id)
INTO playerCount
FROM randomPlayers;

WHILE currentPlayerIndex < playerCount DO
SELECT id
INTO currentPlayerId
FROM randomPlayers LIMIT
currentPlayerIndex, 1;

SELECT NOT COUNT
(playerId) = 0 INTO recordExist FROM playerorders WHERE gameId = currentGameId AND playerId = currentPlayerId;

IF recordExist THEN
UPDATE playerorders
    SET priority = currentPlayerIndex
    WHERE playerId = currentPlayerId AND gameId = currentGameId;
ELSE
INSERT INTO playerorders
    (gameId, playerId, priority)
VALUES
    (currentGameId, currentPlayerId, currentPlayerIndex);
END
IF;

SET currentPlayerIndex
= currentPlayerIndex + 1;
END
WHILE;
	
    DROP TEMPORARY TABLE randomPlayers;

END;
//
DELIMITER ;