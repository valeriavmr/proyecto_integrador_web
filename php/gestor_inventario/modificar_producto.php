<?php
require_once __DIR__ . '/../../config.php';
require_once(BASE_PATH . '/php/admin/auth.php');
require_once('../crud/conexion.php');

// =========================
// Validar ID
// =========================

$id_producto = $_GET['id'] ?? null;

if (!$id_producto) {
    die("ID de producto no válido.");
}

// =========================
// Obtener producto
// =========================

$sql = "SELECT 
            p.*,
            i.param_bajo_stock
        FROM productos p
        JOIN inventario i 
            ON p.id_producto = i.id_producto
        WHERE p.id_producto = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();

$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    die("Producto no encontrado.");
}

// =========================
// Obtener proveedores
// =========================

$sqlProv = "SELECT 
                id_proveedor,
                nombre
            FROM proveedores
            WHERE activo = 1";

$proveedores = $conn->query($sqlProv);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>

    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>

<body>

<?php include_once(BASE_PATH . '/php/gestor_inventario/header_gi.php'); ?>

<main>

    <h1>Modificar Producto</h1>

    <fieldset>

        <form 
            action="../crud/update_producto.php" 
            method="POST"
            id="form_add_producto"
            enctype="multipart/form-data"
        >

            <!-- ID oculto -->
            <input 
                type="hidden"
                name="id_producto"
                value="<?= $producto['id_producto']; ?>"
            >

            <!-- Nombre -->
            <label for="nombre">Nombre del Producto:</label>

            <input 
                type="text"
                id="nombre"
                name="nombre"
                required
                value="<?= htmlspecialchars($producto['nombre_producto']); ?>"
            >

            <!-- Descripción -->
            <label for="descripcion">Descripción:</label>

            <textarea 
                id="descripcion"
                name="descripcion"
                required
            ><?= htmlspecialchars($producto['descripcion_producto']); ?></textarea>

            <!-- Tipo -->
            <label for="tipo">Tipo de Producto:</label>

            <select id="tipo" name="tipo" required>

                <option value="">Seleccione un tipo</option>

                <option 
                    value="Vacuna"
                    <?= $producto['tipo'] === 'Vacuna' ? 'selected' : ''; ?>
                >
                    Vacuna
                </option>

                <option 
                    value="Medicamento"
                    <?= $producto['tipo'] === 'Medicamento' ? 'selected' : ''; ?>
                >
                    Medicamento
                </option>

                <option 
                    value="Otro"
                    <?= $producto['tipo'] === 'Otro' ? 'selected' : ''; ?>
                >
                    Otro
                </option>

            </select>

            <!-- Precio -->
            <label for="precio_unitario">Precio Unitario:</label>

            <input 
                type="number"
                id="precio_unitario"
                name="precio_unitario"
                step="0.01"
                min="0"
                required
                value="<?= htmlspecialchars($producto['precio_unitario']); ?>"
            >

            <!-- Bajo stock -->
            <label for="param_bajo_stock">
                Cantidad mínima aceptable:
            </label>

            <input 
                type="number"
                id="param_bajo_stock"
                name="param_bajo_stock"
                min="0"
                required
                value="<?= htmlspecialchars($producto['param_bajo_stock']); ?>"
            >

            <!-- Activo -->
            <label for="activo">¿El producto está activo?</label>
            <select id="activo" name="activo" required>

                <option value="">Seleccione una opción</option>

                <option 
                    value="1"
                    <?= $producto['activo'] == 1 ? 'selected' : ''; ?>
                >
                    Sí
                </option>

                <option 
                    value="0"
                    <?= $producto['activo'] == 0 ? 'selected' : ''; ?>
                >
                    No
                </option>

            </select>

            <!-- Proveedor -->
            <label for="id_proveedor">Proveedor:</label>

            <select id="id_proveedor" name="id_proveedor">

                <option value="">
                    Sin proveedor asignado
                </option>

                <?php while($prov = $proveedores->fetch_assoc()): ?>

                    <option 
                        value="<?= $prov['id_proveedor']; ?>"
                        <?= $producto['id_proveedor'] == $prov['id_proveedor']
                            ? 'selected'
                            : ''; ?>
                    >
                        <?= htmlspecialchars($prov['nombre']); ?>
                    </option>

                <?php endwhile; ?>

            </select>

            <!-- Imagen actual -->
            <label for="imagen">
                Imagen del producto:
            </label>

            <?php if (!empty($producto['imagen_producto'])): ?>

                <img 
                    src="<?= BASE_URL . '/uploads/productos/' . $producto['imagen_producto']; ?>"
                    width="120"
                    alt="Imagen actual del producto"
                >

            <?php endif; ?>

            <!-- Nueva imagen -->
            <input 
                type="file"
                id="imagen"
                name="imagen"
                accept="image/*"
            >

            <!-- Botón -->
            <input 
                type="submit"
                id="add_producto_btn"
                value="Modificar Producto"
            >

        </form>

    </fieldset>

    <section id="back_section">

        <a 
            href="inventario_productos.php"
            class="btn-volver-admin"
        >
            Volver al inventario
        </a>

    </section>

</main>

<?php include('../footer.php'); ?>

</body>
</html>