<!DOCTYPE html>
<?php include "session.php"; ?>
<html>
  <head>
    <title>SGV</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css\bootstrap.min.css">
    <script src="//code.jquery.com/jquery-3.1.0.min.js"></script>
    <script src="js\bootstrap.min.js"></script>


<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">


<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" charset="utf-8" async defer></script>

    <script src="js\block_btns.js"></script>

<script type="text/javascript">
  
$(document).ready(function()
{
  //Mensaje de Bienvenida
 // $("#mostrarmodal").modal("show");
});

</script>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
var table = $('#example').DataTable();

    table.destroy();
$('#example').dataTable(
    {
        "ordering": false,
        "language": {
             "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "",
    "sInfoEmpty":      "",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
        }
    }
);
console.log("Cargada function:\t Tablas Bootstrap3");
});
</script>

    <?php 
      header('Content-Type: text/html; charset=UTF-8');
      $id = $id_user;

      include "php/connection.php";
      include "php/querys.php";
      //include "php/perm_btn.php";

      $c_id = mysqli_query($connection,"SELECT users.`name` FROM users WHERE users.id = $id");
        $e_id = mysqli_fetch_row($c_id);
        $nombre_pantalla = $e_id[0];


    ?>

  </head>
  <body>

    <div class="" style="padding-left: 0px;padding-right: 0px;border-right-width: 1px;margin-right: 2px;margin-left: 3px;padding-top: 1px;border-bottom-width: 1px;padding-bottom: 1px;margin-top: 1px;margin-bottom: 1px;">
      

<a href = "login/logout.php" class="btn btn-default" role="button" style="float: right; color: #17a8ff;"  >Cerrar Sesion</a></h2>
      
<div class="page-header text-center" style="margin-top: 10px;padding-bottom: 0px;margin-bottom: 10px;">
  <h1 style="margin-left: 50px; margin-right: 50px;" >Servicio de Gestion Vehicular <br>
  <small style="margin-right: 73px;"  >Centro Universitario del Norte</small>
  </h1>
</div>
<h5 style="margin-bottom: 0px;" ><b>Bienvenido: </b><span class="label label-success"><?php echo $nombre_pantalla; ?></span></h5>
<br>




