<?php

require_once('../../crud/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../registrar_compra_proveedor.php?error=Acceso no permitido');
    exit();
}

$id_proveedor = $_POST['id_proveedor'] ?? null;
$id_producto = $_POST['id_producto'] ?? null;
$cantidad = $_POST['cantidad'] ?? 1;
$precio_unitario = $_POST['precio_unitario'] ?? 0;
$observaciones = trim($_POST['observaciones'] ?? '');

if (!$id_proveedor || !$id_producto || $cantidad <= 0 || $precio_unitario < 0) {
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
            id_producto,
            cantidad,
            precio_unitario
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
    ";

    $stmt_detalle = $conn->prepare($sql_detalle);
    $stmt_detalle->bind_param("iiid", $id_compra, $id_producto, $cantidad, $precio_unitario);
    $stmt_detalle->execute();

    $conn->commit();

    session_start();
    $_SESSION['mensaje'] = "Compra registrada correctamente";

    header('Location: ../historial_compras_proveedor.php?id_proveedor=' . $id_proveedor . '&id_compra_nueva=' . $id_compra);
    exit();

    } catch (Exception $e) {
        $conn->rollback();

        header('Location: ../registrar_compra_proveedor.php?error=No se pudo registrar la compra');
        exit();
    }