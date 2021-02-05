DROP FUNCTION IF EXISTS GetRandomAvailablePlayer;

DELIMITER //
CREATE FUNCTION GetRandomAvailablePlayer(currentLobbyId INT)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE randomId INT DEFAULT 0;

    SELECT id
    INTO randomId
    FROM players
    WHERE lobbyId = currentLobbyId AND connected = TRUE AND id NOT IN 
	(SELECT playerId
        FROM playerstoteams, players
        WHERE players.id = playerId AND lobbyId = currentLobbyId)
    ORDER BY RAND()
LIMIT 1;

RETURN randomId;

END;
//
DELIMITER ;