<?php 
	header('Content-Type: text/html; charset=UTF-8');
	include "../php/connection.php";
	$xss = array("'","%","#","+","\"","/","?","*","&","(",")","&&","^","~","`","=","\\","$","{","}");



	$id_request = $_POST['idquest'];
$id_user = $_POST['id_user'];


if(isset($_POST["programa"])){$programa= $_POST["programa"];}
else {$programa = 'NULL';}




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




#echo 	"Conductor:  ", $conductor,"","Responsable:  " ,$responsable,"","Auto:  ",$transporte,"","Asistentes:  ",		$asistentes,"","Codigos:  ",	$codigos,"";



	$list = "";

	$pre_query = "
		SET @ID = $id_request; 
		UPDATE requests SET vehicle = $transporte, driver = $conductor WHERE id = @ID;
		DELETE FROM request_passengers WHERE request = @ID;";

	//$i_query = "SET @ID = LAST_INSERT_ID();";

	for ($c=0; $c < $j-1; $c++) {
		$list .= "INSERT INTO request_passengers VALUES (@ID, '$nombre[$c]', '$codigo[$c]');";
	}

	$list_item = "
		INSERT INTO request_items VALUES(@ID, 1, $hospedaje);
		INSERT INTO request_items VALUES(@ID, 2, $alimentos);
		INSERT INTO request_items VALUES(@ID, 3, $peaje);
		INSERT INTO request_items VALUES(@ID, 4, $combustible);
		INSERT INTO request_items VALUES(@ID, 5, $estacionamiento);
		INSERT INTO request_items VALUES(@ID, 6, $estacionometro);";


#$i_query;
$query = $pre_query.$list.$list_item;

//echo $query;


	if ($link->connect_error){die("Connection failed: ".$link->connect_error);}

	if ($link->multi_query($query) == TRUE  ) {
		$link->query($log);
		header("Location: ../app/principal.php?id=$id_user"); 
	    echo "<script>alert('Solicitud Actualizada');</script>";
	} else {
	    echo "Error: " . "<br>" . $link->error;
	    $link->query("rollback;");
	}

	$link->close();




	mysqli_close($connection);
 ?>