<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar - Tahito</title>
    
    <!-- Design System Theme -->
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    $error = isset($_GET['error']) ? $_GET['error'] : '';
    ?>
    <main class="container auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="main_guest.php"><img src="../recursos/logsinfondo.png" alt="Tahito Logo" class="auth-logo"></a>
                <h2>Ingreso a tu cuenta</h2>
                <p>Bienvenido de vuelta, ingresa tus credenciales.</p>
            </div>
            
            <form action="crud/select_login.php" id="form_cuenta_login" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" name="username" id="username" placeholder="juanperez" required class="form-input">
                </div>
                
                <div class="form-group">
                    <label for="pass">Contraseña</label>
                    <input type="password" name="pass" id="pass" minlength="8" maxlength="16" placeholder="••••••••" required class="form-input">
                </div>
                
                <?php if($error): ?>
                    <p class="error-text"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; unset($_GET['error']);?>
                
                <?php if(isset($_GET['mensaje'])):?>
                    <p class="success-text"><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
                <?php endif; unset($_GET['mensaje']);?>
                
                <input type="submit" value="Ingresar" class="btn-primary w-100">
                
                <div class="auth-links">
                    <a href="registro.php" class="link-secondary">No tengo una cuenta</a>
                    <a href="pass_olvidada.php" class="link-secondary">Olvidé mi contraseña</a>
                    <a href="main_guest.php" class="link-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>