<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Proveedor</title>
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

$busqueda = trim($_POST['busqueda'] ?? $_GET['busqueda'] ?? '');
$estado = $_POST['estado'] ?? $_GET['estado'] ?? '';

$sql = "
    SELECT
        id_proveedor,
        nombre,
        cuit,
        telefono,
        correo,
        direccion,
        activo,
        fecha_alta
    FROM proveedores
    WHERE 1 = 1
";

$tipos = "";
$params = [];

if ($busqueda !== '') {
    $sql .= "
        AND (
            LOWER(nombre) LIKE ?
            OR LOWER(cuit) LIKE ?
            OR LOWER(telefono) LIKE ?
            OR LOWER(correo) LIKE ?
            OR LOWER(direccion) LIKE ?
        )
    ";

    $like = '%' . strtolower($busqueda) . '%';

    $tipos .= "sssss";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($estado !== '') {
    $sql .= " AND activo = ? ";
    $tipos .= "i";
    $params[] = (int)$estado;
}

$sql .= " ORDER BY nombre ASC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($tipos, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$resultados = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<main>
    <br><br>
    <h1>Buscar Proveedor</h1>

    <form action="" method="post">

        <input
            type="text"
            name="busqueda"
            id="busqueda"
            placeholder="Buscar por nombre, CUIT, teléfono, correo o dirección"
            value="<?= htmlspecialchars($busqueda) ?>"
        >

        <select name="estado" id="estado">
            <option value="">Todos los estados</option>
            <option value="1" <?= $estado === '1' ? 'selected' : '' ?>>Activos</option>
            <option value="0" <?= $estado === '0' ? 'selected' : '' ?>>Inactivos</option>
        </select>

        <input type="submit" value="Buscar" id="botonBuscar">

    </form>

    <?php if (!empty($resultados)): ?>
        <section class="resultados-ancho">
            <h2>Proveedores encontrados</h2>

            <table>
                <thead>
                    <tr>
                        <th>ID proveedor</th>
                        <th>Nombre</th>
                        <th>CUIT</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                        <th>Fecha alta</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($resultados as $fila): ?>
                        <tr>
                            <td>
                                <a href="detalle_proveedor.php?id_proveedor=<?= htmlspecialchars($fila['id_proveedor']) ?>">
                                    <?= htmlspecialchars($fila['id_proveedor']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="detalle_proveedor.php?id_proveedor=<?= htmlspecialchars($fila['id_proveedor']) ?>">
                                    <?= htmlspecialchars($fila['nombre']) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($fila['cuit'] ?? '') ?></td>
                            <td><?= htmlspecialchars($fila['telefono'] ?? '') ?></td>
                            <td><?= htmlspecialchars($fila['correo'] ?? '') ?></td>
                            <td><?= htmlspecialchars($fila['direccion'] ?? '') ?></td>
                            <td><?= $fila['activo'] ? 'Activo' : 'Inactivo' ?></td>
                            <td><?= htmlspecialchars($fila['fecha_alta']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    <?php else: ?>
        <p>No se encontraron proveedores con los filtros seleccionados.</p>
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