-- Naam: insertData
-- Use: Gebruikt extern voor het invoeren van data
-- parameters
DELIMITER $$

DROP PROCEDURE IF EXISTS `insertData`$$

CREATE PROCEDURE insertData(
    IN protocolnaam VARCHAR(255)
    IN dataset RSDATA;
)

BEGIN

$$
DELIMITER ;
