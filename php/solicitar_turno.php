<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Turno</title>
    <link rel="stylesheet" href="../css/servicios_cliente.css">
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="../favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon_io/favicon-16x16.png"> 
    <link rel="stylesheet" href="../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>
    <?php include('header_cliente.php'); ?>
    <main>
        <fieldset>
<form action="" method="post">
    <h2>Solicitar Turno</h2>

    <!-- Mascota -->
    <label for="mascota">Mascota:</label><br>
    <select name="mascota" id="mascota" required>
        <option value="" disabled selected>Seleccione una mascota</option>
        <?php
        // Obtener mascotas
        require('crud/conexion.php');
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $usuario = $_SESSION['username'];
        include_once('crud/consultas_varias.php');
        $mascotas = obtenerMascotasPorUsuario($conn, $usuario);
        foreach ($mascotas as $mascota) {
            $selected = ($_POST['mascota'] ?? '') == $mascota['id_mascota'] ? 'selected' : '';
            echo "<option value='{$mascota["id_mascota"]}' $selected>{$mascota['nombre']}</option>";
        }
        ?>
    </select>
    <br><br>

    <!-- Tipo de servicio -->
    <label for="tipo_servicio">Tipo de Servicio:</label><br>
    <select name="tipo_servicio" id="tipo_servicio" required>
        <option value="" disabled selected>Seleccione un servicio</option>
        <?php
        $servicios = ["Adiestramiento canino", "Paseo canino", "Banio y peluqueria"];
        foreach ($servicios as $servicio) {
            $selected = ($_POST['tipo_servicio'] ?? '') == $servicio ? 'selected' : '';
            echo "<option value='$servicio' $selected>$servicio</option>";
        }
        ?>
    </select>
    <br><br>

    <!-- Trabajador -->
    <label for="trabajador">Trabajador asignado:</label><br>
    <select name="trabajador" id="trabajador" required onchange="this.form.submit()">
        <option value="" disabled selected>Selecciona un trabajador</option>
        <?php
        $id_cliente = obtenerIdPersona($conn, $usuario);
        $trabajadores = obtenerTrabajadores($conn, $id_cliente);
        foreach ($trabajadores as $trabajador) {
            $selected = ($_POST['trabajador'] ?? '') == $trabajador['id_trabajador'] ? 'selected' : '';
            echo "<option value='{$trabajador["id_trabajador"]}' $selected>{$trabajador['nombre_completo']}</option>";
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
            foreach ($horas_disponibles as $hora) {
                echo "<option value='$hora'>$hora</option>";
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

        <input type="submit" value="Solicitar Turno" formaction="crud/insert_servicio.php" id="solicitar_turno_btn">
        </form>
            <br>
            <br>
            <a href="servicios_cliente.php">Volver</a>
        </fieldset>
    </main>
    <?php include('footer.php'); ?>
</body>
<link rel="stylesheet" href="../css/footer_styles.css?v=<?= time() ?>">
</html>