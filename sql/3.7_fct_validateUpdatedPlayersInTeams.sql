DROP FUNCTION IF EXISTS ValidateUpdatedPlayersInTeams;

DELIMITER //
CREATE FUNCTION ValidateUpdatedPlayersInTeams(currentGameId INT, playersToUpdate TINYTEXT)
RETURNS BOOLEAN
DETERMINISTIC
BEGIN

    DECLARE targetPlayerCount INT DEFAULT 0;
    DECLARE actualPlayerCount INT DEFAULT 0;
DECLARE passedPlayerCount iNT DEFAULT 0;

DROP TEMPORARY TABLE
IF EXISTS inGamePlayers;
CREATE TEMPORARY TABLE inGamePlayers
SELECT players.id
FROM players, lobbies, games
WHERE players.lobbyId = lobbies.id AND
    lobbies.id = games.lobbyId AND
    games.id = currentGameId AND
    connected = TRUE;

SELECT COUNT(id)
INTO targetPlayerCount
FROM inGamePlayers;

SELECT LENGTH(playersToUpdate) - LENGTH(REPLACE(playersToUpdate, ",", "")) + 1
INTO passedPlayerCount;

IF NOT passedPlayerCount = targetPlayerCount THEN
DROP TEMPORARY TABLE inGamePlayers;
RETURN FALSE;
END
IF;

SELECT MAX(FIND_IN_SET(id, playersToUpdate))
INTO actualPlayerCount
FROM inGamePlayers;

DROP TEMPORARY TABLE inGamePlayers;

RETURN actualPlayerCount
= targetPlayerCount;

END;
//
DELIMITER ;