<?php
//              Creacion de Sesion
   session_start();
?>

<?php
    //Archivo de conexion
    include 'php/connection.php';

//              Comprobacion de fallo en la conexion
    if ($conexion->connect_error) {
        die("La conexion falló: " . $conexion->connect_error);
    }

        if($_SERVER["REQUEST_METHOD"] == "POST") {



//              Captura del nombre de usuario
            $myusername = mysqli_real_escape_string($conexion,$_POST['username']);

//              Captura de contraseña
            $prepass = mysqli_real_escape_string($conexion,$_POST['password']);

//              Metodo de conversion MD5
            $hash = '=/TF&"!a1' . $prepass . '/)%D$"';
            $mypassword = md5($hash);

//              Query para comprobar
            $sql = "SELECT * FROM users WHERE username = '$myusername' AND password = '$mypassword' AND active = '1' AND NOT deleted ";

//              Extraccion del "id" de "usuario""
            $c_id = mysqli_query($conexion,"SELECT users.id FROM users WHERE users.username = '$myusername'");
            $e_id = mysqli_fetch_row($c_id);
            //      id de usuario
            $id = $e_id[0];

//              Comprobacion de credenciales
            $result = mysqli_query($conexion,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $active = $row['active'];
            $count = mysqli_num_rows($result);

//              Direccionamiento a hoja principal
//              paso de var $id en url a hoja principal
            if($count == 1) {
                $_SESSION['login_user'] = $myusername;
                // Se pasa el id
                header("location: ../inicio/principal_v2.php?id=".$id." ");
            }
        else {
//              Si lo datos no son validos regresa al login.php
        header("location: ../login/login.php");
        }
    }
?>