<div class="panel panel-default">
<div class="panel-heading"> <h3> Solicitudes </h3></div>
<div class="panel-body">
  

    <div class="table-responsive">
      

    <?php


 //echo $sql_viewrequest_all;
        global $manita;
        global $aprob;
        $sql_perm = mysqli_query($connection, $query_perm);

        //echo $query_perm;
        function perm_btn($query_perm,$connection,$row){
            $sql_perm = mysqli_query($connection, $query_perm);
            while ($extraido = mysqli_fetch_array($sql_perm)){
                if ($extraido["id"] == 12) {  $consultar = true;    }
                elseif ($extraido["id"] == 13) { $insetar = true;   }
                elseif ($extraido["id"] == 16) { $aprobar = true;   }
                elseif ($extraido["id"] == 15) { $borrar = true;    }
                elseif ($extraido["id"] == 17) { $imprimir = true;  }
                elseif ($extraido["id"] == 14) { $editar = true;    }
            }
            if (isset($consultar)) {
                echo '<td><div class="btn-group btn-group-xs"><button type="button"  value="'.$row['id'].'" class="btn btn-default" onclick="viewWin(this.value);" name="ver" style ="margin-top: 13px;"   ><span class="glyphicon glyphicon-search"></span></button></div></td>';
            }
            if(isset($editar)){
                echo '<td class="text-center" style="width: 90px;"><div class="btn-group btn-group-xs"><button type="button" value="'.$row['id'].'" onclick="data_modal_edit(this.value);" class="btn btn-info" name="editar" style ="margin-top: 13px;" ><span class="glyphicon glyphicon-pencil"></span></button>';
            }
            if (isset($imprimir)) {
                echo '<button type="button" value="'.$row['id'].'" onclick="data_modal_print(this.value);"  class="btn btn-default" style ="margin-top: 13px;" ><span class="glyphicon glyphicon-print"></span></button>';
            }
            if (isset($borrar)) { 
                echo '<button type="button" value="'.$row['id'].'" name="eliminar" onclick="data_modal_delete(this.value);" class="btn btn-warning" style ="margin-top: 13px;" ><span class="glyphicon glyphicon-trash"></span></button></div></td>';
            }
            if (isset($aprobar)) {
                echo '  <td class="text-center" style="width: 65px;"><div class="btn-group btn-group-xs"  style="margin: 13px;">

                      <button type="button" value="'.$row['id'].'" name="aceptar"  onclick="data_modal_accept(this.value)" class="btn btn-success" ><span class="glyphicon glyphicon-ok"></button>

                <button type="button" value="'.$row['id'].'" name="negar"  onclick="denegWin(this.value);" class="btn btn-danger" ><span class="glyphicon glyphicon-remove"></button></div></td>';
            }
        }

        while ($extraido = mysqli_fetch_array($sql_perm)){
          //echo "Numero: ",$extraido[1],"<br><br>";
            if ($extraido[1] =='22'){
                $manita = "viewall";
                //echo "viewall";
            }
            if ($extraido[1] =='16'){
                $aprob = "aprobar";
                //echo "Aprobar";
            }
            else{
              //echo "Normal";
            }
        }


        if ($manita == 'viewall' AND $aprob == "aprobar") {
            $result = mysqli_query($connection,$sql_viewrequest_all);

                echo '<script>
                $(document).ready(function(){
                console.log("Admin o Secretaria Admin");
                });
                </script>';

                echo '
          <table id="example" class="table table-striped table-bordered" >
            <thead>
              <tr class="success">
                <th>Estado</th>
                <th>Creado por</th>
                <th class="text-center" style="width: 148px;">Autorizado</th>
                <th class="text-center" style="width: 148px;">Siguiente Autorizado</th>
                <th class="text-center" style="width: 210px;">Evento</th>
                <th class="text-center" style="width: 112px;">Fecha de salida</th>
                <th class="text-center" style="width: 130px;">Conductor</th>
                <th class="text-center" style="width: 130px;">Responsable</th>
                <th class="text-center" style="width: 90px;">Vehiculo</th>
                <th class="text-center"></th>
                <th class="text-center">Admin or Secre</th>
                <th class="text-center"></th>
              </tr>
            </thead>
                <tbody id="list_request">';
                while($row = mysqli_fetch_array($result)){
                echo "<tr class='small'>";

                    if ($row['approved'] == 1  OR ($row['max_authorized_index'] == $row['max_index'])) {
                  echo "<td style='background-color: chartreuse'>Aprobado</td>";
            }
            else{
              echo "<td style='background-color: gold'>Esperando</td>";
            }


                echo   "<td class='text-center'>".$row['usercreatedname']."</td>
                        <td class='text-center'>".$row['authorized_group']."</td>
                        <td class='text-center'>".$row['next_authorization_group']."</td>
                        <td class='text-center'>".$row['reasons']."</td>
                        <td class='text-center'>".substr($row['departuredate'], 0,-9)."</td>
                        <td class='text-center'>".$row['driver_name']."</td>
                        <td class='text-center'>".$row['responsable_name']."</td>
                        <td class='text-center'>".substr($row['vehicle_cap'],4,25)."</td>";
                perm_btn($query_perm,$connection,$row);
                echo '</tr>';
            }
            echo "</tbody></table>";
        }


  elseif ($aprob == "aprobar") {
            $viewrow = mysqli_query($connection,$sql_viewrequest);

                echo '<script>
                $(document).ready(function(){
                console.log("Jefe de Departamento");
                });
                </script>';

            echo '
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr class="success">
            <th class="text-center">Estado</th>
            <th class="text-center" class="text-center">Creado por</th>
            <th class="text-center" style="width: 148px;">Autorizado</th>
            <th class="text-center" style="width: 148px;">Siguiente Autorizado</th>
            <th class="text-center" style="width: 210px;">Evento</th>
            <th class="text-center" style="width: 112px;">Fecha de salida</th>
            <th class="text-center" style="width: 130px;">Conductor</th>
            <th class="text-center" style="width: 130px;">Responsable</th>
            <th class="text-center" style="width: 90px;">Vehiculo</th>
            <th class="text-center" ></th>
            <th class="text-center" >Departament</th>
            <th class="text-center" ></th>
            </tr>
            </thead>
            <tbody id="list_request" >'; 
            while($row = mysqli_fetch_array($viewrow)){
            echo "<tr class='small'>";

            if ($row['approved'] == 1  OR ($row['max_authorized_index'] == $row['max_index'])) {
            echo "<td style='background-color: chartreuse'>Aprobado</td>";
            }
            else{
            echo "<td style='background-color: gold'>Esperando</td>";
            }


            echo   "<td class='text-center' >".$row['usercreatedname']."</td>
            <td class='text-center' >".$row['authorized_group']."</td>
            <td class='text-center' >".$row['next_authorization_group']."</td>
            <td class='text-center'>".$row['reasons']."</td>
            <td class='text-center' >".substr($row['departuredate'], 0,-9)."</td>
            <td class='text-center' >".$row['driver_name']."</td>
            <td class='text-center' >".$row['responsable_name']."</td>
            <td class='text-center' >".substr($row['vehicle_cap'],4,25)."</td>"
            ;
            perm_btn($query_perm,$connection, $row);
            echo "</tr>";
            }
            echo "</tbody></table>";
        }

      
        else {
          $viewrow = mysqli_query($connection,$sql_viewrequest);

 echo '<script>
                $(document).ready(function(){
                console.log("Coordinador o normal");
                });
                </script>';

          echo '
          <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
          <tr class="success">
          <th class="text-center">Estado</th>
          <th class="text-center" class="text-center">Creado por</th>
          <th class="text-center" style="width: 148px;">Autorizado</th>
          <th class="text-center" style="width: 148px;">Siguiente Autorizado</th>
          <th class="text-center" style="width: 210px;">Evento</th>
          <th class="text-center" style="width: 112px;">Fecha de salida</th>
          <th class="text-center" style="width: 130px;">Conductor</th>
          <th class="text-center" style="width: 130px;">Responsable</th>
          <th class="text-center" style="width: 90px;">Vehiculo</th>
          <th class="text-center" ></th>
          <th class="text-center" >Normal</th>

          </tr>
          </thead>
          <tbody id="list_request" >'; 
          while($row = mysqli_fetch_array($viewrow)){
          echo "<tr class='small'>";

          if ($row['approved'] == 1  OR ($row['max_authorized_index'] == $row['max_index'])) {
          echo "<td style='background-color: chartreuse'>Aprobado</td>";
          }
          else{
          echo "<td style='background-color: gold'>Esperando</td>";
          }


          echo   "<td class='text-center' >".$row['usercreatedname']."</td>
          <td class='text-center' >".$row['authorized_group']."</td>
          <td class='text-center' >".$row['next_authorization_group']."</td>
          <td class='text-center'>".$row['reasons']."</td>
          <td class='text-center' >".substr($row['departuredate'], 0,-9)."</td>
          <td class='text-center' >".$row['driver_name']."</td>
          <td class='text-center' >".$row['responsable_name']."</td>
          <td class='text-center' >".substr($row['vehicle_cap'],4,25)."</td>"
          ;
          perm_btn($query_perm,$connection, $row);
          echo "</tr>";
          }
          echo "</tbody></table>";
        }
    ?>

