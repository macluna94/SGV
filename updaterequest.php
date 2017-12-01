<?php 
header('Content-Type: text/html; charset=UTF-8');
include "php/connection.php";

$id_request = $_POST['id'];
$transporte = $_POST['transporte'];
$conductor = $_POST['conductor']; // NULL

$asistentes = $_POST['nombres'];
$codigos = $_POST['codigos'];

$nombre = explode("\n",$asistentes);
$j = count($nombre);
$codigo = explode("\n",$codigos);
$list = "";

$pre_query = "

SET @ID = $id_request;
UPDATE requests SET vehicle = $transporte, driver = $conductor WHERE id = @ID;
DELETE FROM request_passengers WHERE request = @ID;";



$i_query = "SET @ID = LAST_INSERT_ID()";
echo $pre_query, "<br>",$i_query,"<br>";

$query = "";


for ($c=0; $c < $j-1; $c++) { 
	$list .= "INSERT INTO request_passengers VALUES (@ID, '$nombre[$c]', $codigo[$c]	)"."<br>";

}
	echo $list, "<br>";


mysqli_close($connection);

 ?>