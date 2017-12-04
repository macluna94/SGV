<?php 
	require_once 'class.inputfilter.php';
	include "session.php";
	$filtro = new InputFilter();
	$_POST = $filtro->process($_POST);
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html; UTF-8">

		<title>Solicitudes</title>

		<link rel="stylesheet" href="css\bootstrap.min.css">
		<link rel="stylesheet" href="css\datatables.css">
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" > </script> 
		<script src="js\bootstrap.min.js"></script>
		<script src="js\showdriver.js"></script>
		<script src="js\showcar.js"></script>
		<script src="js\list_names.js"></script>
		<link href="fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />     
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" > </script> 
		<script src="fileinput/js/fileinput.min.js" > </script> 
		<script src="fileinput/js/locales/es.js" > </script> 

		<script>
			$(document).ready(function(){
				$("#file-pdf").fileinput({
					"language": 'es',
					"required": false,
					"showRemove": true,
					"showUpload": false,
					'allowedFileExtensions': ['pdf'],
					"maxFileSize": 5000
				});
				$("#file-xml").fileinput({
					"language": 'es',
					"required": false,
					"showRemove": true,
					"showUpload": false,
					'allowedFileExtensions': ['xml']
				});
			});
		</script>

		<style type="css">
			.modal-header{
				background-color: #1565d3;
				color:white !important;
				text-align: center;
			}
		</style>
 
		<?php
			header('Content-Type: text/html; charset=UTF-8');
			include "php/connection.php";
			$id = $id_user;
			include "php/querys.php";
			include "php/settings_date.php";
		?>

		<script ="select_auto">
			function auto(val){
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
			<h1>Solicitud<br><small>Sistema de Gestion Vehicular</small></h1>
		</div>
		<form name="solicitud" action="proccess_form.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
			<div class="form-group ">
				<div class="panel-group">

					<div class="panel panel-default" id="motivos-panel">
						<div class="panel-heading">	
							<h4><label class="control-label">Instituciones a visitar o Actividades a realizar</label></h4>
						</div>
						<div class="panel-body">
							<div class="form-group row">
								<div class="col-xs-6">
									<textarea  name="motivo" class="form-control input-lg" required style="margin: 0px -570px 0px 0px; width: 1000px; height: 70px; resize: none;"></textarea>
								</div>
								<div class="col-xs-6"></div>
							</div>
						</div>
					</div>

					<div class="panel panel-default" id="itinerario-panel">
						<div class="panel-heading">
							<h4><label><strong> Itinerario</strong></label></h4>
						</div>
						<div class="panel-body">
							<div class="form-group row">
								<div class="col-xs-4">
									<label class="control-label">Lugar de destino o arribo</label>
										<input  type="text" for="solicitud" name="evento"  class="form-control" required placeholder="Lugar del evento">
								</div>

								<div class="col-xs-4">
									<div class="form-group">
										<label class="control-label">Estado</label>
											<select required="true"  name="estados" id="estados" for="solicitud" class="form-control" onchange="showState(this.value);" >
												<option></option>
												<?php echo $option_state; ?>
											</select>
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
												xmlhttp.open("GET","php/municipios.php?estado="+str,true);
												xmlhttp.send();
											}
											//alert("Estado: "+str);
											$("#mncp").remove();
										}
									</script>
								</div>

								<div class="col-xs-4">
									<div class="form-group">
										<div id="selectEstado">
										</div>
										<div id="mncp">										
											<label for="solicitud">Municipio:</label>
												<select name="conductor" class="form-control" >
													<option>
													</option>
												</select>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-xs-6">
									<div class="form-group">
										<script>
											$(document).ready(function(){
													$("#pdf").hide();
										    		$("#xml").hide();
											});
											function showPrograms(hub){
										    	if (hub != "1" ) {
		            							document.getElementById("selectPrograma").innerHTML = "";
		            							return;
										       } 
										      else { 
										       	if (window.XMLHttpRequest) {
															newxhttp = new XMLHttpRequest();
														}
													else {
														newxhttp = new ActiveXObject("Microsoft.XMLHTTP");
													}
													newxhttp.onreadystatechange = function() {
														if (this.readyState == 4 && this.status == 200) {
															document.getElementById("selectPrograma").innerHTML = this.responseText;
														}
													};
														newxhttp.open("GET","php/programas.php?programa="+hub,true);
														newxhttp.send();
												}
											}
											function sub_docs(hub){
												if (hub == 1) {
													$("#pdf").show();
													$("#xml").show();
													$("input[id=file-pdf]").attr("required", true);
													$("input[id=file-xml]").attr("required", true);
												}
												else if (hub == 3) {
													$("#pdf").show();
													$("input[id=file-pdf]").attr("required", true);
													$("input[id=file-xml]").attr("required", false);
												}
												else if(hub == 6){
													$("#xml").show();
													$("input[id=file-xml]").attr("required", true);
													$("input[id=file-pdf]").attr("required", false);
												}
												else{
									    			$("#pdf").hide();
									    			$("#xml").hide();
									    			$("input[id=file-pdf]").attr("required", false);
													$("input[id=file-xml]").attr("required", false);
	    										}
	    									}
										</script>
									<label for="sel2">Ejes:</label>
										<select  name="labor" class="form-control" onchange="showPrograms(this.value);sub_docs(this.value);" required>
											<option></option>
											<?php echo $options_hubs; ?>
										</select>
									</div>
								</div>
								<div class="col-xs-6">
									<div id="selectPrograma" class=""></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-xs-6">
									<div class="form-group">
										<label class="control-label">Lugar de Salida</label>
										<input type="text"  for="solicitud"  name="salida" pattern="[A-Z-a-z-' ']+"  class="form-control" required>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<label class="control-label">Fecha(Salida)</label>
										<input type="date" name="f_salida" for="solicitud" class="form-control"  min="<?php echo $date_init ?>"  value="<?php echo $date_init ?>" required>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<label class="control-label">Hora (Salida)</label>
										<input type="time" name="h_salida" for="solicitud" value="00:00:00" class="form-control" required>
									</div>
								</div>
								<div class="col-xs-6">
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<label class="control-label">Fecha(Evento)</label>
										<input type="date" name="f_evento" for="solicitud" class="form-control" value="<?php echo $date_event ?>" required="" />
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<label class="control-label">Hora(Evento)</label>
										<input type="time" name="h_evento" for="solicitud" class="form-control" value="12:00" required="" />
									</div>
								</div>
								<div class="col-xs-6">
									<div class="form-group">
										<label class="control-label">Lugar de Retorno</label>			
										<input type="text" name="retorno" for="solicitud"   pattern="[A-Z-a-z-' ']+"  class="form-control" required>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<label>Fecha(Retorno)</label>
										<input type="date" name="f_retorno" for="solicitud" class="form-control" min="<?php echo $date_return ?>" value="<?php echo $date_return ?>" required>
									</div>
								</div>
								<div class="col-xs-3">
									<div class="form-group">
										<label class="control-label">Hora(Retorno)</label>
										<input type="time" for="solicitud" name="h_retorno" value="23:59:00" class="form-control"  required>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="panel panel-default" id="transporte-panel">
						<div class="panel-heading">						
							<div class="form-group">
								<h4><strong>Transporte</strong></h4><br>
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
										<td style="width: 68px;"> <img src="imgs/auto.png" width="32px" height="32px" style="margin-left: 8px;"> </td>
										<td id="bb" style="text-align: center;" ></td>
										<td id="tb" style="text-align: center;" ></td>
										<td id="pb" style="text-align: center;" ></td>
									</tr>
								</tbody>
							</table>
							<div class="form-group row">
								<div class="col-xs-6">
									<div id="selectConductor" name="conductor">
										<label for="sel1">Conductor: </label>
											<select name="conductor" class="form-control"  required>
												<option></option>
											</select>
									</div>
								</div>
								<div class="col-xs-6">
										<div class="form-group">
											<label for="sel1">Responsable:</label>
												<select name = "responsable" class="form-control" required>
													<option></option>
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

					<div class="panel panel-default" id="gastos-panel">
						<div class="panel-heading">	
							<h4><label class="control-label">Gastos</label></h4>
						</div>
						<div class="panel-body">
							<div class="form-group row">
								<div class="col-xs-4">
									<label>Hospedaje: </label> <input type="text" name="hospedaje" value="" class="form-control" placeholder="$00.00" required="true">
									<label>Alimentos: </label> <input type="text" name="alimentos" value="" class="form-control" placeholder="$00.00" required="true">
								</div>

								<div class="col-xs-4">
									<label>Peaje: </label> <input type="text" value="" name="peaje" class="form-control" placeholder="$00.00" required="true">
									<label>Combustible: </label> <input type="text" name="combustible" value="" class="form-control" placeholder="$00.00" required="true">
								</div>
								<div class="col-xs-4">
									<label>Estacionamiento: </label> <input type="text" name="estacionamiento" value="" class="form-control" placeholder="$00.00" required="true">
									<label>Estacionometro: </label> <input type="text" name="estacionometro" value="" class="form-control" placeholder="$00.00" required="true">
								</div>
							</div>
						</div>
					</div>
					<br>
					<div id="docs">
						<div class="panel panel-default" id="documentos">
							<div class="panel-heading">
								<h4><label class="control-label">Documentos</label></h4>
							</div>
							<div class="panel-body">
								<div class="form-group row">
									<div id="pdf">
										<div class="col-xs-6">
											<label class="label-control">Subir PDF</label>
											<input id="file-pdf" name="file-pdf" multiple type="file" required="false">
										</div>
									</div>
									<div id="xml">
										<div class="col-xs-6">
											<label class="label-control">Subir XML</label>
											<input id="file-xml" name="file-xml" multiple type="file" required="false">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="nombres" name="nombres"  >
					<input type="hidden" id="codigos" name="codigos"  >
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
	</div>

	<script>
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
			asistentes.push(valores);
			a_codigos.push(codigos);
			$("#codigos").val(codigos);
			$("#nombres").val(valores);
		}
		var asistentes = new Array();
		var a_codigos = new Array();
	</script>

	<!-- Modal -->
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
		<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-title">
						<h3><strong>Lista de Transportes</strong></h3>
					</div>
				</div>
				<div class="modal-body">
					<div class="table-responsive" id="lista_autos">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>

	</body>
</html>