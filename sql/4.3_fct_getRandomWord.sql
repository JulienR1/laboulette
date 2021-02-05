DROP FUNCTION IF EXISTS GetRandomWordId;

DELIMITER //
CREATE FUNCTION GetRandomWordId(currentGameId INT, currentGameMode INT)
RETURNS INT
DETERMINISTIC
BEGIN

    DECLARE randomWordId INT DEFAULT 0;

    IF (NOT (SELECT GetCurrentGameMode(currentGameId)) = currentGameMode) THEN
    RETURN -1;
END
IF;

    DROP TEMPORARY TABLE
IF EXISTS pickedWordsIterations;
DROP TEMPORARY TABLE
IF EXISTS pickedWordsIterationsMinimum;

CREATE TEMPORARY TABLE pickedWordsIterations
SELECT A.*, COUNT(pickedwords.wordId) AS iterations
FROM (
    SELECT words.id AS wordId
    FROM words, games
    WHERE words.lobbyId = games.lobbyId AND games.id = currentGameId
) AS A
    LEFT JOIN pickedwords ON pickedwords.wordId = A.wordId
GROUP BY A.wordId;

CREATE TEMPORARY TABLE pickedWordsIterationsMinimum
SELECT MIN(iterations) AS minIterations
FROM pickedWordsIterations;

SELECT words.id
INTO randomWordId
FROM words, games
WHERE games.lobbyId = words.lobbyId AND games.id = currentGameId AND words.id IN (
		SELECT wordId
    FROM pickedWordsIterations
        JOIN pickedWordsIterationsMinimum
        ON iterations = minIterations)
ORDER BY RAND()
	LIMIT 1;
		
	DROP TEMPORARY
TABLE
IF EXISTS pickedWordsIterations;
DROP TEMPORARY TABLE
IF EXISTS pickedWordsIterationsMinimum;

RETURN randomWordId;

END;
//
DELIMITER ;