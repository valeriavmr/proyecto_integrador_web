<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tipo de Servicio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>
    <?php
    // Incluir el archivo de conexión a la base de datos
    require('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');
    include('header_admin.php');

    # Obtener el ID del tipo de servicio desde la URL
    $id_tipo_servicio = $_GET['id_tipo_servicio'] ?? null;
    if ($id_tipo_servicio) {
        $tipo_servicio = obtenerTipoDeServicioPorId($conn, $id_tipo_servicio);
        if (!$tipo_servicio) {
            echo "<p>Tipo de servicio no encontrado.</p>";
        }
    } else {
        echo "<p>ID de tipo de servicio no proporcionado.</p>";
    }
    ?>
    <main>
        <h1>Editar Tipo de Servicio</h1>
        <?php if (isset($tipo_servicio) && $tipo_servicio): ?>
        <fieldset>
            <form action="crud/update_tipo_servicio.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_tipo_servicio" value="<?php echo htmlspecialchars($tipo_servicio['id_tipo_servicio']); ?>">
            <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($tipo_servicio['imagen_servicio']); ?>">
                <label for="nombre_tipo_servicio">Nombre del Tipo de Servicio:</label><br>
                <input type="text" id="nombre_tipo_servicio" name="nombre_tipo_servicio" value="<?php echo htmlspecialchars($tipo_servicio['tipo_de_servicio']); ?>" required><br>
                <label for="descripcion_tipo_servicio">Descripción del Tipo de Servicio:</label><br>
                <textarea id="descripcion_tipo_servicio" name="descripcion_tipo_servicio" required><?php echo htmlspecialchars($tipo_servicio['descripcion']); ?></textarea><br>
                <label for="precio_tipo_servicio">Precio del Tipo de Servicio:</label><br>
                <input type="number" step="0.01" id="precio_tipo_servicio" name="precio_tipo_servicio" value="<?php echo htmlspecialchars($tipo_servicio['precio_servicio']); ?>" required><br>
                <label for="imagen_servicio">Imagen del tipo de servicio:</label><br>
                <input type="file" id="imagen_servicio" name="imagen_servicio" accept="image/*"><br>
                <img src="<?php echo obtenerRutaImagenTipoServicio($conn,$tipo_servicio['id_tipo_servicio'], "proyecto_adiestramiento_tahito"); ?>" alt="Imagen del tipo de servicio" style="max-width: 200px;"><br>
                <button type="submit" class="act_btn">Actualizar Tipo de Servicio</button>
                <?php
                if (isset($_GET['error'])) {
                    echo "<p style='color: red;'>" . htmlspecialchars($_GET['error']) . "</p>";
                }
                ?>
        </form>
        </fieldset>
        <?php endif; ?>
        <section id="volver_s">
            <a href="servicios_admin.php">Volver a la administración de servicios</a>
        </section>
    </main>
    <?php include('../footer.php'); ?>
</body>
</html>