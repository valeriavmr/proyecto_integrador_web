<?php
// 1. Incluir la configuración al principio de todo
require_once __DIR__ . '/../../config.php';

// 2. Iniciar la sesión
session_start();

// 3. Incluir la conexión a la BD usando la ruta absoluta
require(BASE_PATH . '/php/crud/conexion.php');

if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT nombre, apellido, nombre_de_usuario, correo, telefono, rol FROM persona_g3 WHERE nombre_de_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();


if (!$usuario) {
    session_destroy();
    header('Location: ' . BASE_URL . '/php/login.php?error=usernotfound');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - <?php echo htmlspecialchars($usuario['nombre']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/perfil_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/footer_styles.css?v=<?= time() ?>">    
      
    
</head>
<body>

    <?php   
        
        include(BASE_PATH . '/php/crud/header_perfil.php');
    ?>

    <main class="perfil-container">
        <h1>Perfil de <?php echo htmlspecialchars($usuario['nombre']); ?> <?php echo htmlspecialchars($usuario['apellido']); ?></h1>
        
        <div class="perfil-info">
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario['nombre_de_usuario']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>
            <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['rol']); ?></p>
        </div>

        <div class="perfil-acciones">
            <a href="<?php echo BASE_URL; ?>/php/crud/editar_perfil.php" class="btn btn-editar">Editar Perfil</a> 
            <a href="<?php echo BASE_URL; ?>/php/crud/cambiar_password.php" class="btn btn-password">Cambiar Contraseña</a> 
            <a href="<?php echo BASE_URL; ?>/php/crud/eliminar_perfil.php" class="btn btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta permanentemente?')">Eliminar cuenta</a>
        </div>
    </main>

    <?php 
        // Incluir el footer usando la ruta absoluta
        include(BASE_PATH . '/php/footer.php'); 
    ?>

</body>
</html>
 