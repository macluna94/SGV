






INSERT INTO requests VALUES (NULL, 0, 'motivos', NULL, 'destino', (SELECT id FROM hubs WHERE caption = 'Docencia'), 'salida', "2017-06-01 00:00", 'retorno', "2017-06-01 23:59", 1, (SELECT id FROM users WHERE name = 'Noe Zermeño Mejía'), (SELECT id FROM users WHERE name = 'Luis Angel López Velazco'), NOW(), NULL, FALSE);



SET @ID = LAST_INSERT_ID();
INSERT INTO request_passengers VALUES (@ID, 'Luis Angel López Velazco', 	213386463	);
INSERT INTO request_passengers VALUES (@ID, 'Manuel Alberto Luna',  		213386444	);
INSERT INTO request_passengers VALUES (@ID, 'Jorge Adrián Larios',  		213388888	);
INSERT INTO request_passengers VALUES (@ID, 'Misael Tc',            		213386444	);
INSERT INTO request_passengers VALUES (@ID, 'Rafael De La Torre',   		202020202   );
