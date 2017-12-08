<?php 
	include "../session.php"; 
	$id = $id_user;
?>
<!DOCTYPE html>
	<html lang="es" dir="ltr">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="content-type" content="text/html; UTF-8">
			<title>Solicitudes</title>
			
			<link rel="stylesheet" href="../css\bootstrap.min.css">
			<script src="../js\jquery.min.js"></script>
			<script src="../js\bootstrap.min.js"></script>
			<script src="../js\showdriver.js"></script>

			<style type="text/css">
				.modal-header{
					background-color: #1565d3;
					color:white !important;
					text-align: center;
				}
			</style>

			<?php
				header('Content-Type: text/html; charset=UTF-8');
				include "../php/connection.php";
				$idquest = $_GET['idquest'];

				include "../php/querys.php";
				include "../php/settings_date.php";

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
					requests.id = $idquest";
				$view = mysqli_query($connection, $queryview);
				$row = mysqli_fetch_array($view);
				$infotrans = "SELECT vehicles.id, vehiclesbrands.brand, vehicles.passengercapacity, vehicles.economicno, vehicles.type, vehiclessubbrands.subbrand FROM vehicles INNER JOIN vehiclesbrands ON vehicles.brand = vehiclesbrands.id INNER JOIN vehiclessubbrands ON vehicles.type = vehiclessubbrands.id WHERE vehicles.id = ".$row['vehicle'].";";
				$estados = array("Aguascalientes","Baja California","Baja California Sur","Campeche","Chiapas","Chihuahua","Ciudad de México","Coahuila","Colima","Durango","Guanajuato","Guerrero","Hidalgo","Jalisco","México","Michoacán","Morelos","Nayarit","Nuevo León","Oaxaca","Puebla","Querétaro","Quintana Roo","San Luis Potosí","Sinaloa","Sonora","Tabasco","Tamaulipas","Tlaxcala","Veracruz","Yucatán","Zacatecas");
				$programas = array("Lic. en Administración","Lic. en Agronegocios","Lic. en Antropología","Lic. en Contaduría Pública","Lic. en Derecho","Lic. en Enfermería","Lic. en Nutrición","Lic. en Psicología","Lic. en Turismo","Ing. en Electrónica y Computación","Ing. en Mecánica Eléctrica","Maestría en Administración de Negocios","Maestría en Derecho","Maestría en Salud Pública","Maestría en Tecnologías para el Aprendizaje");
				$infott = mysqli_query($connection,$infotrans);
				$infot = mysqli_fetch_array($infott);
			?>

			<script ="select_auto">
				function auto(val){
					console.log("seleccionar");
					var val;
					var id_car =$(val).val();
					var marca = $("td[id=b" + id_car +"]").text();
					var modelo = $("td[id=t" + id_car +"]").text();
					var pasajeros = $("td[id=p" + id_car +"]").text();
					$("#bb").text(marca);
					$("#tb").text(modelo);
					$("#pb").text(pasajeros);
					$("#transporte").val(id_car);
				}
				console.log("Cargada funcion: \t mio()");
				function mio(){
					var auto = $("input[name=auto_id]").val();
					$("#transporte").val(auto);
					console.log("Funcion mio() \t Ejecutada");
					console.log("Id auto: "+ auto);
				}
			</script>
			<script type="text/javascript" id="asistentes_function">
				$(document).ready(function(){
					$("#agreg_asist").click(function(){
						var vvv = $("#name").val();
						var www = $("#code").val();

						if (vvv != "" && www != "") {
							$("#list_asist").append("<tr><td id='name' >" + $("#name").val()+ "</td>"+"<td id='code'>" + $("#code").val() + "</td><td aling='center' style='width: 80px;padding-left: 20px;'><button type='button' class='btn btn-danger btn-sm' onclick='borrarAsist(this);'><span class='glyphicon glyphicon-remove'></span></button></td></tr>");
							$("#name").val("");
							$("#code").val("");
						}
						else{}
					});
				});
					function borrarAsist(str) {
						var j = str.parentNode.parentNode.rowIndex;
						document.getElementById("list_asist").deleteRow(j-1);
					}
			</script>
		</head>
		<body>
			<div class="container">
				<div class="page-header">
					<h1>Solicitud
					<br>
					<small>Sistema de Gestion Vehicular</small>
					</h1>
				</div>
				<form name="solicitud" action="updaterequest.php" method="POST">
						<div class="panel-group">
							
							<div class="panel panel-default" id="motivos-panel">
								<div class="panel-heading">	
									<h4>
										<label class="control-label">Instituciones a visitar o Actividades a realizar</label>
									</h4>
								</div>
								<div class="panel-body">
									<div class="form-group row">
										<div class="col-xs-6">
											<textarea  name="motivo" class="form-control" rows="4" disabled style="margin: 0px -570px 0px 0px; width: 500px; height: 69px; resize: none;"> <?php echo $row['reasons'] ?></textarea>
										</div>
										<div class="col-xs-6"></div>
									</div>
								</div>
							</div>		

							<div class="panel panel-default" id="itinerario-panel">
								<div class="panel-heading">
									<h4>
										<strong> Itinerario</strong>
									</h4>
								</div>
								<div class="panel-body">
									<div class="form-group row">
										<div class="col-xs-4">
											<div class="form-group">
												<label class="control-label">Lugar de destino</label>
												<input  type="text" for="solicitud" name="destino" pattern="[A-Z-a-z-' ']+" class="form-control" disabled value="<?php echo $row['destination']; ?>" >
											</div>
										</div>
											<div class="col-xs-4">
												<div class="form-group">
													<label class="control-label">Estado</label>
													<input type="text" class="form-control text-center" name="estado" value="<?php $x = $row['state'];echo $estados[$x-1]; ?>" disabled>
												</div>
											</div>
											<div class="col-xs-4">
												<div class="form-group">
													<div id="selectEstado"></div>
														<div id="mncp">										
															<label for="solicitud">Municipio:</label>
															<input type="text" class="form-control text-center" name="municipio" value="<?php echo $row['name'] ?>" disabled>
														</div>
													</div>
											</div>
									</div>

									<div class="form-group row">
										<div class="col-xs-6">
											<div class="form-group">
												<label for="sel2">Labor:</label>
												<input type="text" class="form-control text-center" value="<?php echo $row['labor_cap'] ?>" disabled>
											</div>
										</div>
										<div class="col-xs-6">
											<div id="Vista programa">
											</div>
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
												<input type="text" class="form-control text-center" value="<?php echo $row['departuredate']; $d_salida = $row['departuredate']; ?>" disabled>
											</div>
										</div>
										<div class="col-xs-6">
										</div>
										<div class="col-xs-6">
											<div class="form-group">
												<label class="control-label">Fecha y Hora (Evento)</label>
												<input type="text" class="form-control text-center" value="<?php echo $row['eventdate'];  ?>" disabled>	
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
												<input type="text" class="form-control text-center" value="<?php echo $row['returndate']; $d_retorno = $row['returndate'];?>" disabled>
											</div>
										</div>
									</div>				
								</div>
							</div>

							<?php $query_car = 	"SELECT
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
									AND ( ('$d_salida' BETWEEN departuredate AND returndate)
									OR ('$d_retorno' BETWEEN departuredate AND returndate)
									OR (departuredate BETWEEN '$d_salida' AND '$d_retorno')
									OR (returndate BETWEEN '$d_salida' AND '$d_retorno')
									)
									);";   ?>

							<div class="panel panel-default" id="transporte-panel">
								<div class="panel-heading">						
									<div class="form-group">
										<h4>
											<strong>Transporte</strong>
										</h4>
									<br>
									</div>
								</div>
									<input id="transporte" type="hidden" name="transporte" value="" required>		
								<div class="panel-body">
								<button  type="button" id="start" data-toggle="modal" class="btn btn-success"  data-target="#myModal"  value="MacLuna" >
								<span class="glyphicon glyphicon-road"></span>   Seleccionar
							</button>
									<br>
									<br>
									<table class="table table-bordered">
										<thead>
											<tr class="info">
												<th></th>
												<th>Marca</th>
												<th>Tipo</th>
												<th>Capacidad</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="width: 68px;"> <img src="../imgs/auto.png" width="32px" height="32px" style="margin-left: 8px;"> </td>
												<td id="bb" style="text-align: center;" ></td>
												<td id="tb" style="text-align: center;" ></td>
												<td id="pb" style="text-align: center;" ></td>
											</tr>
										</tbody>
									</table>
										<?php 
											$driver_query =
												"SELECT\n".
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
												"			'$d_salida' BETWEEN departuredate\n".
												"			AND returndate\n".
												"		)\n".
												"		OR (\n".
												"			'$d_retorno' BETWEEN departuredate\n".
												"			AND returndate\n".
												"		)\n".
												"		OR (\n".
												"			departuredate BETWEEN '$d_salida'\n".
												"			AND '$d_retorno'\n".
												"		)\n".
												"		OR (\n".
												"			returndate BETWEEN '$d_salida'\n".
												"			AND '$d_retorno'\n".
												"		)\n".
												"	)\n".
												")\n".
												"AND (\n".
												"	driversidduedate >= '$d_retorno'\n".
												");";
										?>
									<div class="form-group row">
										<div class="col-xs-6">
											<?php 
												$result_driver = mysqli_query($connection,$driver_query);
												echo '<label for="sel1">Conductor:</label> <select name="conductor" class="form-control"  > ';
												while($row_driver = mysqli_fetch_array($result_driver)) {
												echo '<option value="'.$row_driver["id"].'">'.$row_driver["name"].'</option>';
												}
												echo "</select> <br>";
											?>
										</div>
										<div class="col-xs-6">
											<div class="form-group">
												<label for="sel1">Responsable:</label>
												<select name = "responsable" class="form-control" required>
													<?php echo $options_responsable; ?>
												</select>
											</div>
										</div>	
									</div>
								</div>
							</div>

							<div class="panel panel-default" id="asistentes-panel">
								<div class="panel-heading">
									<h4> <strong>Relación de personas a transportar.</strong></h4>
								</div>
								<div class="panel-body">
									<div class="form-group row">
										<div class="col-xs-4"></div>
										<div class="col-xs-4">
											<h4 class="text-center"><strong> Lista de Asistentes </strong></h4>
										</div>
										<div class="col-xs-4"></div>
										<div class="col-xs-12">
											<span class="label label-warning">No incluya chofer ni responsable </span>
										</div>
									</div>




									<div class="form-group row">
										<div class="col-xs-5">
											<label>Nombre</label>
										</div>
										<div class="col-xs-3">
											<label>Codigo</label>	
										</div>
										<div class="col-xs-4"></div>			
										<div class="col-xs-5">	
											<input type="text" id="name" name="nombre" pattern="[A-Z-a-z-' ']+" class="form-control" placeholder="Ingrese nombre completo">
										</div>				
										<div class="col-xs-4">	
											<input type="text" id="code" name="codigo" pattern="[A-Z-a-z-' ']+" class="form-control" placeholder="Codigo o indetificador">
										</div>
										
										<div class="col-xs-2">
											<button type="button" id="agreg_asist" class="btn btn-default" >Agregar</button>
										</div>
									</div>
									<div>

										
										<input id="id_user" type="hidden" name="id_user" value="<?php echo $id; ?>">
									</div>
									<div class="table-responsive">
										<table id="list_nombres" class="table table-bordered">
											<thead>
												<tr class="info">
													<th>Nombre:</th>
													<th>Codigo:</th>
													<th class="center"></th>
												</tr>
											</thead>
											<tbody id="list_asist"></tbody>
										</table>
									</div>
								</div>
							</div>
					<input type="text" id="nombres" name="nombres" >
					<input type="text" id="codigos" name="codigos" >
					<input type="hidden" id="idquest" name="idquest" value="<?php echo $idquest; ?>">
							<div class="panel panel-default" id="gastos-panel">
								<div class="panel-heading">	
									<h4>
										<label class="control-label">Gastos</label>
									</h4>
								</div>
								<div class="panel-body">
									<div class="form-group row">
										<div class="col-xs-4">
											<label>Hospedaje: </label>
											<input type="text" name="hospedaje" value="" class="form-control" placeholder="$00.00" required="true">
											<label>Alimentos: </label>
											<input type="text" name="alimentos" value="" class="form-control" placeholder="$00.00" required="true">
										</div>
										<div class="col-xs-4">
											<label>Peaje: </label>
												<input type="text" value="" name="peaje" class="form-control" placeholder="$00.00" required="true">
											<label>Combustible: </label>
												<input type="text" name="combustible" value="" class="form-control" placeholder="$00.00" required="true">
										</div>
										<div class="col-xs-4">
											<label>Estacionamiento: </label>
												<input type="text" name="estacionamiento" value="" class="form-control" placeholder="$00.00" required="true">
											<label>Estacionometro: </label>
												<input type="text" name="estacionometro" value="" class="form-control" placeholder="$00.00" required="true">
										</div>
									</div>
								</div>
							</div>


						</div>
					</div>		
					<div class="panel-footer">
						<div class="form-group row">
							<div class="col-xs-2">
								<?php echo '<a href="principal.php?id='.$id.'" class="btn btn-warning" role="button">Cancelar</a>';?>
							</div>
							<div class="col-xs-2"></div>
							<div class="col-xs-2"></div>
							<div class="col-xs-2"></div>
							<div class="col-xs-2"></div>
							<div class="col-xs-2">
								<button type="submit" id="enviar" class="btn btn-success" disabled="true" onclick="mio()" >Enviar Solicitud</button>
							</div>
						</div>
					</div>

				</form>


			<script type="text/javascript">
				var state = false;
				$("button[id=start]").click(function() {
					state = true;
					$("button[id=enviar]").attr("disabled", false);
				});
				function mio(){
			var valores="";
			var codigos="";
			$("td").parents("tr").find("#name").each(function(){
				valores+=$(this).html()+"_";
			});
			$("td").parents("tr").find("#code").each(function(){
				codigos+=$(this).html()+"_";
			});

			$("#codigos").val(codigos);
			$("#nombres").val(valores);
		}
			</script>
		</body>

		<!-- Modal -->
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">
								<h3><strong>Lista de transportes</strong></h3>
					</div>

					<div class="modal-body">
						<div class="table-responsive">
							<?php 
								$autos = mysqli_query($connection,$query_car) or die ("Error de conexion");
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
									while($list = mysqli_fetch_array($autos)) {
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
							?>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>


</html>