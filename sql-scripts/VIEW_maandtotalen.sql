DROP VIEW IF EXISTS maandtotalen;

CREATE VIEW maandtotalen AS
SELECT datum, SUM(shouldTotaal) AS maandShouldTot, SUM(doneTotaal) AS maandDoneTotaal
FROM `protocoltotalen`
GROUP BY datum;