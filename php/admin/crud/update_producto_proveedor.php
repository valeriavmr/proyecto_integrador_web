<?php

require_once dirname(__DIR__, 3) . '/config.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

$id_producto = $_POST['id_producto'] ?? null;
$id_proveedor = $_POST['id_proveedor'] ?? null;

$nombre_producto = trim($_POST['nombre_producto'] ?? '');
$descripcion_producto = trim($_POST['descripcion_producto'] ?? '');
$precio_unitario = $_POST['precio_unitario'] ?? 0;
$tipo = $_POST['tipo'] ?? 'Otro';
$activo = $_POST['activo'] ?? 1;

$sql = "
UPDATE productos
SET
    nombre_producto = ?,
    descripcion_producto = ?,
    precio_unitario = ?,
    tipo = ?,
    activo = ?
WHERE id_producto = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssdsii",
    $nombre_producto,
    $descripcion_producto,
    $precio_unitario,
    $tipo,
    $activo,
    $id_producto
);

$stmt->execute();

header(
    "Location: ../detalle_proveedor.php?id_proveedor=" .
        urlencode($id_proveedor)
);

exit();
