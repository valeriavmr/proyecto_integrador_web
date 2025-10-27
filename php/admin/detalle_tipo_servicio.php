<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de tipo de servicio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="stylesheet" href="../../css/detalle_turno.css?v=<?= time() ?>">
</head>
<body>
    <?php 
    require_once('auth.php');
    include('header_admin.php'); 
    
    $id_tipo_servicio = $_GET['id_tipo_servicio'] ?? null;
    if ($id_tipo_servicio) {
        require('../crud/conexion.php');
        include_once('../crud/consultas_varias.php');
        $tipo_servicio = obtenerTipoDeServicioPorId($conn, $id_tipo_servicio);
        if (!$tipo_servicio) {
            echo "<p>Tipo de servicio no encontrado.</p>";
        }
    } else {
        echo "<p>ID de tipo de servicio no proporcionado.</p>";
    }
    ?>
<main>
    <?php if (isset($tipo_servicio) && $tipo_servicio): ?>
    <section class="detalle_tipo_servicio">
    <h2>Detalle del Tipo de Servicio</h2>
    <p><strong>Tipo de Servicio:</strong> <?= htmlspecialchars($tipo_servicio['tipo_de_servicio']) ?></p>
    <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($tipo_servicio['descripcion'])) ?></p>
    <p><strong>Precio:</strong> $<?= number_format($tipo_servicio['precio_servicio'], 2) ?></p>
    <p><strong>Imagen del Tipo de Servicio:</strong></p>
    <img src="<?= obtenerRutaImagenTipoServicio($conn, $tipo_servicio['id_tipo_servicio'], "proyecto_adiestramiento_tahito") ?>" alt="">
    <a class="editar_turno_btn" href="editar_tipo_servicio.php?id_tipo_servicio=<?= $tipo_servicio['id_tipo_servicio'] ?>">Editar Tipo de Servicio</a>
    <a class="eliminar_turno_btn" href="crud/eliminar_tipo_servicio.php?id_tipo_servicio=<?= $tipo_servicio['id_tipo_servicio'] ?>">Eliminar Tipo de Servicio</a>
    </section>
    <?php endif; ?>
    <section id="volver_s">
        <a href="servicios_admin.php">Volver a Administración de servicios</a>
    </section>
</main>
<?php include('../footer.php'); ?>
</body>
<script>
    //Lanzo un alert para preguntar si desea eliminar el tipo de servicio
    const eliminarBtn = document.querySelector('.eliminar_turno_btn');
    eliminarBtn.addEventListener('click', function(event) {
        const confirmar = confirm('¿Estás seguro de que deseas eliminar este tipo de servicio? Esta acción no se puede deshacer.');
        if (!confirmar) {
            event.preventDefault();
        }
    });
</script>
</html>