<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Persona</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
    <link rel="stylesheet" href="../../css/editar_usuario_admin.css">
</head>
<body>
    <?php
    //Validacion de permisos
    require_once('auth.php');

    require_once('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');

    //Valido que tenga pass app para que pueda enviar el correo inicial
    if (session_status() == PHP_SESSION_NONE) { session_start(); }

    $user_admin = $_SESSION['username'];

    $id_admin = obtenerIdPersona($conn,$user_admin);

    $pass_app = obtenerPassAppPorId($conn,$id_admin);

    if($pass_app == null){
      header('Location: main_admin.php?mensaje=No se poseen las credenciales necesarias.');
    }

    //Inserto el header
    include('header_admin.php');
    ?>
        <h1>Nuevo usuario</h1>
        <main>
        <form action="" id="form_cuenta" method="POST">
        <fieldset>
            <h2>Datos personales</h2>
            <label for="nombre_persona"></label>
            <input type="text" name="nombre_persona" id="nombre_persona" 
            placeholder="Ingrese su nombre" required size="50"
            value="<?php echo $_POST['nombre_persona'] ?? '' ?>">
            <label for="apellido_persona"></label>
            <input type="text" name="apellido_persona" id="apellido_persona" 
            placeholder="Ingrese su apellido" required size="50"
            value="<?php echo $_POST['apellido_persona'] ?? '' ?>">
            <div class="username-validado">
              <label for="username"></label>
            <input type="text" name="username" id="username" 
            placeholder="Ingrese un nombre de usuario" required size="50"
            value="<?php echo $_POST['username'] ?? '' ?>" onchange="this.form.submit()">
            <p id='popover-username' style="display:none; color: red;">El nombre de usuario ya existe. Por favor, elija otro.</p>
            
            <?php
              $username = $_POST['username'] ?? '';

              if(verificarNombreUsuario($conn, $username)) {
                  echo "<script>
                  let popover = document.getElementById('popover-username');
                  popover.style.display='block';
                  </script>";
              }
              ?>   
                <script>
                  let inp_user = document.getElementById("username");
                  let popover = document.getElementById("popover-username");

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
            required size="50" value="<?php echo $_POST['correo_persona'] ?? '' ?>" onchange="this.form.submit()">
            <p id='popover-correo' style="display:none; color: red;">El correo ya está registrado. Por favor, utilice otro.</p>

            <?php
              $correo = $_POST['correo_persona'] ?? '';
              if(verificarCorreo($conn, $correo)) {
                  echo "<script>
                  let popover_correo = document.getElementById('popover-correo');
                  popover_correo.style.display='block';
                  </script>";
              }
              ?>   
                <script>
                  let inp_correo = document.getElementById("correo_persona");
                  let popover_correo = document.getElementById("popover-correo");

                  inp_correo.addEventListener("input", ()=>{
                    if(popover_correo.style.display=='block'){
                      popover_correo.style.display = 'none';
                    }
                  });
                </script>
            </div>

            <label for="pass"></label>
            <input type="password" name="pass" id="pass" minlength="8"
            maxlength="16" placeholder="Ingrese una contraseña" required size="50"
            value="<?php echo $_POST['pass'] ?? '' ?>">

            <label for="rol"></label>
            <select name="rol" id="rol" required>
                <option value="" disabled <?php echo empty($_POST['rol']) ? 'selected' : '' ?>>Seleccione el tipo de rol del usuario</option>
                <option value="admin">Administrador</option>
                <option value="cliente">Cliente</option>
                <option value="trabajador">Trabajador</option>
            </select>
            <label for="tel_persona"></label>
            <input type="tel" name="tel_persona" id="tel_persona" minlength="10"
            maxlength="11" placeholder="Ingrese un celular de contacto" required size="50"
            value="<?php echo $_POST['tel_persona'] ?? '' ?>"><br>
        </fieldset>

        <fieldset>
            <h2>Datos de dirección</h2>
            <label for="localidad"></label>
            <select name="localidad" id="localidad" required>
                <option value="" disabled <?php echo empty($_POST['localidad']) ? 'selected' : '' ?>>Seleccione su localidad</option>
                <option value="CABA" <?php echo (($_POST['localidad'] ?? '') == 'CABA') ? 'selected' : '' ?>>Ciudad Autónoma de Buenos Aires</option>
            </select>

            <label for="barrio"></label>
            <select name="barrio" id="barrio">
                <option value="" disabled <?php echo empty($_POST['barrio']) ? 'selected' : '' ?>>Seleccione su barrio</option>
                <?php include('../barrios.php'); ?>
            </select>
            <script>
                // Mantener el barrio seleccionado
                document.getElementById('barrio').value = "<?php echo $_POST['barrio'] ?? '' ?>";
            </script>
            <label for="calle"></label>
            <input type="text" name="calle" id="calle" size="50"
            placeholder="Ingrese su calle" required
            value="<?php echo $_POST['calle'] ?? '' ?>">
            <label for="altura_calle"></label>
            <input type="number" name="altura_calle" id="altura_calle"
            placeholder="Ingrese la altura de su dirección" required min="1" max="20000"
            value="<?php echo $_POST['altura_calle'] ?? '' ?>">
            <input type="submit"  id="btn_guardar_persona" value="Crear cuenta" formaction="../crud/insert_persona.php" id="btn_crear_cuenta">
        </fieldset>
    </form>
    <section id="volver_s">
        <a href="personas_admin.php">Volver a Administración de personas</a>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>