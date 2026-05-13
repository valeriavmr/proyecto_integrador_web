<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../crud/conexion.php");

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['carrito'])) {

    echo json_encode([
        "success" => false,
        "message" => "No hay productos en el carrito o los datos son inválidos"
    ]);

    exit;
}

$carrito = $data['carrito'];
$total = $data['total'];

$idCliente = !empty($data['id_cliente'])
    ? (int)$data['id_cliente']
    : null;

$idMascota = !empty($data['id_mascota'])
    ? (int)$data['id_mascota']
    : null;

try {

    mysqli_begin_transaction($conn);

    /*
    ========================================
    CREAR VENTA
    ========================================
    */

    $sqlVenta = "
        INSERT INTO ventas (
            total,
            id_persona,
            id_mascota,
            fecha
        )
        VALUES (?, ?, ?, NOW())
    ";

    $stmtVenta = mysqli_prepare($conn, $sqlVenta);

    mysqli_stmt_bind_param(
        $stmtVenta,
        "dii",
        $total,
        $idCliente,
        $idMascota
    );

    mysqli_stmt_execute($stmtVenta);

    $idVenta = mysqli_insert_id($conn);


    /*
    ========================================
    VALIDAR STOCK DE TODOS
    ========================================
    */

    $erroresStock = [];

    foreach ($carrito as $item) {

        $idProducto = (int)$item['id'];
        $cantidad = (int)$item['cantidad'];

        $sqlStock = "
            SELECT
                p.nombre_producto AS nombre,
                COALESCE(i.cantidad_actual_producto, 0) AS stock_actual
            FROM productos p
            LEFT JOIN inventario i
                ON p.id_producto = i.id_producto
            WHERE p.id_producto = ?
            AND p.activo = 1
            FOR UPDATE
        ";

        $stmtStock = mysqli_prepare($conn, $sqlStock);

        mysqli_stmt_bind_param(
            $stmtStock,
            "i",
            $idProducto
        );

        mysqli_stmt_execute($stmtStock);

        $res = mysqli_stmt_get_result($stmtStock);

        $producto = mysqli_fetch_assoc($res);

        if (!$producto) {

            $erroresStock[] =
                "Producto ID $idProducto no encontrado.";

            continue;
        }

        if ((int)$producto['stock_actual'] < $cantidad) {

            $erroresStock[] =
                $producto['nombre']
                . " (Solicitado: "
                . $cantidad
                . ", Disponible: "
                . $producto['stock_actual']
                . ")";

            continue;
        }
    }

    if (!empty($erroresStock)) {

        throw new Exception(
            "Stock insuficiente en:\n"
            . implode("\n", $erroresStock)
        );
    }


    /*
    ========================================
    GUARDAR DETALLE + DESCONTAR STOCK
    ========================================
    */

    foreach ($carrito as $item) {

        $idProducto = (int)$item['id'];
        $cantidad = (int)$item['cantidad'];
        $precio = (float)$item['precio'];
        $subtotal = $precio * $cantidad;

        /*
        INSERTAR DETALLE
        */

        $sqlDetalle = "
            INSERT INTO detalle_venta (
                id_venta,
                id_producto,
                cantidad,
                precio_unitario,
                subtotal
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $stmtDetalle = mysqli_prepare($conn, $sqlDetalle);

        mysqli_stmt_bind_param(
            $stmtDetalle,
            "iiidd",
            $idVenta,
            $idProducto,
            $cantidad,
            $precio,
            $subtotal
        );

        mysqli_stmt_execute($stmtDetalle);


        /*
        DESCONTAR STOCK EN INVENTARIO
        */

        $sqlUpdate = "
            UPDATE inventario
            SET cantidad_actual_producto =
                cantidad_actual_producto - ?
            WHERE id_producto = ?
        ";

        $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);

        mysqli_stmt_bind_param(
            $stmtUpdate,
            "ii",
            $cantidad,
            $idProducto
        );

        mysqli_stmt_execute($stmtUpdate);
    }

    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Venta #$idVenta registrada con éxito"
    ]);

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

?>