<?php
	ob_start();
	include "../session.php";
	include "../php/connection.php";
	$idquest = $_GET['idquest'];

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



	$query_list = "SELECT * FROM request_passengers WHERE request = $idquest";
	$Tlist = mysqli_query($connection, $query_list);

	$num_list = mysqli_num_rows($Tlist);
	$i = 1;
	$j = $num_list+1;
?>
<!--
<style type="css" media="screen">
		p,li{
			text-align: justify-all;
			font-size: 12px;
		}
		table, td,th{
		font-size: 12px;
		border-collapse: collapse;
		border: 1px solid black;
		}
		.lista{
			font-size: 11px;
		}
</style>
-->
<page backtop="0mm" backbottom="0mm" backleft="10mm" backright="10mm" style="text-align: justify;" >
	<img src="../imgs/udg-norte.jpg" alt="udg-norte">
	<p style="font-size: 12px; text-align: justify;"><b>Mtro. Efrain de Jesús Gutiérrez Velázquez <br> Secretario Administrativo del CUNorte</b> <br> P R E S E N T E</p>
	<p style="font-size: 12px;text-align: justify;" >Por medio del presente escrito me permito saludarlo y a la vez solicitarle, en cumplimiento de nuestra labor de <?php echo $row['labor_cap'],", el vehiculo oficial tipo ", $infot['brand'],"con las placas ",$infot['licenseplates']," para la siguiente salida:"; ?></p>
	<table style=width: 100%;">
	<tr>
	<th colspan="6" style="font-size: 12px; text-align: center; background-color: #DCDCDC;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	INSTITUCION A VISITAR O ACTIVIDADES A REALIZAR  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</th>
	</tr>
	<tr>
	<td colspan="6">  <?php echo $row['reasons']; ?> <br><br><br> </td>
	</tr>
	<tr>
	<th colspan="6" style="font-size: 12px; text-align: center; background-color: #DCDCDC;">ITINERARIO</th>
	</tr>
	<tr>
	<td colspan="2"><b>Municipio destino:</b> ______________________
	<br>
	<br>
	</td>
	<td colspan="4"><b>Estado de destino:</b>______________________
	<br><br>


	</td>
	</tr>
	<tr>
	<td colspan="1"><b>Lugar de salida:</b> <br> <br></td>
	<td> <?php echo $row['departureplace']; ?></td>
	<td><b>Fecha de Salida:</b> <br> </td> 
	<td>  <?php $date = new DateTime(substr($row['departuredate'], 0,-9));echo "",$date->format("d/m/Y"),"";?>   </td>
	<td><b>Hora de salida:</b> <br><br> </td>
	<td><?php echo substr($row['departuredate'],-8,5); ?></td>
	</tr>
	<tr>
	<td><b>Lugar de evento:</b> <br></td>
	<td><?php echo $row['destination']; ?></td>
	<td><b>Fecha de evento:</b> <br> </td>
	<td><?php $date = new DateTime(substr($row['eventdate'], 0,-9));echo "",$date->format("d/m/Y"),"";?></td>
	<td><b>Hora del evento:</b><br><br> </td>
	<td><?php echo substr($row['eventdate'],-8,5); ?></td>
	</tr>
	<tr>
	<td><b>Lugar de retorno:</b>	<br>	</td>
	<td><?php echo $row['returnplace']; ?></td>
	<td><b>Fecha de retorno:</b></td>
	<td><?php $date = new DateTime(substr($row['returndate'], 0,-9));echo "",$date->format("d/m/Y"),"";?></td>
	<td><b>Hora de retorno:</b> <br><br> </td>
	<td><?php echo substr($row['returndate'],-8,5); ?></td>
	</tr>
	</table>
	<br>


	<table style="border: 0px;">

	<tr style="border: 0px;">
	<td style="border: 0px;"><b>Responsable del viaje:</b></td>
	<td style="border: 0px;"><?php echo $row['responsable_name']; ?></td>
	<td style="border: 0px;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<b>Programa educativo: </b></td>
	<td style="border: 0px;">________________________</td>
	</tr>
	<tr>
	<td style="border: 0px;"><b>Conductor del vehiculo:</b> </td>
	<td style="border: 0px;"><?php echo $row['driver_name']; ?></td>
	<td style="border: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>No. Licencia:</b></td>
	<td style="border: 0px;">________________________</td>
	</tr>
	</table>
	
	<p><b>Relación de personas a transportar, incluyendo chofer y responsable:</b></p>
	<?php 
	echo '<table class="lista">';
	if ($num_list != 0) {
	echo '
	<tr style="text-align: center; background-color: #DCDCDC;" class="lista">
	<th class="lista">No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
	<th class="lista" style="text-align: center;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Nombre
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</th>
	<th class="lista">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Código
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</th>
	</tr>
	';


	while ($list = mysqli_fetch_array($Tlist)){
	echo "<tr class='lista'><td class='lista'>".$i."</td><td class='lista'>".$list['name']."</td><td class='lista'>".$list['code']."</td></tr>";
	$i++;
	}

	while ($j <= 23) {
	echo "	<tr>
	<td class='lista' >".$j."</td><td></td><td></td>
	</tr>";
	$j++;
	}

	echo "</table>"; 	
	}
	else{
	}
	?>

	<p>Nos comprometemos a cumplir con los requisitos y obligaciones del solicitante del vehículo, a saber.
	<br><br>
	<b>CONSIDERACIONES PARTICULARES PARA SOLICITAR VEHÍCULO OFICIAL</b></p>
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

		
			<div style="text-align: center; font-family: times;">
				Carretera Federal No. 23, Km. 191, C.P: 46200,
				<br>
				Colotlán, Jalisco, México. Tels. 01 499 992 1333 / 0110 / 2467 / 2466
			</div>
			<p style="text-align: center;"><b>www.cunorte.udg.mx</b></p>
	
	
