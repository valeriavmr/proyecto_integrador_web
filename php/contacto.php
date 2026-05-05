<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Tahito</title>
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/main_guest_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/contacto.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php 
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    ?>
    <header class="container main-header">
        <a class="img" href="main_guest.php">
            <img src="../recursos/logsinfondo.png" alt="Tahito Logo">
        </a>
        <nav>
            <ul id="nav_menu">
                <li><a href="main_guest.php#servicios">Servicios</a></li>
                <li><a href="main_guest.php">Home</a></li>
                <li><a href="trabaja_con_nosotros.php">Trabaja con nosotros</a></li>
            </ul>
        </nav>
        <div id="nav_registro">
            <a id="link_login" href="login.php" class="nav-link">Ingresar</a>
            <a id="link_registro" href="registro.php" class="btn-primary" style="padding: 0.5rem 1rem;">Registrarse</a>
        </div>
    </header>

    <main class="container contact-main">
        <div class="contact-card">
            <div class="contact-header">
                <h1>Formulario de contacto</h1>
                <p>¿Tenés preguntas? Completá el formulario y te respondemos a la brevedad.</p>
            </div>
            <form action="crud/enviar_mail_contacto.php" method="post" class="auth-form">
                <div class="form-group">
                    <label for="nombre_contacto">Nombre</label>
                    <input type="text" name="nombre_contacto" id="nombre_contacto" placeholder="Tu nombre" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="correo_contacto">Correo electrónico</label>
                    <input type="email" name="correo_contacto" id="correo_contacto" placeholder="tu@email.com"
                    pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="asunto_contacto">Asunto</label>
                    <input type="text" name="asunto_contacto" id="asunto_contacto" placeholder="¿En qué te podemos ayudar?" required class="form-input">
                </div>
                <div class="form-group">
                    <label for="mensaje_contacto">Mensaje</label>
                    <textarea name="mensaje_contacto" id="mensaje_contacto" rows="5" required
                    placeholder="Tu mensaje..." class="form-input" style="resize: vertical; height: auto;"></textarea>
                </div>
                <?php if(isset($_GET['mensaje'])): ?>
                    <p class="success-text"><?php echo htmlspecialchars($_GET['mensaje']) ?></p>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <p class="error-text"><?php echo htmlspecialchars($_GET['error']) ?></p>
                <?php endif; ?>
                <input type="submit" value="Enviar mensaje" class="btn-primary w-100">
            </form>
        </div>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>