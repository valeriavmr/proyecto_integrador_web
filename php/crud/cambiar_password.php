<?php
<<<<<<< HEAD
// 1. Incluir la configuración al principio de todo
require_once __DIR__ . '/../../config.php';

// 2. Iniciar la sesión
session_start();

// 3. Incluir la conexión a la BD usando la ruta absoluta
require_once(BASE_PATH . '/php/crud/conexion.php');

// 4. Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
=======
session_start();
require 'conexion.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
    exit();
}

$username = $_SESSION['username'];
$msg = "";
<<<<<<< HEAD
$msg_type = ""; // Para saber si el mensaje es de éxito o error

// 5. Lógica para procesar el formulario cuando se envía
=======

>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual'];
    $nueva = $_POST['nueva'];
    $confirmar = $_POST['confirmar'];

<<<<<<< HEAD
    // Traer la contraseña actual del usuario
=======
    // 1. Traer la contraseña actual del usuario
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
    $sql = "SELECT password FROM persona WHERE nombre_de_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

<<<<<<< HEAD
    // Verificar contraseña actual
    if ($user && password_verify($actual, $user['password'])) {
        if (!empty($nueva) && $nueva === $confirmar) {
            // Hashear la nueva contraseña
            $hash = password_hash($nueva, PASSWORD_DEFAULT);

            // Guardar en la BD
            $sql_update = "UPDATE persona SET password=? WHERE nombre_de_usuario=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $hash, $username);

            if ($stmt_update->execute()) {
                $msg = "Contraseña actualizada correctamente.";
                $msg_type = "exito"; // Mensaje de éxito
            } else {
                $msg = "Error al actualizar la contraseña.";
                $msg_type = "error"; // Mensaje de error
            }
        } else {
            $msg = "La nueva contraseña y la confirmación no coinciden o están vacías.";
            $msg_type = "error";
        }
    } else {
        $msg = "La contraseña actual es incorrecta.";
        $msg_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/perfil_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/menu_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/footer_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/form_style.css?v=<?= time() ?>">
</head>
<body>

    <?php include_once(BASE_PATH . '/php/crud/header_perfil.php'); ?>

    <main class="perfil-container">
        <section class="formulario-password">
            <h1>Cambiar Contraseña</h1>

            <?php if ($msg): ?>
                <div class="mensaje <?php echo $msg_type; ?>"><?php echo $msg; ?></div>
            <?php endif; ?>

            <form method="POST" action="cambiar_password.php">
                <div class="form-group">
                    <label for="actual">Contraseña actual:</label>
                    <input type="password" id="actual" name="actual" required>
                </div>
                <div class="form-group">
                    <label for="nueva">Nueva contraseña:</label>
                    <input type="password" id="nueva" name="nueva" required>
                </div>
                <div class="form-group">
                    <label for="confirmar">Confirmar nueva contraseña:</label>
                    <input type="password" id="confirmar" name="confirmar" required>
                </div>
                
                <button type="submit" class="btn-guardar">Actualizar Contraseña</button>
            </form>
            <a href="<?php echo BASE_URL; ?>/php/crud/perfil.php" class="link-volver">Volver al perfil</a>
        </section>
    </main>

    <?php include_once(BASE_PATH . '/php/footer.php'); ?>

</body>
</html>
=======
    // 2. Verificar contraseña actual
    if ($user && password_verify($actual, $user['password'])) {
        if ($nueva === $confirmar) {
            // 3. Hashear la nueva contraseña
            $hash = password_hash($nueva, PASSWORD_DEFAULT);

            // 4. Guardar en la BD
            $sql = "UPDATE persona SET password=? WHERE nombre_de_usuario=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hash, $username);

            if ($stmt->execute()) {
                $msg = "Contraseña actualizada correctamente.";
            } else {
                $msg = "Error al actualizar la contraseña.";
            }
        } else {
            $msg = "La nueva contraseña y la confirmación no coinciden.";
        }
    } else {
        $msg = "La contraseña actual es incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña</title>
</head>
<body>
  <h1>Cambiar Contraseña</h1>
  <?php if ($msg) echo "<p style='color:red;'>$msg</p>"; ?>
  <form method="POST">
    <label>Contraseña actual:</label>
    <input type="password" name="actual" required><br><br>

    <label>Nueva contraseña:</label>
    <input type="password" name="nueva" required><br><br>

    <label>Confirmar nueva contraseña:</label>
    <input type="password" name="confirmar" required><br><br>

    <button type="submit">Actualizar</button>
  </form>
  <br>
  perfil.phpVolver al perfil</a>
</body>
</html>
>>>>>>> 83af6d2b3b41e3066e08b2b90fb992b5ed7a0a45
