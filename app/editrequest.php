<!DOCTYPE html>
<?php include "../session.php"; 
$id = $id_user;
?>
<html lang="es" dir="ltr">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html; UTF-8">

		<title>Solicitudes</title>

		<link rel="stylesheet" href="css\bootstrap.min.css">
		
		<script src="js\jquery.min.js"></script>
		<script src="js\bootstrap.min.js"></script>
	
		<script src="js\showdriver.js"></script>
	
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

		<script type="  ,ext/javascript">
			$(document).ready(function() {
				$("form").keypress(function(e) {
					if (e.which == 13) {
						return false;
					}
				});
			});
		</script>


<script>


console.log("Cargada funcion: \t mio()");

function mio(){
	var valores="";
	var codigos="";
	var auto = $("input[name=auto_id]").val();
	
	$("td").parents("tr").find("#name").each(function(){
		valores+=$(this).html()+"\n";
	});

	$("td").parents("tr").find("#code").each(function(){
		codigos+=$(this).html()+"\n";
	});

	var nombre = $("#name").val();
	var apellido = $("#code").val();

	asistentes.push(valores);
	a_codigos.push(codigos);
	$("#codigos").val(codigos);
	$("#nombres").val(valores);
	$("#transporte").val(auto);
console.log("Funcion mio() \t Ejecutada");

console.log("Id auto: "+ auto);
console.log("Nombre:"+asistentes+" Codigo: "+ a_codigos);

}

var asistentes = new Array();
var a_codigos = new Array();