</div>
</div>
<div class="panel-footer">

    <script>
        function viewWin(str){
            v_Win = window.open("viewrequest.php?idquest="+str,"Visualizacion_de_Solicitud","width=900, height=600, centerscreen=yes,location=no,status=no");
        }
        function editWin(str){
            e_Win = window.open("editrequest.php?idquest="+str,"Visualizacion_de_Solicitud","width=900, height=600, centerscreen=yes,location=no,status=no");
            console.log("Editar la solicitud: " + str);
        }
        function data_modal_print(str){
            console.log("Valor: " + str);
            $("button[id=b_print]").val(str);
            console.log("Valor "+str+" asignado al boton aceptar(modal[imprimir])");
            $("#imprimir").modal("toggle");
        }
        function data_modal_edit(str){
            console.log("Valor: " + str);
            $("button[id=b_edit]").val(str);
            console.log("Valor "+str+" asignado al boton aceptar(modal[editar])");
            $("#editar").modal("toggle");
        }
        function data_modal_delete(str){
            console.log("Valor: " + str);
            $("button[id=b_delete]").val(str);
            console.log("Valor "+str+" asignado al boton aceptar(modal[eliminar])");
            $("#eliminar").modal("toggle");
        }
            function delete_n(str){
            

                $.post("deleterequest.php",{str:str},function(str){
                console.log("Datos cargados "+ str);
                    $(".selected").remove();
                });
            }

        function print_n(str){
            p_Win = window.open("printrequest.php?idquest="+str,"Visualizacion_de_Solicitud","width=900, height=600, centerscreen=yes,location=no,status=no");
            console.log("Solicitud: "+str+ " impresa");
        }


function data_modal_accept(str){
  console.log("Valor: " + str);
            $("button[id=b_accept]").val(str);
            console.log("Valor "+str+" asignado al boton aceptar(modal[aceptar])");
            $("#aceptar").modal("toggle");
}

  function accept_n(str){
                $.post("acceptrequest.php",{str:str},function(str){
                console.log("Datos cargados "+ str);
                });
            }



    </script>

  <div class="row">  
    <div class="col-xs-2">

        <button id="actualizar" class="btn btn-info " onclick="javascript:location.reload()" > <span class="glyphicon glyphicon-refresh"></span> Actualizar</button>

    </div>
      <div class="col-xs-8"></div>
      <div class="col-xs-2">


