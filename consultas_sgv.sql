#Vista de Solicitudes
	/*
	Lista de solicituders
	:USER id usuario de sistema
	la columna de estado es calculada bajo las siguientes condiciones
	los identificadores en mayuscula son columnas en el dataset devuelto por la consulta


	si ISUSERCREATED es verdadero, 
				si APPROVED OR (MAX_AUTHORIZED_INDEX = MAX_INDEX) entonces estado = "Aprobado"

				tambien si CANCELLED entonces estado = "Cancelado"
				sino estado = "En espera de autorización"
	sino
				si APPROVED OR (MAX_AUTHORIZED_INDEX = MAX_INDEX) entonces estado = "Aprobado"
				tambien si CANCELLED entonces estado = "Cancelado"
				sino 
					si (MAX_AUTHORIZED_INDEX + 1) = CURRENT_USER_INDEX entonces estado = "Aprobar"
					sino estado = "En espera de autorización previa"




	fin de las condiciones
	la columna conductor es calculada bajo las siguientes condiciones
	si CURRENT_USER_INDEX AND MAX_INDEX AND REQUESTDRIVERTOLASTLEVEL entonces conductor = "Asignar"
	sino conductor = DRIVERNAME
	*/

#Consulta que muestra todas las solicitudes
#comprobar permiso requests.viewall
	SELECT
	requests_status.*,
	requests.*,
	drivers.`name` AS driver_name,
	responsables.`name` AS responsable_name,
	CONCAT_WS( " ", vehicles.economicno, vehiclesbrands.brand, vehiclessubbrands.subbrand ) AS vehicle_cap 
	FROM
	requests
	INNER JOIN (
				SELECT
					records_status.*,
					groups.`name` AS next_authorization_group
				FROM
					(
						SELECT
								records.*,
								NOT ISNULL( user_authorizations.record ) AS authorized,
								NOT ISNULL( user_cancellations.record ) AS cancelled,
								IF ( ISNULL( user_index.`index` ), - 1, user_index.`index` ) AS current_user_index,
								IF ( ISNULL( last_authorized_indexes.max_authorized_index ), - 1, last_authorized_indexes.max_authorized_index ) AS max_authorized_index,
								IF ( ISNULL( max_auth_indexes.max_index ), - 1, max_auth_indexes.max_index ) AS max_index,					
								authorized_group					
							FROM
								(
									SELECT
										log.record,
										requests.hub,
										log.`user` AS usercreated,
										user_groups.`group` AS user_group,
										users.`name` AS usercreatedname,
										( log.`user` = :USER AND action = 0 ) AS isusercreated,
										NOT ISNULL( auditors.hubid ) AS isauditor 
									FROM
										log
										INNER JOIN requests ON requests.id = log.record
										INNER JOIN user_groups ON user_groups.`user` = log.`user`
										INNER JOIN users ON users.id = log.`user`
										LEFT JOIN (
											SELECT
												hubid,
												audited.`user` AS audited,
												auditors.`user` AS auditor
											FROM
												authorizations
												INNER JOIN user_groups AS auditors ON auditors.`group` = authorizations.`group`
												INNER JOIN user_groups AS audited ON audited.`group` = authorizations.groupid 
											WHERE
												auditors.`user` = :USER 
										) AS auditors ON auditors.audited = log.`user` 
										AND auditors.hubid = requests.hub 
									WHERE
										log.module = 1 
										AND action = 0 

									GROUP BY
										log.record
								) AS records
								LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.`user` = :USER AND log.action = 3 ) AS user_authorizations ON user_authorizations.record = records.record
								LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.action = 5 ) AS user_cancellations ON user_cancellations.record = records.record
								LEFT JOIN (
											SELECT
												user_groups.`user`,
												auth.hubid,
												auth.`index` AS max_index
											FROM
												user_groups
												INNER JOIN (
											SELECT
												authorizations.groupid,
												authorizations.hubid,
												Max( authorizations.`index` ) AS `index`
											FROM
												authorizations
											GROUP BY
												authorizations.groupid,
												authorizations.hubid
												) AS auth ON auth.groupid = user_groups.`group`
								) AS max_auth_indexes ON max_auth_indexes.`user` = records.usercreated AND max_auth_indexes.hubid = records.hub
								LEFT JOIN (
											SELECT
												user_groups.`user`,
												authorizations.`index`,
												authorizations.hubid
											FROM
												authorizations
												INNER JOIN user_groups ON user_groups.`group` = authorizations.groupid
												INNER JOIN user_groups AS ug ON ug.`group` = authorizations.`group`
											WHERE
												ug.`user` = :USER
								) AS user_index ON user_index.`user` = records.usercreated AND user_index.hubid = records.hub
								LEFT JOIN (
											SELECT
												user_approver.record,
												MAX( authorizations.`index` ) AS max_authorized_index,
												groups.`name` AS authorized_group
											FROM
												(									
													SELECT
														log.record,
														user_groups.`group` AS usergroup,
														approvers.approvergroup
													FROM
														log
														INNER JOIN user_groups ON user_groups.`user` = log.`user`
														LEFT JOIN (
																	SELECT
																		log.record,
																		log.`user` AS approver,
																		user_groups.`group` AS approvergroup
																	FROM
																		log
																		INNER JOIN user_groups ON user_groups.`user` = log.`user`
																	WHERE
																		log.module = 1
																		AND log.action = 3
														) AS approvers ON approvers.record = log.record
													WHERE
														log.module = 1
														AND log.action = 0
												) AS user_approver
												INNER JOIN requests ON requests.id = user_approver.record
												INNER JOIN authorizations ON authorizations.groupid = user_approver.usergroup
												INNER JOIN groups ON groups.id = user_approver.approvergroup
												AND authorizations.`group` = user_approver.approvergroup 
												AND requests.hub = authorizations.hubid 
											GROUP BY
												user_approver.record
								) AS last_authorized_indexes ON last_authorized_indexes.record = records.record
					) AS records_status
					LEFT JOIN authorizations ON authorizations.hubid = records_status.hub AND authorizations.groupid = records_status.user_group AND authorizations.`index` = records_status.max_authorized_index + 1
					LEFT JOIN groups ON groups.id = authorizations.`group`
	) AS requests_status ON requests_status.record = requests.id
	LEFT JOIN users AS drivers ON drivers.id = requests.driver
	INNER JOIN users AS responsables ON responsables.id = requests.responsable
	INNER JOIN vehicles ON vehicles.id = requests.vehicle
	INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand
	INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type 
	WHERE
	NOT requests.deleted 
	ORDER BY
	id DESC;

	#Consulta sólo las solicitudes pertinentes al usuario del sistema
	SELECT
	requests_status.*,
	requests.*,
	drivers.`name` AS driver_name,
	responsables.`name` AS responsable_name,
	CONCAT_WS( " ", vehicles.economicno, vehiclesbrands.brand, vehiclessubbrands.subbrand ) AS vehicle_cap 
	FROM
	requests
	INNER JOIN (
				SELECT
					records_status.*,
					groups.`name` AS next_authorization_group
				FROM
					(
						SELECT
								records.*,
								NOT ISNULL( user_authorizations.record ) AS authorized,
								NOT ISNULL( user_cancellations.record ) AS cancelled,
								IF ( ISNULL( user_index.`index` ), - 1, user_index.`index` ) AS current_user_index,
								IF ( ISNULL( last_authorized_indexes.max_authorized_index ), - 1, last_authorized_indexes.max_authorized_index ) AS max_authorized_index,
								IF ( ISNULL( max_auth_indexes.max_index ), - 1, max_auth_indexes.max_index ) AS max_index,					
								authorized_group					
							FROM
								(
									SELECT
										log.record,
										requests.hub,
										log.`user` AS usercreated,
										user_groups.`group` AS user_group,
										users.`name` AS usercreatedname,
										( log.`user` = :USER AND action = 0 ) AS isusercreated,
										NOT ISNULL( auditors.hubid ) AS isauditor 
									FROM
										log
										INNER JOIN requests ON requests.id = log.record
										INNER JOIN user_groups ON user_groups.`user` = log.`user`
										INNER JOIN users ON users.id = log.`user`
										LEFT JOIN (
											SELECT
												hubid,
												audited.`user` AS audited,
												auditors.`user` AS auditor
											FROM
												authorizations
												INNER JOIN user_groups AS auditors ON auditors.`group` = authorizations.`group`
												INNER JOIN user_groups AS audited ON audited.`group` = authorizations.groupid 
											WHERE
												auditors.`user` = :USER 
										) AS auditors ON auditors.audited = log.`user` 
										AND auditors.hubid = requests.hub 
									WHERE
										log.module = 1 
										AND action = 0 

									GROUP BY
										log.record
								) AS records
								LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.`user` = :USER AND log.action = 3 ) AS user_authorizations ON user_authorizations.record = records.record
								LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.action = 5 ) AS user_cancellations ON user_cancellations.record = records.record
								LEFT JOIN (
											SELECT
												user_groups.`user`,
												auth.hubid,
												auth.`index` AS max_index
											FROM
												user_groups
												INNER JOIN (
											SELECT
												authorizations.groupid,
												authorizations.hubid,
												Max( authorizations.`index` ) AS `index`
											FROM
												authorizations
											GROUP BY
												authorizations.groupid,
												authorizations.hubid
												) AS auth ON auth.groupid = user_groups.`group`
								) AS max_auth_indexes ON max_auth_indexes.`user` = records.usercreated AND max_auth_indexes.hubid = records.hub
								LEFT JOIN (
											SELECT
												user_groups.`user`,
												authorizations.`index`,
												authorizations.hubid
											FROM
												authorizations
												INNER JOIN user_groups ON user_groups.`group` = authorizations.groupid
												INNER JOIN user_groups AS ug ON ug.`group` = authorizations.`group`
											WHERE
												ug.`user` = :USER
								) AS user_index ON user_index.`user` = records.usercreated AND user_index.hubid = records.hub
								LEFT JOIN (
											SELECT
												user_approver.record,
												MAX( authorizations.`index` ) AS max_authorized_index,
												groups.`name` AS authorized_group
											FROM
												(									
													SELECT
														log.record,
														user_groups.`group` AS usergroup,
														approvers.approvergroup
													FROM
														log
														INNER JOIN user_groups ON user_groups.`user` = log.`user`
														LEFT JOIN (
																	SELECT
																		log.record,
																		log.`user` AS approver,
																		user_groups.`group` AS approvergroup
																	FROM
																		log
																		INNER JOIN user_groups ON user_groups.`user` = log.`user`
																	WHERE
																		log.module = 1
																		AND log.action = 3
														) AS approvers ON approvers.record = log.record
													WHERE
														log.module = 1
														AND log.action = 0
												) AS user_approver
												INNER JOIN requests ON requests.id = user_approver.record
												INNER JOIN authorizations ON authorizations.groupid = user_approver.usergroup
												INNER JOIN groups ON groups.id = user_approver.approvergroup
												AND authorizations.`group` = user_approver.approvergroup 
												AND requests.hub = authorizations.hubid 
											GROUP BY
												user_approver.record
								) AS last_authorized_indexes ON last_authorized_indexes.record = records.record
					) AS records_status
					LEFT JOIN authorizations ON authorizations.hubid = records_status.hub AND authorizations.groupid = records_status.user_group AND authorizations.`index` = records_status.max_authorized_index + 1
					LEFT JOIN groups ON groups.id = authorizations.`group`
	) AS requests_status ON requests_status.record = requests.id
	LEFT JOIN users AS drivers ON drivers.id = requests.driver
	INNER JOIN users AS responsables ON responsables.id = requests.responsable
	INNER JOIN vehicles ON vehicles.id = requests.vehicle
	INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand
	INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type 
	WHERE
	NOT requests.deleted 
	ORDER BY
	id DESC;

