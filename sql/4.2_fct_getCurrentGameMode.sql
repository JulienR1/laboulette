DROP FUNCTION IF EXISTS GetCurrentGameMode;

DELIMITER //
CREATE FUNCTION GetCurrentGameMode(currentGameId INT)
RETURNS INT
DETERMINISTIC
BEGIN

    RETURN (
		  SELECT FLOOR(SUM(pickedCount) / GREATEST(SUM(wordCount), 1))
      FROM (
        SELECT COUNT(words.id) AS wordCount, 0 AS pickedCount
        FROM words, games
        WHERE words.lobbyId = games.lobbyId AND games.id = currentGameId
        UNION
        SELECT 0 AS wordCount, COUNT(pickedwords.id) AS pickedCount
        FROM pickedwords
        WHERE pickedwords.gameId = currentGameId
      ) AS A
	);

END;
//
DELIMITER ;