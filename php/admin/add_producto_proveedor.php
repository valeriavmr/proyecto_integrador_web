<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar producto a proveedor</title>

    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>

<body>

    <?php
    require_once dirname(__DIR__, 2) . '/config.php';
    require_once('auth.php');
    include_once(BASE_PATH . '/php/includes/sidebar.php');
    require_once('../crud/conexion.php');

    $id_proveedor = $_GET['id_proveedor'] ?? null;
    $proveedor = null;

    if ($id_proveedor) {
        $sql = "
        SELECT id_proveedor, nombre
        FROM proveedores
        WHERE id_proveedor = ?
        LIMIT 1
    ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_proveedor);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $proveedor = $resultado->fetch_assoc();

        $stmt->close();
    }
    ?>

    <main>
        <fieldset>
            <h2>Agregar producto a proveedor</h2>

            <?php if ($proveedor): ?>

                <p>
                    Proveedor:
                    <strong><?= htmlspecialchars($proveedor['nombre']) ?></strong>
                </p>

                <form action="crud/insert_producto_proveedor.php" method="POST">

                    <input
                        type="hidden"
                        name="id_proveedor"
                        value="<?= htmlspecialchars($proveedor['id_proveedor']) ?>">

                    <label for="nombre_producto">Nombre del producto:</label>
                    <input
                        type="text"
                        name="nombre_producto"
                        id="nombre_producto"
                        required>

                    <label for="descripcion_producto">Descripción:</label>
                    <textarea
                        name="descripcion_producto"
                        id="descripcion_producto"
                        rows="4"
                        placeholder="Descripción del producto"></textarea>

                    <label for="precio_unitario">Precio unitario:</label>
                    <input
                        type="number"
                        name="precio_unitario"
                        id="precio_unitario"
                        step="0.01"
                        min="0"
                        required>

                    <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo" required>
                        <option value="Otro" selected>Otro</option>
                        <option value="Vacuna">Vacuna</option>
                        <option value="Medicamento">Medicamento</option>
                    </select>

                    <label for="activo">Estado:</label>
                    <select name="activo" id="activo" required>
                        <option value="1" selected>Activo</option>
                        <option value="0">Inactivo</option>
                    </select>

                    <input
                        type="submit"
                        value="Agregar producto"
                        id="add_producto_btn">

                </form>

            <?php else: ?>

                <p>Proveedor no encontrado.</p>

            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>
        </fieldset>

        <section id="back_section">
            <?php if ($proveedor): ?>
                <a
                    href="detalle_proveedor.php?id_proveedor=<?= htmlspecialchars($proveedor['id_proveedor']) ?>"
                    class="btn-volver-admin">
                    Volver al detalle del proveedor
                </a>
            <?php else: ?>
                <a href="tabla_proveedores.php" class="btn-volver-admin">
                    Volver a la lista de proveedores
                </a>
            <?php endif; ?>
        </section>
    </main>

    <?php include('../footer.php'); ?>

</body>

</html>