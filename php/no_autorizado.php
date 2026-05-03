<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso no autorizado - Tahito</title>
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    ?>
    <main class="container auth-container">
        <div class="auth-card" style="text-align: center;">
            <div class="auth-header">
                <a href="main_guest.php"><img src="../recursos/logsinfondo.png" alt="Tahito Logo" class="auth-logo"></a>
                <h2>Acceso no autorizado</h2>
                <p>No tenés permisos para acceder a esta página.</p>
            </div>
            <?php
            if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'cliente'){
                echo '<a href="main_cliente.php" class="btn-primary" style="margin-top: 1rem;">Volver al inicio</a>';
            } else {
                echo '<a href="main_guest.php" class="btn-primary" style="margin-top: 1rem;">Volver al inicio</a>';
            }
            ?>
        </div>
    </main>
</body>
</html>