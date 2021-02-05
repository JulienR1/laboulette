CREATE TABLE `pickedwords`
(
`id` INT NOT NULL AUTO_INCREMENT,
`wordId` INT NOT NULL,
`gameId` INT NOT NULL,
`playerId` INT NOT NULL,
PRIMARY KEY
(`id`),
FOREIGN KEY
(`wordId`) REFERENCES words
(`id`),
FOREIGN KEY
(`gameId`) REFERENCES games
(`id`),
FOREIGN KEY
(`playerId`) REFERENCES players
(`id`)
)