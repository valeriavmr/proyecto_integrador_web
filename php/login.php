<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_ACTIVE) {
        session_destroy();
    }

    // Capturamos un posible mensaje de error desde la URL
    $error = isset($_GET['error']) ? $_GET['error'] : '';

    ?>
    <main>
        <form action="crud/select_login.php" id="form_cuenta_login" method="POST">
        <fieldset>
        <h2>Ingresar a la cuenta</h2>
        <label for="username">Nombre de usuario</label><br>
        <input type="text" name="username" id="username" 
            placeholder="Ingrese un nombre de usuario" required size="50"><br>
        <label for="pass">Contraseña</label><br>
        <input type="password" name="pass" id="pass" minlength="8"
            maxlength="16" placeholder="Ingrese una contraseña" required size="50"><br>
            <!-- Mensaje de error -->
            <?php if($error): ?>
                <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <input type="submit" value="Ingresar" id="btn_login"><br><br>
            <div id="seccion_volver">
            <a href="registro.php" id="link_registro">No tengo una cuenta</a><br><br>
            <a href="main_guest.php" id="link_main">Cancelar</a>
            </div>
        </fieldset>
    </form>
    </main>
    <?php
    include('footer.php');
    ?>
</body>
<<<<<<< HEAD
<link rel="stylesheet" href="../css/footer_styles.css">
=======
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
</html>