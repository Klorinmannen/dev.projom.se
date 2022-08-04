DROP TABLE IF EXISTS `PokemonRarity`;
CREATE TABLE `PokemonRarity` (
  `PokemonRarityID` int(11) NOT NULL AUTO_INCREMENT,
  `Rarity` varchar(250) DEFAULT '',
  `Active` int(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonRarityID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
