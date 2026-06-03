<?php
require_once __DIR__ . '\..\..\config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Insumo</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    include_once(__DIR__ . '/../includes/sidebar.php');
    require_once(BASE_PATH . '/php/crud/conexion.php');
    require_once(BASE_PATH . '/php/crud/consultas_varias.php');
    ?>
    <main>
        <h1>Agregar Insumo</h1>
        <fieldset>
            <form action="../crud/insert_insumo.php" method="POST" id="form_add_insumo" enctype="multipart/form-data">
                <label for="nombre">Nombre del Insumo:</label>
                <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>

            <label for="tipo">Tipo de Insumo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="equipamiento">Equipamiento</option>
                <option value="medicinas">Medicinas</option>
                <option value="consumibles">Consumibles</option>
                <option value="cuidado_y_aseo">Cuidado y Aseo</option>
                <option value="otro">Otro</option>
            </select>

            <label for="costo_unidad">Costo Unitario:</label>
            <input type="number" id="costo_unidad" name="costo_unidad" step="0.01" min="0" required>

            <label for="param_bajo_stock">Cantidad mínima aceptable:</label>
            <input type="number" id="param_bajo_stock" name="param_bajo_stock" min="0" required>

            <label for="proveedor">Proveedor:</label>
            <select id="proveedor" name="id_proveedor">
                <option value="">Seleccione un proveedor</option>
                <?php
                $proveedores = getProveedores($conn);
                foreach ($proveedores as $proveedor) {
                    echo "<option value=\"{$proveedor['id_proveedor']}\">" . htmlspecialchars($proveedor['nombre_proveedor']) . "</option>";
                }
                ?>
            </select>

            <input type="submit" id="add_insumo_btn" value="Agregar Insumo">
        </form>
        </fieldset>
        <section id="back_section">
        <a href="gestion_insumos.php" class="btn-volver-admin">Volver a Gestión de insumos</a>
    </section>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
</html>