#Conductores
	/*
		Lista de conductores
		:STARTDATE fecha de inicio
		:ENDDATE fecha de fin
	*/
	#Todos los conductores
	#Comprobar permiso requests.listalldrivers
	SELECT id, `name` FROM users WHERE NOT deleted AND driversid != '''' AND NOT id IN (SELECT driver FROM requests WHERE NOT ISNULL(driver) AND NOT id IN (SELECT record FROM log WHERE module = 1 AND (action = 2 OR action = 5)) AND ((:STARTDATE BETWEEN departuredate AND returndate) OR (:ENDDATE BETWEEN departuredate AND returndate) OR (departuredate BETWEEN :STARTDATE AND :ENDDATE) OR (returndate BETWEEN :STARTDATE AND :ENDDATE))) AND (driversidduedate >= :ENDDATE);


	#Conductores en el grupo del usuario de sistema
	SELECT id, `name` FROM users WHERE NOT deleted AND driversid != '''' AND NOT id IN (SELECT driver FROM requests WHERE NOT ISNULL(driver) AND NOT id IN (SELECT record FROM log WHERE module = 1 AND (action = 2 OR action = 5)) AND ((:STARTDATE BETWEEN departuredate AND returndate) OR (:ENDDATE BETWEEN departuredate AND returndate) OR (departuredate BETWEEN :STARTDATE AND :ENDDATE) OR (returndate BETWEEN :STARTDATE AND :ENDDATE))) AND (driversidduedate >= :ENDDATE) AND id IN (SELECT DISTINCT `user` FROM user_group_drivers WHERE `group` IN (SELECT `group` FROM user_group_drivers WHERE `user` = :USERID));

