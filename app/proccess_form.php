<!DOCTYPE html>
<html>
<head>
</head>
<body> 
<?php
header('Content-Type: text/html; charset=UTF-8');
include "../php/connection.php";

$xss = array("'","%","#","+","\"","/","?","*","&","(",")","&&","^","~","`","=","\\","$");


$id_user = $_POST['id_user'];
$motivo = $_POST['motivo'];
$motivo = str_replace($xss, '', $motivo);


$evento = $_POST['evento'];
$evento = str_replace($xss, '', $evento);


$municipio = $_POST['municipio'];

$labor = $_POST['labor'];

if(isset($_POST["programa"])){$programa= $_POST["programa"];}
else {$programa = 'NULL';}

$salida = $_POST['salida'];
$salida = str_replace($xss, '', $salida);

$f_salida = $_POST['f_salida'];
$h_salida = $_POST['h_salida'];
$date_salida = $f_salida." ".$h_salida;

$evento = $_POST['evento'];
$f_evento = $_POST['f_evento'];
$h_evento = $_POST['h_evento'];
$date_evento = $f_evento." ".$h_evento;

$retorno = $_POST['retorno'];
$retorno = str_replace($xss, '', $retorno);

$f_retorno = $_POST['f_retorno'];
$h_retorno = $_POST['h_retorno'];
$date_retorno = $f_retorno." ".$h_retorno;

$transporte = $_POST['transporte'];

if (isset($_POST['conductor'])){$conductor = $_POST['conductor'];}
else{$conductor = 'NULL';}

$responsable = $_POST['responsable'];

$asistentes = $_POST['nombres'];
$asistentes = str_replace($xss, '', $asistentes);

$codigos = $_POST['codigos'];
$codigos = str_replace($xss, '', $codigos);

//$driver_asing = $_POST[''];
//$auth = $_POST[''];

$driver_asing = 'FALSE';
$auth = 'FALSE';

$nombre = explode("_",$asistentes);
$j = count($nombre);
$codigo = explode("_",$codigos);
$list = "";

$xml_query = "";$pdf_query = "";

$alimentos = $_POST['alimentos'];
$alimentos = str_replace($xss, '', $alimentos);

$combustible = $_POST['combustible'];
$combustible = str_replace($xss, '', $combustible);

$estacionamiento = $_POST['estacionamiento'];
$estacionamiento = str_replace($xss, '', $estacionamiento);

$estacionometro = $_POST['estacionometro'];
$estacionometro = str_replace($xss, '', $estacionometro);

$hospedaje = $_POST['hospedaje'];
$hospedaje = str_replace($xss, '', $hospedaje);

$peaje = $_POST['peaje'];
$peaje = str_replace($xss, '', $peaje);




echo 	"Motivo:  ",$motivo,"<br>","evento:  ",$evento,"<br>","Salida:  ",$salida,"<br>",
		"Retorno:  ".$retorno,"<br>","Labor:    ",$labor,"<br>","Programa:  ",$programa,"<br>",
		"Fecha de evento:", $date_evento,"<br>","Fecha Retorno:  ",$date_retorno,"<br>",
		"Fecha salida:  ",$date_salida,"<br>","Conductor:  ", $conductor,"<br>",
		"Responsable:  " ,$responsable,"<br>","Auto:  ",$transporte,"<br>",
		"Asistentes:  ",
		$asistentes,"<br>","Codigos:  ",
		$codigos;


$pre_query = "INSERT INTO requests VALUES (NULL,'$motivo','$evento', $municipio, $labor, $programa, '$salida', '$date_salida', '$date_evento', '$retorno','$date_retorno',$transporte, $conductor,$driver_asing, $responsable,$auth,FALSE);";

	$i_query = "SET @ID = LAST_INSERT_ID();";

for ($c=0; $c < $j-1; $c++) {
	$list .= "INSERT INTO request_passengers VALUES (@ID, '$nombre[$c]', '$codigo[$c]');\n";
}


$list_item = "
	INSERT INTO request_items VALUES(@ID, 1, $hospedaje);
	INSERT INTO request_items VALUES(@ID, 2, $alimentos);
	INSERT INTO request_items VALUES(@ID, 3, $peaje);
	INSERT INTO request_items VALUES(@ID, 4, $combustible);
	INSERT INTO request_items VALUES(@ID, 5, $estacionamiento);
	INSERT INTO request_items VALUES(@ID, 6, $estacionometro);";

$log="INSERT INTO `log` VALUES (NULL, 1, @ID, $id_user, 0, 'Solicitud creada', NOW());";


	

	$pdf_size = $_FILES['file-pdf']['size'];
		$pdf_tmp_name = $_FILES['file-pdf']['tmp_name'];
		$pdf_name = $_FILES['file-pdf']['name'];
		$pdf_name  = str_replace($xss, '', $pdf_name);

		$xml_size = $_FILES['file-xml']['size'];
		$xml_tmp_name = $_FILES['file-xml']['tmp_name'];
		$xml_name = $_FILES['file-xml']['name'];
		$xml_name = str_replace($xss, '', $xml_name);
	$ruta = '../tmp_folder/';


	if ($xml_size == 0) {
		#echo "<br> No hay XML  <br>";
	}
	else{
	$xml_query = "INSERT INTO request_documents VALUES (NULL, @ID, 2,'$xml_name',$xml_size);";
	}
	if ($pdf_size == 0) {
		#echo "<br> No hay pdf  <br>";
	}
	else{
		echo "<br>Si Hay pdf <br>";
		$pdf_query = "INSERT INTO request_documents VALUES (NULL, @ID, 1,'$pdf_name', $pdf_size);";
	}

		opendir($ruta);
		$destino = $ruta.$pdf_name;
		$destino_b = $ruta.$xml_name;

		move_uploaded_file($pdf_tmp_name,$destino);
		move_uploaded_file($xml_tmp_name, $destino_b);




$files = $pdf_query.$xml_query;

$pre = $pre_query.$i_query.$log;

$todo = $pre_query.$i_query.$log.$list.$list_item.$files; 



echo "<br>",$list;



$link = new mysqli('localhost','root','','autos');

	if ($link->connect_error){die("Connection failed: ".$link->connect_error);}

	if ($link->multi_query($todo) == TRUE  ) {
		$link->query($log);
		header("Location: ../principal.php?id=$id_user"); 
	    echo "<script>alert('Solicitud creada');</script>";
	} else {
	    echo "Error: " . "<br>" . $link->error;
	    $link->query("rollback;");
	}

	$link->close();

?>
</body>
</html>