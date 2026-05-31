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

require_once('auth.php');
include('header_admin.php');
require('../crud/conexion.php');

$proveedores = [];
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
    <fieldset>
        <h2>Registrar compra a proveedor</h2>

        <form action="crud/insert_compra_proveedor.php" method="POST">

            <label for="id_proveedor">Proveedor:</label>
            <select name="id_proveedor" id="id_proveedor" required>
                <option value="" disabled selected>Seleccione un proveedor</option>
                <?php foreach ($proveedores as $proveedor): ?>
                    <option value="<?= htmlspecialchars($proveedor['id_proveedor']) ?>">
                        <?= htmlspecialchars($proveedor['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_producto">Producto:</label>
            <select name="id_producto" id="id_producto" required>
                <option value="" disabled selected>Seleccione un producto</option>
                <?php foreach ($productos as $producto): ?>
                    <option
                        value="<?= htmlspecialchars($producto['id_producto']) ?>"
                        data-precio="<?= htmlspecialchars($producto['precio_unitario']) ?>"
                    >
                        <?= htmlspecialchars($producto['nombre_producto']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" min="1" value="1" required>

            <label for="precio_unitario">Precio unitario:</label>
            <input type="number" name="precio_unitario" id="precio_unitario" step="0.01" min="0" required>

            <label for="observaciones">Observaciones:</label>
            <textarea name="observaciones" id="observaciones" placeholder="Observaciones de la compra"></textarea>

            <input type="submit" value="Registrar compra" id="add_proveedor_btn">
        </form>

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
document.getElementById('id_producto').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const precio = selected.getAttribute('data-precio');

    if (precio) {
        document.getElementById('precio_unitario').value = precio;
    }
});
</script>

</body>
</html>