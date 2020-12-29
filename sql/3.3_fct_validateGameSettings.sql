DROP FUNCTION IF EXISTS ValidateGameSettings;

DELIMITER //
CREATE FUNCTION ValidateGameSettings(currentLobbyId INT)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE teamCount INT DEFAULT 0;
    DECLARE playerCount INT DEFAULT 0;
DECLARE wordCount INT DEFAULT 0;
DECLARE minWords INT DEFAULT 10;

SELECT games.teamCount, lobbies.minWords
INTO teamCount
, minWords FROM games, lobbies WHERE lobbyId = currentLobbyId AND lobbies.id = lobbyId;
SELECT COUNT(id)
INTO playerCount
FROM players
WHERE lobbyId = currentLobbyId AND connected = TRUE;
SELECT COUNT(id)
INTO wordCount
FROM words
WHERE lobbyId = currentLobbyId;

RETURN wordCount
>= minWords AND teamCount >= 2 AND playerCount / teamCount >= 2;
END;
//
DELIMITER ;