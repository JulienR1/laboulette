CREATE TABLE `points`(
`id` INT NOT NULL AUTO_INCREMENT,
`gameId` INT NOT NULL,
`playerId` INT NOT NULL,
`score` INT NOT NULL DEFAULT 0,
`roundNo` INT NOT NULL,
`recordTime` DATETIME NOT NULL DEFAULT NOW(),
PRIMARY KEY(`id`),
FOREIGN KEY(`gameId`) REFERENCES games(`id`),
FOREIGN KEY(`playerId`) REFERENCES players(`id`)
)