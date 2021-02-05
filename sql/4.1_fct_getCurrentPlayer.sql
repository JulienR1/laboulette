DROP FUNCTION IF EXISTS GetCurrentPlayer;

DELIMITER //
CREATE FUNCTION GetCurrentPlayer(currentGameId INT)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE playerCount INT DEFAULT 0;
    DECLARE playerIndex INT DEFAULT 0;
DECLARE currentPlayerId INT DEFAULT 0;

SELECT COUNT(*)
INTO playerCount
FROM players
    JOIN playerstoteams ON players.id = playerId
    JOIN games ON players.lobbyId = games.lobbyId AND teamIdToPlay = teamId AND games.id = currentGameId;

SELECT MOD(FLOOR(roundNo / teamCount), playerCount)
INTO playerIndex
FROM games
WHERE id = currentGameId;

SELECT players.id
INTO currentPlayerId
FROM players
    JOIN playerstoteams ON playerId = players.id
    JOIN games ON teamId = teamIdToPlay AND games.lobbyId = players.lobbyId AND games.id = currentGameId
    JOIN playerorders ON players.id = playerorders.playerId
ORDER BY priority
    LIMIT playerIndex, 1;

	RETURN currentPlayerId;

END;
//
DELIMITER ;