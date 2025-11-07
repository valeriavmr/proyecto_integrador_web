<?php
// 1. INCLUDES Y CONFIGURACIÓN INICIAL

require_once dirname(__FILE__) . '/../../config.php'; 
session_start();
require_once(BASE_PATH . '/php/crud/conexion.php');

// 2. VERIFICACIÓN DE SESIÓN Y OBTENCIÓN DE ROL
if (!isset($_SESSION['username'])) {
    header('Location: ' . BASE_URL . '/php/login.php');
    exit();
}

// 3. OBTENER EL ID, EL USERNAME Y EL ROL DEL USUARIO LOGUEADO
$username = $_SESSION['username'];
// Aseguramos obtener el campo 'rol'
$sql_user = "SELECT id_persona, rol FROM persona_g3 WHERE nombre_de_usuario = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_data = $result_user->fetch_assoc();

$id_usuario_logueado = $user_data['id_persona'];
// Control para la doble autorización
$es_administrador = ($user_data['rol'] === 'admin'); 

$mensaje = "";
$mensaje_tipo = "";


// Función auxiliar para subir la imagen y devolver la RUTA WEB
function subirImagen($archivo, $nombreMascota, $idUsuario) {
    
    $directorio_subida_absoluta = BASE_PATH . '/uploads/mascotas/'; 
    $directorio_subida_web = BASE_URL . '/uploads/mascotas/';

    
    if (!is_dir($directorio_subida_absoluta)) {
        if (!mkdir($directorio_subida_absoluta, 0777, true)) {
            
            error_log("Fallo al crear el directorio de subida: " . $directorio_subida_absoluta);
            return null; 
        }
    }
    
    // 3. Generar un nombre de archivo seguro y único
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    
    $nombre_limpio = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', $nombreMascota));
    $nombre_archivo = $nombre_limpio . '_' . $idUsuario . '_' . time() . '.' . $extension;
    
    $ruta_completa_servidor = $directorio_subida_absoluta . $nombre_archivo;
    $ruta_completa_web = $directorio_subida_web . $nombre_archivo; // La URL que se guarda en BD
    
    // 4. Mover el archivo subido al servidor
    if (move_uploaded_file($archivo['tmp_name'], $ruta_completa_servidor)) {
        
        return $ruta_completa_web;
    }
    
    error_log("Fallo al mover el archivo subido a: " . $ruta_completa_servidor . " Error: " . $archivo['error']);
    return null; // Error en la subida
}


// 4. MANEJO DE ACCIONES (CREAR, ACTUALIZAR, BORRAR)
$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';

// --- ACCIÓN: AGREGAR MASCOTA ---
// Nota: La acción 'agregar' SIEMPRE es para el usuario logueado, sin excepción.
if ($accion === 'agregar') {
    $nombre = $_POST['nombre']; 
    $fecha_nac = $_POST['fecha_de_nacimiento'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $tamanio = $_POST['tamanio'];
    $color = $_POST['color'];
    $imagen_url = null;

    // Manejo de la subida de imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_url = subirImagen($_FILES['imagen'], $nombre, $id_usuario_logueado);
    }

    
    $sql = "INSERT INTO mascota_g3 (id_persona, nombre, fecha_de_nacimiento, edad, raza, tamanio, color, imagen_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ississss", $id_usuario_logueado, $nombre, $fecha_nac, $edad, $raza, $tamanio, $color, $imagen_url); 
    if ($stmt->execute()) {
        $mensaje = "Mascota agregada correctamente.";
        $mensaje_tipo = "exito";
    } else {
        $mensaje = "Error al agregar la mascota: " . $stmt->error;
        $mensaje_tipo = "error";
    }
}

// --- ACCIÓN: ACTUALIZAR MASCOTA ---
if ($accion === 'actualizar' && isset($_POST['id_mascota'])) {
    $id_mascota = $_POST['id_mascota'];
    $nombre = $_POST['nombre'];
    $fecha_nac = $_POST['fecha_de_nacimiento'];
    $edad = $_POST['edad'];
    $raza = $_POST['raza'];
    $tamanio = $_POST['tamanio'];
    $color = $_POST['color'];
    $imagen_url = $_POST['imagen_actual'] ?? null; 

    // Manejo de la subida de nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nueva_imagen_url = subirImagen($_FILES['imagen'], $nombre, $id_usuario_logueado);
        if ($nueva_imagen_url) {
            $imagen_url = $nueva_imagen_url;
        }
    }
    
    // Lógica de doble autorización para UPDATE
    if ($es_administrador) {
        // Administrador: actualiza por ID de mascota (sin id_persona)
        $sql = "UPDATE mascota_g3 SET nombre=?, fecha_de_nacimiento=?, edad=?, raza=?, tamanio=?, color=?, imagen_url=? WHERE id_mascota=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssi", $nombre, $fecha_nac, $edad, $raza, $tamanio, $color, $imagen_url, $id_mascota);
    } else {
        // Cliente: actualiza solo sus mascotas (requiere id_mascota AND id_persona)
        $sql = "UPDATE mascota_g3 SET nombre=?, fecha_de_nacimiento=?, edad=?, raza=?, tamanio=?, color=?, imagen_url=? WHERE id_mascota=? AND id_persona=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssii", $nombre, $fecha_nac, $edad, $raza, $tamanio, $color, $imagen_url, $id_mascota, $id_usuario_logueado);
    }

    if ($stmt->execute()) {
        $mensaje = "Mascota actualizada correctamente.";
        $mensaje_tipo = "exito";
    } else {
        $mensaje = "Error al actualizar la mascota: " . $stmt->error;
        $mensaje_tipo = "error";
    }
}

