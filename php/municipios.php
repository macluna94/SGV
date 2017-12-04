<?php 
include "connection.php";

$estado = $_GET['estado'];

if (!$connection) {
	die("<h1>Error de coneccion</h1>".mysqli_error($connection));
}
mysqli_select_db($connection,"ajax_demo2");

$sql_mp = "SELECT id, `name` FROM municipalities WHERE state = ".$estado." ORDER BY `name`";


$result = mysqli_query($connection, $sql_mp);

echo '<label for="solicitud">Municipio:</label>
<select name="municipio" class="form-control" required>
<option></option>
';

while ($d = mysqli_fetch_array($result)) {
echo '<option value="'.$d["id"].'">'.$d["name"].'</option>';
}
echo "</select>";

mysqli_close($connection);
 ?>
