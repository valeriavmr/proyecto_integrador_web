<?php
session_start();
require 'conexion.php'; 

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];  // debería contener el nombre_de_usuario
$sql = "SELECT * FROM persona WHERE nombre_de_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$usuario1 = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="estilos.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adiestramiento Tahito</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png">
  <link rel="stylesheet" href="../../css/main_cliente_style.css?v=<?= time() ?>">
  <link rel="stylesheet" href="../../css/servicios_cliente.css?v=<?= time() ?>">
  

</head>
<body>
  
  <?php include('../header_cliente.php'); ?>
  <h1>Perfil de <?php echo htmlspecialchars($usuario1['nombre']); ?> <?php echo htmlspecialchars($usuario1['apellido']); ?></h1>
  <p>Usuario: <?php echo htmlspecialchars($usuario1['nombre_de_usuario']); ?></p>
  <p>Email: <?php echo htmlspecialchars($usuario1['correo']); ?></p>
  <p>Teléfono: <?php echo htmlspecialchars($usuario1['telefono']); ?></p>
  <p>Rol: <?php echo htmlspecialchars($usuario1['rol']); ?></p>

  <a href="editar_perfil.php">Editar Perfil</a> 
  <a href="eliminar_perfil.php" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta :)?')">Eliminar cuenta</a>
  <?php include('../footer.php'); ?>
  
  

  
</body>
</html>
