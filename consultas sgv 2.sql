/*
	Lista de rubros
*/
#SELECT * FROM items ORDER BY caption;

/*
	Cargar los gastos de una solicitud especifica
	:REQUEST id de la solicitud
*/
SELECT caption, item, value FROM request_items INNER JOIN items ON items.id = request_items.item WHERE request = :REQUEST;

/*
	Documentación requerida por rubro
	:HUB Eje seleccionado
*/
SELECT documents.* FROM documents INNER JOIN hub_documents ON hub_documents.document = documents.id WHERE hub_documents.hub = :HUB;

/* 
	Carga el archivo coeespondite a la solicitud
	:REQUEST id de la solicitud
*/
SELECT documents.id, documents.document, request_documents.filename, request_documents.filesize, request_documents.id AS fileid FROM request_documents INNER JOIN documents ON request_documents.document = documents.id WHERE request_documents.request = :REQUEST;

/*
	Tipos de archivos permitidos por documento
	:DOCUMENT id del documento
*/
SELECT filters.`name`, filters.filters FROM filters INNER JOIN document_filters ON document_filters.filter = filters.id WHERE document_filters.document = :DOCUMENT;

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
				id de vehículo
				id de conductor "NULL si no está seleccionado"
					solicitar asignación de conductor a nivel superior, Verdadero si no se ha seleccionado ningún conductor, falso de otro modo
				id de responsable
					autorizado, verdadero si el solicitante no requiere autorizaciones posteriores, falso de otro modo
	
	parámetros requests UPDATE
				id de vehículo
				id de conductor "NULL si no está seleccionado"
				id de la solicitud

	parámetros request_passengers
				Nombre
				código

	parámetros request_items
				id de rubro
				valor en decimal



NOTA todos los rubros deben ser motrados vacíos y deben ser especificados por el usuario del programa en sus valores al momento de crear la solicitud
Los archivos adjuntos deben ser cargados en el directorio temporal php y copiados a la carpeta files relativa a los scripts php
una vez copiados todos los arjuntos y ejecutada la consulta de insert se considera completado el proceso correctamente
si ocurre un error los adjuntos dallidos deben ser eliminados y la transacción SQL debe ser rollback
*/
#NUEVO REGISTRO
INSERT INTO requests VALUES (NULL, %s, %s, %d, %d, %s, %s, %s, %s, %s, %s, %d, %s, %s, %d, %s, FALSE);
SET @ID = LAST_INSERT_ID();

INSERT INTO request_passengers VALUES (@ID, %s, %s);
					.
INSERT INTO request_passengers VALUES (@ID, %s, %s);


INSERT INTO request_items VALUES(@ID, %d, %f);
					.
INSERT INTO request_items VALUES(@ID, %d, %f);


/*
	parámetros request_documents
				id de documento
				nombre de archivo con extensión incluída
				tamaño de archivo en bytes
*/

INSERT INTO request_documents VALUES (NULL, @ID, %d, %s, %d);
					.
					.
					.
INSERT INTO request_documents VALUES (NULL, @ID, %d, %s, %d);

#ACTUALIZACIÖN
UPDATE requests SET vehicle = %d, driver = %s WHERE id = :ID;