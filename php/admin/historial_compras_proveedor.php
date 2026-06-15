<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="stylesheet" href="../../css/buscar_persona.css?v=<?= time() ?>">
</head>
<body>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '\..\..\config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
include_once(__DIR__ . '/../includes/sidebar.php');
require('../crud/conexion.php');

/* ==========================
   Cargar proveedores
========================== */

$proveedores = [];

$sql_proveedores = "
    SELECT id_proveedor, nombre
    FROM proveedores
    ORDER BY nombre ASC
";

$resultado_proveedores = $conn->query($sql_proveedores);

while ($row = $resultado_proveedores->fetch_assoc()) {
    $proveedores[] = $row;
}

/* ==========================
   Filtros
========================== */

$id_proveedor = $_POST['id_proveedor'] ?? $_GET['id_proveedor'] ?? '';
$tipo_item = $_POST['tipo_item'] ?? $_GET['tipo_item'] ?? '';
$fecha_desde = $_POST['fecha_desde'] ?? $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_POST['fecha_hasta'] ?? $_GET['fecha_hasta'] ?? '';
$id_compra_nueva = $_GET['id_compra_nueva'] ?? null;

/* ==========================
   Consulta dinámica
========================== */

$compras = [];

$sql = "
    SELECT
        c.id_compra,
        c.fecha_compra,
        p.nombre AS proveedor,

        CASE
            WHEN cd.tipo_item = 'producto'
                THEN pr.nombre_producto
            WHEN cd.tipo_item = 'insumo'
                THEN i.nombre_insumo
            ELSE 'Sin identificar'
        END AS item_nombre,

        cd.tipo_item,
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

    LEFT JOIN productos pr
        ON cd.id_producto = pr.id_producto

    LEFT JOIN insumo i
        ON cd.id_insumo = i.id_insumo

    WHERE 1 = 1
";

$tipos = "";
$params = [];

if (!empty($id_proveedor)) {
    $sql .= " AND c.id_proveedor = ? ";
    $tipos .= "i";
    $params[] = $id_proveedor;
}

if (!empty($tipo_item)) {
    $sql .= " AND cd.tipo_item = ? ";
    $tipos .= "s";
    $params[] = $tipo_item;
}

if (!empty($fecha_desde)) {
    $sql .= " AND DATE(c.fecha_compra) >= ? ";
    $tipos .= "s";
    $params[] = $fecha_desde;
}

if (!empty($fecha_hasta)) {
    $sql .= " AND DATE(c.fecha_compra) <= ? ";
    $tipos .= "s";
    $params[] = $fecha_hasta;
}

$sql .= "
    ORDER BY c.fecha_compra DESC,
             c.id_compra DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();
$compras = $resultado->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <br><br>
    <h1>Historial de Compras</h1>

    <form action="" method="POST">

        <select name="id_proveedor" id="id_proveedor">
            <option value="">Todos los proveedores</option>

            <?php foreach ($proveedores as $proveedor): ?>
                <option
                    value="<?= htmlspecialchars($proveedor['id_proveedor']) ?>"
                    <?= ((string)$id_proveedor === (string)$proveedor['id_proveedor']) ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($proveedor['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="tipo_item" id="tipo_item">
            <option value="">Todos los tipos</option>
            <option value="producto" <?= $tipo_item === 'producto' ? 'selected' : '' ?>>
                Producto
            </option>
            <option value="insumo" <?= $tipo_item === 'insumo' ? 'selected' : '' ?>>
                Insumo
            </option>
        </select>

        <input
            type="date"
            name="fecha_desde"
            value="<?= htmlspecialchars($fecha_desde) ?>"
            title="Fecha desde"
        >

        <input
            type="date"
            name="fecha_hasta"
            value="<?= htmlspecialchars($fecha_hasta) ?>"
            title="Fecha hasta"
        >

        <input
            type="submit"
            value="Buscar"
            id="botonBuscar"
        >

    </form>

    <?php if (!empty($compras)): ?>

        <section class="resultados-ancho">

            <h3>Compras registradas</h3>

            <table>
                <thead>
                    <tr>
                        <th>ID Compra</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Tipo</th>
                        <th>Artículo</th>
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

                            <td><?= htmlspecialchars($compra['id_compra']) ?></td>
                            <td><?= htmlspecialchars($compra['fecha_compra']) ?></td>
                            <td><?= htmlspecialchars($compra['proveedor']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($compra['tipo_item'])) ?></td>
                            <td><?= htmlspecialchars($compra['item_nombre']) ?></td>
                            <td><?= htmlspecialchars($compra['cantidad']) ?></td>
                            <td>$<?= number_format($compra['precio_unitario'], 2, ',', '.') ?></td>
                            <td>$<?= number_format($compra['subtotal'], 2, ',', '.') ?></td>
                            <td>$<?= number_format($compra['total'], 2, ',', '.') ?></td>
                            <td><?= htmlspecialchars($compra['observaciones'] ?? '') ?></td>

                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

        </section>

    <?php else: ?>

        <p>No existen compras registradas con los filtros seleccionados.</p>

    <?php endif; ?>

    <section id="volver_s">
        <a href="proveedores_admin.php" class="btn-volver-admin">
            Volver a Gestión de Proveedores
        </a>
    </section>

</main>

<?php if (isset($_SESSION['mensaje'])): ?>
    <script>
        alert("<?= htmlspecialchars($_SESSION['mensaje']) ?>");
    </script>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<?php include('../footer.php'); ?>

</body>
</html>