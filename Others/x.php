<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>

    <script>
    $(document).ready(function(){
        $(".boton").click(function(){

            var valores="";

            // Obtenemos todos los valores contenidos en los <td> de la fila
            // seleccionada
            $("td").parents("tr").find("td").each(function(){
                valores+=$(this).html()+"\n";
            });

            //alert(valores);
        });
    });
    </script>

    <style>
        .boton {border:1px solid #808080;cursor:pointer;padding:2px 5px;color:Blue;}
    </style>

<?php header('Content-Type: text/html; charset=UTF-8'); ?>

</head>




<body>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <td class="td">val 1</td>
            <td class="td">val 2</td>
            <td class="td">val 3</td>
            <td class="boton">coger valores de la fila</td>
        </tr>
        <tr>
            <td class="td">val 4</td>
            <td class="td">val 5</td>
            <td class="td">val 6</td>
            <td class="boton">coger valores de la fila</td>
        </tr>
        <tr>
            <td>val 7</td>
            <td>val 8</td>
            <td>val 9</td>
            <td class="boton">coger valores de la fila</td>
        </tr>
    </table>
</body>
</html>
