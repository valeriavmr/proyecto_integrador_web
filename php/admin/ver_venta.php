<?php

include("../crud/conexion.php");

$idVenta = $_GET['id'] ?? null;

if (!$idVenta) {
    die("ID de venta no recibido");
}

/* =========================
   CABECERA VENTA
========================= */

$sqlVenta = "
    SELECT
        v.id_venta,
        v.fecha,
        v.total,

        COALESCE(
            CONCAT(p.nombre, ' ', p.apellido),
            'Consumidor Final'
        ) AS cliente,

        COALESCE(m.nombre, '-') AS mascota

    FROM ventas v

    LEFT JOIN persona p
        ON v.id_persona = p.id_persona

    LEFT JOIN mascota m
        ON v.id_mascota = m.id_mascota

    WHERE v.id_venta = ?
";

$stmtVenta = mysqli_prepare($conn, $sqlVenta);
mysqli_stmt_bind_param($stmtVenta, "i", $idVenta);
mysqli_stmt_execute($stmtVenta);

$resultVenta = mysqli_stmt_get_result($stmtVenta);
$venta = mysqli_fetch_assoc($resultVenta);

if (!$venta) {
    die("Venta no encontrada");
}

/* =========================
   DETALLE VENTA
========================= */

$sqlDetalle = "
    SELECT
        dv.cantidad,
        dv.precio_unitario,
        dv.subtotal,
        p.nombre_producto

    FROM detalle_venta dv

    INNER JOIN productos p
        ON dv.id_producto = p.id_producto

    WHERE dv.id_venta = ?
";

$stmtDetalle = mysqli_prepare($conn, $sqlDetalle);
mysqli_stmt_bind_param($stmtDetalle, "i", $idVenta);
mysqli_stmt_execute($stmtDetalle);

$detalle = mysqli_stmt_get_result($stmtDetalle);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalle Venta #<?= $venta['id_venta'] ?></title>

    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/style.css?v=<?= time() ?>">

    <style>
        body { padding: 2rem; }
        .card { max-width: 900px; margin: 0 auto; }
        h1 { margin-top: 0; }
        table { margin-top: 1.25rem; }
        .total {
            text-align: right;
            font-family: var(--font-display);
            font-size: 1.35rem;
            font-weight: 800;
            margin-top: 1.25rem;
            font-variant-numeric: tabular-nums;
        }
        .acciones {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.5rem;
        }
        .btn-secundario {
            background: var(--ink-secondary);
        }
    </style>

</head>

<body>

<div class="card">

    <h1>
        Detalle de Venta #<?= $venta['id_venta'] ?>
    </h1>

    <p>
        <strong>Fecha:</strong>
        <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?>
    </p>

    <p>
        <strong>Cliente:</strong>
        <?= htmlspecialchars($venta['cliente']) ?>
    </p>

    <p>
        <strong>Mascota:</strong>
        <?= htmlspecialchars($venta['mascota']) ?>
    </p>

    <table>

        <thead>

            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>

        </thead>

        <tbody>

            <?php while($row = mysqli_fetch_assoc($detalle)): ?>

                <tr>
                    <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td>$ <?= number_format($row['precio_unitario'], 2) ?></td>
                    <td>$ <?= number_format($row['subtotal'], 2) ?></td>
                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

    <div class="total">
        Total: $ <?= number_format($venta['total'], 2) ?>
    </div>

    <div class="acciones">

        <a href="venta_productos.php" class="btn btn-secundario">
            ← Volver
        </a>

        <a href="ticket_venta.php?id=<?= $venta['id_venta'] ?>" class="btn">
            🧾 Ver Ticket
        </a>

    </div>

</div>

</body>

</html>