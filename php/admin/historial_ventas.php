<?php

include("../crud/conexion.php");

$busqueda = $_GET['q'] ?? '';

$sql = "
    SELECT
        v.id_venta,
        v.fecha,
        v.total,

        COALESCE(
            CONCAT(p.nombre, ' ', p.apellido),
            'Consumidor Final'
        ) AS cliente,

        COALESCE(m.nombre, '-') AS mascota,

        GROUP_CONCAT(
            pr.nombre_producto
            SEPARATOR ', '
        ) AS productos

    FROM ventas v

    LEFT JOIN persona p
        ON v.id_persona = p.id_persona

    LEFT JOIN mascota m
        ON v.id_mascota = m.id_mascota

    INNER JOIN detalle_venta dv
        ON v.id_venta = dv.id_venta

    INNER JOIN productos pr
        ON dv.id_producto = pr.id_producto

    WHERE
        CONCAT(
            COALESCE(p.nombre, ''),
            ' ',
            COALESCE(p.apellido, '')
        ) LIKE ?

    GROUP BY
        v.id_venta,
        v.fecha,
        v.total,
        cliente,
        mascota

    ORDER BY v.fecha DESC
";

$stmt = mysqli_prepare($conn, $sql);

$like = "%" . $busqueda . "%";

mysqli_stmt_bind_param($stmt, "s", $like);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Historial de Ventas</title>

    <link rel="stylesheet" href="../../css/theme.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/style.css?v=<?= time() ?>">

    <style>
        body { padding: 2rem; }
        .card { max-width: 1100px; margin: 0 auto; }
        h1 { margin-top: 0; }
        .top-bar {
            display: flex;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .top-bar input { flex: 1; }
    </style>

</head>

<body>

<div class="card">

    <h1>
        Historial de Ventas
    </h1>

    <form method="GET" class="top-bar">

        <input
            type="text"
            name="q"
            placeholder="Buscar por cliente..."
            value="<?= htmlspecialchars($busqueda) ?>"
        >

        <button class="btn" type="submit">
            Buscar
        </button>

        <a href="venta_productos.php" class="btn">
            Volver
        </a>

    </form>

    <table>

        <thead>

            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Mascota</th>
                <th>Productos</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

            <?php if($result && mysqli_num_rows($result) > 0): ?>

                <?php while($row = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?= date("d/m/Y H:i", strtotime($row['fecha'])) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['cliente']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['mascota']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['productos']) ?>
                        </td>

                        <td>
                            $ <?= number_format($row['total'], 2) ?>
                        </td>

                        <td>

                            <a
                                href="ver_venta.php?id=<?= $row['id_venta'] ?>"
                                class="btn-accion"
                                title="Ver detalle"
                            >
                                👁️
                            </a>

                            <a
                                href="ticket_venta.php?id=<?= $row['id_venta'] ?>"
                                class="btn-accion"
                                title="Ver ticket"
                            >
                                🧾
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6" style="text-align:center;">
                        No hay ventas registradas
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

</body>

</html>