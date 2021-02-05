DROP PROCEDURE IF EXISTS RegisterUpdate;
DELIMITER //

CREATE PROCEDURE RegisterUpdate(
IN targetLobbyId INT
)
BEGIN
    UPDATE lobbies
	SET	lastModification = NOW()
	WHERE id = targetLobbyId;
END;
//
DELIMITER ;