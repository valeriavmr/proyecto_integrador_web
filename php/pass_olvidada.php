<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi contraseña</title>
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
    <main>
        <form action="crud/recuperar_pass.php" method="POST">
            <fieldset>
            <h2>Olvidé mi contraseña</h2>
            <label for="username">Nombre de usuario</label><br>
            <input type="text" required size="50" placeholder="Ingrese su nombre de usuario" 
            name="username" id="username"><br>
            <label for="correo">Correo electrónico</label><br>
            <input type="mail" required size="50" placeholder="Ingrese el correo relacionado a su cuenta"
            name="correo" id="correo" pattern="[^@\s]+@[^@\s]+\.[^@\s]+"><br>
            <input type="submit" value="Recuperar contraseña" id='btn_rec_pass'><br>
            <a href="login.php" id="link_main">Cancelar</a>
            </fieldset>
        </form>
    </main>
    <?php include_once('footer.php');?>
</body>
</html>