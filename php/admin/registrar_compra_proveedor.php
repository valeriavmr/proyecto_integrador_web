<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar compra a proveedor</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    $rol = $_SESSION['rol'];
    if ($rol == 'admin') {
        include_once(BASE_PATH . '/php/admin/header_admin.php');
    } elseif ($rol == 'gestor') {
        include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
    } else {
        header('Location: ' . BASE_URL . '/php/login.php');
        exit();
    }
require('../crud/conexion.php');

$proveedores = [];
$id_proveedor_seleccionado = $_POST['id_proveedor'] ?? '';
$sql_proveedores = "SELECT id_proveedor, nombre FROM proveedores WHERE activo = 1 ORDER BY nombre ASC";
$result_proveedores = $conn->query($sql_proveedores);

while ($row = $result_proveedores->fetch_assoc()) {
    $proveedores[] = $row;
}

$productos = [];
$sql_productos = "SELECT id_producto, nombre_producto, precio_unitario FROM productos WHERE activo = 1 ORDER BY nombre_producto ASC";
$result_productos = $conn->query($sql_productos);

while ($row = $result_productos->fetch_assoc()) {
    $productos[] = $row;
}
?>

<main>
    <?php

$insumos = [];

$sql_insumos = "
SELECT
    id_insumo,
    nombre_insumo,
    costo_unidad
FROM insumo
ORDER BY nombre_insumo
";

$result_insumos = $conn->query($sql_insumos);

while ($row = $result_insumos->fetch_assoc()) {
    $insumos[] = $row;
}
?>
    <fieldset>
        <h2>Registrar compra a proveedor</h2>

        <form method="POST">

            <label for="id_proveedor">
                Proveedor:
            </label>

            <select
                name="id_proveedor"
                id="id_proveedor"
                required
            >
                <option value="">
                    Seleccione un proveedor
                </option>

                <?php foreach ($proveedores as $proveedor): ?>

                    <option
                        value="<?= $proveedor['id_proveedor'] ?>"
                        <?= ($id_proveedor_seleccionado == $proveedor['id_proveedor']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($proveedor['nombre']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

            <input
                type="submit"
                value="Cargar artículos"
            >

        </form>

        <hr>
        <?php if (!empty($id_proveedor_seleccionado)): ?>
        <form action="crud/insert_compra_proveedor.php" method="POST">

         <input
            type="hidden"
            name="id_proveedor"
            value="<?= $id_proveedor_seleccionado ?>"
        >
            <?php
                $id_proveedor_seleccionado = $_POST['id_proveedor'] ?? '';

                $productos = [];
                $insumos = [];

                if (!empty($id_proveedor_seleccionado)) {

                    $stmt = $conn->prepare("
                        SELECT
                            id_producto,
                            nombre_producto,
                            precio_unitario
                        FROM productos
                        WHERE activo = 1
                        AND id_proveedor = ?
                        ORDER BY nombre_producto ASC
                    ");

                    $stmt->bind_param("i", $id_proveedor_seleccionado);
                    $stmt->execute();

                    $productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                    $stmt = $conn->prepare("
                        SELECT
                            id_insumo,
                            nombre_insumo,
                            costo_unidad
                        FROM insumo
                        WHERE id_proveedor = ?
                        ORDER BY nombre_insumo ASC
                    ");

                    $stmt->bind_param("i", $id_proveedor_seleccionado);
                    $stmt->execute();

                    $insumos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                }
            ?>
            <label for="tipo_item">Tipo:</label>

            <select name="tipo_item" id="tipo_item" required>
                <option value="" selected disabled>
                    Seleccione una opción
                </option>

                <option value="producto">Producto</option>
                <option value="insumo">Insumo</option>
            </select>
            <div id="contenedor_productos" style="display:none;">
                <label for="id_producto">Producto:</label>
                <select name="id_producto" id="id_producto">
                    <option value="" selected>
                        Seleccione un producto
                    </option>
                    <?php foreach ($productos as $producto): ?>

                        <option
                            value="<?= $producto['id_producto'] ?>"
                            data-precio="<?= $producto['precio_unitario'] ?>"
                        >
                            <?= htmlspecialchars($producto['nombre_producto']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="contenedor_insumos" style="display:none;">
                <label for="id_insumo">Insumo:</label>
                <select name="id_insumo" id="id_insumo">
                    <option value="" selected>
                        Seleccione un insumo
                    </option>
                    <?php foreach ($insumos as $insumo): ?>
                        <option
                            value="<?= $insumo['id_insumo'] ?>"
                            data-precio="<?= $insumo['costo_unidad'] ?>"
                        >
                            <?= htmlspecialchars($insumo['nombre_insumo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" value="1" required>

            <label for="precio_unitario">Precio unitario:</label>
            <input type="number" name="precio_unitario" id="precio_unitario" step="0.01" min="0" required>

            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" id="observaciones" placeholder="Observaciones de la compra"></textarea>

            <input type="submit" value="Registrar compra" id="add_proveedor_btn">
        </form>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
    </fieldset>

    <section id="back_section">
        <a href="proveedores_admin.php" class="btn-volver-admin">Volver a Gestión de proveedores</a>
    </section>
</main>

<?php include('../footer.php'); ?>

<script>

const tipoItem = document.getElementById('tipo_item');
const contProductos = document.getElementById('contenedor_productos');
const contInsumos = document.getElementById('contenedor_insumos');

tipoItem.addEventListener('change', function() {

    if (this.value === 'producto') {

        contProductos.style.display = 'block';
        contInsumos.style.display = 'none';

    } else {

        contProductos.style.display = 'none';
        contInsumos.style.display = 'block';
    }
});

document.getElementById('id_producto').addEventListener('change', function() {

    const precio =
        this.options[this.selectedIndex]
            .getAttribute('data-precio');

    document.getElementById('precio_unitario').value = precio;
});

document.getElementById('id_insumo').addEventListener('change', function() {

    const precio =
        this.options[this.selectedIndex]
            .getAttribute('data-precio');

    document.getElementById('precio_unitario').value = precio;
});

</script>

</body>
</html>