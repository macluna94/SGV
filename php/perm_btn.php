<?php 



function perm_btn($sql_perm,$id){
    while ($extraido = mysqli_fetch_array($sql_perm)){
        if ($extraido["app_id"] == "requests.insert") {
            echo "insertar <br>";
        }
        elseif ($extraido["app_id"] == "requests.query") {
            echo "consultar <br>";
        }
        elseif ($extraido["app_id"] == "requests.aprove") {
            echo "aprobar <br>";
        }
        elseif ($extraido["app_id"] == "requests.delete") {
            echo "borrar <br>";
        }
        elseif ($extraido["app_id"] == "requests.update") {
            echo "actulizar <br>";
        }
        elseif ($extraido["app_id"] == "requests.print") {
            echo "imprimir <br>";
        }
    }
}
function perm_btn($query_perm,$connection,$row){
  $sql_perm = mysqli_query($connection, $query_perm);

  while ($extraido = mysqli_fetch_array($sql_perm)){
  if ($extraido["app_id"] == "requests.insert") {
  echo "insertar <br>";
  }
  elseif ($extraido["app_id"] == "requests.query") {
  echo '
  <td>
  <div class="btn-group btn-group-xs">
  <button type="button"  value="'.$row['id'].'" class="btn btn-default" onclick="viewWin(this.value);" name="ver"><span class="glyphicon glyphicon-search"></span></button>
  </div>
  </td>';
  }
  elseif ($extraido["app_id"] == "requests.delete") {
  echo ' 
  <td>
  <button type="button" value="'.$row['id'].'" name="eliminar"  onclick="delWin(this.value);" class="btn btn-warning" ><span class="glyphicon glyphicon-trash"></span></button>
  </td>';
  }
  elseif ($extraido["app_id"] == "requests.update") {
  echo '
  <td>
  <button type="button" value="'.$row['id'].'" onclick="data_modal_edit(this.value);" class="btn btn-info" name="editar" ><span class="glyphicon glyphicon-pencil"></span></button>
  </td>
  ';
  }
  elseif ($extraido["app_id"] == "requests.print") {
  echo '
  <td>
  <button type="button" value="'.$row['id'].'" onclick="data_modal_print(this.value);"  class="btn btn-default" ><span class="glyphicon glyphicon-print"></span></button>
  </td>
  ';
  }
  elseif ($extraido["app_id"] == "requests.aprove") {
  echo '
  <td>
  <button type="button" value="'.$row['id'].'" name="aprobar"  onclick="aprobWin(this.value);" class="btn btn-success" ><span class="glyphicon glyphicon-ok
  "></button>
  </td>

  ';
  }
  }
}

 ?>