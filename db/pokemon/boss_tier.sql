DROP TABLE IF EXISTS `PokemonBossTier`;
CREATE TABLE `PokemonBossTier` (
  `PokemonBossTierID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` TEXT DEFAULT '',
  `Description` varchar(255) DEFAULT '',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonBossTierID`),
  KEY `CreatedBy` (`CreatedBy`),
  KEY `DeletedBy` (`DeletedBy`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
