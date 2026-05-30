<?php
require_once('../crud/conexion.php');
include_once('../../config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validación de permisos
include_once(BASE_PATH . '/php/admin/auth.php');

// =========================
// QUERY
// =========================

$sql ="SELECT
        m.id_movimiento,
        m.tipo_movimiento,
        m.fecha,
        m.cantidad,
        i.nombre_insumo

        FROM movimientos_insumo m

        JOIN inventario_insumo ii
        ON m.id_stock_insumo = ii.id_stock_insumo

        JOIN insumo i
        ON ii.id_insumo = i.id_insumo

        ORDER BY m.fecha DESC";

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
    <title>Lista de Trazabilidad de Insumos</title>

    <link rel="stylesheet" href="../../css/buscar_persona.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">

</head>

<body>

<main>

    <br>

    <h1>Lista de Trazabilidad de Insumos</h1>

    <section>

        <table class="tabla-admin">

            <thead>

                <tr>

                    <th>ID Movimiento</th>
                    <th>Insumo</th>
                    <th>Tipo de Movimiento</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $row['id_movimiento']; ?>
                        </td>

                        <td>
                            <?php echo $row['nombre_insumo']; ?>
                        </td>

                        <td>
                            <?php echo ucfirst($row['tipo_movimiento']); ?>
                        </td>

                        <td>
                            <?php echo $row['cantidad']; ?>
                        </td>

                        <td>
                            <?php echo date("d/m/Y H:i", strtotime($row['fecha'])); ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </section>

    <section id="volver_s">

        <a href="trazabilidad_insumos.php" class="btn-volver-admin">

            Volver a trazabilidad de insumos

        </a>

    </section>

</main>

<?php
include_once(BASE_PATH . '/php/footer.php');
?>

</body>
</html>