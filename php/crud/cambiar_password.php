<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual'];
    $nueva = $_POST['nueva'];
    $confirmar = $_POST['confirmar'];

    // 1. Traer la contraseña actual del usuario
    $sql = "SELECT password FROM persona WHERE nombre_de_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

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