</script>

 
<script id="car_function">
	$(document).ready(function(){
		console.log("Cargada funcion: \t Agregar Transporte(add)");

	    $(".addcar").click(function(){
	    	var v1 = $(this).parents("tr").find("td")[0].innerHTML;
	    	var v2 = $(this).parents("tr").find("td")[1].innerHTML;
	    	var v3 = $(this).parents("tr").find("td")[2].innerHTML;
	    	var v4 = $(this).parents("tr").find("td")[3].innerHTML;
	$("#addcar").append("<tr id='car'><td>"+v1+"</td><td>"+v2+"</td><td>"+v3+"</td><td>"+v4+"</td> <td><button type='button' id='delete_auto' class='btn btn-info'>Button</button></td></tr>");
	 

	        deletecar();
	    });

	console.log("Cargada funcion: \t Agregar Transporte(delete)");

	function deletecar() {
	  $("#delete_auto").click(function(){
	        $("#car").remove();
	        console.log("Auto borrado");
	    });


	}
});
</script>

	<script type="text/javascript" id="asistentes_function">
			$(document).ready(function(){
			console.log("Cargada funcion: \t Agregar Asistentes()");


			$("#agreg_asist").click(function(){
			console.log("add_name");

			$("#list_asist").append("<tr><td id='name' >" + $("#name").val()+ "</td>"+"<td id='code'>" + $("#code").val() + "</td><td aling='center' style='width: 80px;padding-left: 20px;'><button type='button' class='btn btn-danger btn-sm' onclick='borrarAsist(this);'><span class='glyphicon glyphicon-remove'></span></button></td></tr>");
			console.log("Agregado: "+$("#name").val()+ " "+$("#code").val());



			$("#name").val("");
			$("#code").val("");
			});
			});

			function borrarAsist(str) {
			console.log("borrar_asistente");

			var j = str.parentNode.parentNode.rowIndex;
			document.getElementById("list_asist").deleteRow(j-1);
			}
	</script>

	<script id="gastos_function">
			$(document).ready(function(){


			console.log("Cargada funcion: \t Agregar Gastos()");
			$("#expense_add").click(function(){
			console.log("gastos");
			$("#list_expense").append("<tr><td id='concepto'>"+$("select[name=concepto]").val()+"</td><td id='cantidad'>" + "$ " + $("input[name=cantidad]").val()+"</td><td aling='center' style='width: 80px;padding-left: 20px;'><button type='button' class='btn btn-danger btn-sm' onclick='borrarGasto(this);'><span class='glyphicon glyphicon-remove'></span></button></td></tr></tr>");

			var concepto = $("select[name=concepto]").val();
			var cantidad = $("input[name=cantidad]").val();
			console.log(concepto + "   "+ cantidad);
			$("input[name=cantidad]").val("00.00");
			});
			});
			function borrarGasto(jdr) {
			console.log("borrar_gasto");

			var i = jdr.parentNode.parentNode.rowIndex;
			document.getElementById("list_expense").deleteRow(i-1);
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
							<h4><label class="control-label">Instituciones a visitar o Actividades a realizar</label></h4>
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
							<h4> <strong> Itinerario</strong></h4>
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
								<script>
									function showState(str) {
									if (str == "") {
										document.getElementById("selectEstado").innerHTML = "";
										return;
									}
									else { 
										if (window.XMLHttpRequest) {
											xmlhttp = new XMLHttpRequest();
										}
										else {
											xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
										}
										xmlhttp.onreadystatechange = function() {
										if (this.readyState == 4 && this.status == 200) {
												document.getElementById("selectEstado").innerHTML = this.responseText;
											}
										};
										xmlhttp.open("GET","php/estados.php?estado="+str,true);
										xmlhttp.send();
									}
									//alert("Estado: "+str);
									$("#mncp").remove();
									}
								</script>
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
											<?php //Accion [Solo docencia]
											/*
											if ($row['program'] != NULL) {
											$y = $row['program'];
											echo '	<div class="form-group">
											<label class="control-label">Programa:</label>
											<select name = "programa" class="form-control" required>';
											echo $options_programs;
											echo '</select></div>';
											}else{}*/
											?>
											<?php 
											if ($row['program'] != NULL) {
											$y = $row['program'];
											echo '	<div class="form-group">
											<label class="control-label">Programa:</label>
											<input type="text"  class="form-control text-center" name="" value="'.$programas[$y-1].'"  disabled>
											</div>';
											}
											else{}
											?>
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
							
					<div class="panel panel-default" id="transporte-panel">
						<div class="panel-heading">

							<div class="form-group row">
								<div class="col-xs-6">
									<h4><strong>Transporte</strong></h4>
								</div>
								<div class="col-xs-4"></div>
								<div class="col-xs-2">	
									<button type="button" data-toggle="modal" class="btn btn-success"  data-target="#myModal">Seleccionar</button>
								</div>
							</div>
						</div>
						<div class="panel-body">

							<table class="table table-bordered">
								<thead>
									<tr>
										<th></th>
										<th>Marca</th>
										<th>Tipo</th>
										<th>Capacidad</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="addcar">

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

					<div class="panel panel-default" id="gastos-panel">
						<div class="panel-heading"> gastos</div>
							<div class="panel-body">
							<div class="form-group">
								<div class="col-xs-12">
									
								<label class="control-label">Gastos derivados del Viaje</label>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr class="info">
													
												<th>Concepto</th>
												<th>Importe</th>
												<th>Total</th>
												</tr>
											</thead>
											<tbody id="list_expense" >
											</tbody>
										</table>
									</div>
									</div>	
							
									<div class="col-xs-6">
									<select class="form-control" name="concepto" id="concepto">
										<option value="Combustibles">Combustibles</option>
										<option value="Comidas">Comidas</option>
										<option value="Otros">Otros</option>
										<option value="Peajes,casetas,multas,estacionamientos">Peajes, casetas, multas y estacionamientos</option>
									</select>
									</div>
									<div class="col-xs-2">
										<input type="number" placeholder="00.00" step="0.01" min="0" name="cantidad" id="cantidad" class="form-control">
									</div>
									<div class="col-xs-1">
										
									<button type="button" id="expense_add" class="btn btn-default"><span class="glyphicon glyphicon glyphicon-usd"></span></button>
									</div>
									<div class="col-xs-4">
									</div>
							</div>
						</div>
						<div class="panel-footer">
							<div class="form-group row">
								<div class="col-xs-2">				
									<button type="submit" class="btn btn-info"  onclick="mio();">Enviar</button>
								</div>
								<div class="col-xs-2"></div>
								<div class="col-xs-2"></div>
								<div class="col-xs-2"></div>
								<div class="col-xs-2"></div>
								<div class="col-xs-2">
									<button type="button" class="btn btn-warning" onclick="window.close();" >Cancelar</button>
								</div>
							</div>
						</div>
					</div>
	<input id="transporte" type="hidden" name="transporte" value="">
	<input type="text" name="idquest" value="<?php echo $idquest; ?>">
			</form>
		</div>




<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
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

						echo "<table id='myTable' class='table table-condensed' >
						<thead>
						<tr class='active'>
						<th class='text-center'></th>
						<th class='text-center'>Marca</th>
						<th class='text-center'>Modelo</th>
						<th class='text-center'>Pasajeros</th>
						<th class='text-center'></th>
						</tr>
						</thead>
						<tbody>";

						while($row = mysqli_fetch_array($autos)) {
						echo "<tr id='tabs'>";

						echo '<td><input type="hidden" value="'.$row['id'].'" name="auto_id" for="solicitud"  style="width:40px;height:40px" disabled></td>';
						echo "<td class='text-center' >" . $row['brand_cap'] . "</td>";
						echo "<td class='text-center' >" . $row['type_cap'] . "</td>";
						echo "<td class='text-center' >" . $row['passengercapacity'] . "</td>";

						echo '  <td id="btn-s">
						<input type="button" value="Seleccionar" class="addcar btn btn-info" data-dismiss="modal" />
						</td>

						</tr>';

						}
						mysqli_close($connection);
						echo "
						</tbody>
						</table>

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
 
	</body>
</html>