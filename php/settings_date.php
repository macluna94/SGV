<?php header('Content-Type: text/html; charset=UTF-8');
// Configuracion de Zona Horaria


date_default_timezone_set("America/Mexico_City");
			// Configuracion de calendario, 1+ dia a la fecha actual
			date_add(($date_now = date_create(date("Y-m-d"))), date_interval_create_from_date_string('1 days'));
			// Fecha post-registro
			$date_init = date_format($date_now, 'Y-m-d');

			date_add(($date_post = date_create(date("Y-m-d"))), date_interval_create_from_date_string('2 days'));

			$date_event = date_format($date_post,"Y-m-d");

			date_add(($date_post = date_create(date("Y-m-d"))), date_interval_create_from_date_string('4 days'));

			$date_return = date_format($date_post,'Y-m-d');
?>
