SELECT Protocol.naam, Data.datum, SUM(Data.shouldBeen), SUM(Data.haveBeen)
FROM Protocol INNER JOIN Data
   ON Protocol.id=Data.protocolID
GROUP BY Data.datum