// --- ACCIÓN: ELIMINAR MASCOTA ---
if ($accion === 'eliminar') {
    if (isset($_POST['id_mascota'])) { 
        $id_mascota = $_POST['id_mascota'];
        
        // Lógica de doble autorización para DELETE
        if ($es_administrador) {
            $sql = "DELETE FROM mascota_g3 WHERE id_mascota=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_mascota);
        } else {
            $sql = "DELETE FROM mascota_g3 WHERE id_mascota=? AND id_persona=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_mascota, $id_usuario_logueado);
        }

        if ($stmt->execute()) {
            $mensaje = "Mascota eliminada correctamente.";
            $mensaje_tipo = "exito";
        } else {
            $mensaje = "Error al eliminar la mascota.";
            $mensaje_tipo = "error";
        }
    }
}

// 5. LÓGICA PARA EL MODO EDICIÓN DEL FORMULARIO
$mascota_a_editar = null;
if ($accion === 'editar' && isset($_GET['id_mascota'])) { 
    $id_mascota_editar = $_GET['id_mascota'];
    
    if ($es_administrador) {
        $sql_editar = "SELECT * FROM mascota_g3 WHERE id_mascota=?";
        $stmt_editar = $conn->prepare($sql_editar);
        $stmt_editar->bind_param("i", $id_mascota_editar);
    } else {
        $sql_editar = "SELECT * FROM mascota_g3 WHERE id_mascota=? AND id_persona=?";
        $stmt_editar = $conn->prepare($sql_editar);
        $stmt_editar->bind_param("ii", $id_mascota_editar, $id_usuario_logueado);
    }

    if ($stmt_editar->execute()) {
        $result_editar = $stmt_editar->get_result();
        $mascota_a_editar = $result_editar->fetch_assoc();
    }
}


// 6. LÓGICA PARA LA LISTA DE MASCOTAS (VISUALIZACIÓN)
// Lógica de doble autorización para SELECT (Listado)
if ($es_administrador) {
    // Administrador: ve TODAS las mascotas
    $sql_select = "SELECT * FROM mascota_g3";
    $stmt_select = $conn->prepare($sql_select);
} else {
    // Cliente: ve SOLO sus mascotas
    $sql_select = "SELECT * FROM mascota_g3 WHERE id_persona = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_usuario_logueado);
}

// Ejecución del SELECT, con manejo de error
if ($stmt_select) {
    if (!$stmt_select->execute()) {
        error_log("Error al ejecutar la consulta de selección: " . $stmt_select->error);
        $lista_mascotas = new stdClass(); 
        $lista_mascotas->num_rows = 0;
    } else {
        $lista_mascotas = $stmt_select->get_result();
    }
} else {
    $lista_mascotas = new stdClass(); 
    $lista_mascotas->num_rows = 0;
}

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

    <?php 
    
//Inserto el header
    if($_SESSION['rol']=='admin'){
        include('../admin/header_admin.php');
    }else{
        if($_SESSION['rol']=='cliente'){
            include_once(BASE_PATH . '/php/crud/header_mascota.php');
        }
    }
     ?>
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

            <form method="POST" action="mascotas.php" enctype="multipart/form-data"> 
                <input type="hidden" name="accion" value="<?php echo $mascota_a_editar ? 'actualizar' : 'agregar'; ?>">
                <?php if ($mascota_a_editar): ?>
                    <input type="hidden" name="id_mascota" value="<?php echo $mascota_a_editar['id_mascota']; ?>">
                    <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($mascota_a_editar['imagen_url'] ?? ''); ?>"> 
                <?php endif; ?>

                <div class="form-group">
                    <label for="imagen">Foto de la Mascota:</label>
                    <?php if ($mascota_a_editar && $mascota_a_editar['imagen_url']): ?>
                        <div style="margin-bottom: 10px;">
                            <p>Imagen actual:</p>
                            <img src="<?php echo htmlspecialchars($mascota_a_editar['imagen_url']); ?>" alt="Foto de <?php echo htmlspecialchars($mascota_a_editar['nombre']); ?>" style="max-width: 150px; height: auto; border-radius: 8px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                    <?php if ($mascota_a_editar): ?>
                        <small>Dejar vacío para mantener la imagen actual.</small>
                    <?php endif; ?>
                </div>
                
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
                                    <a href="mascotas.php?accion=editar&id_mascota=<?php echo $mascota['id_mascota']; ?>" class="btn-accion editar">Editar</a>
                                    <a href="ficha_mascota_pdf.php?id_mascota=<?php echo $mascota['id_mascota']; ?>" class="btn-accion descargar">Descargar Ficha</a>
                                    
                                    <form method="POST" action="mascotas.php" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a <?php echo htmlspecialchars($mascota['nombre']); ?>?')">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id_mascota" value="<?php echo $mascota['id_mascota']; ?>">
                                        <button type="submit" class="btn-accion eliminar">Eliminar</button>
                                    </form>
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