#Consulta de Solicitudes |General	|Vehiculo	|Asistentes
	/*
		Carga los detalles de la solicitud especificada
		:ID identificador de la solicitud
	*/
	SELECT requests.*, municipalities.state, drivers.`name` AS driver_name, responsables.`name` AS responsable_name, hubs.caption AS labor_cap, `log`.`user` AS usercreated FROM requests LEFT JOIN users AS drivers ON drivers.id = requests.driver INNER JOIN users AS responsables ON responsables.id = requests.responsable INNER JOIN hubs ON hubs.id = requests.hub LEFT JOIN municipalities ON municipalities.id = requests.municipality INNER JOIN `log` ON `log`.record = requests.id AND `log`.module = 1 AND `log`.action = 0 WHERE requests.id = :ID;
	/*
		Detalles del vehículo seleccionado en una solicitud
		:ID identificador del vehículo seleccionado
	*/
	SELECT vehicles.*, vehiclesbrands.brand AS brand_cap, vehiclessubbrands.subbrand AS TYPE_CAP FROM vehicles INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type WHERE vehicles.id = :ID;
	/*
		Lista de asistentes en una solicitud
		:REQUEST id de la solicitud
	*/
	SELECT * FROM request_passengers WHERE request = :REQUEST;


/*
	Comprueba si un vehículo está disponible para una fecha definida
	:STARTDATE fecha de inicio
	:ENDDATE fecha de fin
	:VEHICLEID id de vehículo a comprobar
*/
SELECT 1 FROM requests WHERE NOT id IN (SELECT record FROM log WHERE log.module = 1 AND (log.action = 2 OR log.action = 5)) AND ((:STARTDATE BETWEEN departuredate AND returndate) OR (:ENDDATE BETWEEN departuredate AND returndate) OR (departuredate BETWEEN :STARTDATE AND :ENDDATE) OR (returndate BETWEEN :STARTDATE AND :ENDDATE)) AND vehicle = :VEHICLEID


