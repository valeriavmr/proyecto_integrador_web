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
<main>
        <h1>Nueva cuenta</h1>
    <form action="crud/insert_persona.php" id="form_cuenta" method="POST">
        <fieldset>
            <h2>Datos personales</h2>
        <label for="nombre_persona"></label>
        <input type="text" name="nombre_persona" id="nombre_persona" 
        placeholder="Ingrese su nombre" required size="50"><br>
        <label for="apellido_persona"></label>
        <input type="text" name="apellido_persona" id="apellido_persona" 
        placeholder="Ingrese su apellido" required size="50"><br>
        <label for="username"></label>
        <input type="text" name="username" id="username" 
        placeholder="Ingrese un nombre de usuario" required size="50"><br>
        <label for="correo_persona"></label>
        <input type="email" name="correo_persona" id="correo_persona" 
        placeholder="Ingrese su correo electrónico" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
        required size="50"><br>
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
                <option selected>Seleccione su localidad</option>
                <option value="CABA">Ciudad Autónoma de Buenos Aires</option>
            </select><br>
            <label for="barrio"></label>
            <select name="barrio" id="barrio">
                <option selected>Seleccione su barrio</option>
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
            <input type="submit" value="Crear cuenta" id="btn_crear_cuenta">
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