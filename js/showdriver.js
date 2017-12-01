	$(document).ready(function(){
var date_salida,date_retorno;
var time_salida, time_retorno;

console.log("Cargada funcion: \t Leer showDriver()");
 
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
			console.log("Ejecutada");
			};
			xmlhttp.open("GET","php/drivers.php?date_salida="+date_salida+"&date_retorno="+date_retorno+"&time_salida="+time_salida+"&time_retorno="+time_retorno,true);
			xmlhttp.send();
			}
		}

		$( ("input[name=f_salida]") && ("input[name=f_retorno]") && ("input[name=h_salida]")&&("input[name=h_retorno]")).change(function(){
			date_salida = $("input[name=f_salida]").val();
			date_retorno = $("input[name=f_retorno]").val();
			time_salida = $("input[name=h_salida]").val();
			time_retorno = $("input[name=h_retorno]").val();
			console.log(time_salida);

			showDriver(date_salida ,date_retorno, time_salida, time_retorno);
		});

});