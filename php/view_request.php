<?php
header('Content-Type: text/html; charset=UTF-8');
include "php/connection.php";

$query_general=" SELECT\n".
			"	requests_status.*, CASE\n".
			"WHEN requests_status.cancelled = TRUE THEN\n".
			"	0\n".
			"WHEN requests.approved THEN\n".
			"	3\n".
			"WHEN requests_status.isauditor\n".
			"AND NOT requests_status.authorized THEN\n".
			"	1\n".
			"WHEN requests_status.isauditor\n".
			"AND requests_status.authorized THEN\n".
			"	2\n".
			"END AS `status`,\n".
			" requests.*, drivers.`name` AS driver_name,\n".
			" responsables.`name` AS responsable_name,\n".
			" CONCAT_WS(\n".
			"	\" \",\n".
			"	vehicles.economicno,\n".
			"	vehiclesbrands.brand,\n".
			"	vehiclessubbrands.subbrand\n".
			") AS vehicle_cap\n".
			"FROM\n".
			"	requests\n".
			"INNER JOIN (\n".
			"	SELECT\n".
			"		records.*,\n".
			"	IF (\n".
			"		ISNULL(user_authorizations.record),\n".
			"		FALSE,\n".
			"		TRUE\n".
			"	) AS authorized,\n".
			"IF (\n".
			"	ISNULL(user_cancellations.record),\n".
			"	FALSE,\n".
			"	TRUE\n".
			") AS cancelled,\n".
			" user_index.`index` AS current_user_index,\n".
			" last_authorized_indexes.max_authorized_index,\n".
			" max_auth_indexes.`max_index`\n".
			"FROM\n".
			"	(\n".
			"		SELECT\n".
			"			log.record,\n".
			"			log.`user` AS usercreated,\n".
			"			(log.`user` = ".$id." AND action = 0) AS isusercreated,\n".
			"		IF (\n".
			"			ISNULL(auth_users.`user`),\n".
			"			FALSE,\n".
			"			auth_users.`user` = ".$id."\n".
			"		) AS isauditor\n".
			"		FROM\n".
			"			log\n".
			"		INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
			"		LEFT JOIN authorizations ON authorizations.groupid = user_groups.`group`\n".
			"		LEFT JOIN user_groups AS auth_users ON auth_users.`group` = authorizations.`group`\n".
			"		WHERE\n".
			"			log.module = 1\n".
			"		AND (\n".
			"			(log.`user` = ".$id." AND action = 0)\n".
			"			OR auth_users.`user` = ".$id."\n".
			"		)\n".
			"		GROUP BY\n".
			"			log.record\n".
			"	) AS records\n".
			"LEFT JOIN (\n".
			"	SELECT\n".
			"		record\n".
			"	FROM\n".
			"		log\n".
			"	WHERE\n".
			"		log.module = 1\n".
			"	AND log.`user` = ".$id."\n".
			"	AND log.action = 3\n".
			") AS user_authorizations ON user_authorizations.record = records.record\n".
			"LEFT JOIN (\n".
			"	SELECT\n".
			"		record\n".
			"	FROM\n".
			"		log\n".
			"	WHERE\n".
			"		log.module = 1\n".
			"	AND log.action = 5\n".
			") AS user_cancellations ON user_cancellations.record = records.record\n".
			"LEFT JOIN (\n".
			"	SELECT\n".
			"		user_groups.`user`,\n".
			"		auth.`index` AS max_index\n".
			"	FROM\n".
			"		user_groups\n".
			"	INNER JOIN (\n".
			"		SELECT\n".
			"			authorizations.groupid,\n".
			"			Max(authorizations.`index`) AS `index`\n".
			"		FROM\n".
			"			authorizations\n".
			"		GROUP BY\n".
			"			authorizations.groupid\n".
			"	) AS auth ON auth.groupid = user_groups.`group`\n".
			") AS max_auth_indexes ON max_auth_indexes.`user` = records.usercreated\n".
			"LEFT JOIN (\n".
			"	SELECT\n".
			"		user_groups.`user`,\n".
			"		authorizations.`index`\n".
			"	FROM\n".
			"		authorizations\n".
			"	INNER JOIN user_groups ON user_groups.`group` = authorizations.groupid\n".
			"	INNER JOIN user_groups AS ug ON ug.`group` = authorizations.`group`\n".
			"	WHERE\n".
			"		ug.`user` = ".$id."\n".
			") AS user_index ON user_index.`user` = records.usercreated\n".
			"LEFT JOIN (\n".
			"	SELECT\n".
			"		user_approver.record,\n".
			"		MAX(authorizations.`index`) AS max_authorized_index\n".
			"	FROM\n".
			"		(\n".
			"			SELECT\n".
			"				log.record,\n".
			"				user_groups.`group` AS usergroup,\n".
			"				approvers.approvergroup\n".
			"			FROM\n".
			"				log\n".
			"			INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
			"			LEFT JOIN (\n".
			"				SELECT\n".
			"					log.record,\n".
			"					log.`user` AS approver,\n".
			"					user_groups.`group` AS approvergroup\n".
			"				FROM\n".
			"					log\n".
			"				INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
			"				WHERE\n".
			"					log.module = 1\n".
			"				AND log.action = 3\n".
			"			) AS approvers ON approvers.record = log.record\n".
			"			WHERE\n".
			"				log.module = 1\n".
			"			AND log.action = 0\n".
			"		) AS user_approver\n".
			"	INNER JOIN authorizations ON authorizations.groupid = user_approver.usergroup\n".
			"	AND authorizations.`group` = user_approver.approvergroup\n".
			"	GROUP BY\n".
			"		user_approver.record\n".
			") AS last_authorized_indexes ON last_authorized_indexes.record = records.record\n".
			") AS requests_status ON requests_status.record = requests.id\n".
			"LEFT JOIN users AS drivers ON drivers.id = requests.driver\n".
			"INNER JOIN users AS responsables ON responsables.id = requests.responsable\n".
			"INNER JOIN vehicles ON vehicles.id = requests.vehicle\n".
			"INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand\n".
			"INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type\n".
			"WHERE\n".
			"	NOT requests.deleted";

