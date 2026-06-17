<?php

require_once dirname(__DIR__, 3) . '/config.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tabla_proveedores.php');
    exit();
}

$id_producto = $_POST['id_producto'] ?? null;
$id_proveedor = $_POST['id_proveedor'] ?? null;

if (!$id_producto || !$id_proveedor) {
    header('Location: ../tabla_proveedores.php');
    exit();
}

$sql = "
    UPDATE productos
    SET activo = 0
    WHERE id_producto = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    header('Location: ../detalle_proveedor.php?id_proveedor=' . urlencode($id_proveedor));
    exit();
}

$stmt->bind_param("i", $id_producto);
$stmt->execute();

session_start();
$_SESSION['mensaje'] = "Producto desactivado correctamente";

header('Location: ../detalle_proveedor.php?id_proveedor=' . urlencode($id_proveedor));
exit();
