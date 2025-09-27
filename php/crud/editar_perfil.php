<?php
// 1. Incluir la configuración al principio de todo
require_once __DIR__ . '/../../config.php';

// 2. Iniciar la sesión
session_start();

// 3. Incluir la conexión a la BD usando la ruta absoluta
require_once(BASE_PATH . '/php/crud/conexion.php');

// 4. Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}

$username = $_SESSION['username'];

// 5. Lógica para procesar el formulario cuando se envía (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nombre_de_usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    $sql = "UPDATE persona SET nombre=?, apellido=?, nombre_de_usuario=?, correo=?, telefono=? WHERE nombre_de_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $apellido, $nombre_de_usuario, $correo, $telefono, $username);

    if ($stmt->execute()) {
        // Si la actualización es exitosa, actualiza la sesión y redirige al perfil
        $_SESSION['username'] = $nombre_de_usuario;
        // CORRECCIÓN: Usar la ruta absoluta para la redirección
        header('Location: ' . BASE_URL . '/php/crud/perfil.php');
        exit();
    } else {
        $error_message = "Error al actualizar el perfil.";
    }
}

// 6. Lógica para obtener los datos actuales del usuario y mostrarlos en el formulario
$sql_select = "SELECT * FROM persona WHERE nombre_de_usuario=?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("s", $username);
$stmt_select->execute();
$result = $stmt_select->get_result();
$datos_usuario = $result->fetch_assoc();

if (!$datos_usuario) {
    echo "No se pudo cargar la información del usuario.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/perfil_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/menu_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/footer_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/form_style.css?v=<?= time() ?>">
</head>
<body>

    <?php 
        
        include_once(BASE_PATH . '/php/crud/header_perfil.php');
    ?>

    <main class="perfil-container">
        <section class="formulario-edicion">
            <h1>Editar Perfil</h1>

            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form method="POST" action="editar_perfil.php">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($datos_usuario['nombre_de_usuario']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($datos_usuario['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($datos_usuario['apellido']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($datos_usuario['correo']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($datos_usuario['telefono']); ?>" required>
                </div>
                
                <button type="submit" class="btn-guardar">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <?php 
        // Incluimos el footer
        include_once(BASE_PATH . '/php/footer.php');
    ?>

                

</body>
</html>