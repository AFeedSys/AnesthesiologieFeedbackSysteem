CREATE VIEW LaatsteWaarden AS
SELECT naam, MAX(datum), shouldTotaal, doneTotaal
FROM protocolTotalen
GROUP BY naam