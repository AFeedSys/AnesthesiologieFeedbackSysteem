CREATE VIEW ProtocolTotalen AS
SELECT Protocol.naam, Data.datum, SUM(Data.shouldBeen) AS shouldTotaal, SUM(Data.haveBeen) AS haveTotaal
FROM Protocol INNER JOIN Data
   ON Protocol.id=Data.protocolID
GROUP BY Data.datum