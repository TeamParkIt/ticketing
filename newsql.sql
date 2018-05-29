CREATE TABLE `ticket`.`whitelist` ( `ID` INT NOT NULL AUTO_INCREMENT , `licensePlate` VARCHAR(10) NOT NULL , `fk_Lot` INT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;
ALTER TABLE `whitelist` CHANGE `fk_Lot` `fk_Lot` INT(11) NULL;
