<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/registro_cuenta.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    ?>
<main>
        <h1>Nueva cuenta</h1>
        <form action="" id="form_cuenta" method="POST">
        <fieldset>
            <h2>Datos personales</h2>
            <label for="nombre_persona"></label>
            <input type="text" name="nombre_persona" id="nombre_persona" 
            placeholder="Ingrese su nombre" required size="50"><br>
            <label for="apellido_persona"></label>
            <input type="text" name="apellido_persona" id="apellido_persona" 
            placeholder="Ingrese su apellido" required size="50"><br>
            <div class="username-validado">
              <label for="username"></label>
            <input type="text" name="username" id="username" 
            placeholder="Ingrese un nombre de usuario" required size="50" value="<?php echo $_POST['username'] ?? '' ?>" onchange="this.form.submit()"><br>
            <p id='popover-username'>El nombre de usuario ya existe. Por favor, elija otro.</p>
            
            <?php
            #Avisa que el username ya existe
              $username = $_POST['username'] ?? '';
              include_once('crud/conexion.php');
              include_once('crud/consultas_varias.php');

              if(verificarNombreUsuario($conn, $username)) {
                  echo "<script>
                  let popover = document.getElementById('popover-username');
                  popover.style.display='block';
                  </script>";
              }
              ?>   
                <script>
                  //Agrego un evento al input para que el mensaje se esconda si hay cambios en el input
                  let inp_user = document.getElementById("username");

                  inp_user.addEventListener("input", ()=>{
                    if(popover.style.display=='block'){
                      popover.style.display = 'none';
                    }
                  });
                </script>
            </div>
            <div class="correo-validado">
            <label for="correo_persona"></label>
            <input type="email" name="correo_persona" id="correo_persona" 
            placeholder="Ingrese su correo electrónico" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
            required size="50" value="<?php echo $_POST['correo_persona'] ?? '' ?>" onchange="this.form.submit()"><br>
            <p id='popover-correo'>El correo ya está registrado. Por favor, utilice otro.</p>

            <?php
            #Avisa que el correo ya existe
              $correo = $_POST['correo_persona'] ?? '';
              include_once('crud/conexion.php');
              include_once('crud/consultas_varias.php');

              if(verificarCorreo($conn, $correo)) {
                  echo "<script>
                  let popover_correo = document.getElementById('popover-correo');
                  popover_correo.style.display='block';
                  </script>";
              }
              ?>   
                <script>
                  //Agrego un evento al input para que el mensaje se esconda si hay cambios en el input
                  let inp_correo = document.getElementById("correo_persona");

                  inp_user.addEventListener("input", ()=>{
                    if(popover_correo.style.display=='block'){
                      popover_correo.style.display = 'none';
                    }
                  });
                </script>
            </div>
            <label for="pass"></label>
            <input type="password" name="pass" id="pass" minlength="8"
            maxlength="16" placeholder="Ingrese una contraseña" required size="50"><br>
            <label for="tel_persona"></label>
            <input type="tel" name="tel_persona" id="tel_persona" minlength="10"
            maxlength="11" placeholder="Ingrese un celular de contacto" required size="50"><br>
        </fieldset>
        <fieldset>
            <h2>Datos de dirección</h2>
            <label for="localidad"></label>
            <select name="localidad" id="localidad" required>
                <option value="" disabled selected>Seleccione su localidad</option>
                <option value="CABA">Ciudad Autónoma de Buenos Aires</option>
            </select><br>
            <label for="barrio"></label>
            <select name="barrio" id="barrio">
                <option value="" disabled selected>Seleccione su barrio</option>
                <?php
                include('barrios.php');
                ?>
            </select><br>
            <label for="calle"></label>
            <input type="text" name="calle" id="calle" size="50"
            placeholder="Ingrese su calle" required><br>
            <label for="altura_calle"></label>
            <input type="number" name="altura_calle" id="altura_calle"
            placeholder="Ingrese la altura de su dirección" required min="1" max="20000"><br>
            <input type="submit" value="Crear cuenta" formaction="crud/insert_persona.php" id="btn_crear_cuenta">
        </fieldset><br>
    </form>
    <div id="seccion_volver">
        <a href="login.php" id="link_login">Ya tengo una cuenta</a><br><br>
        <a href="main_guest.php" id="link_main">Cancelar</a>
    </div>
</main>
<?php
include('footer.php');
?>
</body>
<link rel="stylesheet" href="../css/footer_styles.css">
</html>