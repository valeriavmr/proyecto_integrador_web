<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto</title>

    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>

<body>

    <?php
    require_once dirname(__DIR__, 2) . '/config.php';
    require_once('auth.php');
    include_once(BASE_PATH . '/php/includes/sidebar.php');
    require_once('../crud/conexion.php');

    $id_producto = $_GET['id_producto'] ?? null;
    $id_proveedor = $_GET['id_proveedor'] ?? null;

    $producto = null;

    if ($id_producto) {

        $sql = "
        SELECT *
        FROM productos
        WHERE id_producto = ?
        LIMIT 1
    ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();

        $stmt->close();
    }
    ?>

    <main>

        <fieldset>

            <h2>Editar producto</h2>

            <?php if ($producto): ?>

                <form action="crud/update_producto_proveedor.php" method="POST">

                    <input
                        type="hidden"
                        name="id_producto"
                        value="<?= htmlspecialchars($producto['id_producto']) ?>">

                    <input
                        type="hidden"
                        name="id_proveedor"
                        value="<?= htmlspecialchars($id_proveedor) ?>">

                    <label>Nombre del producto</label>
                    <input
                        type="text"
                        name="nombre_producto"
                        value="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                        required>

                    <label>Descripción</label>
                    <textarea
                        name="descripcion_producto"
                        rows="4"><?= htmlspecialchars($producto['descripcion_producto']) ?></textarea>

                    <label>Precio unitario</label>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="precio_unitario"
                        value="<?= htmlspecialchars($producto['precio_unitario']) ?>"
                        required>

                    <label>Tipo</label>
                    <select name="tipo">

                        <option
                            value="Otro"
                            <?= $producto['tipo'] == 'Otro' ? 'selected' : '' ?>>
                            Otro
                        </option>

                        <option
                            value="Vacuna"
                            <?= $producto['tipo'] == 'Vacuna' ? 'selected' : '' ?>>
                            Vacuna
                        </option>

                        <option
                            value="Medicamento"
                            <?= $producto['tipo'] == 'Medicamento' ? 'selected' : '' ?>>
                            Medicamento
                        </option>

                    </select>

                    <label>Estado</label>
                    <select name="activo">

                        <option
                            value="1"
                            <?= $producto['activo'] ? 'selected' : '' ?>>
                            Activo
                        </option>

                        <option
                            value="0"
                            <?= !$producto['activo'] ? 'selected' : '' ?>>
                            Inactivo
                        </option>

                    </select>

                    <input
                        type="submit"
                        value="Guardar cambios">

                </form>

            <?php else: ?>

                <p>Producto no encontrado.</p>

            <?php endif; ?>

        </fieldset>

        <section id="back_section">

            <a
                href="detalle_proveedor.php?id_proveedor=<?= htmlspecialchars($id_proveedor) ?>"
                class="btn-volver-admin">
                Volver al proveedor
            </a>

        </section>

    </main>

    <?php include('../footer.php'); ?>

</body>

</html>