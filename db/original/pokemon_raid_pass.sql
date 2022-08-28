DROP TABLE IF EXISTS `PokemonRaidPass`;
CREATE TABLE `PokemonRaidPass` (
  `PokemonRaidPassID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT '',
  `Description` varchar(255) DEFAULT '',
  `CoinCost` int(11) NOT NULL DEFAULT 0,
  `Active` int(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT -1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonRaidPassID`),
  KEY `CreatedBy` (`CreatedBy`),
  KEY `DeletedBy` (`DeletedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
