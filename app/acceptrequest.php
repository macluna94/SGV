<?php
include "../php/connection.php";
include "../session.php";

//$id_user -> session.php
$idrequest =$_POST['str'];

$log_accepted = "INSERT INTO `log` VALUES (NULL, 1, $idrequest, $id_user, 3, 'Aceptada', NOW());";


if ($link->connect_error){
	die("Connection failed: ".$link->connect_error);
}
 
if ($link->query($log_accepted) === TRUE) {
	echo "Aceptado";

} else {
    echo "Error: " . "<br>" . $link->error;
}

$link->close();
 ?>