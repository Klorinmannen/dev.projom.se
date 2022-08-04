DROP TABLE IF EXISTS `ItemProperty`;
CREATE TABLE `ItemProperty` (
       `ItemPropertyID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
       `ItemID` INT(11) NOT NULL,
       `PropertyLevelID` INT(11) NOT NULL,
       `PropertyTypeID` INT(11) NOT NULL,
       `Name` TEXT DEFAULT NULL,
       `Value` INT(11) DEFAULT 0,
       CONSTRAINT `item_property_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `Item` (`ItemID`),
       CONSTRAINT `item_property_ibfk_2` FOREIGN KEY (`PropertyLevelID`) REFERENCES `PropertyLevel` (`PropertyLevelID`),
       CONSTRAINT `item_property_ibfk_3` FOREIGN KEY (`PropertyTypeID`) REFERENCES `PropertyType` (`PropertyTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
