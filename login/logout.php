<?php
   include "../session.php";
   include "../php/connection.php";
   if(session_destroy()) {
      header("Location: login.php");
      mysqli_query($connection, "INSERT INTO `log` VALUES (NULL, 9, 0, $id_user, 7, 'Cerrar Sesion', NOW());");
   }
?>
 