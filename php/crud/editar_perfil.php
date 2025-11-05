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
    $nombre_de_usuario_nuevo = $_POST['usuario'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $provincia = $_POST['provincia'];
    $localidad = $_POST['localidad'];
    $calle = $_POST['calle'];
    $altura = $_POST['altura'];

    // --- 5.1. Actualizar tabla persona ---
    $sql_persona = "UPDATE persona_g3 
                    SET nombre=?, apellido=?, nombre_de_usuario=?, correo=?, telefono=? 
                    WHERE nombre_de_usuario=?";
    $stmt_persona = $conn->prepare($sql_persona);
    if (!$stmt_persona) {
        die("Error en SQL persona: " . $conn->error);
    }

    $stmt_persona->bind_param("ssssss", $nombre, $apellido, $nombre_de_usuario_nuevo, $correo, $telefono, $username);

    if ($stmt_persona->execute()) {
        // Si el nombre de usuario cambió, actualizamos la sesión
        if ($nombre_de_usuario_nuevo !== $username) {
            $_SESSION['username'] = $nombre_de_usuario_nuevo;
            $username_update = $nombre_de_usuario_nuevo;
        } else {
            $username_update = $username;
        }

        // --- 5.2. Obtener id_persona ---
        $sql_id = "SELECT id_persona FROM persona_g3 WHERE nombre_de_usuario = ?";
        $stmt_id = $conn->prepare($sql_id);
        if (!$stmt_id) {
            die("Error en SQL id_persona: " . $conn->error);
        }
        $stmt_id->bind_param("s", $username_update);
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();
        $row_id = $result_id->fetch_assoc();
        $id_persona = $row_id['id_persona'];

        // --- 5.3. Verificar si ya tiene dirección ---
        $sql_check = "SELECT COUNT(*) AS existe FROM direccion_g3 WHERE id_persona = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $id_persona);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result()->fetch_assoc();
        $existe_direccion = $result_check['existe'] > 0;

        if ($existe_direccion) {
            // --- UPDATE ---
            $sql_direccion = "UPDATE direccion_g3 
                              SET provincia=?, localidad=?, calle=?, altura=? 
                              WHERE id_persona=?";
            $stmt_direccion = $conn->prepare($sql_direccion);
            $stmt_direccion->bind_param("sssii", $provincia, $localidad, $calle, $altura, $id_persona);
        } else {
            // --- INSERT ---
            $sql_direccion = "INSERT INTO direccion_g3 (id_persona, provincia, localidad, calle, altura) 
                              VALUES (?, ?, ?, ?, ?)";
            $stmt_direccion = $conn->prepare($sql_direccion);
            $stmt_direccion->bind_param("isssi", $id_persona, $provincia, $localidad, $calle, $altura);
        }

        if ($stmt_direccion->execute()) {
            header('Location: ' . BASE_URL . '/php/crud/perfil.php');
            exit();
        } else {
            $error_message = "Perfil actualizado, pero hubo un error al actualizar la dirección.";
        }

    } else {
        $error_message = "Error al actualizar el perfil: " . $stmt_persona->error;
    }
}

// 6. Lógica para obtener los datos actuales del usuario y su dirección
$sql_select = "
    SELECT 
        p.*, d.provincia, d.localidad, d.calle, d.altura
    FROM 
        persona_g3 p
    LEFT JOIN 
        direccion_g3 d ON p.id_persona = d.id_persona 
    WHERE 
        p.nombre_de_usuario = ?
";
$stmt_select = $conn->prepare($sql_select);
if (!$stmt_select) {
    die("Error al preparar SQL de selección: " . $conn->error);
}
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

                <div class="form-group">
                    <label for="provincia">Provincia:</label>
                    <input type="text" id="provincia" name="provincia" value="<?php echo htmlspecialchars($datos_usuario['provincia']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="localidad">Localidad:</label>
                    <input type="text" id="localidad" name="localidad" value="<?php echo htmlspecialchars($datos_usuario['localidad']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="calle">Calle:</label>
                    <input type="text" id="calle" name="calle" value="<?php echo htmlspecialchars($datos_usuario['calle']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="altura">Altura:</label>
                    <input type="number" id="altura" name="altura" value="<?php echo htmlspecialchars((int)$datos_usuario['altura']); ?>" required>
                </div>
                <div class="botones-acciones">

    <button type="submit" class="btn-guardar">Guardar Cambios</button>
    <button type="button" class="btn-volver" onclick="window.location.href='<?php echo BASE_URL; ?>/php/crud/perfil.php'">
        Volver al Perfil
    </button>
</div>
            </form>
        </section>
    </main>

    <?php 
        // Incluimos el footer
        include_once(BASE_PATH . '/php/footer.php');
    ?>

                

</body>
</html>