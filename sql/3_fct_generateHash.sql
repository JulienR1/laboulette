DROP FUNCTION IF EXISTS GenerateHash;
DELIMITER //

CREATE FUNCTION GenerateHash() RETURNS CHAR(6)
DETERMINISTIC
BEGIN
        DECLARE MAX_ITERATIONS INT DEFAULT 100;
        DECLARE foundHash BOOLEAN DEFAULT FALSE;
DECLARE i INT DEFAULT 0;
DECLARE gameHash CHAR
(6);

SET @chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
SET @charCount = 62;
SET @intSize = 4294937269;

WHILE i < MAX_ITERATIONS AND NOT foundHash DO
SET gameHash
= CONCAT
(
		substring
(@chars, rand
(
@seed:
=round
(rand
()*@intSize))*@charCount+1,1),
        substring
(@chars, rand
(
@seed:
=round
(rand
(@seed)*@intSize))*@charCount+1,1),
        substring
(@chars, rand
(
@seed:
=round
(rand
(@seed)*@intSize))*@charCount+1,1),
        substring
(@chars, rand
(
@seed:
=round
(rand
(@seed)*@intSize))*@charCount+1,1),
        substring
(@chars, rand
(
@seed:
=round
(rand
(@seed)*@intSize))*@charCount+1,1),
        substring
(@chars, rand
(
@seed:
=round
(rand
(@seed)*@intSize))*@charCount+1,1),
        substring
(@chars, rand
(
@seed:
=round
(rand
(@seed)*@intSize))*@charCount+1,1)
    );
IF NOT EXISTS (SELECT hash
FROM lobbies
WHERE hash = gameHash) THEN
SET foundHash
= true;
END
IF;
    SET i
= i + 1;
END
WHILE;

RETURN gameHash;

END;

//
DELIMITER ;