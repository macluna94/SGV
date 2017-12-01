<style>
div{
	text-align: justify;
}
p, li{
	font-size: 11px;
	font-family: sans-serif;
}

#table_alumnos{
	font-family: sans-serif;
	font-size: 11px;
	border-collapse: collapse;
	border: 1px solid black;
}

table, td,th{
	font-family: sans-serif;
	font-size: 11px;
	border-collapse: collapse;
	border: 1px solid black;
}


</style>
<?php
	ob_start();
	include "session.php";
	include "php/connection.php";
	$idquest = 191;
	// $_GET['idquest'];

	$queryview = "SELECT requests.*, municipalities.state, drivers.`name` AS driver_name, responsables.`name` AS responsable_name, hubs.caption AS labor_cap, `log`.`user` AS usercreated FROM requests LEFT JOIN users AS drivers ON drivers.id = requests.driver INNER JOIN users AS responsables ON responsables.id = requests.responsable INNER JOIN hubs ON hubs.id = requests.hub LEFT JOIN municipalities ON municipalities.id = requests.municipality INNER JOIN `log` ON `log`.record = requests.id AND `log`.module = 1 AND `log`.action = 0 WHERE requests.id = $idquest";

	$view = mysqli_query($connection, $queryview);
	$row = mysqli_fetch_array($view);

	$infotrans = "SELECT
vehicles.id,
vehiclesbrands.brand,
vehicles.passengercapacity,
vehicles.economicno,
vehicles.type,
vehiclessubbrands.subbrand,
vehicles.licenseplates
FROM
	vehicles
INNER JOIN vehiclesbrands ON vehicles.brand = vehiclesbrands.id
INNER JOIN vehiclessubbrands ON vehicles.type = vehiclessubbrands.id
WHERE
	vehicles.id  = ".$row['vehicle'].";";
 
	$infott = mysqli_query($connection,$infotrans);
	$infot = mysqli_fetch_array($infott);
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

mysqli_query($connection,"INSERT INTO `log` VALUES (NULL,1, 1, $idquest, $id_user, 4, 'Impresion de Solicutd', NOW());");


?>

<img src="imgs/udg-norte.jpg" alt="udg-norte">

	<p><b>Mtro. Efrain de Jesús Gutiérrez Velázquez <br> Secretario Administrativo del CUNorte</b> <br> P R E S E N T E</p>

	<p>Por medio del presente escrito me permito saludarlo y a la vez solicitarle, en cumplimiento de nuestra labor de <?php echo $row['labor_cap'],", el vehiculo oficial tipo ", $infot['brand'],"con las placas ",$infot['licenseplates']," para la siguiente salida:"; ?></p>







	<table style="width: 100%;">
		<tr>
			<th colspan="6" style="text-align: center; background-color: #DCDCDC;">INSTITUCION A VISITAR O ACTIVIDADES A REALIZAR</th>
		</tr>
		<tr>
			<td colspan="6">  <?php echo $row['reasons']; ?> <br><br><br> </td>
		</tr>
		<tr>
			<th colspan="6" style="text-align: center; background-color: #DCDCDC;">ITINERARIO</th>
		</tr>
		<tr>
			<td colspan="2"><b>Municipio destino:</b>______________________</td>
			<td colspan="4"><b>Estado de destino:</b>______________________</td>
		</tr>
		<tr>
			<td colspan="1"><b>Lugar de salida:</b></td>
			<td> <?php echo $row['departureplace']; ?></td>
			<td><b>Fecha de Salida:</b> </td> 
			<td>  <?php $date = new DateTime(substr($row['departuredate'], 0,-9));echo "",$date->format("d/m/Y"),"";?>   </td>
			<td><b>Hora de salida:</b></td>
			<td><?php echo substr($row['departuredate'],-8,5); ?></td>
		</tr>
		<tr>
			<td><b>Lugar de evento:</b></td>
			<td><?php echo $row['destination']; ?></td>
			<td><b>Fecha de evento:</b></td>
			<td><?php $date = new DateTime(substr($row['eventdate'], 0,-9));echo "",$date->format("d/m/Y"),"";?></td>
			<td><b>Hora del evento:</b></td>
			<td><?php echo substr($row['eventdate'],-8,5); ?></td>
		</tr>
		<tr>
			<td><b>Lugar de retorno:</b></td>
			<td><?php echo $row['returnplace']; ?></td>
			<td><b>Fecha de retorno:</b></td>
			<td><?php $date = new DateTime(substr($row['returndate'], 0,-9));echo "",$date->format("d/m/Y"),"";?></td>
			<td><b>Hora de retorno:</b></td>
			<td><?php echo substr($row['returndate'],-8,5); ?></td>
		</tr>
	</table>


