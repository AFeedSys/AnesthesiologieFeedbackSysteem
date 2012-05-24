CREATE VIEW LaatsteTotalen AS
SELECT naam, MAX(datum), shouldTotaal, haveTotaal
FROM protocolTotalen
GROUP BY naam