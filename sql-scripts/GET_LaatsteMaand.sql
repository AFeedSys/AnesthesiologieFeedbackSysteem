SELECT *
FROM data
WHERE EXTRACT(MONTH from datum) = EXTRACT(MONTH from CURDATE()) - 1