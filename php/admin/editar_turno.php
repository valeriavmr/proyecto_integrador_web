<?php
if (session_status() == PHP_SESSION_NONE) { 
                session_start(); 
            }

//Recupero el id del servicio a editar
$id_turno = $_GET['id_servicio'] ?? $_POST['id_servicio'] ?? null;

//Si no lo recupero, redirijo a la tabla de servicios
if (!$id_turno) {
    header("Location: tabla_historico_servicios.php");
    exit();
}else {
    //Conecto a la base de datos y obtengo los datos del turno
    require('../crud/conexion.php');
    include_once('../crud/consultas_varias.php');
    $turno = obtenerTurnoPorId($conn, $id_turno);
    if (!$turno) {
        header("Location: tabla_historico_servicios.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar turno</title>
    <link rel="stylesheet" href="../../css/solicitar_turno.css?v=<?= time() ?>">
</head>
<body>
    <?php include('header_admin.php'); ?>
    <main>
        <fieldset>
<form action="" method="post">
    <h2>Editar Turno</h2>
    <!-- ID del turno (oculto) -->
     <label for="id_servicio"></label>
    <input type="hidden" name="id_servicio" value="<?php echo $id_turno; ?>">

    <!-- Mascota -->
    <label for="mascota">Mascota:</label><br>
    <select name="mascota" id="mascota" required>
        <option value="<?php echo $turno['id_mascota'] ?>" selected>
            <?php echo htmlspecialchars(obtenerNombreMascota($conn, $turno['id_mascota'])); ?></option>
        <?php
        // Obtener el usuario de las mascotas
        $mascota = obtenerMascotaPorId($conn, $turno['id_mascota']);
        $usuario = obtenerUsername($conn, $mascota['id_persona']);

        // Obtener el usuario del trabajador
        $usuario_trabajador = obtenerUsername($conn, $turno['id_trabajador']);

        //Obtener la fecha y la hora del turno
        $fecha_turno = date('Y-m-d', strtotime($turno['horario']));
        $hora_turno = date('H:i', strtotime($turno['horario']));

        // Obtener mascotas
        $mascotas = obtenerMascotasPorUsuario($conn, $usuario);
        
        foreach ($mascotas as $mascota) {
            echo "<option value='{$mascota["id_mascota"]}'>{$mascota['nombre']}</option>";
        }
        ?>
    </select>
    <br><br>

    <!-- Tipo de servicio -->
    <label for="tipo_servicio">Tipo de Servicio:</label><br>
    <select name="tipo_servicio" id="tipo_servicio" required>
        <option value="<?php echo $turno['tipo_de_servicio'] ?? $_POST['tipo_de_servicio'] ?? '' ?>" selected><?php echo $turno['tipo_de_servicio'] ?? $_POST['tipo_de_servicio'] ?? '' ?></option>
        <?php
        $servicios = ["Adiestramiento canino", "Paseo canino", "Baño y peluqueria"];
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
        <option value="<?php echo $turno['id_trabajador'] ?? $_POST['trabajador'] ?? '' ?>" selected><?php echo htmlspecialchars(obtenerNombreUsuario($conn, $usuario_trabajador)); ?></option>
        <?php
        $trabajadores = obtenerTrabajadores($conn, $mascota['id_persona']);
        foreach ($trabajadores as $trabajador) {
            $selected = ($_POST['trabajador'] ?? '') == $trabajador['id_trabajador'] ? 'selected' : '';
            echo "<option value='{$trabajador["id_trabajador"]}' $selected>{$trabajador['nombre_completo']}</option>";
        }
        ?>
    </select>
    <br><br>

    <label for="fecha">Fecha:</label><br>
    <input type="date" id="fecha" name="fecha" min="<?= date('Y-m-d') ?>" required value="<?= $_POST['fecha'] ?? $fecha_turno ?? '' ?>" onchange="this.form.submit()">
    <br><br>

    <label for="hora">Hora:</label><br>
    <select name="hora" id="hora" required>
    <option value="<?php echo $hora_turno ?>" selected><?php echo $hora_turno ?></option>
    <?php
    $_POST['trabajador'] = $_POST['trabajador'] ?? $turno['id_trabajador'];
    $_POST['fecha'] = $_POST['fecha'] ?? $fecha_turno;

    if (!empty($_POST['trabajador']) && !empty($_POST['fecha'])) {
        $id_trabajador = $_POST['trabajador'];
        $fecha = $_POST['fecha'];

        $horas_disponibles = obtenerHorasDisponibles($conn, $id_trabajador, $fecha);
        echo $horas_disponibles;

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
        <textarea id="detalles" name="detalles" rows="4" cols="50"><?= $turno['comentarios'] ?? $_POST['detalles'] ?? '' ?></textarea>
        <br><br>

        <input type="submit" value="Editar Turno" name="editar_turno_btn" formaction="crud/update_servicio.php" id="editar_turno_btn">

        <!-- Monto del servicio -->
        <label for="monto_servicio">Monto del servicio:</label><br>
        <input type="number" id="monto_servicio" name="monto_servicio" required min="0" step="0.01" value="<?= $_POST['monto_servicio'] ?? $turno['monto'] ?? '' ?>" readonly>
        <br><br>
        </form>
            <br>
            <br>
            <a href="servicios_admin.php">Volver a Administración de turnos</a>
        </fieldset>
    </main>
    <?php include('../footer.php'); ?>
</body>
</html>