DROP TABLE IF EXISTS `PokemonSpecialForm`;
CREATE TABLE `PokemonSpecialForm` (
  `PokemonAlolanID` int(11) NOT NULL AUTO_INCREMENT,
  `PokemonID` int(11) DEFAULT NULL,
  `Alolan` INT(11) NOT NULL DEFAULT 0,
  `Galar` INT(11) NOT NULL DEFAULT 0,
  `PokemonTypeID1` INT(11) DEFAULT NULL,
  `PokemonTypeID2` INT(11) DEFAULT NULL,
  `Active` int(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` int(11) NOT NULL DEFAULT 1,
  `Deleted` int(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` int(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonAlolanID`),
  CONSTRAINT `PokemonAlolan_ibfk` FOREIGN KEY (`PokemonID`) REFERENCES `Pokemon` (`PokemonID`),
  CONSTRAINT `PokemonAlolan_ibfk_1` FOREIGN KEY (`PokemonTypeID1`) REFERENCES `PokemonType` (`PokemonTypeID`),
  CONSTRAINT `PokemonAlolan_ibfk_2` FOREIGN KEY (`PokemonTypeID2`) REFERENCES `PokemonType` (`PokemonTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
