<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Tahito</title>
    
    <!-- Design System Theme -->
    <link rel="stylesheet" href="../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/login_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../css/registro_cuenta.css?v=<?= time() ?>">
    
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
    <main class="container auth-container">
        <div class="auth-card" style="max-width: 600px;">
            <div class="auth-header">
                <a href="main_guest.php"><img src="../recursos/logsinfondo.png" alt="Tahito Logo" class="auth-logo"></a>
                <h2>Nueva cuenta</h2>
                <p>Crea tu perfil para empezar a reservar turnos.</p>
            </div>

            <form action="" id="form_cuenta" method="POST" class="auth-form registration-grid">
                
                <h3 class="form-section-title">Datos personales</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_persona">Nombre</label>
                        <input type="text" name="nombre_persona" id="nombre_persona" placeholder="Ingresa tu nombre" required class="form-input" value="<?php echo $_POST['nombre_persona'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="apellido_persona">Apellido</label>
                        <input type="text" name="apellido_persona" id="apellido_persona" placeholder="Ingresa tu apellido" required class="form-input" value="<?php echo $_POST['apellido_persona'] ?? '' ?>">
                    </div>
                </div>

                <div class="username-validado form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text" name="username" id="username" placeholder="Elige un usuario" required class="form-input" value="<?php echo $_POST['username'] ?? '' ?>" onchange="this.form.submit()">
                    <p id='popover-username' class="error-text" style="display:none; margin-top: 5px;">Este usuario ya existe.</p>
                
                    <?php
                    $username = $_POST['username'] ?? '';
                    include_once('crud/conexion.php');
                    include_once('crud/consultas_varias.php');

                    if(verificarNombreUsuario($conn, $username)) {
                        echo "<script>
                        document.getElementById('popover-username').style.display='block';
                        </script>";
                    }
                    ?>   
                    <script>
                        document.getElementById("username").addEventListener("input", ()=>{
                            document.getElementById("popover-username").style.display = 'none';
                        });
                    </script>
                </div>

                <div class="correo-validado form-group">
                    <label for="correo_persona">Correo electrónico</label>
                    <input type="email" name="correo_persona" id="correo_persona" placeholder="tu@email.com" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" required class="form-input" value="<?php echo $_POST['correo_persona'] ?? '' ?>" onchange="this.form.submit()">
                    <p id='popover-correo' class="error-text" style="display:none; margin-top: 5px;">El correo ya está registrado.</p>

                    <?php
                    $correo = $_POST['correo_persona'] ?? '';
                    if(verificarCorreo($conn, $correo)) {
                        echo "<script>
                        document.getElementById('popover-correo').style.display='block';
                        </script>";
                    }
                    ?>   
                    <script>
                        document.getElementById("correo_persona").addEventListener("input", ()=>{
                            document.getElementById("popover-correo").style.display = 'none';
                        });
                    </script>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pass">Contraseña</label>
                        <input type="password" name="pass" id="pass" minlength="8" maxlength="16" placeholder="Mínimo 8 caracteres" required class="form-input" value="<?php echo $_POST['pass'] ?? '' ?>">
                    </div>

                    <div class="form-group">
                        <label for="tel_persona">Celular de contacto</label>
                        <input type="tel" name="tel_persona" id="tel_persona" minlength="10" maxlength="11" placeholder="Ej. 1122334455" required class="form-input" value="<?php echo $_POST['tel_persona'] ?? '' ?>">
                    </div>
                </div>

                <div class="divider"></div>
                <h3 class="form-section-title">Datos de dirección</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="localidad">Localidad</label>
                        <select name="localidad" id="localidad" required class="form-input">
                            <option value="" disabled <?php echo empty($_POST['localidad']) ? 'selected' : '' ?>>Seleccioná</option>
                            <option value="CABA" <?php echo (($_POST['localidad'] ?? '') == 'CABA') ? 'selected' : '' ?>>CABA</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="barrio">Barrio</label>
                        <select name="barrio" id="barrio" class="form-input">
                            <option value="" disabled <?php echo empty($_POST['barrio']) ? 'selected' : '' ?>>Seleccioná</option>
                            <?php include('barrios.php'); ?>
                        </select>
                        <script>
                            document.getElementById('barrio').value = "<?php echo $_POST['barrio'] ?? '' ?>";
                        </script>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label for="calle">Calle</label>
                        <input type="text" name="calle" id="calle" placeholder="Nombre de calle" required class="form-input" value="<?php echo $_POST['calle'] ?? '' ?>">
                    </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="altura_calle">Altura</label>
                        <input type="number" name="altura_calle" id="altura_calle" placeholder="Ej: 123" required min="1" max="20000" class="form-input" value="<?php echo $_POST['altura_calle'] ?? '' ?>">
                    </div>
                </div>

                <input type="submit" value="Crear cuenta" formaction="crud/insert_persona.php" class="btn-primary w-100">
                
                <div class="auth-links">
                    <a href="login.php" class="link-secondary">Ya tengo una cuenta</a>
                    <a href="main_guest.php" class="link-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>