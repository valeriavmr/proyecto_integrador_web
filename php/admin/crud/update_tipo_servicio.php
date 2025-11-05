<?php
require_once('../../crud/conexion.php');
require_once('../../crud/consultas_varias.php');

// Recuperamos id_tipo_servicio y campos del tipo de servicio
$id_tipo_servicio = $_POST['id_tipo_servicio'] ?? null;
$nombre_tipo_servicio = $_POST['nombre_tipo_servicio'] ?? '';
$descripcion_tipo_servicio = $_POST['descripcion_tipo_servicio'] ?? '';
$precio_tipo_servicio = $_POST['precio_tipo_servicio'] ?? '';
$imagen_servicio = $_FILES['imagen_servicio'] ?? null;
$imagen_actual = $_POST['imagen_actual'] ?? '';
$uploads_dir = '../../../uploads/';
if (!$id_tipo_servicio) {
    die("ID de tipo de servicio no proporcionado.");
}

//Verifico que el tipo de servicio nuevo no exista ya en otro registro
if(tipoServicioExisteExcluyendoId($conn, $nombre_tipo_servicio, $id_tipo_servicio)) {
    header("Location: ../../admin/editar_tipo_servicio.php?id_tipo_servicio=$id_tipo_servicio&error=El tipo de servicio ya existe");
    exit();
}
// Manejo de la imagen
if ($imagen_servicio && $imagen_servicio['error'] === UPLOAD_ERR_OK)
{
    // Generar nombre único para la nueva imagen
    $nombreArchivo = uniqid() . "_" . basename($imagen_servicio['name']);
    $rutaDestino = $uploads_dir . $nombreArchivo;

    // Mover el archivo subido al directorio de destino
    if (move_uploaded_file($imagen_servicio['tmp_name'], $rutaDestino)) {
        // Eliminar la imagen anterior si existe y es distinta
        if (!empty($imagen_actual) && file_exists($uploads_dir . $imagen_actual)) {
            unlink($uploads_dir . $imagen_actual);
        }
        $nombreImagenFinal = $nombreArchivo;
    } else {
        // Si falla la subida, mantener la imagen actual
        $nombreImagenFinal = $imagen_actual;
        error_log("Falló la subida del archivo a $rutaDestino");
    }
} else {
    // Si no se subió una nueva imagen, mantener la actual
    $nombreImagenFinal = $imagen_actual;
}

// Preparar y ejecutar la consulta de actualización
$sql = "UPDATE tipo_de_servicio_g3 SET tipo_de_servicio = ?, descripcion = ?, precio_servicio = ?, imagen_servicio = ? WHERE id_tipo_servicio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdsi", $nombre_tipo_servicio, $descripcion_tipo_servicio, $precio_tipo_servicio, $nombreImagenFinal, $id_tipo_servicio);
if ($stmt->execute()) {
    // Redirigir de vuelta a la página de administración de tipos de servicio
    header("Location: ../../admin/servicios_admin.php?mensaje=Tipo de servicio actualizado correctamente");
    exit();
} else {
    echo "Error al actualizar el tipo de servicio: " . $stmt->error;
}