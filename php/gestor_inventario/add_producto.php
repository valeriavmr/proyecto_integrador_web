<?php
require_once __DIR__ . '\..\..\config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    require_once(BASE_PATH . '/php/crud/conexion.php');
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
    require_once(BASE_PATH . '/php/crud/consultas_varias.php');
    ?>
    <main>
        <h1>Agregar Producto</h1>
        <fieldset>
            <form action="../crud/insert_producto.php" method="POST" id="form_add_producto" enctype="multipart/form-data">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="tipo">Tipo de Producto:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="Vacuna">Vacuna</option>
                <option value="Medicamento">Medicamento</option>
                <option value="Otro">Otro</option>
            </select>

            <label for="precio_unitario">Precio Unitario:</label>
            <input type="number" id="precio_unitario" name="precio_unitario" step="0.01" min="0" required>

            <label for="param_bajo_stock">Cantidad mínima aceptable:</label>
            <input type="number" id="param_bajo_stock" name="param_bajo_stock" min="0" required>

            <label for="proveedor">Proveedor:</label>
            <select id="proveedor" name="id_proveedor">
                <option value="">Seleccione un proveedor</option>
                <?php
                // Obtener proveedores desde la base de datos
                $proveedores = getProveedores($conn);
                foreach ($proveedores as $proveedor) {
                    echo "<option value=\"{$proveedor['id_proveedor']}\">" . htmlspecialchars($proveedor['nombre']) . "</option>";
                }
                ?>

            <label for="imagen">Imagen del producto:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">

            <input type="submit" id="add_producto_btn" value="Agregar Producto">
        </form>
        </fieldset>
        <section id="back_section">
        <a href="gestion_productos.php" class="btn-volver-admin">Volver a Gestión de productos</a>
        </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>