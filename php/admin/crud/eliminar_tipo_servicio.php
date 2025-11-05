<?php
require_once('../../crud/conexion.php');
require_once('../../crud/consultas_varias.php');

// Recuperamos id_tipo_servicio
$id_tipo_servicio = $_GET['id_tipo_servicio'] ?? $_POST['id_tipo_servicio'] ?? null;
if (!$id_tipo_servicio) {
    die("ID de tipo de servicio no proporcionado.");
}
// Verificamos si el tipo de servicio existe
$tipo_servicio = obtenerTipoDeServicioPorId($conn, $id_tipo_servicio);
if (!$tipo_servicio) {
    die("Tipo de servicio no encontrado.");
}
// Preparar y ejecutar la consulta de eliminación
$sql = "DELETE FROM tipo_de_servicio_g3 WHERE id_tipo_servicio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tipo_servicio);
if ($stmt->execute()) {
    // Eliminar la imagen asociada si existe
    if (!empty($tipo_servicio['imagen_servicio'])) {
        $uploads_dir = '../../../uploads/';
        $rutaImagen = $uploads_dir . $tipo_servicio['imagen_servicio'];
        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }
    }
    // Redirigir de vuelta a la página de administración de tipos de servicio
    header("Location: ../../admin/servicios_admin.php?mensaje=Tipo de servicio eliminado correctamente");
    exit();
} else {
    echo "Error al eliminar el tipo de servicio: " . $stmt->error;
}