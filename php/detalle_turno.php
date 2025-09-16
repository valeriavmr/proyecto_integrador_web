<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del turno</title>
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
            <button class='cancelar_turno_btn'><a href='crud/eliminar_servicio.php?id_servicio=" . $servicio['id_servicio'] . "'>Cancelar turno</a></button><br>
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
</html>