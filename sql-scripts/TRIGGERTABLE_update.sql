DELIMITER //

DROP TRIGGER IF EXISTS `updateTrigger`//
DROP TABLE IF EXISTS `dataChace`//
DROP PROCEDURE IF EXISTS `naamCheck`//
--DROP PROCEDURE IF EXISTS `insertChace`;

CREATE TABLE dataChace (
    `protocolNaam` varchar(255),
    `datum` date NOT NULL,
    `userID` varchar(255) NOT NULL,
    `shouldBeen` int(10) NOT NULL,
    `haveDone` int(10) NOT NULL,
    PRIMARY KEY (`protocolNaam`, `datum`, `userID`)
)//

CREATE PROCEDURE naamCheck (IN inNaam VARCHAR(255))
BEGIN
    SELECT COUNT(*)
    FROM protocol
    WHERE protocol.naam = inNaam
    INTO @counter;
    IF @counter = 0 THEN
        INSERT INTO protocol (naam)
        VALUES (@inNaam);
    END IF;
END//

CREATE TRIGGER insertChace AFTER UPDATE ON datachace
FOR EACH ROW
BEGIN
    --NAMECHECK
    CALL naamCheck(NEW.protocolNaam);
    --INSERT ROW
    SELECT id 
    FROM protocol 
    WHERE procotol.naam = NEW.protocolNaam
    INTO @protocolID;
    INSERT INTO `data` (`data`.protocolID, `data`.datum, `data`.userID, `data`.shouldBeen, `data`.haveDone)
    VALUES (@protocolID, NEW.datum, NEW.userID, NEW.shouldBeen, NEW.haveDone);
END//

DELIMITER ;