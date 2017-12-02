<?php 
	session_start();
?>

<?php
 
include "../php/connection.php";

$usuario = $_POST['username'];
$prepass = $_POST['password'];

$hash = '=/TF&"!a1' . $prepass . '/)%D$"';
$pass = md5($hash);

$sql = "SELECT * FROM users WHERE users.username = '$usuario' AND users.`password` = '$pass' AND (users.active = '-1' OR '1') AND NOT deleted";



        $c_id = mysqli_query($connection,"SELECT users.id FROM users WHERE users.username = '$usuario'");
        $e_id = mysqli_fetch_row($c_id);
        $id = $e_id[0];


$rows = mysqli_query($connection,$sql);

if ($row = mysqli_fetch_row($rows)) {
	echo $row[3];
	if ($row[3] == $pass) {
		session_start();
			$_SESSION['username'] = $usuario;
			header("Location: ../principal.php");
			mysqli_query($connection, "INSERT INTO `log` VALUES (NULL, 9, 0, $id, 6, 'Inicio de sesion $id', NOW());");
	}
	else{
		header("Location: login.php");
			exit();
	}

}
else{
	header("Location: login.php");
		exit();
}
?>