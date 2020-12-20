CREATE TABLE `playerstoteams`
(
`playerId` INT NOT NULL,
`teamId` INT NOT NULL,
FOREIGN KEY
(`playerId`) REFERENCES players
(`id`),
FOREIGN KEY
(`teamId`) REFERENCES teams
(`id`)
)