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

$subtotal = 0;

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ticket Venta #<?= $venta['id_venta'] ?></title>

    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/ticket.css?v=<?= time() ?>">

    <style>
        body { padding: 2rem; }
        #ticket {
            width: 80mm;
            background: var(--pure-surface);
            margin: auto;
            padding: 1rem;
            font-family: var(--font-mono);
            font-size: 0.8rem;
            box-shadow: var(--shadow-card);
            border-radius: var(--radius-lg);
        }
        .divider {
            text-align: center;
            margin: 0.75rem 0;
            border-bottom: 1.5px dashed var(--whisper-border-dark);
            font-size: 0;
            height: 0;
            overflow: hidden;
            color: transparent;
        }
        .right { text-align: right; font-size: 0.82rem; }
        .total {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 800;
            text-align: right;
            margin-top: 0.75rem;
            font-variant-numeric: tabular-nums;
        }
        .acciones {
            text-align: center;
            margin-top: 1.5rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        table { font-size: 0.78rem; }
        th { border-bottom: 1px dashed var(--charcoal-ink); background: none; }
        td { border-bottom: none; padding: 0.25rem 0; }

        @media print {
            body { background: white; padding: 0; margin: 0; }
            body * { visibility: hidden; }
            #ticket, #ticket * { visibility: visible; }
            #ticket {
                position: absolute;
                top: 0;
                left: 0;
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
            .acciones { display: none !important; }
        }
    </style>

</head>

<body>

<div id="ticket">

    <div class="ticket-header">

        <img src="../../recursos/logsinfondo.png"
             class="ticket-logo"
             alt="Logo">

        <h2>Tahito</h2>

        <div>Centro Veterinario</div>

        <br>

        <div>
            Fecha:
            <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?>
        </div>

        <div>
            Ticket N°:
            <strong><?= $venta['id_venta'] ?></strong>
        </div>

    </div>

    <div class="divider">
        --------------------------------
    </div>

    <div>
        Cliente:
        <?= htmlspecialchars($venta['cliente']) ?>
    </div>

    <div>
        Mascota:
        <?= htmlspecialchars($venta['mascota']) ?>
    </div>

    <div class="divider">
        --------------------------------
    </div>

    <table>

        <thead>

            <tr>
                <th>Prod.</th>
                <th>Cant</th>
                <th>P.Unit</th>
                <th>Sub</th>
            </tr>

        </thead>

        <tbody>

            <?php while($row = mysqli_fetch_assoc($detalle)): ?>

                <?php $subtotal += $row['subtotal']; ?>

                <tr>
                    <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td>$<?= number_format($row['precio_unitario'], 0) ?></td>
                    <td>$<?= number_format($row['subtotal'], 0) ?></td>
                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

    <div class="divider">
        --------------------------------
    </div>

    <div class="right">
        Subtotal: $ <?= number_format($subtotal, 2) ?>
    </div>

    <div class="right">
        IVA 21%: $ <?= number_format($subtotal * 0.21, 2) ?>
    </div>

    <div class="total">
        TOTAL: $ <?= number_format($venta['total'], 2) ?>
    </div>

    <div class="divider">
        --------------------------------
    </div>

    <div style="text-align:center;">
        Gracias por su compra
    </div>

</div>

<div class="acciones">

    <button class="btn" onclick="window.print()">
        🖨️ Imprimir
    </button>

    <a href="ver_venta.php?id=<?= $venta['id_venta'] ?>" class="btn">
        Ver detalle
    </a>

    <a href="venta_productos.php" class="btn">
        Volver
    </a>

</div>

</body>

</html>