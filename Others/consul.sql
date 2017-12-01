/*
Carga la lista de conductores disponibles sin importar el grupo al que pertenecen

	:departuredate 		Es la fecha de salida incluír hora YYYY-MM-DD HH:NN:SS
	
	:returndate 		Es la fecha de retorno, incluír hora YYYY-MM-DD HH:NN:SS


comprobar el premiso requests.listalldrivers para poder usar esta consulta
*/

SELECT `name` FROM users WHERE NOT id IN (SELECT driver FROM requests WHERE NOT ISNULL(driver) AND (('$date_salida' BETWEEN departuredate  AND returndate) OR ('$date_retorno' BETWEEN departuredate AND returndate) OR (departuredate BETWEEN '$date_salida' AND '$date_retorno') OR (returndate BETWEEN '$date_salida' AND '$date_retorno'))) AND (driversidduedate >= '$date_retorno') AND NOT deleted;


SELECT `name` FROM users INNER JOIN user_groups ON user_groups.`user` = users.id WHERE `group` = (SELECT `group` FROM user_groups WHERE `user` = '12') AND NOT id IN (SELECT driver FROM requests WHERE NOT ISNULL(driver) AND (('$date_salida' BETWEEN departuredate  AND returndate) OR ('$date_retorno' BETWEEN departuredate AND returndate) OR (departuredate BETWEEN '$date_salida' AND '$date_retorno') OR (returndate BETWEEN '$date_salida' AND '$date_retorno'))) AND (driversidduedate >= '$date_retorno') AND NOT deleted GROUP BY `name`;