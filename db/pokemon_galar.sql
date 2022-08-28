DROP TABLE IF EXISTS `PokemonGalarian`;
CREATE TABLE `PokemonGalarian` (
  `PokemonGalarianID` int(11) NOT NULL AUTO_INCREMENT,
  `PokemonID` int(11) DEFAULT NULL,
  `PokemonTypeID_1` int(11) DEFAULT NULL,
  `PokemonTypeID_2` int(11) DEFAULT NULL,
  `Active` int(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonGalarianID`),
  KEY `CreatedBy` (`CreatedBy`),
  KEY `DeletedBy` (`DeletedBy`),
  CONSTRAINT `PokemonGalarian_ibfk_1` FOREIGN KEY (`PokemonID`) REFERENCES `Pokemon` (`PokemonID`),
  CONSTRAINT `PokemonGalarian_ibfk_2` FOREIGN KEY (`PokemonTypeID_1`) REFERENCES `PokemonType` (`PokemonTypeID`),
  CONSTRAINT `PokemonGalarian_ibfk_3` FOREIGN KEY (`PokemonTypeID_2`) REFERENCES `PokemonType` (`PokemonTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
