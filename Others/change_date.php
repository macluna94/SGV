<!DOCTYPE html>
<html>
<head>
	<title></title>

<link rel="stylesheet" href="css\bootstrap.min.css">
  <link rel="stylesheet" href="css\datatables.css">
  <script src="js\jquery.min.js"></script>
  <script src="js\bootstrap.min.js"></script>
  <script src="js\datatables.js"></script>

<script type="text/javascript">

	var date_salida,date_retorno;
	var time_salida, time_retorno;

	$(document).ready(function(){
		function showDriver(date_salida, date_retorno, time_salida, time_retorno) {

		    if ((date_retorno == "") && (date_salida = "") && (time_salida == "") && (time_retorno == "") ) {
		        document.getElementById("").innerHTML = "";
		        return;
		    } else { 
		        if (window.XMLHttpRequest) {
		            // code for IE7+, Firefox, Chrome, Opera, Safari
		            xmlhttp = new XMLHttpRequest();
		        } else {
		            // code for IE6, IE5
		            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		        }
		        xmlhttp.onreadystatechange = function() {
		            if (this.readyState == 4 && this.status == 200) {
		                document.getElementById("selectConductor").innerHTML = this.responseText;
		            }
		        };
		        xmlhttp.open("GET","drivers.php?date_salida="+date_salida+"&date_retorno="+date_retorno+"&time_salida="+time_salida+"&time_retorno="+time_retorno,true);
		        xmlhttp.send();
		    }
		}
		$( ("input[name=f_salida]") && ("input[name=f_retorno]") && ("input[name=h_salida]")&&("input[name=h_retorno]")).change(function(){
				//$('input[name=valor1]').val($("input[name=h_salida]").val());
	            //$('input[name=valor2]').val($("input[name=h_retorno]").val());
	            date_salida = $("input[name=f_salida]").val();
	            date_retorno = $("input[name=f_retorno]").val();
	            time_salida = $("input[name=h_salida]").val();
	            time_retorno = $("input[name=h_retorno]").val();

				showDriver(date_salida ,date_retorno, time_salida, time_retorno);
		});

	});
</script>


<?php 
include "php/settings_date.php";
 ?>

</head>
<body>
<div class="container">
	<br>
		<div >
			<label >Fecha de Salida</label>
			<br>
			<input type="date" name="f_salida" for="solicitud" class="col-sm-10"  min="<?php echo $date_init ?>"  value="<?php echo $date_init ?>">


			<input type="time" class="col-sm-2"  name="h_salida" for="solicitud" value="00:00:00">




			<input type="date" name="f_retorno" for="solicitud" class="col-sm-10" min="<?php echo $date_return ?>" value="<?php echo $date_return ?>">


			<input type="time" for="solicitud" class="col-sm-2" name="h_retorno" value="23:59:00">

		</div>



	<br>
	<br>
	<br>

<br>
<div id="selectConductor" name="conductor">
<label for="sel1">
	Conductor: 
</label>
</div>
<br>

	

</div>

</body>
</html>
