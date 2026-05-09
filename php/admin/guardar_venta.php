<?php
// Desactivar en producción, útil en desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../crud/conexion.php");
header('Content-Type: application/json');

// Recibir JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['carrito'])) {
    echo json_encode([
        "success" => false, 
        "message" => "No hay productos en el carrito o los datos son inválidos"
    ]);
    exit;
}

$carrito = $data['carrito'];
$total   = $data['total'];
// Recibimos los IDs que enviamos desde ventas.js
$idCliente = !empty($data['id_cliente']) ? $data['id_cliente'] : null;
$idMascota = !empty($data['id_mascota']) ? $data['id_mascota'] : null;

try {
    mysqli_begin_transaction($conn);

    /* 1. CREAR LA CABECERA DE LA VENTA */
    // Añadimos id_persona e id_mascota a tu tabla ventas
    $sqlVenta = "INSERT INTO ventas (total, id_persona, id_mascota, fecha) VALUES (?, ?, ?, NOW())";
    $stmtVenta = mysqli_prepare($conn, $sqlVenta);
    
    // "dii" -> double (total), integer (cliente), integer (mascota)
    mysqli_stmt_bind_param($stmtVenta, "dii", $total, $idCliente, $idMascota);
    mysqli_stmt_execute($stmtVenta);
    
    $idVenta = mysqli_insert_id($conn);

    /* 2. VALIDAR STOCK Y GUARDAR DETALLES */
    $erroresStock = [];

    foreach ($carrito as $item) {
        $idProducto = $item['id'];
        $cantidad   = $item['cantidad'];
        $precio     = $item['precio'];
        $subtotal   = $precio * $cantidad;

        // Comprobar Stock actual
        $sqlStock = "SELECT stock_actual, nombre FROM productos WHERE id_producto = ? FOR UPDATE";
        $stmtStock = mysqli_prepare($conn, $sqlStock);
        mysqli_stmt_bind_param($stmtStock, "i", $idProducto);
        mysqli_stmt_execute($stmtStock);
        $res = mysqli_stmt_get_result($stmtStock);
        $producto = mysqli_fetch_assoc($res);

        if (!$producto) {
            $erroresStock[] = "Producto ID $idProducto no encontrado.";
            continue;
        }

        if ($producto['stock_actual'] < $cantidad) {
            $erroresStock[] = "{$producto['nombre']} (Solicitado: $cantidad, Disponible: {$producto['stock_actual']})";
            continue;
        }

        // Insertar en detalle_venta
        $sqlDetalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) 
                       VALUES (?, ?, ?, ?, ?)";
        $stmtDetalle = mysqli_prepare($conn, $sqlDetalle);
        mysqli_stmt_bind_param($stmtDetalle, "iiidd", $idVenta, $idProducto, $cantidad, $precio, $subtotal);
        mysqli_stmt_execute($stmtDetalle);

        // Descontar stock
        $sqlUpdate = "UPDATE productos SET stock_actual = stock_actual - ? WHERE id_producto = ?";
        $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "ii", $cantidad, $idProducto);
        mysqli_stmt_execute($stmtUpdate);
    }

    // Si hubo errores de stock, lanzamos excepción para hacer Rollback
    if (!empty($erroresStock)) {
        throw new Exception("Stock insuficiente en:\n" . implode("\n", $erroresStock));
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