<br>

<table style="border: 0px; width: 100%">
		<tr style="border: 0px;">
			<td style="border: 0px;"><b>Responsable del viaje:</b></td>
			<td style="border: 0px;"><?php echo $row['responsable_name']; ?></td>
			<td style="border: 0px;"><b>Programa educativo: </b></td>
			<td style="border: 0px;">_______________________</td>
		</tr>
		<tr>
			<td style="border: 0px;"><b>Conductor del vehiculo:</b> </td>
			<td style="border: 0px;"><?php echo $row['driver_name']; ?></td>
			<td style="border: 0px;"><b>No. Licencia:</b></td>
			<td style="border: 0px;">______________________</td>
		</tr>
</table>


<p> <b>
	
Relación de personas a transportar, incluyendo chofer y responsable:
</b>
</p>

<?php 

$query_list = "SELECT * FROM request_passengers WHERE request = $idquest";
		$Tlist = mysqli_query($connection, $query_list);

$num_list = mysqli_num_rows($Tlist);
$i = 1;
$j = $num_list+1;

		echo '<table id="table_alumnos" style ="width: 100%">';
	if ($num_list != 0) {
		echo '
		<tr style="text-align: center; background-color: #DCDCDC;" >
		<th>#</th>
		<th>Nombre</th>
		<th>Código</th>
		</tr>
		';

		
		while ($list = mysqli_fetch_array($Tlist)){
			echo "<tr><td>".$i."</td><td>".$list['name']."</td><td>".$list['code']."</td></tr>";
			$i++;
		}

		while ($j <= 23) {
			echo "<tr><td>".$j."</td><td></td><td></td></tr>";
			$j++;
		}

echo "</table>"; 	
}
else{

}
 ?>


 <br>

<p>Nos comprometemos a cumplir con los requisitos y obligaciones del solicitante del vehículo, a saber.</p>

<p><b>CONSIDERACIONES PARTICULARES PARA SLICITAR VEHÍCULO OFICIAL</b></p>

<ul>
	<li>
		La solicitud debera presentarse 72 horas previas al viaje, por una sola salida y no por el viaje redondo.
	</li>
	<li>
		Después de presentada la solicitud, se le notificará al solicitante mediante correo electronico la resolución de la misma en un periodo no mayor a 48 horas.
	</li>
	<li>
		La autorización está sujeta a cancelaciones con previo aviso, por la presentación de imprevistos que imposibiliten la salida.
	</li>
</ul>


<p><b>REQUISITOS DEL CONDUCTOR DE VEHÍCULOS OFICIALES </b> </p>
<ul>
	<li>Ser trabajador Universitario de Cunorte; llenar y firmar bitácora de salida</li>
	<li>Copia de licencia de chofer o conductor vigente ( según el vehículo solicitado ).</li>
</ul>

<P><b>CONSIDERACIONES PARTICULARES PARA SOLICITAR </b></P>

<ul>
	<li>
		Que los ocupantes se apeguen a una tolerancia de salida de 10 minutos.
	</li>
	<li>
		Cuando haya responsable de grupo, el conductor sólo acordará con él.
	</li>
	<li>
		al conductor no le corresponde pagar peaje, combustible, estacionamientos, parquímetros, su hospedaje o sus propios alimentos; cuyo gasto correrá a cargo del solicitante del vehículo. Si el chofer del vehículo no tiene cubiertos de manera anticipada dichos gastos, no se entregará el vehículo que se haya autorizado. por lo tanto, nos comprometemos a cubrir de manera previa a la salida, al conductor, lo siguiente:
	</li>
