<?php
require_once('../crud/conexion.php');
$id_producto = $_GET['id'] ?? null;
if (!$id_producto) {
    die("ID de producto no válido.");
}

// Eliminar producto

$sql = "DELETE FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id_producto);

//Borro de inventario primero para evitar errores de clave foránea
$sqlInventario = "DELETE FROM inventario WHERE id_producto = ?";
$stmtInventario = $conn->prepare($sqlInventario);
$stmtInventario->bind_param("i", $id_producto);
$stmtInventario->execute();

if ($stmt->execute()) {
    header("Location: inventario_productos.php?mensaje=Producto eliminado exitosamente");
} else {
    header("Location: inventario_productos.php?error=Error al eliminar el producto");
}

?>