/*
	Lista de áreas que requieren autorizar
	:HUBID eje seleccionado
	:USER id de usuario que crea o creó la solicitud
*/
SELECT groups.`name` FROM authorizations INNER JOIN groups ON groups.id = authorizations.`group` WHERE groupid = (SELECT `group` FROM user_groups WHERE `user` = :USER) AND hubid = :HUBID ORDER BY `index` ASC;

/*
	Nivel de usuario
	:USERID id de usuario de sistema
*/
SELECT authorizations.`index` FROM users INNER JOIN user_groups ON user_groups.`user` = users.id INNER JOIN groups ON user_groups.`group` = groups.id INNER JOIN authorizations ON authorizations.groupid = groups.id WHERE users.id = :USERID;


#Creacion de Solicitudes
	/*
		Creación de una nueva solicitud
		parámetros requests INSERT

		motivos
		destino
		id de municipio
		id de eje
		id de programa "NULL si no está seleccionado ninguno"
		Lugar de partida
		Fecha de partida
		Fecha de evento
		Lugar de retorno
		Fecha de retorno
		id de vehículo
		id de conductor "NULL si no está seleccionado" 
		|TRUE - FALSE| solicitar asignación de conductor a nivel superior, Verdadero si no se ha seleccionado ningún conductor, falso de otro modo
		id de responsable autorizado, 
		|TRUE - FALSE| verdadero si el solicitante no requiere autorizaciones posteriores, falso de otro modo

		parámetros request_passengers
					Nombre
					código
	*/
	#NUEVO REGISTRO
	INSERT INTO requests VALUES (NULL, %s, %s, %d, %d, %s, %s, %s, %s, %s, %s, %d, %s, %s, %d, %s, FALSE);
	SET @ID = LAST_INSERT_ID();
	INSERT INTO request_passengers VALUES (@ID, %s, %s);
						.
						.
						.
	INSERT INTO request_passengers VALUES (@ID, %s, %s);




	/*

		parámetros requests UPDATE
			id de vehículo
			id de conductor "NULL si no está seleccionado"
			id de la solicitud

	*/
	#ACTUALIZACIÖN
	SET @ID = :ID;
	UPDATE requests SET vehicle = %d, driver = %s WHERE id = @ID;
	DELETE FROM request_passengers WHERE request = @ID;
	INSERT INTO request_passengers VALUES (@ID, %s, %s);
						.
						.
						.
	INSERT INTO request_passengers VALUES (@ID, %s, %s);

