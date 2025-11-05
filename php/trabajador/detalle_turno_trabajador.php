<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del turno</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="stylesheet" href="../../css/toggle_switch.css?v=<?= time() ?>">
    
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) { 
            session_start(); 
        }

    include('header_trabajador.php');
    ?>
    <main>
        <?php
        require_once('../crud/conexion.php');
        $usuario = $_SESSION['username'];
        include_once('../crud/consultas_varias.php');
        $id_servicio = $_GET['id_servicio'];
        $servicio = info_servicio($conn, $id_servicio);

        $nombre_mascota = obtenerNombreMascota($conn, $servicio['id_mascota']);
        $nombre_trabajador = buscarNombreCompletoPorId($conn, $servicio['id_trabajador']);

        if ($servicio) {

            $pagado = $servicio['pagado'] == 1 ? true : false;

            echo"<article class='servicio_detalle'>
            <h2>Detalles del turno</h2>
            <h3>Tipo de servicio: " . $servicio['tipo_de_servicio'] . "</h3>
            <p>Mascota: " . $nombre_mascota . "</p>
            <p>Trabajador asignado: " . $nombre_trabajador . "</p>
            <p>Fecha y Hora: " . $servicio['horario'] . "</p>
            <p>Comentarios adicionales: " . $servicio['comentarios'] . "</p>
            <p>Monto: " . $servicio['monto'] . "</p>";
            if($pagado){
                echo "<p>Estado: Pagado</p>";
            }else{
                echo "<p>Pagado: No</p>";?>
                <p><strong>Marcar como pagado</strong></p>
                <form action="../admin/crud/update_servicio.php" method="post">
                    <input type="hidden" name="id_servicio" value="<?php echo htmlspecialchars($servicio['id_servicio']);?>">
                    <input type="hidden" name="mascota" value="<?php echo htmlspecialchars($servicio['id_mascota']);?>">
                    <input type="hidden" name="tipo_servicio" value="<?php echo htmlspecialchars($servicio['tipo_de_servicio']);?>">
                    <input type="hidden" name="trabajador" value="<?php echo htmlspecialchars($servicio['id_trabajador']);?>">
                    <input type="hidden" name="horario" value="<?php echo htmlspecialchars($servicio['horario']);?>">
                    <input type="hidden" name="detalles" value="<?php echo htmlspecialchars($servicio['comentarios']);?>">
                    <label class="switch" name="pagado">
                        <input type="checkbox" name="pagado" id="pagado"
                        class="pagado-toggle" value="1" 
                        onchange="this.form.submit()"
                        <?= $pagado ? 'checked' : '' ?>>
                        <span class="slider round"></span>
                    </label>
                </form>
            <?php
            }

            //Para que solo se puedan cancelar turnos futuros
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $horarioTurno = new DateTime($servicio['horario']);
            $now = new DateTime();
            if($horarioTurno >= $now){
            echo "<button class='cancelar_turno_btn'><a href='../crud/eliminar_servicio.php?id_servicio=" . $servicio['id_servicio'] . "'>Cancelar turno</a></button>";}
            echo "<a href='main_trabajador.php'>Volver al inicio</a>";
            echo "</article>";
        } else {
            echo "<p>No se encontró el servicio solicitado.</p>";
        }
        ?>
    </main>
    <?php
    include('../footer.php');
    ?>
</body>
<link rel="stylesheet" href="../../css/servicios_cliente.css?v=<?= time() ?>">
<link rel="stylesheet" href="../../css/detalle_turno.css?v=<?= time() ?>">
</html>