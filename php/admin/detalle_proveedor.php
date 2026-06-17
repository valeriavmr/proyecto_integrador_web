<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de proveedor</title>

    <link rel="stylesheet" href="../../css/tablas_admin.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>

<body>
    <?php
    require_once dirname(__DIR__, 2) . '/config.php';
    require_once('auth.php');
    include_once(BASE_PATH . '/php/includes/sidebar.php');
    require_once('../crud/conexion.php');

    $id_proveedor = $_GET['id_proveedor'] ?? null;
    $estado = $_GET['estado'] ?? 'activos';

    $proveedor = null;
    $productos = [];

    if ($id_proveedor) {
        $sql = "SELECT * FROM proveedores WHERE id_proveedor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedor);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $proveedor = $resultado->fetch_assoc();
        $stmt->close();

        if ($proveedor) {
            $sql_productos = "
            SELECT
                id_producto,
                nombre_producto,
                descripcion_producto,
                precio_unitario,
                tipo,
                activo
            FROM productos
            WHERE id_proveedor = ?
        ";

            if ($estado === 'activos') {
                $sql_productos .= " AND activo = 1 ";
            }

            $sql_productos .= " ORDER BY nombre_producto ASC ";

            $stmt_productos = $conn->prepare($sql_productos);
            $stmt_productos->bind_param("i", $id_proveedor);
            $stmt_productos->execute();
            $resultado_productos = $stmt_productos->get_result();

            while ($row = $resultado_productos->fetch_assoc()) {
                $productos[] = $row;
            }

            $stmt_productos->close();
        }
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

                <a href="add_producto_proveedor.php?id_proveedor=<?= htmlspecialchars($proveedor['id_proveedor']) ?>" class="btn-volver-admin">
                    Agregar producto
                </a>

                <br><br>

                <h2>Productos asociados</h2>

                <form class="filtro-productos" method="GET">

                    <input
                        type="hidden"
                        name="id_proveedor"
                        value="<?= htmlspecialchars($id_proveedor) ?>">

                    <label for="estado">Mostrar:</label>

                    <select
                        name="estado"
                        id="estado"
                        class="input-filtro">
                        <option value="activos" <?= $estado === 'activos' ? 'selected' : '' ?>>
                            Solo activos
                        </option>

                        <option value="todos" <?= $estado === 'todos' ? 'selected' : '' ?>>
                            Todos
                        </option>
                    </select>

                    <button
                        type="submit"
                        class="btn-volver-admin">
                        Filtrar
                    </button>

                </form>

                <?php if (!empty($productos)): ?>

                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Descripción</th>
                                <th>Precio unitario</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($producto['id_producto']) ?></td>
                                    <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                                    <td><?= htmlspecialchars($producto['descripcion_producto'] ?? '') ?></td>
                                    <td>$<?= number_format($producto['precio_unitario'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($producto['tipo']) ?></td>
                                    <td><?= $producto['activo'] ? 'Activo' : 'Inactivo' ?></td>

                                    <td class="acciones">
                                        <a
                                            href="editar_producto_proveedor.php?id_producto=<?= htmlspecialchars($producto['id_producto']) ?>&id_proveedor=<?= htmlspecialchars($proveedor['id_proveedor']) ?>"
                                            class="edit_btn">
                                            <img src="../../recursos/edit_icon.png" alt="Editar">
                                        </a>

                                        <form
                                            method="POST"
                                            action="crud/eliminar_producto_proveedor.php"
                                            class="form_eliminar"
                                            style="display:inline;">
                                            <input type="hidden" name="id_producto" value="<?= htmlspecialchars($producto['id_producto']) ?>">
                                            <input type="hidden" name="id_proveedor" value="<?= htmlspecialchars($proveedor['id_proveedor']) ?>">

                                            <button type="submit" class="delete_btn">
                                                <img src="../../recursos/delete_icon.png" alt="Eliminar">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php else: ?>

                    <p>Este proveedor no tiene productos asociados para el filtro seleccionado.</p>

                <?php endif; ?>

            <?php else: ?>

                <p>Proveedor no encontrado.</p>

            <?php endif; ?>
        </section>

        <section id="volver_s">
            <a href="tabla_proveedores.php" class="btn-volver-admin">
                Volver a la lista de proveedores
            </a>
        </section>
    </main>

    <?php include('../footer.php'); ?>

    <script>
        document.querySelectorAll('.form_eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm("¿Está seguro de que desea desactivar este producto?")) {
                    e.preventDefault();
                }
            });
        });
    </script>

</body>

</html>