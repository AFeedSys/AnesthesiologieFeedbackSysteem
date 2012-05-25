CREATE VIEW ProtocolTotalen AS
SELECT Protocol.naam, Data.datum, SUM(Data.shouldBeen) AS shouldTotaal, SUM(Data.haveDone) AS doneTotaal
FROM Protocol INNER JOIN Data
   ON Protocol.id=Data.protocolID
WHERE Protocol.active = 1
GROUP BY Data.datum