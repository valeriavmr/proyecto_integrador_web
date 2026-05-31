<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar proveedor</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png">
</head>
<body>
<?php
require_once('auth.php');
include('header_admin.php');
require_once('../crud/conexion.php');

$id_proveedor = $_GET['id_proveedor'] ?? null;
$proveedor = null;

if ($id_proveedor) {
    $sql = "SELECT * FROM proveedores WHERE id_proveedor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_proveedor);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $proveedor = $resultado->fetch_assoc();
}
?>

<main>
    <h1>Editar proveedor</h1>

    <?php if ($proveedor): ?>
        <fieldset>
            <form action="crud/update_proveedor.php" method="POST">

                <input type="hidden" name="id_proveedor" value="<?= htmlspecialchars($proveedor['id_proveedor']) ?>">

                <label for="nombre">Nombre del proveedor:</label><br>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= htmlspecialchars($proveedor['nombre']) ?>"
                    required
                ><br>

                <label for="cuit">CUIT:</label><br>
                <input
                    type="text"
                    id="cuit"
                    name="cuit"
                    value="<?= htmlspecialchars($proveedor['cuit'] ?? '') ?>"
                ><br>

                <label for="telefono">Teléfono:</label><br>
                <input
                    type="text"
                    id="telefono"
                    name="telefono"
                    value="<?= htmlspecialchars($proveedor['telefono'] ?? '') ?>"
                ><br>

                <label for="correo">Correo electrónico:</label><br>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    value="<?= htmlspecialchars($proveedor['correo'] ?? '') ?>"
                ><br>

                <label for="direccion">Dirección:</label><br>
                <input
                    type="text"
                    id="direccion"
                    name="direccion"
                    value="<?= htmlspecialchars($proveedor['direccion'] ?? '') ?>"
                ><br>

                <label for="activo">Estado:</label><br>
                <select name="activo" id="activo" required>
                    <option value="1" <?= $proveedor['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $proveedor['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                </select><br>

                <button type="submit" class="act_btn">Actualizar proveedor</button>

                <?php if (isset($_GET['error'])): ?>
                    <p style="color:red;"><?= htmlspecialchars($_GET['error']) ?></p>
                <?php endif; ?>

            </form>
        </fieldset>
    <?php else: ?>
        <p>Proveedor no encontrado.</p>
    <?php endif; ?>

    <section id="volver_s">
        <a href="tabla_proveedores.php" class="btn-volver-admin">Volver a la lista de proveedores</a>
    </section>
</main>

<?php include('../footer.php'); ?>
</body>
</html>