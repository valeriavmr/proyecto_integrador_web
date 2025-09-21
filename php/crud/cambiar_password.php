<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual'];
    $nueva = $_POST['nueva'];
    $confirmar = $_POST['confirmar'];

    // 1. Traer la contraseña actual del usuario
    $sql = "SELECT password FROM usuarios WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // 2. Verificar contraseña actual
    if ($user && password_verify($actual, $user['password'])) {
        if ($nueva === $confirmar) {
            // 3. Hashear la nueva contraseña
            $hash = password_hash($nueva, PASSWORD_DEFAULT);

            // 4. Guardar en la BD
            $sql = "UPDATE usuarios SET password=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hash, $user_id);

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
  <a href="perfil.php">Volver al perfil</a>
</body>
</html>
