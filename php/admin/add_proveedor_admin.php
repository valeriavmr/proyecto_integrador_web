<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar proveedor</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>

<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include_once __DIR__ . '\..\..\config.php';
    require_once(BASE_PATH . '/php/admin/auth.php');
    include_once(__DIR__ . '/../includes/sidebar.php');
?>
<main>
<fieldset>

    <h2>Agregar nuevo proveedor</h2>

    <form action="crud/insert_proveedor.php" method="POST">

        <input
            type="text"
            name="nombre"
            placeholder="Nombre del proveedor"
            required
        ><br>

        <input
            type="text"
            name="cuit"
            placeholder="CUIT"
        ><br>

        <input
            type="text"
            name="telefono"
            placeholder="Teléfono"
        ><br>

        <input
            type="email"
            name="correo"
            placeholder="Correo electrónico"
        ><br>

        <input
            type="text"
            name="direccion"
            placeholder="Dirección"
        ><br>

        <input
            type="submit"
            value="Agregar proveedor"
            id="add_proveedor_btn"
        >

    </form>

    <?php if(isset($_GET['error'])): ?>
        <p style="color:red;">
            <?= htmlspecialchars($_GET['error']) ?>
        </p>
    <?php endif; ?>

</fieldset>

<section id="back_section">
    <a href="proveedores_admin.php" class="btn-volver-admin">
        Volver a Gestión de proveedores
    </a>
</section>

</main>

<?php include('../footer.php'); ?>

</body>
</html>