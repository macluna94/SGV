<?php 
	include "connection.php";
	include "../session.php";

	$date_s = $_GET['date_salida'];
	$date_r = $_GET['date_retorno'];
	$time_salida = $_GET['time_salida'];
	$time_retorno = $_GET['time_retorno'];
	$date_salida = $date_s . " ". $time_salida;
	$date_retorno = $date_r . " " . $time_retorno;


	$query_perm = 	"SELECT
					permissions.app_id,
					permissions.id
					FROM
					permissions
					INNER JOIN group_perms ON group_perms.permission = permissions.id
					INNER JOIN groups ON groups.id = group_perms.`group`
					INNER JOIN user_groups ON user_groups.`group` = groups.id
					WHERE
					user_groups.`user` = $id_user
					AND permissions.app_id LIKE 'requests%' ";

	$perm_all_drivers = '18';
	$list_perms = '';
	$sql_perm = mysqli_query($connection, $query_perm);

	if (!$connection) {die('Could not connect: ' . mysqli_error($connection));}

	while ($extraido = mysqli_fetch_array($sql_perm)){
		$list_perms .= $extraido['id'].",";
	}

	$all_drivers = strpos($list_perms, $perm_all_drivers);

	if ($all_drivers == true) {
		#echo "<br> Lista Completa <br>";
		$driver_all =	"SELECT\n".
	"	id,\n".
	"	`name`\n".
	"FROM\n".
	"	users\n".
	"WHERE\n".
	"	NOT deleted\n".
	"AND driversid != ''''\n".
	"AND NOT id IN (\n".
	"	SELECT\n".
	"		driver\n".
	"	FROM\n".
	"		requests\n".
	"	WHERE\n".
	"		NOT ISNULL(driver)\n".
	"	AND NOT id IN (\n".
	"		SELECT\n".
	"			record\n".
	"		FROM\n".
	"			log\n".
	"		WHERE\n".
	"			module = 1\n".
	"		AND (action = 2 OR action = 5)\n".
	"	)\n".
	"	AND (\n".
	"		(\n".
	"			'$date_salida' BETWEEN departuredate\n".
	"			AND returndate\n".
	"		)\n".
	"		OR (\n".
	"			'$date_retorno' BETWEEN departuredate\n".
	"			AND returndate\n".
	"		)\n".
	"		OR (\n".
	"			departuredate BETWEEN '$date_salida'\n".
	"			AND '$date_retorno'\n".
	"		)\n".
	"		OR (\n".
	"			returndate BETWEEN '$date_salida'\n".
	"			AND '$date_retorno'\n".
	"		)\n".
	"	)\n".
	")\n".
	"AND (\n".
	"	driversidduedate >= '$date_retorno'\n".
	");";
		$driver_list = $driver_all;
	}
	else{
		#echo "<br> Lista Semi-completa <br>";
		$drivers = 	"SELECT\n".
	"	id,\n".
	"	`name`\n".
	"FROM\n".
	"	users\n".
	"WHERE\n".
	"	NOT deleted\n".
	"AND driversid != ''''\n".
	"AND NOT id IN (\n".
	"	SELECT\n".
	"		driver\n".
	"	FROM\n".
	"		requests\n".
	"	WHERE\n".
	"		NOT ISNULL(driver)\n".
	"	AND NOT id IN (\n".
	"		SELECT\n".
	"			record\n".
	"		FROM\n".
	"			log\n".
	"		WHERE\n".
	"			module = 1\n".
	"		AND (action = 2 OR action = 5)\n".
	"	)\n".
	"	AND (\n".
	"		(\n".
	"			'$date_salida' BETWEEN departuredate\n".
	"			AND returndate\n".
	"		)\n".
	"		OR (\n".
	"			'$date_retorno' BETWEEN departuredate\n".
	"			AND returndate\n".
	"		)\n".
	"		OR (\n".
	"			departuredate BETWEEN '$date_salida'\n".
	"			AND '$date_retorno'\n".
	"		)\n".
	"		OR (\n".
	"			returndate BETWEEN '$date_salida'\n".
	"			AND '$date_retorno'\n".
	"		)\n".
	"	)\n".
	")\n".
	"AND (\n".
	"	driversidduedate >= '$date_retorno'\n".
	")\n".
	"AND id IN (\n".
	"	SELECT DISTINCT\n".
	"		`user`\n".
	"	FROM\n".
	"		user_group_drivers\n".
	"	WHERE\n".
	"		`group` IN (\n".
	"			SELECT\n".
	"				`group`\n".
	"			FROM\n".
	"				user_group_drivers\n".
	"			WHERE\n".
	"				`user` = $id_user\n".
	"		)\n".
	");";
		$driver_list = $drivers;
	}
	mysqli_select_db($connection,"ajax_demo");
	
	$result = mysqli_query($connection,$driver_list);
	echo '<label for="sel1">Conductor:</label> <select name="conductor" class="form-control"  required> ';
	while($row = mysqli_fetch_array($result)) {
		echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
	}
	echo "</select> <br>";
	mysqli_close($connection);
 ?>