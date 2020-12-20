CREATE TABLE `playerorders`
(
`gameId` INT NOT NULL,
`playerid` INT NOT NULL,
`priority` INT NOT NULL DEFAULT -1,
FOREIGN KEY
(`playerId`) REFERENCES players
(`id`),
FOREIGN KEY
(`gameId`) REFERENCES games
(`id`)
)