</page>


<page backtop="5mm" backbottom="5mm" backleft="10mm" backright="15mm">

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
			Al conductor no le corresponde pagar peaje, combustible, estacionamientos, parquímetros, su hospedaje o sus propios alimentos; cuyo gasto correrá a cargo del solicitante del vehículo. Si el chofer del vehículo no tiene cubiertos de manera anticipada dichos gastos, no se entregará el vehículo que se haya autorizado. por lo tanto, nos comprometemos a cubrir de manera previa a la salida, al conductor, lo siguiente:
		</li>
	</ul>
	<table align="center" >
		<tr style="padding-left: 50px;">	
			<td style="text-align: center; background-color: #DCDCDC;">
				
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<b>Hospedaje</b>

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			</td>
				<td >___________</td>
				<td style="text-align: center; background-color: #DCDCDC;">

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

					<b>Combustibles</b>;
				
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				</td>
				<td>___________</td>
			</tr>
			<tr>
				<td style="text-align: center; background-color: #DCDCDC;"><b>Alimentos</b></td>
				<td>___________</td>
				<td style="text-align: center; background-color: #DCDCDC;"><b>Estacionamiento</b></td>
				<td>___________</td>
			</tr>
			<tr>
				<td style="text-align: center; background-color: #DCDCDC;"><b>Peaje</b></td>
				<td>____________</td>
				<td style="text-align: center; background-color: #DCDCDC;"><b>Estacionómetro</b></td>
				<td>___________</td>
			</tr>
		</table>

		<page_footer>
			<div id="pie_pagina">
				<div style="text-align: center; font-family: times;">
					Carretera Federal No. 23, Km. 191, C.P: 46200,
					<br>
					Colotlán, Jalisco, México. Tels. 01 499 992 1333 / 0110 / 2467 / 2466
				</div>
				<p style="text-align: center;"><b>www.cunorte.udg.mx</b></p>
				<br><br>
			</div>
		</page_footer>
</page>


<?php
	$content = ob_get_clean();
	require '../vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;
	use Spipu\Html2Pdf\Exception\Html2PdfException;
	use Spipu\Html2Pdf\Exception\ExceptionFormatter;
	try{
		$html2pdf = new Html2Pdf('P', 'LETTER', 'es', true,'UTF-8');
		$html2pdf->pdf->SetDisplayMode('fullpage');
		#$html2pdf->setModeDebug();
		$html2pdf->addFont("Times");
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('sgv_doc.pdf');
	}
	catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
	}
?>