<?php 
 echo '



      <a href="requests.php" class="btn btn-primary" ><span class="glyphicon glyphicon-plus"></span> Agregar </a>
 ';
 ?>




      </div>
  </div>
</div>

  <div class="col-xs-10"></div>
  <div class="col-xs-1">
  </div>
<div class="col-xs-1">
    
</div>

  </div>

  <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h3>Cabecera de la ventana</h3>
  </div>

  <div class="modal-body">
  <h4>Texto de la ventana</h4>
  Mas texto en la ventana.
  </div>
  <div class="modal-footer">
  <a href="#" data-dismiss="modal" class="btn btn-danger">Cerrar</a>
  </div>
  </div>
  </div>
  </div> 


<!-- Confirmar -->
  <div id="aceptar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: limegreen"  >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title text-center" style="color: white"><strong>Aprobar</strong></h2>
        </div>
        <div class="modal-body">
          <h4>¿Esta seguro de aprobar?</h4>
          <img src="imgs\aprobar.png" alt="borrar" height="90" width="90" style="display: block;margin-left: auto;margin-right: auto; border: none;">
        </div>
        <div class="modal-footer">
          <div class="col-xs-5">
            <button type="button" class="btn btn-success" id="b_accept"  value="" onclick="accept_n(this.value);" data-dismiss="modal">Aceptar</button>
          </div>
          <div class="col-xs-1"></div>
          <div class="col-xs-5">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-1"></div>
        </div>
      </div>
    </div>
  </div>

<!-- Negar -->
  <div id="negar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: tomato;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title text-center" style="color: white;"><strong>Denegar</strong></h2>
        </div>
        <div class="modal-body">
          <h4>¿Esta seguro de cancelar la solicitud?</h4>
          <img src="imgs\negar.png" alt="borrar" height="90" width="90" style="display: block;margin-left: auto;margin-right: auto; border: none;">
        </div>
        <div class="modal-footer">
          <div class="col-xs-5">
               <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
          </div>
          <div class="col-xs-1"></div>
          <div class="col-xs-5">
             <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-1"></div>
        </div>
      </div>
    </div>
  </div>

<!-- Imprimir -->
  <div id="imprimir" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: steelblue">
          <h2 class="modal-title text-center" style="color: white;"> <strong>Imprimir</strong></h2>
        </div>
        <div class="modal-body">
          <h4 class="text-center" >¿Desea imprimir la solicitud?</h4>
          <img src="imgs\imprimir.png" alt="borrar" height="90" width="90" style="display: block;margin-left: auto;margin-right: auto; border: none;">
        </div>
        <div class="modal-footer">
          <div class="col-xs-5">
             <button type="button"  id="b_print" class="btn btn-success" value="" onclick="print_n(this.value);" data-dismiss="modal">Aceptar</button>
          </div>
          <div class="col-xs-1"></div>
          <div class="col-xs-5">
             <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-1"></div>
        </div>
      </div>
    </div>
  </div>

<!-- Eliminar -->
  <div id="eliminar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: gold;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title text-center" style="color: white"> <strong>Eliminar</strong></h2>
        </div>
        <div class="modal-body">
          <h4>¿Esta seguro de eliminar esta solicitud?</h4>
          <img src="imgs\borrar.png" alt="borrar" height="90" width="90" style="display: block;margin-left: auto;margin-right: auto; border: none;">
        </div>
        <div class="modal-footer">
          <div class="col-xs-5">
            <button type="button" id="b_delete" class="btn btn-success" value="" onclick="delete_n(this.value);" data-dismiss="modal">Aceptar</button>
          </div>
          <div class="col-xs-1"></div>
          <div class="col-xs-5">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-1"></div>
        </div>
      </div>
    </div>
  </div>

<!-- Editar -->
  <div id="editar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: deepskyblue;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title text-center" style="color: white"> <strong>Editar solicitud</strong></h2>
        </div>
        <div class="modal-body">
          <h4>¿Esta seguro de editar esta solicitud?</h4>
          <img src="imgs\editar.png" alt="editar" height="90" width="90" style="display: block;margin-left: auto;margin-right: auto; border: none;">
        </div>
        <div class="modal-footer">
          <div class="col-xs-5">
            <button type="button" id="b_edit" onclick="editWin(this.value);" class="btn btn-success" data-dismiss="modal">Aceptar</button>
          </div>
          <div class="col-xs-1"></div>
          <div class="col-xs-5">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          <div class="col-xs-1"></div>
        </div>
      </div>
    </div>
  </div>

<div>
</div>
  </body>
</html>




