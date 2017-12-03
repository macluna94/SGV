<?php 
	include "connection.php";
	include "../session.php";

	
	$date_s = $_GET['date_salida'];
	$date_r = $_GET['date_retorno'];
	$time_salida = $_GET['time_salida'];
	$time_retorno = $_GET['time_retorno'];
	$date_salida = $date_s . " ". $time_salida;
	$date_retorno = $date_r . " " . $time_retorno;


$query_car = 	"SELECT
				vehicles.id,
				vehicles.economicno,
				vehicles.passengercapacity,
				vehiclesbrands.brand AS brand_cap,
				vehiclessubbrands.subbrand AS type_cap
				FROM vehicles
				INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand
				INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type
				WHERE NOT deleted AND NOT vehicles.id 
				IN ( SELECT vehicle FROM requests WHERE NOT id 
				IN ( SELECT record FROM log WHERE log.module = 1
				AND (log.action = 2 OR log.action = 5)
				)
				AND ( ('$date_salida' BETWEEN departuredate AND returndate)
				OR ('$date_retorno' BETWEEN departuredate AND returndate)
				OR (departuredate BETWEEN '$date_salida' AND '$date_retorno')
				OR (returndate BETWEEN '$date_salida' AND '$date_retorno')
				)
				);";

if (!$connection) {die('Could not connect: ' . mysqli_error($connection));}




	mysqli_select_db($connection,"ajax_demo");
	
	$result = mysqli_query($connection,$query_car);
	echo '


<table class="table table-striped">
<thead>
	<th></th>
	<th>Marca</th>
	<th>Tipo</th>
	<th>Capacidad</th>
	<th></th>
</thead>
	<tbody>';
	while($list = mysqli_fetch_array($result)) {
		echo '<tr id="tabs">

		<td><input type="hidden" value="'.$list['id'].'" id="s_car"></td>
		<td class="text-center"  id="b'.$list['id'].'"  >' . $list["brand_cap"] . '</td>
		<td class="text-center"  id="t'.$list['id'].'"  >' . $list["type_cap"] . '</td>
		<td class="text-center"  id="p'.$list['id'].'"  >' . $list["passengercapacity"] . '</td>
		<td><button type="button" class="btn btn-success"  value="'.$list['id'].'" onclick="auto(this);"  data-dismiss="modal" >Seleccionar</button></td>
		</tr>';
	}
	echo "</tbody></table>

	";
	mysqli_close($connection);



?>