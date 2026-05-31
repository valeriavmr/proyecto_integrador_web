<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras por Proveedor</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/buscar_persona.css?v=<?= time() ?>">
</head>
<body>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('auth.php');
include('header_admin.php');
require('../crud/conexion.php');

/* ==========================
   Cargar proveedores
========================== */

$proveedores = [];

$sql_proveedores = "
    SELECT
        id_proveedor,
        nombre
    FROM proveedores
    ORDER BY nombre ASC
";

$resultado_proveedores = $conn->query($sql_proveedores);

while ($row = $resultado_proveedores->fetch_assoc()) {
    $proveedores[] = $row;
}

/* ==========================
   Búsqueda
========================== */

$id_proveedor = $_POST['id_proveedor'] ?? $_GET['id_proveedor'] ?? '';
$id_compra_nueva = $_GET['id_compra_nueva'] ?? null;

$compras = [];

if (!empty($id_proveedor)) {

    $sql = "
        SELECT
            c.id_compra,
            c.fecha_compra,
            p.nombre AS proveedor,
            pr.nombre_producto,
            cd.cantidad,
            cd.precio_unitario,
            (cd.cantidad * cd.precio_unitario) AS subtotal,
            c.total,
            c.observaciones
        FROM compras c

        INNER JOIN proveedores p
            ON c.id_proveedor = p.id_proveedor

        INNER JOIN compra_detalle cd
            ON c.id_compra = cd.id_compra

        INNER JOIN productos pr
            ON cd.id_producto = pr.id_producto

        WHERE c.id_proveedor = ?

        ORDER BY c.fecha_compra DESC,
                 c.id_compra DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $compras = $resultado->fetch_all(MYSQLI_ASSOC);
}
?>

<main>

    <h1>Historial de Compras por Proveedor</h1>

    <form action="" method="POST">

        <select name="id_proveedor" id="id_proveedor" required>
            <option value="" disabled <?= empty($id_proveedor) ? 'selected' : '' ?>>
                Seleccione un proveedor
            </option>

            <?php foreach ($proveedores as $proveedor): ?>
                <option
                    value="<?= htmlspecialchars($proveedor['id_proveedor']) ?>"
                    <?= ((string)$id_proveedor === (string)$proveedor['id_proveedor']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($proveedor['nombre']) ?>
                </option>
            <?php endforeach; ?>

        </select>

        <input
            type="submit"
            value="Buscar"
            id="botonBuscar"
        >

    </form>

    <?php if (!empty($id_proveedor)): ?>

        <?php if (!empty($compras)): ?>

            <section class="resultados-ancho">

                <h3>Compras registradas</h3>

                <table>

                    <thead>
                        <tr>
                            <th>ID Compra</th>
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Total Compra</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($compras as $compra): ?>

                            <tr class="<?= ($id_compra_nueva == $compra['id_compra']) ? 'compra-nueva' : '' ?>">

                                <td>
                                    <?= htmlspecialchars($compra['id_compra']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($compra['fecha_compra']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($compra['proveedor']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($compra['nombre_producto']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($compra['cantidad']) ?>
                                </td>

                                <td>
                                    $<?= number_format($compra['precio_unitario'], 2, ',', '.') ?>
                                </td>

                                <td>
                                    $<?= number_format($compra['subtotal'], 2, ',', '.') ?>
                                </td>

                                <td>
                                    $<?= number_format($compra['total'], 2, ',', '.') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($compra['observaciones'] ?? '') ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </section>

        <?php else: ?>

            <p>No existen compras registradas para este proveedor.</p>

        <?php endif; ?>

    <?php endif; ?>

    <section id="volver_s">
        <a href="proveedores_admin.php" class="btn-volver-admin">
            Volver a Gestión de Proveedores
        </a>
    </section>

</main>

<?php include('../footer.php'); ?>

</body>
</html>