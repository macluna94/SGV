<!DOCTYPE html>
<html>
 <head>
 	<title>Vista de Solicitud</title>
 	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <script src="js\jquery.min.js"></script>
    <script src="js\bootstrap.min.js"></script>
    <script src="js\datatables.js"></script>
  
	<?php 
		include "php/connection.php";
		$idquest = $_GET['idquest'];

		$queryview = "SELECT
			requests.id,
			requests.reasons,
			requests.destination,
			requests.municipality,
			requests.hub,
			requests.program,
			requests.departureplace,
			requests.departuredate,
			requests.eventdate,
			requests.returnplace,
			requests.returndate,
			requests.vehicle,
			requests.driver,
			requests.requestdrivertolastlevel,
			requests.responsable,
			requests.approved,
			requests.deleted,
			municipalities.state,
			drivers.`name` AS driver_name,
			responsables.`name` AS responsable_name,
			hubs.caption AS labor_cap,
			log.`user` AS usercreated,
			municipalities.`name`
			FROM
			requests
			LEFT JOIN users AS drivers ON drivers.id = requests.driver
			INNER JOIN users AS responsables ON responsables.id = requests.responsable
			INNER JOIN hubs ON hubs.id = requests.hub
			LEFT JOIN municipalities ON municipalities.id = requests.municipality
			INNER JOIN log ON log.record = requests.id
			AND log.module = 1
			AND log.action = 0
			WHERE
			requests.id =  $idquest
";

		$view = mysqli_query($connection, $queryview);

		$row = mysqli_fetch_array($view);

		$infotrans = "SELECT vehicles.id, vehiclesbrands.brand, vehicles.passengercapacity, vehicles.economicno, vehicles.type, vehiclessubbrands.subbrand FROM vehicles INNER JOIN vehiclesbrands ON vehicles.brand = vehiclesbrands.id INNER JOIN vehiclessubbrands ON vehicles.type = vehiclessubbrands.id WHERE vehicles.id = ".$row['vehicle'].";";
$estados = array("Aguascalientes","Baja California","Baja California Sur","Campeche","Chiapas","Chihuahua","Ciudad de México","Coahuila","Colima","Durango","Guanajuato","Guerrero","Hidalgo","Jalisco","México","Michoacán","Morelos","Nayarit","Nuevo León","Oaxaca","Puebla","Querétaro","Quintana Roo","San Luis Potosí","Sinaloa","Sonora","Tabasco","Tamaulipas","Tlaxcala","Veracruz","Yucatán","Zacatecas");
$programas = array("Lic. en Administración","Lic. en Agronegocios","Lic. en Antropología","Lic. en Contaduría Pública","Lic. en Derecho","Lic. en Enfermería","Lic. en Nutrición","Lic. en Psicología","Lic. en Turismo","Ing. en Electrónica y Computación","Ing. en Mecánica Eléctrica","Maestría en Administración de Negocios","Maestría en Derecho","Maestría en Salud Pública","Maestría en Tecnologías para el Aprendizaje");


		$infott = mysqli_query($connection,$infotrans);
		$infot = mysqli_fetch_array($infott);
	 ?>

 </head>
