<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de contacto</title>
    <link rel="stylesheet" href="../css/contacto.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php 
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    ?>
    <header>
        <a class="img" href="main_guest.php">
            <img src="../recursos/logsinfondo.png" alt="">
        </a>
        <nav>
            <ul id="nav_menu">
                <li><a href="main_guest.php#servicios">Servicios</a></li>
                <li><a href="main_guest.php">Home</a></li>
                <li><a href="trabaja_con_nosotros.php">Trabaja con nosotros</a></li>
            </ul>
        </nav>
        <div id="nav_registro">
            <a id="link_registro" href="registro.php">Registrarse</a>
            <a id="link_login" href="login.php">Ingresar</a>
        </div>
    </header>
    <main>
        <h2>Formulario de contacto</h2>
        <form action="crud/enviar_mail_contacto.php" method="post">
            <fieldset>
                <label for="nombre_contacto"></label>
                <input type="text" name="nombre_contacto" id="nombre_contacto"
                placeholder="Ingrese su nombre" required size="50"><br>
                <label for="correo_contacto"></label>
                <input type="email" name="correo_contacto" id="correo_contacto"
                placeholder="Ingrese su correo electrónico" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"
                required size="50"><br>
                <label for="asunto_contacto"></label>
                <input type="text" name="asunto_contacto" id="asunto_contacto"
                placeholder="Ingrese el asunto de contacto" required size="50"><br>
                <textarea name="mensaje_contacto" id="mensaje_contacto" rows="4" cols="50" required
                placeholder="¡Cuéntanos tu mensaje!"></textarea><br>
                <input type="submit" value="Enviar mensaje" id="contacto_btn">
                <?php if(isset($_GET['mensaje'])):?>
                    <p style="color:green;"><?php echo htmlspecialchars($_GET['mensaje'])?></p>
                <?php endif;?>
                <?php if(isset($_GET['error'])):?>
                    <p style="color:red;"><?php echo htmlspecialchars($_GET['error'])?></p>
                <?php endif;?>
            </fieldset>
        </form>
    </main>
    <?php
    include('footer.php');
    ?>
</body>
</html>