DROP TABLE IF EXISTS `PokemonFamily`;
CREATE TABLE `PokemonFamily` (
  `PokemonFamilyID` int(11) NOT NULL AUTO_INCREMENT,
  `PokemonID` int(11) DEFAULT NULL,
  `PokemonRelationID` int(11) DEFAULT NULL,
  `RelativePokemonID` int(11) DEFAULT NULL,
  `Active` int(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonFamilyID`),
  KEY `CreatedBy` (`CreatedBy`),
  KEY `DeletedBy` (`DeletedBy`),
  CONSTRAINT `PokemonFamily_ibfk` FOREIGN KEY (`PokemonID`) REFERENCES `Pokemon` (`PokemonID`) ON DELETE CASCADE,
  CONSTRAINT `PokemonFamily_ibfk_1` FOREIGN KEY (`PokemonRelationID`) REFERENCES `PokemonRelation` (`PokemonRelationID`),
  CONSTRAINT `PokemonFamily_ibfk_2` FOREIGN KEY (`RelativePokemonID`) REFERENCES `Pokemon` (`PokemonID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
