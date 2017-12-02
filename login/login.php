<!DOCTYPE html>
<html>
<!-- Hoja de Inicio de sesion -->
<head>
	<title>Login Admin</title>
<!--  Hoja de estilo interna -->
	<link rel="stylesheet" href="../css/theme_login.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">

<!--  Archivos de jquery y bootstrap externos -->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

<script>
     
$(document).ready(function(){
            usuario = $('#username');
            pass = $('#password');
            b_enviar = $('#submit');


            usuario.on('input', validate);
            pass.on('input', validate);
function validate() {
    limpio = (usuario.val().length > 0) && (pass.val().length > 0);
    b_enviar.prop('disabled', !limpio);
}

validate();
});
</script>



</head>
<body>

    <div class="container">
        <div class="page-header">
          <h1 class="text-center" >Sistema de Gestión Vehicular</h1>
        </div>
        <div class="card card-container">
            
            <img id="profile-img" class="profile-img-card" src="../imgs\logo.png" alt="SGV" />
            <p id="profile-name" class="profile-name-card"></p>

<!--          Checklogin es la parte que valida los datos ingresados  -->
            <form class="form-signin" action="check_login.php" method="POST" >
<!--                Nombre de usuario = 'username' -->
                <input type="text" id="username" class="form-control" placeholder="Codigo" name="username" required autofocus>
<!--                 Contraseña = 'password'   -->
                <input type="text" id="password" class="form-control" name="password" placeholder="Contraseña" required>
                <button class="btn btn-lg btn-primary btn-block btn-signin"  name="submit" id="submit" type="submit">Iniciar</button>
            </form>
            </div>
        </div>
    </div>
 
</body>
</html>