<?php
require_once('../crud/conexion.php');
include_once('../../config.php');

if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
// Validación de permisos
include_once(BASE_PATH . '/php/admin/auth.php');

// =========================
// 1. Query
// =========================
$sql ="SELECT
        m.id_movimiento_stock,
        m.tipo_movimiento,
        m.fecha_movimiento,
        m.cantidad_producto,
        p.nombre_producto
        FROM inventario_movimientos m
        JOIN inventario pi ON m.id_producto_stock = pi.id_producto_stock
        JOIN productos p ON pi.id_producto = p.id_producto
        ORDER BY m.fecha_movimiento DESC";
$result = $conn->query($sql);
?>
<?php
include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Trazabilidad de Productos</title>
    <link rel="stylesheet" href="../../css/buscar_persona.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">

</head>

<body>
<main>
    <br>
    <h1>Lista de Trazabilidad de Productos</h1>
    <section>
    <table class="tabla-admin">
        <thead>
            <tr>
                <th>ID Movimiento</th>
                <th>Producto</th>
                <th>Tipo de Movimiento</th>
                <th>Cantidad</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_movimiento_stock']; ?></td>
                    <td><?php echo $row['nombre_producto']; ?></td>
                    <td><?php echo ucfirst($row['tipo_movimiento']); ?></td>
                    <td><?php echo $row['cantidad_producto']; ?></td>
                    <td><?php echo date("d/m/Y H:i", strtotime($row['fecha_movimiento'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>
    <section id="volver_s">
        <a href="trazabilidad_productos.php" class="btn-volver-admin">Volver a trazabilidad de productos</a>
    </section>
</main>
<?php
include_once(BASE_PATH . '/php/footer.php');
?>
</body>
</html>
