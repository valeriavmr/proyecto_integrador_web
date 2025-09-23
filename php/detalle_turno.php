<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del turno</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    
</head>
<body>
    <?php
    include('header_cliente.php');
    ?>
    <main>
        <?php
        require('crud/conexion.php');
        if (session_status() == PHP_SESSION_NONE) { 
            session_start(); 
        }
        $usuario = $_SESSION['username'];
        include_once('crud/consultas_varias.php');
        $id_servicio = $_GET['id_servicio'];
        $servicio = info_servicio($conn, $id_servicio);

        $nombre_mascota = obtenerNombreMascota($conn, $servicio['id_mascota']);
        $nombre_trabajador = buscarNombreCompletoPorId($conn, $servicio['id_trabajador']);

        if ($servicio) {
            echo"<article class='servicio_detalle'>
            <h2>Detalles del turno</h2>
            <h3>Tipo de servicio: " . $servicio['tipo_de_servicio'] . "</h3>
            <p>Mascota: " . $nombre_mascota . "</p>
            <p>Trabajador asignado: " . $nombre_trabajador . "</p>
            <p>Fecha y Hora: " . $servicio['horario'] . "</p>
            <p>Comentarios adicionales: " . $servicio['comentarios'] . "</p>
            <button class='cancelar_turno_btn'><a href='crud/eliminar_servicio.php?id_servicio=" . $servicio['id_servicio'] . "'>Cancelar turno</a></button>
            <a href='main_cliente.php'>Volver al inicio</a>
            </article>";
        } else {
            echo "<p>No se encontró el servicio solicitado.</p>";
        }
        $conn->close();
        ?>
    </main>
    <?php
    include('footer.php');
    ?>
</body>
<link rel="stylesheet" href="../css/footer_styles.css?v=<?= time() ?>">
<link rel="stylesheet" href="../css/servicios_cliente.css?v=<?= time() ?>">
<link rel="stylesheet" href="../css/detalle_turno.css?v=<?= time() ?>">
</html>