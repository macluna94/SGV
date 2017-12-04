<?php
include "php/connection.php";
include "session.php";

//$id_user -> session.php
$idrequest =$_POST['str'];

#INSERT INTO `log` VALUES (NULL, 1, 1, :RECORD, :USER, 5, 'Motivos de cancelacion', NOW());
$log_delete = "INSERT INTO `log` VALUES (NULL, 1, $idrequest, $id_user, 5, 'Cancelado - $idrequest', NOW());";

if ($link->connect_error){die("Connection failed: ".$link->connect_error);}


if ($link->query($log_delete) === TRUE) {
	echo "Solicitud: ",$idrequest," Cancelada";;
} else {
    echo "Error: " . "<br>" . $link->error;
}

$link->close();
 ?>