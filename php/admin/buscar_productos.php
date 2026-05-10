<?php

include("../crud/conexion.php");

header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

$sql = "
    SELECT
        p.id_producto,
        p.nombre_producto AS nombre,
        p.tipo,
        p.precio_unitario AS precio_venta,
        COALESCE(i.cantidad_actual_producto, 0) AS stock_actual
    FROM productos p
    LEFT JOIN inventario i
        ON p.id_producto = i.id_producto
    WHERE p.activo = 1
    AND (
        p.nombre_producto LIKE ?
        OR p.tipo LIKE ?
        OR p.descripcion_producto LIKE ?
    )
    ORDER BY p.nombre_producto ASC
";

$like = "%" . $q . "%";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $like,
    $like,
    $like
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$productos = [];

while ($row = mysqli_fetch_assoc($result)) {
    $productos[] = $row;
}

echo json_encode($productos);