<?php
	include "../php/connection.php";
	include "../session.php";
	
	$idrequest =$_POST['str'];


	$delete_query = "UPDATE requests SET deleted = 1 WHERE requests.id = $idrequest";

	$log_delete = "INSERT INTO `log` VALUES (NULL, 1,1, $idrequest, $id_user, 2, 'Solicitud eliminada $idrequest', NOW());";

	$link = new mysqli('localhost','root','','autos');

	if ($link->connect_error){die("Connection failed: ".$link->connect_error);}


	if ($link->query($delete_query) === TRUE) {
		$link->query($log_delete);
		echo "Solicitud: ",$idrequest," eliminada";;

	} else {
	    echo "Error: " . "<br>" . $link->error;
	}

	$link->close();
?>