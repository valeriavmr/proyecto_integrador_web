<?php

require_once dirname(__DIR__, 3) . '/config.php';
require_once(BASE_PATH . '/php/crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../tabla_proveedores.php');
    exit();
}

$id_proveedor = $_POST['id_proveedor'] ?? null;
$nombre_producto = trim($_POST['nombre_producto'] ?? '');
$descripcion_producto = trim($_POST['descripcion_producto'] ?? '');
$precio_unitario = $_POST['precio_unitario'] ?? null;
$tipo = $_POST['tipo'] ?? 'Otro';
$activo = $_POST['activo'] ?? 1;

if (!$id_proveedor || empty($nombre_producto) || $precio_unitario === null || $precio_unitario < 0) {
    header('Location: ../add_producto_proveedor.php?id_proveedor=' . urlencode($id_proveedor) . '&error=Datos inválidos');
    exit();
}

$sql = "
    INSERT INTO productos
    (
        nombre_producto,
        descripcion_producto,
        precio_unitario,
        imagen_producto,
        tipo,
        activo,
        id_proveedor
    )
    VALUES
    (
        ?,
        ?,
        ?,
        NULL,
        ?,
        ?,
        ?
    )
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    header('Location: ../add_producto_proveedor.php?id_proveedor=' . urlencode($id_proveedor) . '&error=Error al preparar la consulta');
    exit();
}

$stmt->bind_param(
    "ssdsii",
    $nombre_producto,
    $descripcion_producto,
    $precio_unitario,
    $tipo,
    $activo,
    $id_proveedor
);

if ($stmt->execute()) {
    session_start();
    $_SESSION['mensaje'] = "Producto agregado correctamente";

    header('Location: ../detalle_proveedor.php?id_proveedor=' . urlencode($id_proveedor));
    exit();
}

header('Location: ../add_producto_proveedor.php?id_proveedor=' . urlencode($id_proveedor) . '&error=No se pudo agregar el producto');
exit();
