DROP TABLE IF EXISTS `PokemonBoss`;
CREATE TABLE `PokemonBoss` (
  `PokemonBossID` int(11) NOT NULL AUTO_INCREMENT,
  `PokemonID` int(11) DEFAULT NULL,
  `PokemonBossTierID` int(11) DEFAULT NULL,
  `PokemonFormID` int(11) DEFAULT NULL,
  `MinCP` int(11) DEFAULT 0,
  `MaxCP` int(11) DEFAULT 0,
  `BoostedMinCP` int(11) DEFAULT 0,
  `BoostedMaxCP` int(11) DEFAULT 0,
  `Active` int(11) NOT NULL DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonBossID`),
  CONSTRAINT `PokemonBoss_ibfk` FOREIGN KEY (`PokemonID`) REFERENCES `Pokemon` (`PokemonID`) ON DELETE CASCADE,
  CONSTRAINT `PokemonBoss_ibfk_1` FOREIGN KEY (`PokemonBossTierID`) REFERENCES `PokemonBossTier` (`PokemonBossTierID`),
  CONSTRAINT `PokemonBoss_ibfk_2` FOREIGN KEY (`PokemonFormID`) REFERENCES `PokemonForm` (`PokemonFormID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
