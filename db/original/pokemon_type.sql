DROP TABLE IF EXISTS `PokemonType`;
CREATE TABLE `PokemonType` (
  `PokemonTypeID` INT(11) NOT NULL AUTO_INCREMENT,
  `Type` VARCHAR(250) DEFAULT '',
  `Active` INT(11) NOT NULL DEFAULT -1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` INT(11) NOT NULL DEFAULT 1,
  `Deleted` INT(11) NOT NULL DEFAULT 0,
  `DeletedAt` datetime DEFAULT NULL,
  `DeletedBy` INT(11) DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`PokemonTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
