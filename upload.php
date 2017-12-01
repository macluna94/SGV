<?php 
require_once('class.inputfilter.php');

$filtro = new inputFilter();
$_POST = $filtro->process($_POST);

 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Subir archivos</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
<link rel="stylesheet" href="css/bootstrap.min.css" >  
<script src="https://code.jquery.com/jquery-3.2.1.min.js" > </script> 
<script src="js/bootstrap.min.js" type = "text/javascript" > </script>  


<link href="fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />     
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" > </script> 
<script src="fileinput/js/fileinput.min.js" > </script> 
<script src="fileinput/js/locales/es.js" > </script> 



<?php
include "php/connection.php";
?>


<script>
	$(document).ready(function(){
		$("#file-pdf").fileinput({
			"language": 'es',
			"required": true,
			"showRemove": true,
			"showUpload": false,
			'allowedFileExtensions': ['pdf'],
			"maxFileSize": 5000
		});
		$("#file-xml").fileinput({
			"language": 'es',
			"required": true,
			"showRemove": true,
			"showUpload": false,
			'allowedFileExtensions': ['xml']
		});
	});
</script>
<script>

$(document).ready(function(){

$("#next").click(function(){

	$("#block").remove();
	$("ul").append('<li><a href="#disabled-tabs-above" role="tab-kv" data-toggle="tab" ><i class="glyphicon glyphicon-knight"></i> Disabled</a></li>');
});

});
</script>
</head>
<body>
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2>Sube un archivo</h2>
			</div>
			<div class="panel-body">


<form action="upload_submit.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
	<div class="form-group row">
				<div class="col-xs-6">
				<label class="label-control">Subir PDF</label>
				<input id="file-pdf" name="file-pdf" multiple type="file" >
				</div>
			
				<div class="col-xs-6">
				<label class="label-control">Subir XML</label>
				<input id="file-xml" name="file-xml" multiple type="file" >
				</div>
				<div class="col-xs-6">
					<label class="label-control">Nombre:</label>
		
					<input type="text" name="nombre" value="" class="form-control" >
				</div>
				<div class="col-xs-6">
					<label class="label-control">Apellido<input type="text" name="apellido" value="" class="form-control" ></label>
				</div>
			</div>
			<button type="submit" class="btn btn-success" >Enviar</button>
</form>
<br><br>



<!--

				<div class='tabs-x tabs-above tab-bordered tabs-krajee'>
    <ul id="myTab-tabs-above" class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#home-tabs-above" role="tab" data-toggle="tab">Nombre</a></li>
        <li><a href="#profile-tabs-above" role="tab-kv" data-toggle="tab">Documentos</a></li>
        <li><a href="#disabled-tabs-above" role="tab-kv" data-toggle="tab" ><i class="glyphicon glyphicon-knight"></i> Fotografia</a></li>
    </ul>

    <div id="myTabContent-tabs-above" class="tab-content">

	<form action="upload_submit.php" method="POST" accept-charset="utf-8" enctype="multipart/form-data">

			<div class="tab-pane fade in active" id="home-tabs-above">


				<div class="form-group row">
				<div class="col-xs-6">
				<label class="label-control">Nombre: </label>
				<input type="text" name="nombre" class="form-control" required>
				</div>
				<div class="col-xs-6">
				<label class="label-control">Apellido: </label>
				<input type="text" name="apellido" class="form-control" required>

				</div>
				</div>

			</div>
			<div class="tab-pane fade" id="profile-tabs-above">
				<div class="form-group row">
				<div class="col-xs-6">
				<label class="label-control">Subir PDF</label>
				<input id="file-pdf" name="file-pdf" multiple type="file" >
				</div>
			
				<div class="col-xs-6">
				<label class="label-control">Subir XML</label>
				<input id="file-xml" name="file-xml" multiple type="file" >
				</div>

			</div>


			<div class="tab-pane fade" id="disabled-tabs-above">
				<input type="file" class="file" name="">


			</div>
			


		</form>
    </div>
</div>
<script>


</script>
-->
			</div>
		</div>
	</div>





</body>
</html>