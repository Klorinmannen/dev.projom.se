DROP TABLE IF EXISTS `Requirement`;
CREATE TABLE `Requirement` (
       `RequirementID` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
       `ItemID` INT(11) NOT NULL,
       `RequirementTypeID` INT(11) NOT NULL,	
       `Value` INT(11) DEFAULT 0,
       CONSTRAINT `Requirement_ibfk_1` FOREIGN KEY (`RequirementTypeID`) REFERENCES `RequirementType` (`RequirementTypeID`),
       CONSTRAINT `Requirement_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `Item` (`ItemID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