</ul>


	
<table id="table_alumnos" style="width: 80%;margin-left: 10%;">
	<tr>	
		<td style="text-align: center; background-color: #DCDCDC;"><b>Hospedaje</b></td>
		<td >___________</td>
		<td style="text-align: center; background-color: #DCDCDC;"><b>Combustibles</b></td>
		<td>___________</td>
	</tr>
	<tr>	
		<td style="text-align: center; background-color: #DCDCDC;"><b>Alimentos</b></td>
		<td>___________</td>
		<td style="text-align: center; background-color: #DCDCDC;"><b>Estacionamiento</b></td>
		<td>___________</td>
		<td style="text-align: center; background-color: #DCDCDC;"><b>Peaje</b></td>
		<td>____________</td>
		<td style="text-align: center; background-color: #DCDCDC;"><b>Estacionómetro</b></td>
		<td>___________</td>
	</tr>
</table>



<ul>
	<li>Evitar en la medida de lo posible la transportación de miembros de la comunidad universitaria en horas avanzadas de la noche, procurado su hospedaje, para la salvaguarda de la integridas fisica de los mismos.</li>
	<li>Cuando la naturaleza de la actividad requiera de la transportación de menores de esas, la solicitud del vehículo deberá acompañarse con escritos de consentimiento de los padres de familia, en el formato preestablecido.</li>
</ul>

<P> <b>OBLIGACIONES DEL RESPONSABLE, CONDUCTOR Y USUARIOS DE VEHÍCULOS OFICIALES: </b></P>

<table style="border: 0px;">
	<tr>
	<td style="border: 0px;">
		<ul>
			<li>Seran pagadas por el chofer del vehiculo, las infracciones de transito que se recibieran por el desarrollo del viaje.</li>
			<li>Comprobar la existencia de documentación en el vehiculo (tarjeta de circulacion, placas, poliza y reglamento).</li>
			<li>Cuidar la imagen institucional de la Universidad de Guadalajara, trasladandose sin descortesias de transito o prepotencia.</li>
			<li>Revisar el vehiculo al recibirlo.</li>
			<li>No rebasar los horarios del itinerario.</li>
			<li>Reportar cualquier falla mecanica o funcional del vehiculo.</li>
			<li>Respetar el tiempo de tolerancia.</li>
		</ul>
	</td>
	<td style="border: 0px;">
		<ul>
			<li>Ocupar el vehiculo unicamente con las personas que se relacionaron en esta solicitud.</li>
			<li>Reportar el robo, accidente, o cualquier daño a la compañia de seguros y a quien dio la autorizacion de salida del vehiculo oficial.</li>
			<li>No ingerir bebidas embriagantes o consumir estupefacienes en el vehiculo oficial o durante el trayecto del itinerario.</li>
			<li>No desviarse del itinerario.</li>
			<li>Salir a tiempo.</li>
			<li>Devolver el vehiculo en las mismas condiciones en que se le entrego</li>
		</ul>
	</td>
	</tr>
	<tr>
		<td colspan="2" style="border: 0px;">
				<p style="text-align: center;">Sin otro particular por el momento, me despido de usted, agradeciendo de antemano la atencion brindada.</p>
		</td>
	</tr>
</table>


<br>
<div style="text-align: center; line-height: 0.5;">
	<p  style="letter-spacing: 3px;">ATENTAMENTE</p>
	<p style="font-size: 14px;"><b>"Piensa y Trabaja"</b></p>
	<p>Colotlán, Jalisco a [#####]</p>
</div>

<br>

<table style="width: 100%">
	<th style="text-align: center; background-color: #DCDCDC;">Nombre y firma del olicitante</th>
	<th style="text-align: center; background-color: #DCDCDC;">Nombre y firma del Jefe de Departamento <br> (en caso de que la solicitud sea de docencia)</th>
</tr>
<tr>
	<td>Nombre[##########]</td>
	<td> <br> <br> <br>  ___________________</td>
</tr>
</table>

<br>
<table style="width: 100%">
	<tr>
		<th colspan="3" style="text-align: center; background-color: #DCDCDC;"> AUTORIZACIÓN DE LA SALIDA DEL VEHÍCULO</th>
	</tr>

	<td style="text-align: center;">FIRMA <br> <br>Mtro. Efraín de Jesús Gutiérrez Velázquez</td>
	<td style="text-align: center;" colspan="3"> <br> SELLO <br> </td>
</table>










