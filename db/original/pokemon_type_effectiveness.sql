DROP TABLE IF EXISTS `PokemonTypeEffectiveness`;
CREATE TABLE `PokemonTypeEffectiveness` (
  `PokemonTypeEffectivenessID` int(11) NOT NULL AUTO_INCREMENT,
  `AttackingPokemonTypeID` int(11) DEFAULT NULL,
  `Multiplier` varchar(250) DEFAULT '',
  `DefendingPokemonTypeID` int(11) DEFAULT NULL,
  `Active` int(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonTypeEffectivenessID`),
  KEY `AttackingPokemonTypeID` (`AttackingPokemonTypeID`),
  KEY `DefendingPokemonTypeID` (`DefendingPokemonTypeID`),
  CONSTRAINT `PokemonTypeEffectiveness_ibfk_1` FOREIGN KEY (`AttackingPokemonTypeID`) REFERENCES `PokemonType` (`PokemonTypeID`),
  CONSTRAINT `PokemonTypeEffectiveness_ibfk_2` FOREIGN KEY (`DefendingPokemonTypeID`) REFERENCES `PokemonType` (`PokemonTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
