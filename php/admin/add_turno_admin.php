<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear turno</title>
    <link rel="stylesheet" href="../../css/servicios_cliente.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../favicon_io/favicon-16x16.png"> 
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>
    <?php
    if(session_status() == PHP_SESSION_NONE) { 
        session_start(); 
    }

    require_once('auth.php');
    include('header_admin.php');

    // Recuperar los valores posibles de trabajadores y mascotas
    require_once('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');
    $mascotas_posibles = obtenerMascotas($conn);
    if (!$mascotas_posibles) {
        $mascotas_posibles = [];
    }

    //Valores preexistentes
        // Valores persistentes
    $mascotaSeleccionada = $_POST['mascota'] ?? '';
    $servicioSeleccionado = $_POST['tipo_servicio'] ?? '';
    $trabajadorSeleccionado = $_POST['trabajador'] ?? '';
    $horaSeleccionada = $_POST['hora'] ?? '';
    $detalles = $_POST['detalles'] ?? '';
    ?>
<main>
<fieldset>
    <h1>Crear nuevo turno</h1>
    <form action="" method="POST">
        <label for="mascota">Seleccionar Mascota:</label>
        <select name="mascota" id="mascota" required onchange="this.form.submit()">
            <option value="" disabled selected>Seleccione una mascota</option>
            <?php
            foreach ($mascotas_posibles as $mascota) {
                $selected = ($mascotaSeleccionada == $mascota['id_mascota']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($mascota['id_mascota']) . '" ' . $selected . '>'
                    . htmlspecialchars($mascota['nombre']) . ' (ID: ' . htmlspecialchars($mascota['id_mascota']) . ')</option>';
            }
            ?>
        </select>
        <br><br>
            <!-- Tipo de servicio -->
    <label for="tipo_servicio">Tipo de Servicio:</label><br>
    <select name="tipo_servicio" id="tipo_servicio" required onchange="this.form.submit()">
        <option value="" disabled selected>Seleccione un servicio</option>
        <?php
        $servicios = obtenerTiposDeServicios($conn);
        $servicioSeleccionado = $_POST['tipo_servicio'] ?? '';
        foreach ($servicios as $servicio) {
            $selected = ($servicioSeleccionado == $servicio['tipo_de_servicio']) ? 'selected' : '';
            echo "<option value='{$servicio['tipo_de_servicio']}' $selected>{$servicio['tipo_de_servicio']}</option>";
        }
        ?>
    </select>
    <br><br>
        <?php
        // Si se ha seleccionado una mascota y un tipo de servicio, mostrar los trabajadores disponibles
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mascota']) && isset($_POST['tipo_servicio'])) {
            $mascota_seleccionada = obtenerMascotaPorId($conn, $_POST['mascota']);
            $servicioSeleccionado = $_POST['tipo_servicio'] ?? '';
            $trabajadores = obtenerTrabajadores($conn, $mascota_seleccionada['id_persona'],$servicioSeleccionado);
        } else {
            $trabajadores = [];
        }
        ?>
        <label for="trabajador">Seleccionar Trabajador:</label>
        <select name="trabajador" id="trabajador" required>
            <option value="" disabled selected>Seleccione un trabajador</option>
            <?php
            foreach ($trabajadores as $trabajador) {
                $selected = ($trabajadorSeleccionado == $trabajador['id_trabajador']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($trabajador['id_trabajador']) . '" ' . $selected . '>' . htmlspecialchars($trabajador['nombre_completo']) . '</option>';
            }
            ?>
        </select>
        <br><br>

        <label for="fecha">Fecha:</label><br>
    <input type="date" id="fecha" name="fecha" min="<?= date('Y-m-d') ?>" required value="<?= $_POST['fecha'] ?? '' ?>" onchange="this.form.submit()">
    <br><br>

    <label for="hora">Hora:</label><br>
    <select name="hora" id="hora" required>
    <option value="" disabled selected>Seleccione una hora</option>
    <?php
    if (!empty($_POST['trabajador']) && !empty($_POST['fecha'])) {
        $id_trabajador = $_POST['trabajador'];
        $fecha = $_POST['fecha'];

        include_once('crud/consultas_varias.php');
        $horas_disponibles = obtenerHorasDisponibles($conn, $id_trabajador, $fecha);

        if (!empty($horas_disponibles)) {
            $horaSeleccionada = $_POST['hora'] ?? '';
            foreach ($horas_disponibles as $hora) {
                echo "<option value='$hora' " . ($horaSeleccionada == $hora ? 'selected' : '') . ">$hora</option>";
            }
        } else {
            echo "<option value=''>No hay horarios disponibles</option>";
        }
    } else {
        echo "<option value=''>Selecciona trabajador y fecha</option>";
    }
    ?>
</select>
    <br><br>

    <!-- Detalles -->
        <label for="detalles">Observaciones a tener en cuenta:</label><br>
        <textarea id="detalles" name="detalles" rows="4" cols="50"><?= $_POST['detalles'] ?? '' ?></textarea>
        <br><br>

    <!-- Monto del servicio -->

        <label for="monto_servicio">Monto del servicio:</label><br>
        <?php
         if (!empty($_POST['tipo_servicio'])){
            $monto_servicio = obtenerMontoServicio($conn, $servicioSeleccionado);
            $_POST['monto_servicio'] = $monto_servicio;
         }
        ?>
        <input type="number" id="monto_servicio" name="monto_servicio" required min="0" step="0.001" value="<?= $_POST['monto_servicio'] ?? '' ?>" readonly>
         <br>
        <input type="submit" value="Solicitar Turno" name="solicitar_turno_btn" formaction="crud/insert_servicio_admin.php" id="solicitar_turno_btn">
    </form>
</fieldset>
<section>
    <section id="volver_s">
        <a href="servicios_admin.php">Volver a Administración de servicios</a>
    </section>
</section>
</main>
<?php include('../footer.php'); ?>
</body>
</html>