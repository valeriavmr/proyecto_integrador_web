<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
require_once(BASE_PATH . '/php/crud/conexion.php');

// =======================
// 1. Filtro
// =======================

$filtro = $_GET['filtro'] ?? null;

// =======================
// 2. Query base
// =======================

$sql = "SELECT 
            i.id_insumo,
            ii.id_stock_insumo,
            i.nombre_insumo,
            i.descripcion_insumo,
            i.tipo_insumo,
            i.costo_unidad,
            ii.cantidad_actual,
            ii.param_bajo_stock
        FROM inventario_insumo ii
        JOIN insumo i 
            ON ii.id_insumo = i.id_insumo";

// Filtro bajo stock
if ($filtro === 'bajo_stock') {
    $sql .= " WHERE ii.cantidad_actual <= ii.param_bajo_stock";
}

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

    <link rel="stylesheet" href="../../css/menu_gestor_inventario.css">
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="stylesheet" href="../../css/buscar_persona.css">

    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">

    <title>Inventario de Insumos</title>
</head>

<body>
<main>

    <h1>Inventario de Insumos</h1>

    <?php if ($filtro === 'bajo_stock'): ?>
        <h2>⚠ Insumos con bajo stock</h2>

        <a class="btn-volver-admin" href="inventario_insumos.php">
            Ver todos
        </a>
    <?php endif; ?>

    <section>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Costo Unitario</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Estado</th>
                    <th>Proveedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <?php
                    $bajoStock = $row['cantidad_actual'] <= $row['param_bajo_stock'];
                    ?>
                    <tr style="<?= $bajoStock ? 'background-color:#ffe5e5;' : '' ?>">
                        <td>
                            <?= htmlspecialchars($row['nombre_insumo']); ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['descripcion_insumo']); ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['tipo_insumo']); ?>
                        </td>

                        <td>
                            $<?= number_format($row['costo_unidad'], 2); ?>
                        </td>

                        <td>
                            <?= $row['cantidad_actual']; ?>
                        </td>

                        <td>
                            <?= $row['param_bajo_stock']; ?>
                        </td>

                        <td>
                            <?php if ($bajoStock): ?>
                                ⚠ Bajo stock
                            <?php else: ?>
                                ✔ Normal
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($row['id_proveedor'] ?? null): $proveedorNombre = getProveedorNombre($conn, $row['id_proveedor']); ?>
                                <?= htmlspecialchars($proveedorNombre) ?>
                            <?php else: ?>
                                Sin proveedor asignado
                            <?php endif; ?>
                        </td>
                <td><a href="eliminar_insumo.php?id=<?= $row['id_insumo'] ?>" title="Eliminar insumo" id="eliminar-insumo">❌</a>
                <a href="modificar_insumo.php?id=<?= $row['id_insumo'] ?>" title="Modificar insumo">✏️</a>
            </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="7">
                        No hay insumos registrados
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

            </section>
<section id="volver_s">
        <a href="gestion_insumos.php" class="btn-volver-admin">Volver a Gestión de insumos</a>
    </section>
</main>

<?php
include('../footer.php');
?>

</body>
<script>
    const eliminarLinks = document.querySelectorAll('#eliminar-insumo');
    eliminarLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            if (!confirm('¿Estás seguro de que deseas eliminar este insumo?')) {
                event.preventDefault();
            }
        });
    });
</script>
</html>