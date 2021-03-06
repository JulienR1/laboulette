CREATE TABLE `games`(
`id` INT NOT NULL AUTO_INCREMENT,
`lobbyId` INT NOT NULL,
`roundNo` INT NOT NULL DEFAULT -1,
`roundStarted` BOOLEAN NOT NULL DEFAULT FALSE,
`teamIdToPlay` INT NOT NULL DEFAULT -1,
`roundTimer` INT NOT NULL DEFAULT 60,
`teamCount` INT NOT NULL DEFAULT 2,
`startTime` DATETIME NOT NULL DEFAULT NOW(),
`gameState` TINYINT NOT NULL DEFAULT 0,
`roundEndTime` DATETIME NOT NULL DEFAULT 0,
PRIMARY KEY(`id`),
FOREIGN KEY(`lobbyId`) REFERENCES lobbies(`id`)
)