#Registros del Registro(log)
	/*
		Registros
		:USER  id de usuario que realiza la acción
		:RECORD registro afectado
	*/
	#INICIO DE SESIÓN
	#INSERT INTO `log` VALUES (NULL, 9, 0, :USER, 6, '', NOW());

	#CIERRE DE SESIÓN
	#INSERT INTO `log` VALUES (NULL, 9, 0, :USER, 7, '', NOW());

	#IMPRESIÓN DE SOLICITUD
	#INSERT INTO `log` VALUES (NULL, 1, :RECORD, :USER, 4, '', NOW());

	#ELIMINACIÓN DE SOLICITUD
	#INSERT INTO `log` VALUES (NULL, 1, 1, :RECORD, :USER, 2, '', NOW());

	#Cancelacion
	#INSERT INTO `log` VALUES (NULL, 1, 1, :RECORD, :USER, 5, 'Motivos de cancelacion', NOW());
	#Motivos de cancelacion [required]

	#CREACIÓN DE SOLICITUD
	#INSERT INTO `log` VALUES (NULL, 1, :RECORD, :USER, 0, '', NOW());

	#ACTUALIZACIÓN DE SOLICITUD con cambio de conductor y vehículo
	INSERT INTO `log` VALUES (NULL, 1, :RECORD, :USER, 1, 'Cambió el conductor y el vehículo asignado', NOW());

	#ACTUALIZACIÓN DE SOLICITUD con cambio de conductor
	INSERT INTO `log` VALUES (NULL, 1, :RECORD, :USER, 1, 'Cambió el conductor asignado', NOW());

	#ACTUALIZACIÓN DE SOLICITUD con cambio de vehículo
	INSERT INTO `log` VALUES (NULL, 1,   	  , :RECORD, :USER,     1, , 'Cambió el vehículo asignado', NOW());
	
#Actualizacion de solicitud, solo se agrego el uno *1* 
	INSERT INTO 'log' VALUES (NULL, *1*, :MODULE, :RECORD, :USER, :ACTION, :DETAILS, NOW());


#Aceptar
#INSERT INTO `log` VALUES (NULL, 1, 1, :RECORD, :USER, 3, '', NOW());