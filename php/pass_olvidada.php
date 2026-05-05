<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - Tahito</title>
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <main class="container auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="main_guest.php"><img src="../recursos/logsinfondo.png" alt="Tahito Logo" class="auth-logo"></a>
                <h2>Recuperar contraseña</h2>
                <p>Ingresá tu usuario y correo para recibir las instrucciones.</p>
            </div>
            <form action="crud/recuperar_pass.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" required placeholder="Ingresá tu usuario" name="username" id="username" class="form-input">
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" required placeholder="tu@email.com" name="correo" id="correo" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" class="form-input">
                </div>
                <input type="submit" value="Recuperar contraseña" class="btn-primary w-100">
                <div class="auth-links">
                    <a href="login.php" class="link-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
    <?php include_once('footer.php'); ?>
</body>
</html>