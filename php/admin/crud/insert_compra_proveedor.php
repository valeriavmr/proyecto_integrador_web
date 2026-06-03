<?php

require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registrar_compra_proveedor.php?error=Acceso no permitido');
    exit();
}

$id_proveedor = $_POST['id_proveedor'] ?? null;
$tipo_item = $_POST['tipo_item'] ?? '';

$id_producto = !empty($_POST['id_producto']) ? $_POST['id_producto'] : null;
$id_insumo = !empty($_POST['id_insumo']) ? $_POST['id_insumo'] : null;

$cantidad = $_POST['cantidad'] ?? 1;
$precio_unitario = $_POST['precio_unitario'] ?? 0;
$observaciones = trim($_POST['observaciones'] ?? '');

if (
    !$id_proveedor ||
    $cantidad <= 0 ||
    $precio_unitario < 0
) {
    header('Location: ../registrar_compra_proveedor.php?error=Datos inválidos');
    exit();
}

$total = $cantidad * $precio_unitario;

$conn->begin_transaction();

try {

    $sql_compra = "
        INSERT INTO compras
        (
            id_proveedor,
            total,
            observaciones
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
    ";

    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->bind_param("ids", $id_proveedor, $total, $observaciones);
    $stmt_compra->execute();

    $id_compra = $conn->insert_id;

    $sql_detalle = "
        INSERT INTO compra_detalle
        (
            id_compra,
            tipo_item,
            id_producto,
            id_insumo,
            cantidad,
            precio_unitario
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmt_detalle = $conn->prepare($sql_detalle);

    $stmt_detalle->bind_param(
        "isiiid",
        $id_compra,
        $tipo_item,
        $id_producto,
        $id_insumo,
        $cantidad,
        $precio_unitario
    );

    if ($tipo_item === 'producto') {
    $id_insumo = null;
    }

    if ($tipo_item === 'insumo') {
        $id_producto = null;
    }

    if (
    $tipo_item === 'producto' &&
    empty($id_producto)
    ) {
        die("Debe seleccionar un producto");
    }

    if (
        $tipo_item === 'insumo' &&
        empty($id_insumo)
    ) {
        die("Debe seleccionar un insumo");
    }

    $stmt_detalle->execute();

    if ($tipo_item === 'producto') {

        $sql_stock = "
            UPDATE inventario
            SET cantidad_actual_producto =
                cantidad_actual_producto + ?
            WHERE id_producto = ?
        ";

        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $cantidad, $id_producto);
        $stmt_stock->execute();

        $sql_movimiento = "
            INSERT INTO inventario_movimientos
            (
                id_producto_stock,
                tipo_movimiento,
                cantidad_producto
            )
            SELECT
                id_producto_stock,
                'entrada',
                ?
            FROM inventario
            WHERE id_producto = ?
        ";

        $stmt_mov = $conn->prepare($sql_movimiento);
        $stmt_mov->bind_param("ii", $cantidad, $id_producto);
        $stmt_mov->execute();
    }

    if ($tipo_item === 'insumo') {

        $sql_stock = "
            UPDATE inventario_insumo
            SET cantidad_actual =
                cantidad_actual + ?
            WHERE id_insumo = ?
        ";

        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->bind_param("ii", $cantidad, $id_insumo);
        $stmt_stock->execute();

        $sql_movimiento = "
            INSERT INTO movimientos_insumo
            (
                id_stock_insumo,
                tipo_movimiento,
                cantidad
            )
            SELECT
                id_stock_insumo,
                'entrada',
                ?
            FROM inventario_insumo
            WHERE id_insumo = ?
        ";

        $stmt_mov = $conn->prepare($sql_movimiento);
        $stmt_mov->bind_param("ii", $cantidad, $id_insumo);
        $stmt_mov->execute();
    }

    $conn->commit();

    session_start();
    $_SESSION['mensaje'] = "Compra registrada correctamente";

    header(
        'Location: ../historial_compras_proveedor.php?id_proveedor='
        . $id_proveedor .
        '&id_compra_nueva=' .
        $id_compra
    );

    exit();

} catch (Exception $e) {

    $conn->rollback();

    header(
        'Location: ../registrar_compra_proveedor.php?error=No se pudo registrar la compra'
    );

    exit();
}