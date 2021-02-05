DROP PROCEDURE IF EXISTS AssignHost;
DELIMITER //

CREATE PROCEDURE AssignHost(
IN currentLobbyId INT
)
BEGIN

    SELECT @newHostId := id
    FROM players
    WHERE connected = TRUE AND lobbyId = currentLobbyId
    LIMIT 1;

    UPDATE players
SET isHost=TRUE
WHERE id = @newHostId;

END;
//
DELIMITER ;