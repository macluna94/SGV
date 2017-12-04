<?php 
include "connection.php";

if (!$connection) {
	die("<h1>Error de coneccion</h1>".mysqli_error($connection));
}
mysqli_select_db($connection,"ajax_demo3");

    $query_programs = $connection->query("SELECT programs.id, programs.caption FROM programs ");

echo '
	<div class="form-group">
		<label for="sel1">Programa:</label>
			<select name = "programa" class="form-control" required>
				<option></option>
						';

while ($x = $query_programs->fetch_array()) {
    $options_programs.='<option value="'.$x["id"].'">'.$x["caption"].'</option>';
}

echo $options_programs,'
						</select>
				</div>
			</div>';

mysqli_close($connection);
 ?>



    

       
      