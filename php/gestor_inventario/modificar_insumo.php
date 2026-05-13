<?php
require_once __DIR__ . '\..\..\config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
require_once('../crud/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Insumo</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
    <?php
    include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php');
    $id_insumo = $_GET['id'] ?? null;
    if (!$id_insumo) {
        echo "<p>ID de insumo no proporcionado.</p>";
        exit;
    }

    $sql = "SELECT 
                i.*,
                ii.cantidad_actual,
                ii.param_bajo_stock
            FROM insumo i
            JOIN inventario_insumo ii ON i.id_insumo = ii.id_insumo
            WHERE i.id_insumo = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_insumo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Insumo no encontrado.</p>";
        exit;
    }

    ?>
    <main>
        <h1>Modificar Insumo</h1>
        <fieldset>
            <form action="../crud/update_insumo.php" method="POST" id="form_add_insumo" enctype="multipart/form-data">
                <input type="hidden" name="id_insumo" value="<?= htmlspecialchars($id_insumo) ?>">
                <label for="nombre">Nombre del Insumo:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($row['nombre_insumo']) ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($row['descripcion_insumo']) ?></textarea>

            <label for="tipo">Tipo de Insumo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Seleccione un tipo</option>
                <option value="equipamiento" <?= $row['tipo_insumo'] === 'equipamiento' ? 'selected' : '' ?>>Equipamiento</option>
                <option value="medicinas" <?= $row['tipo_insumo'] === 'medicinas' ? 'selected' : '' ?>>Medicinas</option>
                <option value="consumibles" <?= $row['tipo_insumo'] === 'consumibles' ? 'selected' : '' ?>>Consumibles</option>
                <option value="cuidado_y_aseo" <?= $row['tipo_insumo'] === 'cuidado_y_aseo' ? 'selected' : '' ?>>Cuidado y Aseo</option>
                <option value="otro" <?= $row['tipo_insumo'] === 'otro' ? 'selected' : '' ?>>Otro</option>
            </select>

            <label for="costo_unidad">Costo Unitario:</label>
            <input type="number" id="costo_unidad" name="costo_unidad" step="0.01" min="0" value="<?= $row['costo_unidad'] ?>" required>

            <label for="param_bajo_stock">Cantidad mínima aceptable:</label>
            <input type="number" id="param_bajo_stock" name="param_bajo_stock" min="0" value="<?= $row['param_bajo_stock'] ?>" required>

            <label for="proveedor">Proveedor:</label>
            <select id="proveedor" name="proveedor">
                <option value="">Sin proveedor</option>
                <?php
                $proveedores = getProveedores($conn);
                foreach ($proveedores as $proveedor) {
                    $selected = ($row['id_proveedor'] == $proveedor['id_proveedor']) ? 'selected' : '';
                    echo "<option value=\"{$proveedor['id_proveedor']}\" $selected>" . htmlspecialchars($proveedor['nombre_proveedor']) . "</option>";
                }
                ?>
            </select>
            <input type="submit" id="add_insumo_btn" value="Modificar Insumo">
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