$query_request = "SELECT
									requests.*, drivers. NAME AS driver_name,
									responsables. NAME AS responsable_name,
									hubs.caption AS labor_cap
									FROM
									requests
									LEFT JOIN users AS drivers ON drivers.id = requests.driver
									INNER JOIN users AS responsables ON responsables.id = requests.responsable
									INNER JOIN hubs ON hubs.id = requests.hub
									WHERE
									requests.deleted = 0";

$requests_permissions = "SELECT\n".
									"	permissions.app_id\n".
									"FROM\n".
									"	permissions\n".
									"INNER JOIN group_perms ON group_perms.permission = permissions.id\n".
									"INNER JOIN groups ON groups.id = group_perms.`group`\n".
									"INNER JOIN user_groups ON user_groups.`group` = groups.id\n".
									"WHERE\n".
									"	user_groups.`user` = ".$id."\n".
									"AND permissions.app_id LIKE 'requests%'";
 ?>


<table id="example" class="table table-bordered" >
		<thead>
			<tr>
				<th>Estado</th>
				<th>Evento</th>
				<th>Salida</th>
				<th>Conductor</th>
				<th>Responsable</th>
				<th>Vehiculo</th>
			</tr>
		</thead>
		<?php
			//$result = mysqli_query($connection,$query_request);
			include "connection.php";
			$result = mysqli_query($connection,$query_general);

			$check_permissions = mysqli_query($connection, $requests_permissions);


function perm_btn($check_permissions,$id){
while ($extraido = mysqli_fetch_array($check_permissions)){

	if ($extraido["app_id"] == "requests.insert") {
	echo '
<a href="registro.php?id='.$id.'" class="btn btn-primary" onclick="VentanaCentrada(this.href,\'1000\',\'600\',\'Popupuno\');return false;" ><span class="glyphicon glyphicon-plus"></span> Agregar </a>

';
	}
	elseif ($extraido["app_id"] == "requests.delete") {
	echo '<button id="eliminar" class="btn btn-warning disabled"><span class="glyphicon glyphicon-trash" ></span>  Eliminar</button>';
	}
	elseif ($extraido["app_id"] == "requests.aprove") {
	echo '<button id="aprobar" class="btn btn-success disabled"><span class="glyphicon glyphicon-ok"></span>  Aprobar</button>

	<button id="denegar" class="btn btn-danger disabled"><span class="glyphicon glyphicon-remove"></span>  Denegar </button>';
	}
	elseif ($extraido["app_id"] == "requests.print") {
	echo '<button id="imprimir" class="btn btn-default disabled"><span class="glyphicon glyphicon-print"></span>  Imprimir</button>';
	}
	elseif ($extraido["app_id"] == "requests.update") {
	echo '<button  id="editar" class="btn btn-default disabled"><span class="glyphicon glyphicon-pencil"></span>  Editar</button>';
	}
}
}




			while($row = mysqli_fetch_array($result)){

				echo "<tbody><tr>";

				if ($row['approved'] == 1) {
					echo "<td style = 'background-color:Chartreuse' >Aprobado</td>";
				}
				elseif ($row['status'] == 3) {
					echo "<td style = 'background-color:tomato' >Denegado</td>";
				}
				else{
					echo "<td style = 'background-color:cyan'>Pendiente</td>";
				}
				echo "
					<td>".$row['reasons']."</td>
					<td>".$row['departuredate']."</td>
					<td>".$row['driver_name']."</td>
					<td>".$row['responsable_name']."</td>
					<td>".$row['vehicle_cap']."</td>
					</tr>";
				}
				echo "</tbody></table>";
		?>
</div>

<div id="buttons">
	<?php

		perm_btn($check_permissions,$id);
	?>
		<button id="actualizar" class="btn btn-info " onclick="javascript:location.reload()" > <span class="glyphicon glyphicon-refresh"></span> Actualizar</button>
</div>