<body>
	<div class="container">
		
		<div class="panel-group">
			<div class="panel panel-warning" id="motivos-panel">
				<div class="panel-heading">	
					<h4><label class="control-label">Instituciones a visitar o Actividades a realizar</label></h4>
				</div>
				<div class="panel-body">
					<div class="form-group row">
						<div class="col-xs-6">
							<textarea  name="motivo" class="form-control text-center" rows="4" disabled="yes" style="margin: 0px -356px 0px 0px;resize: none; height: 89px; width: 686px;" > <?php echo $row['reasons']; ?></textarea>
						</div>
						<div class="col-xs-6"></div>
					</div>
				</div>
			</div>

			<div class="panel panel-info" id="itinerario-panel">
				<div class="panel-heading">
					<h4> <label class="control-label">Itinerario</label></h4>
				</div>
				<div class="panel-body">
					<div class="form-group row">
						<div class="col-xs-4">
							<div class="form-group">
								
								<label class="control-label">Lugar  de destino o arribo</label>
								<input  type="text" class="form-control text-center" value="<?php echo $row['destination']; ?>" disabled>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<label class="control-label">Estado:</label>
								<input type="text" class="form-control text-center" name="estado" value="<?php $x = $row['state'];echo $estados[$x-1]; ?>" disabled>
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<label class="control-label">Municipio:</label>
								<input type="text" class="form-control text-center" name="municipio" value="<?php echo $row['name'] ?>" disabled>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-xs-6">
							<div class="form-group">
								<label class="label-control">Ejes:</label>
								<input type="text" class="form-control text-center" value="<?php echo $row['labor_cap'] ?>" disabled>
							</div>
						</div>
						<div class="col-xs-6">
							<?php 
								if ($row['program'] != NULL) {
									$y = $row['program'];
									echo '	<div class="form-group">
												<label class="control-label">Programa:</label>
												<input type="text"  class="form-control text-center" name="" value="'.$programas[$y-1].'"  disabled>
											</div>';
								}								else{}
							?>
						</div>
					</div>

					<div class="form-group row">
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label">Lugar de Salida</label>
								<input type="text" class="form-control text-center" value="<?php echo $row['departureplace']?>" disabled > 
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label">Fecha(Salida)</label>
								<input type="text" class="form-control text-center" value="<?php echo $row['departuredate'] ?>" disabled>
							</div>
						</div>

						<div class="col-xs-6">
						</div>
						<div class="col-xs-6">
							<div class="form-gropu">
								<label class="control-label">Fecha de evento</label>
								<input type="text" class="form-control text-center" value="<?php echo $row['eventdate'] ?>" disabled>	
							</div>
						</div>

						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label">Lugar de Retorno</label>			
								<input type="text" class="form-control text-center" value="<?php echo $row['departureplace']?>" disabled>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label>Fecha y Hora (Retorno)</label>
								<input type="text" class="form-control text-center" value="<?php echo $row['returndate'] ?>" disabled>
							</div>
						</div>

					</div>

				</div>
			</div>	

			<div class="panel panel-primary" id="transporte">
				<div class="panel-heading">
					<div class="form-group row">
						<div class="col-xs-6">
							<h4><label class="control-label">
								
							Transporte
							</label>
						</h4>
							
						</div>
						<div class="col-xs-6"></div>
					</div>

				</div>
				<div class="panel-body">
					<div class="table table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-center">Marca</th>
									<th class="text-center">Tipo</th>
									<th class="text-center">Capacidad</th>
									<th class="text-center">No. Economico</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-center"><?php echo $infot['brand']; ?></td>
									<td class="text-center"><?php echo $infot['subbrand']; ?></td>
									<td class="text-center"><?php echo $infot['passengercapacity']; ?>    pasajeros</td>
									<td class="text-center"><?php echo $infot['economicno']; ?> </td>
								</tr>
							</tbody>
						</table>
					</div>
						<div class="form-group row">
						<div class="col-xs-6">
							<label class="control-label">Conductor: </label>
							<input type="text" value="<?php echo $row['driver_name'] ?>" class="form-control text-center" disabled>
						</div>
						<div class="col-xs-6">
							<label class="control-label">Responsable:</label>
							<input type="text" class="form-control text-center" value="<?php echo $row['responsable_name'] ?>" disabled>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-danger" id="asistentes">
				<div class="panel-heading">
					<h4>
						<label class="control-label">
							
						
						
					Relación de personas a transportar
						</label>
				</h4>
					
				</div>

				<div class="panel-body">
					<div class="form-group row">
						<div class="col-xs-4"></div>
						<div class="col-xs-4">
						<h4 class="text-center"><strong> Lista de Asistentes </strong></h4>
						</div>
						<div class="col-xs-4"></div>
						</div>

					<div class="form-group row">
						<?php 
							$query_list = "SELECT * FROM request_passengers WHERE request = $idquest";
							$Tlist = mysqli_query($connection, $query_list);


							if (mysqli_num_rows($Tlist) != 0) {
							$i = 1;

							echo "<div class='fluid-container'><table class='table table-bordered'><thead><tr class='info'><th class='text-center'>#</th><th>Nombre</th><th>Codigo</th></tr></thead><tbody>";

							while ($list = mysqli_fetch_array($Tlist)){
							echo "<tr><td class='text-center'>".$i."</td><td>".$list['name']."</td><td>".$list['code']."</td></tr>";
							$i++;
							}
							echo "</tbody></table> </div>";
							}
							else{
							echo '<div class="alert alert-warning text-center"><strong>No hay lista de asistentes.</strong></div>';
							}
						?>	
					</div>
				</div>
			</div>

			<div class="panel panel-default" id="costos">
				
				<div class="panel-heading">
					<h4><label class="label-control">Compromisos de pago del solicitante al chofer</label></h4>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Concepto</th>
									<th>Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$items = "SELECT request_items.request, request_items.item, request_items.`value`, items.caption FROM request_items INNER JOIN items ON request_items.item = items.id WHERE request = $idquest";

									$list_items = mysqli_query($connection, $items);

									while ($item = mysqli_fetch_array($list_items)) {
										echo "<tr><td>".$item['caption']."</td><td>$ ".$item['value']."</td>";
									}
								?>	
							</tbody>
						</table>			
					</div>
				</div>
			</div>

<div class="panel panel-warning" id="docs">
<div class="panel-heading">
	<h4>
		<label class="label-control">
			Documentos
		</label>
	</h4>
</div>
<div class="panel-body">
	<div class="container">
		

<?php 
 $query_docs = "SELECT
request_documents.id,
request_documents.request,
request_documents.document,
request_documents.filename
FROM
request_documents
WHERE
	request = $idquest";

							$docs = mysqli_query($connection, $query_docs);
								echo "<label>Documentos cargados</label>:<br> <ul>";
							while ($dcs = mysqli_fetch_array($docs)){
								echo '<li> <a href="tmp_folder/'.$dcs['filename'].'">'.$dcs['filename'].'</a></li>';

							}

echo "</ul>";

 ?>
	</div>
</div>

</div>



<br>
				<div class="panel-footer">
					<div class="form-group row">
						<div class="col-xs-10"></div>
						<div class="col-xs-2">
							<button type="button" class="btn btn-danger" onclick="window.close()">Cerrar</button>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

</body>
</html>