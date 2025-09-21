<?php
session_start();
require 'conexion.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>
  <h1>Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?></h1>
  <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
  <p>Fecha de registro: <?php echo $usuario['fecha_creacion']; ?></p>

  <a href="editar_perfil.php">Editar Perfil</a> | 
  <a href="eliminar_perfil.php" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta?')">Eliminar cuenta</a>
</body>
</html>
