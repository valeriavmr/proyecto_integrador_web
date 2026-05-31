<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de proveedor</title>
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
<?php
require_once('auth.php');
include('header_admin.php');
require_once('../crud/conexion.php');

$id_proveedor = $_GET['id_proveedor'] ?? null;
$proveedor = null;

if ($id_proveedor) {
    $sql = "SELECT * FROM proveedores WHERE id_proveedor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $proveedor = $resultado->fetch_assoc();
}
?>

<main>
    <section id="lista_proveedores_sec">
        <h2>Detalle de proveedor</h2>

        <?php if ($proveedor): ?>
            <table>
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td><?= htmlspecialchars($proveedor['id_proveedor']) ?></td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td><?= htmlspecialchars($proveedor['nombre']) ?></td>
                    </tr>
                    <tr>
                        <th>CUIT</th>
                        <td><?= htmlspecialchars($proveedor['cuit'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td><?= htmlspecialchars($proveedor['telefono'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Correo</th>
                        <td><?= htmlspecialchars($proveedor['correo'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Dirección</th>
                        <td><?= htmlspecialchars($proveedor['direccion'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Activo</th>
                        <td><?= $proveedor['activo'] ? 'Sí' : 'No' ?></td>
                    </tr>
                    <tr>
                        <th>Fecha de alta</th>
                        <td><?= htmlspecialchars($proveedor['fecha_alta']) ?></td>
                    </tr>
                </tbody>
            </table>

            <br>

            <a href="editar_proveedor.php?id_proveedor=<?= htmlspecialchars($proveedor['id_proveedor']) ?>" class="btn-volver-admin">
                Editar proveedor
            </a>
        <?php else: ?>
            <p>Proveedor no encontrado.</p>
        <?php endif; ?>
    </section>

    <section id="volver_s">
        <a href="tabla_proveedores.php" class="btn-volver-admin">Volver a la lista de proveedores</a>
    </section>
</main>

<?php include('../footer.php'); ?>
</body>
</html>