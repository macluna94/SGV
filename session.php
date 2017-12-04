<?php
   include "php/connection.php";
   session_start();
   
   $usuario = $_SESSION['username'];
   
   $ses_sql = mysqli_query($connection,"SELECT * FROM users WHERE users.username = '$usuario' AND (users.active = '-1' OR '1') AND NOT deleted ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   $id_user = $row['id'];
    
   if(!isset($_SESSION['username'])){
      header("location: login/login.php");
   }
?>