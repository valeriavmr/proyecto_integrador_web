<?php

require_once('conexion.php');

//Para cambiar la contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pass_nueva'])) {
        include_once('consultas_varias.php');
        cambiarPassPorUsername($conn,$_POST['username'],$_POST['pass_nueva']);
        exit();
        }

//Código inicial
$username = $_POST['username'] ?? '';
$correo = $_POST['correo'] ?? '';

//Hago un select para verificar que las credenciales le corresponden a una cuenta
$sql = "SELECT * from persona where nombre_de_usuario = ? AND correo = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ss",$username,$correo);

$stmt->execute();

$persona = $stmt->get_result();

if(!$persona || $persona->num_rows == 0){
    header('Location: ../login.php?error=Credenciales incorrectas');
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña - Tahito</title>
    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/login_styles.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <main class="container auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="../main_guest.php"><img src="../../recursos/logsinfondo.png" alt="Tahito Logo" class="auth-logo"></a>
                <h2>Cambiar contraseña</h2>
                <p>Ingresá tu nueva contraseña para que quede actualizada.</p>
            </div>
            <form action="" method="post" class="auth-form">
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <div class="form-group">
                    <label for="pass_nueva">Contraseña nueva</label>
                    <input type="password" required minlength="8" placeholder="Mínimo 8 caracteres"
                    name="pass_nueva" id="pass_nueva" class="form-input">
                </div>
                <button type="submit" class="btn-primary w-100">Cambiar contraseña</button>
                <div class="auth-links">
                    <a href="../login.php" class="link-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
    <?php include_once('../footer.php'); ?>
</body>
</html>