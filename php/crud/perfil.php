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
$sql = "SELECT nombre, apellido, nombre_de_usuario, correo, telefono, rol FROM persona WHERE nombre_de_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$usuario_perfil = $result->fetch_assoc();


if (!$usuario_perfil) {
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
    <title>Mi Perfil - <?php echo htmlspecialchars($usuario_perfil['nombre']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/perfil_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/footer_styles.css?v=<?= time() ?>">    
      
    
</head>
<body>

    <?php   
        if($usuario_perfil['rol'] === 'admin' || $usuario_perfil['rol'] === 'gestor') {
            include(BASE_PATH . '/php/includes/sidebar.php'); 
        } else {
            include(BASE_PATH . '/php/crud/header_perfil.php'); 
        }
    ?>

    <main class="main-content">
        <div class="perfil-container">
            <h1>Perfil de <?php echo htmlspecialchars($usuario_perfil['nombre']); ?> <?php echo htmlspecialchars($usuario_perfil['apellido']); ?></h1>
        
        <div class="perfil-info">
            <p><strong>Usuario:</strong> <?php echo htmlspecialchars($usuario_perfil['nombre_de_usuario']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario_perfil['correo']); ?></p>
            <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario_perfil['telefono']); ?></p>
            <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario_perfil['rol']); ?></p>
        </div>

        <div class="perfil-acciones">
            <a href="<?php echo BASE_URL; ?>/php/crud/editar_perfil.php" class="btn btn-editar">Editar Perfil</a> 
            <a href="<?php echo BASE_URL; ?>/php/crud/cambiar_password.php" class="btn btn-password">Cambiar Contraseña</a> 
            <a href="<?php echo BASE_URL; ?>/php/crud/eliminar_perfil.php" class="btn btn-eliminar" onclick="return confirm('¿Estás seguro de que deseas eliminar tu cuenta permanentemente?')">Eliminar cuenta</a>
        </div>
        </div>
    </main>

    <?php 
        // Incluir el footer usando la ruta absoluta
        include(BASE_PATH . '/php/footer.php'); 
    ?>

</body>
</html>
 