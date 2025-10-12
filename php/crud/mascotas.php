<?php
// 1. INCLUDES Y CONFIGURACIÓN INICIAL
require_once __DIR__ . '/../../config.php';
session_start();
require_once(BASE_PATH . '/php/crud/conexion.php');

// 2. VERIFICACIÓN DE SESIÓN DE USUARIO
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}

// 3. OBTENER EL ID DEL USUARIO LOGUEADO
$username = $_SESSION['username'];
$sql_user = "SELECT id_persona FROM persona WHERE nombre_de_usuario = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();
$id_usuario_logueado = $user_data['id_persona'];

$mensaje = "";
$mensaje_tipo = "";

// 4. MANEJO DE ACCIONES (CREAR, ACTUALIZAR, BORRAR)
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

// --- ACCIÓN: AGREGAR MASCOTA ---
if ($accion === 'agregar') {
    $nombre = $_POST['nombre']; 
    $fecha_nac = $_POST['fecha_de_nacimiento'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $tamanio = $_POST['tamanio'];
    $color = $_POST['color'];

    $sql = "INSERT INTO mascota (id_persona, nombre, fecha_de_nacimiento, edad, raza, tamanio, color) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississs", $id_usuario_logueado, $nombre, $fecha_nac, $edad, $raza, $tamanio, $color);
    if ($stmt->execute()) {
        $mensaje = "Mascota agregada correctamente.";
        $mensaje_tipo = "exito";
    } else {
        $mensaje = "Error al agregar la mascota.";
        $mensaje_tipo = "error";
    }
}

// --- ACCIÓN: ACTUALIZAR MASCOTA ---
if ($accion === 'actualizar') {
    $id_mascota = $_POST['id_mascota'];
    $nombre = $_POST['nombre'];
    $fecha_nac = $_POST['fecha_de_nacimiento'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $tamanio = $_POST['tamanio'];
    $color = $_POST['color'];

    $sql = "UPDATE mascota SET nombre=?, fecha_de_nacimiento=?, edad=?, raza=?, tamanio=?, color=? WHERE id_mascota=? AND id_persona=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssii", $nombre, $fecha_nac, $edad, $raza, $tamanio, $color, $id_mascota, $id_usuario_logueado);
    if ($stmt->execute()) {
        $mensaje = "Mascota actualizada correctamente.";
        $mensaje_tipo = "exito";
    } else {
        $mensaje = "Error al actualizar la mascota.";
        $mensaje_tipo = "error";
    }
}

// --- ACCIÓN: ELIMINAR MASCOTA ---
if ($accion === 'eliminar') {
    $id_mascota = $_GET['id'];
    $sql = "DELETE FROM mascota WHERE id_mascota=? AND id_persona=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_mascota, $id_usuario_logueado);
    if ($stmt->execute()) {
        $mensaje = "Mascota eliminada correctamente.";
        $mensaje_tipo = "exito";
    } else {
        $mensaje = "Error al eliminar la mascota.";
        $mensaje_tipo = "error";
    }
}

// 5. LÓGICA PARA EL MODO EDICIÓN DEL FORMULARIO
$mascota_a_editar = null;
if ($accion === 'editar') {
    $id_mascota_editar = $_GET['id'];
    $sql_editar = "SELECT * FROM mascota WHERE id_mascota=? AND id_persona=?";
    $stmt_editar = $conn->prepare($sql_editar);
    $stmt_editar->bind_param("ii", $id_mascota_editar, $id_usuario_logueado);
    $stmt_editar->execute();
    $result_editar = $stmt_editar->get_result();
    $mascota_a_editar = $result_editar->fetch_assoc();
}


$sql_select = "SELECT * FROM mascota WHERE id_persona = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $id_usuario_logueado);
$stmt_select->execute();
$lista_mascotas = $stmt_select->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Mascotas</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/footer_styles.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/main_cliente_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/perfil_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/tabla_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/menu_style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/form_style.css?v=<?= time() ?>">


</head>
<body>

    <?php include_once(BASE_PATH . '/php/crud/header_mascota.php'); ?>

    <main class="perfil-container">
        
        <section class="formulario-edicion-mascota">
            <h1><?php echo $mascota_a_editar ? 'Editar Mascota' : 'Agregar Nueva Mascota'; ?></h1>
            
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo $mensaje_tipo; ?>"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <?php
            $fecha_formateada = '';
            if ($mascota_a_editar && !empty($mascota_a_editar['fecha_de_nacimiento'])) {
                $fecha_formateada = date('Y-m-d', strtotime($mascota_a_editar['fecha_de_nacimiento']));
            }
            ?>

            <form method="POST" action="mascotas.php">
                <input type="hidden" name="accion" value="<?php echo $mascota_a_editar ? 'actualizar' : 'agregar'; ?>">
                <?php if ($mascota_a_editar): ?>
                    <input type="hidden" name="id_mascota" value="<?php echo $mascota_a_editar['id_mascota']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($mascota_a_editar['nombre'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="fecha_de_nacimiento">Fecha de Nacimiento:</label>
                    <input 
                    type="date" 
                    id="fecha_de_nacimiento" 
                    name="fecha_de_nacimiento" 
                    value="<?php echo $mascota_a_editar ? $fecha_formateada : ''; ?>" 
                    required
                  >
                </div>

                <div class="form-group">
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($mascota_a_editar['edad'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="raza">Raza:</label>
                    <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($mascota_a_editar['raza'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="tamanio">Tamaño (Pequeño, Mediano, Grande):</label>
                    <input type="text" id="tamanio" name="tamanio" value="<?php echo htmlspecialchars($mascota_a_editar['tamanio'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($mascota_a_editar['color'] ?? ''); ?>" required>
                </div>
                
                <button type="submit" class="btn-guardar"><?php echo $mascota_a_editar ? 'Guardar Cambios' : 'Agregar Mascota'; ?></button>
                <?php if ($mascota_a_editar): ?>
                    <a href="mascotas.php" class="link-cancelar">Cancelar Edición</a>
                <?php endif; ?>
            </form>
        </section>

        <section class="tabla-container">
            
            <h2>Mis Mascotas Registradas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Raza</th>
                        <th>Tamaño</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($lista_mascotas->num_rows > 0): ?>
                        <?php while($mascota = $lista_mascotas->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($mascota['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($mascota['edad']); ?></td>
                                <td><?php echo htmlspecialchars($mascota['raza']); ?></td>
                                <td><?php echo htmlspecialchars($mascota['tamanio']); ?></td>
                                <td class="acciones">
                                    <a href="mascotas.php?accion=editar&id=<?php echo $mascota['id_mascota']; ?>" class="btn-accion editar">Editar</a>
                                    <a href="mascotas.php?accion=eliminar&id=<?php echo $mascota['id_mascota']; ?>" class="btn-accion eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar a <?php echo htmlspecialchars($mascota['nombre']); ?>?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Aún no tienes mascotas registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </main>

    <?php include_once(BASE_PATH . '/php/footer.php'); ?>

   
</body>
</html>
