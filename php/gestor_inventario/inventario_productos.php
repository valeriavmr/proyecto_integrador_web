<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');

require_once('../crud/conexion.php');

// =======================
// 1. Filtro
// =======================

$filtro = $_GET['filtro'] ?? null;

// =======================
// 2. Query base
// =======================

$sql = "SELECT 
            i.id_producto_stock,
            p.nombre_producto,
            p.descripcion_producto,
            p.precio_unitario,
            p.imagen_producto,
            p.tipo,
            p.activo,
            i.cantidad_actual_producto,
            i.param_bajo_stock
        FROM inventario i
        JOIN productos p ON i.id_producto = p.id_producto";

// Aplicar filtro
if ($filtro === 'bajo_stock') {
    $sql .= " WHERE i.cantidad_actual_producto <= i.param_bajo_stock";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de inicio</title>
    <link rel="stylesheet" href="../../css/menu_gestor_inventario.css">
    <link rel="stylesheet" href="../../css/tablas_admin.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
<?php
    include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
    include_once(BASE_PATH . '/php/crud/consultas_varias.php');
?>
<main>
<section>
    <h1>Inventario de Productos</h1>

<?php if ($filtro === 'bajo_stock'): ?>
    <h2>⚠ Productos con bajo stock</h2>
    <a href="gestion_productos.php">Ver todos</a>
<?php endif; ?>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Estado</th>
            <th>Tipo</th>
            <th>Activo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

    <?php if ($result && $result->num_rows > 0): ?>
        
        <?php while ($row = $result->fetch_assoc()): ?>

            <?php
            $bajoStock = $row['cantidad_actual_producto'] <= $row['param_bajo_stock'];
            ?>

            <tr style="<?= $bajoStock ? 'background-color:#ffe5e5;' : '' ?>">

                <td>
                    <?php if ($row['imagen_producto']): ?>
                        <img src="<?= BASE_URL . '/uploads/productos/' . $row['imagen_producto']; ?>" width="60">
                    <?php else: ?>
                        Sin imagen
                    <?php endif; ?>
                </td>

                <td><?= $row['nombre_producto']; ?></td>
                <td><?= $row['descripcion_producto']; ?></td>
                <td>$<?= number_format($row['precio_unitario'], 2); ?></td>
                <td><?= $row['cantidad_actual_producto']; ?></td>

                <td>
                    <?php if ($bajoStock): ?>
                        ⚠ Bajo stock
                    <?php else: ?>
                        ✔ Normal
                    <?php endif; ?>
                </td>
                <td><?= $row['tipo']; ?></td>
                <td><?php if($row['activo']==1):?>
                    Sí
                    <?php else: ?>
                    No
                    <?php endif; ?>
                </td>
                <td><a href="#" title="Eliminar producto">❌</a><br>
                <a href="#" title="Modificar producto">✏️</a>
            </td>
            </tr>

        <?php endwhile; ?>

    <?php else: ?>
        <tr>
            <td colspan="6">No hay productos registrados</td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>
</section>
<section id="volver_s">
        <a href="gestion_productos.php" class="btn-volver-admin">Volver a Gestión de productos</a>
    </section>
</main>
</body>
</html>