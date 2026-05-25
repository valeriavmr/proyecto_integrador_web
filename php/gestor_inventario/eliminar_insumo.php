<?php
require_once('../crud/conexion.php');
$id_insumo = $_GET['id'] ?? null;
if (!$id_insumo) {
    die("ID de insumo no válido.");
}

// Eliminar insumo
$sql = "DELETE FROM insumo WHERE id_insumo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_insumo);

//elimino de inventario primero para evitar errores de clave foránea
$sqlInventario = "DELETE FROM inventario_insumo WHERE id_insumo = ?";
$stmtInventario = $conn->prepare($sqlInventario);
$stmtInventario->bind_param("i", $id_insumo);
$stmtInventario->execute();

if ($stmt->execute()) {
    header("Location: inventario_insumos.php?mensaje=Insumo eliminado exitosamente");
} else {
    header("Location: inventario_insumos.php?error=Error al eliminar el insumo");
}
?>